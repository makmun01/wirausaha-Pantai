<?php

namespace App\Services;

use InvalidArgumentException;
use PDO;

class EtlMigrationService
{
    public const PAMEKASAN_LAT_MIN = -7.5;
    public const PAMEKASAN_LAT_MAX = -7.0;
    public const PAMEKASAN_LNG_MIN = 113.3;
    public const PAMEKASAN_LNG_MAX = 113.8;

    public function normalizePhoneNumber(string $raw): string
    {
        $digits = preg_replace('/[^0-9+]/', '', trim($raw));
        if ($digits === '' || $digits === '+') {
            return '';
        }

        if (str_starts_with($digits, '+62')) {
            return $digits;
        }
        if (str_starts_with($digits, '62')) {
            return '+' . $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '+62' . substr($digits, 1);
        }

        return '+62' . $digits;
    }

    public function normalizeCurrency(string $raw): float
    {
        $clean = trim(str_ireplace(['Rp', 'IDR', ' '], '', $raw));
        if ($clean === '') {
            throw new InvalidArgumentException('Nominal harga kosong.');
        }

        // Convert Indonesian thousands separator (25.000 or 25.000,50)
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/', $clean)) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } else {
            $clean = str_replace(',', '', $clean);
        }

        if (!is_numeric($clean)) {
            throw new InvalidArgumentException("Format nominal tidak valid: {$raw}");
        }

        $value = (float) $clean;
        if ($value < 0) {
            throw new InvalidArgumentException("Nominal tidak boleh negatif: {$raw}");
        }

        return round($value, 2);
    }

    public function parseCoordinates(string $raw): ?array
    {
        $parts = array_map('trim', explode(',', $raw));
        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            return null;
        }

        $lat = round((float) $parts[0], 8);
        $lng = round((float) $parts[1], 8);

        if (
            $lat < self::PAMEKASAN_LAT_MIN || $lat > self::PAMEKASAN_LAT_MAX ||
            $lng < self::PAMEKASAN_LNG_MIN || $lng > self::PAMEKASAN_LNG_MAX
        ) {
            return null;
        }

        return ['latitude' => $lat, 'longitude' => $lng];
    }

    public function normalizeCategory(string $raw): string
    {
        $upper = strtoupper(trim($raw));
        $allowed = ['KULINER', 'KERAJINAN', 'HOMESTAY', 'JASA_WISATA'];
        if (in_array($upper, $allowed, true)) {
            return $upper;
        }
        if (str_contains($upper, 'BATIK') || str_contains($upper, 'KERAJINAN')) {
            return 'KERAJINAN';
        }
        if (str_contains($upper, 'HOMESTAY') || str_contains($upper, 'PENGINAPAN')) {
            return 'HOMESTAY';
        }
        return 'KULINER';
    }

    public function processUmkmSurveyCsv(string $csvPath, ?PDO $pdo = null, bool $dryRun = true): array
    {
        if (!is_file($csvPath)) {
            throw new InvalidArgumentException("File CSV sumber tidak ditemukan: {$csvPath}");
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            throw new InvalidArgumentException("Gagal membuka file CSV: {$csvPath}");
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new InvalidArgumentException('Header CSV kosong atau tidak valid.');
        }
        $headers = array_map(fn ($h) => trim((string) $h), $headers);

        $extracted = 0;
        $transformed = [];
        $quarantined = [];
        $duplicates = [];
        $seenKeys = [];

        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }
            $extracted++;

            $assoc = [];
            foreach ($headers as $idx => $col) {
                $assoc[$col] = trim((string) ($row[$idx] ?? ''));
            }

            $businessName = ucwords(strtolower(preg_replace('/\s+/', ' ', $assoc['Nama_Toko'] ?? '')));
            $ownerName = ucwords(strtolower(preg_replace('/\s+/', ' ', $assoc['Nama_Pemilik'] ?? '')));
            $phone = $this->normalizePhoneNumber($assoc['Kontak_WA'] ?? '');
            $coords = $this->parseCoordinates($assoc['Titik_Koordinat'] ?? '');
            $address = $assoc['Alamat_Fisik'] ?? '';

            $errors = [];
            if ($businessName === '') {
                $errors[] = 'Nama_Toko kosong';
            }
            if ($ownerName === '') {
                $errors[] = 'Nama_Pemilik kosong';
            }
            if ($phone === '' || strlen($phone) < 10) {
                $errors[] = 'Kontak_WA tidak valid';
            }
            if ($address === '') {
                $errors[] = 'Alamat_Fisik kosong';
            }
            if ($coords === null) {
                $errors[] = 'Titik_Koordinat di luar wilayah Pantai Jumiang / format salah';
            }

            $samplePrice = null;
            if (isset($assoc['Harga_Produk_Utama']) && $assoc['Harga_Produk_Utama'] !== '') {
                try {
                    $samplePrice = $this->normalizeCurrency($assoc['Harga_Produk_Utama']);
                } catch (InvalidArgumentException $e) {
                    $errors[] = $e->getMessage();
                }
            }

            if (!empty($errors)) {
                $quarantined[] = [
                    'row_number' => $rowNum,
                    'source_file' => basename($csvPath),
                    'reason' => implode('; ', $errors),
                    'raw_payload' => $assoc,
                ];
                continue;
            }

            $dedupeKey = strtolower($businessName) . '|' . $phone;
            if (isset($seenKeys[$dedupeKey])) {
                $duplicates[] = [
                    'row_number' => $rowNum,
                    'business_name' => $businessName,
                    'phone_number' => $phone,
                    'reason' => 'Duplikasi Nama_Toko & Kontak_WA pada batch sumber',
                ];
                continue;
            }
            $seenKeys[$dedupeKey] = true;

            $transformed[] = [
                'business_name' => $businessName,
                'owner_name' => $ownerName,
                'category' => $this->normalizeCategory($assoc['Kategori_Usaha'] ?? 'KULINER'),
                'phone_number' => $phone,
                'address' => $address,
                'latitude' => $coords['latitude'],
                'longitude' => $coords['longitude'],
                'sample_price' => $samplePrice,
            ];
        }
        fclose($handle);

        $loadedCount = 0;
        $quarantinedSaved = 0;
        $batchDuplicatesCount = count($duplicates);
        $dbDuplicatesCount = 0;

        if ($pdo !== null && !$dryRun) {
            $pdo->beginTransaction();
            try {
                foreach ($quarantined as $q) {
                    $stmt = $pdo->prepare(
                        'INSERT INTO migration_quarantine_logs (source_file, row_number, raw_payload, error_reason) VALUES (?, ?, ?, ?)'
                    );
                    $stmt->execute([
                        $q['source_file'],
                        $q['row_number'],
                        json_encode($q['raw_payload'], JSON_UNESCAPED_UNICODE),
                        $q['reason'],
                    ]);
                    $quarantinedSaved++;
                }

                $checkMerchantStmt = $pdo->prepare('SELECT id FROM umkm_merchants WHERE LOWER(business_name) = LOWER(?) LIMIT 1');
                $checkMarkerStmt = $pdo->prepare('SELECT id FROM map_markers WHERE LOWER(title) = LOWER(?) LIMIT 1');
                $insertMarker = $pdo->prepare(
                    'INSERT INTO map_markers (category, title, description, latitude, longitude, icon_type, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)'
                );

                foreach ($transformed as $item) {
                    $checkMerchantStmt->execute([$item['business_name']]);
                    $existsInMerchant = (bool) $checkMerchantStmt->fetchColumn();
                    $checkMarkerStmt->execute([$item['business_name']]);
                    $existsInMarker = (bool) $checkMarkerStmt->fetchColumn();

                    if ($existsInMerchant || $existsInMarker) {
                        $duplicates[] = [
                            'row_number' => 0,
                            'business_name' => $item['business_name'],
                            'phone_number' => $item['phone_number'],
                            'reason' => 'Sudah terdaftar pada database target',
                        ];
                        $dbDuplicatesCount++;
                        continue;
                    }

                    $insertMarker->execute([
                        'UMKM',
                        $item['business_name'],
                        "Gerai {$item['category']} - {$item['address']} ({$item['phone_number']})",
                        $item['latitude'],
                        $item['longitude'],
                        'shop',
                    ]);
                    $loadedCount++;
                }

                $pdo->commit();
            } catch (\Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }
        } else {
            $loadedCount = count($transformed);
            $quarantinedSaved = count($quarantined);
        }

        $reconciledTotal = count($transformed) + count($quarantined) + $batchDuplicatesCount;
        $loadReconciled = $dryRun || (($loadedCount + $dbDuplicatesCount) === count($transformed));

        return [
            'dry_run' => $dryRun,
            'source_file' => basename($csvPath),
            'extracted_rows' => $extracted,
            'valid_transformed_rows' => count($transformed),
            'loaded_rows' => $loadedCount,
            'quarantined_rows' => count($quarantined),
            'quarantine_logged' => $quarantinedSaved,
            'duplicates_skipped' => count($duplicates),
            'zero_data_loss' => ($extracted === $reconciledTotal) && $loadReconciled,
            'valid_records' => $transformed,
            'quarantine_details' => $quarantined,
            'duplicate_details' => $duplicates,
        ];
    }
}
