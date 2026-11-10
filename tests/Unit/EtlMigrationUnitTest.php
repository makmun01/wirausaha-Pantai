<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\EtlMigrationService;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Assert;

class EtlMigrationUnitTest extends TestCase
{
    private EtlMigrationService $etl;

    protected function setUp(): void
    {
        $this->etl = new EtlMigrationService();
    }

    public function test_normalizes_phones_currency_and_pamekasan_coordinates(): void
    {
        Assert::same('+6281234567801', $this->etl->normalizePhoneNumber('0812-3456-7801'));
        Assert::same('+6281398765432', $this->etl->normalizePhoneNumber('6281398765432'));
        Assert::same('+6281512349988', $this->etl->normalizePhoneNumber('+6281512349988'));

        Assert::same(25000.0, $this->etl->normalizeCurrency('Rp 25.000'));
        Assert::same(225000.0, $this->etl->normalizeCurrency('Rp 225.000'));
        Assert::same(45000.0, $this->etl->normalizeCurrency('45000'));

        Assert::throws(\InvalidArgumentException::class, function (): void {
            $this->etl->normalizeCurrency('-15000');
        });

        $validCoords = $this->etl->parseCoordinates('-7.22815, 113.53780');
        Assert::true($validCoords !== null);
        Assert::same(-7.22815, $validCoords['latitude']);
        Assert::same(113.5378, $validCoords['longitude']);

        // Out of Pamekasan bounds (Jakarta coordinate should be rejected to quarantine)
        Assert::true($this->etl->parseCoordinates('-6.10000, 106.80000') === null);
    }

    public function test_csv_etl_apply_quarantine_and_idempotency(): void
    {
        $csvPath = __DIR__ . '/../../database/samples/raw_umkm_survey.csv';
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE umkm_merchants (id INTEGER PRIMARY KEY AUTOINCREMENT, business_name TEXT)');
        $pdo->exec('CREATE TABLE map_markers (id INTEGER PRIMARY KEY AUTOINCREMENT, category TEXT, title TEXT, description TEXT, latitude REAL, longitude REAL, icon_type TEXT, is_active INTEGER DEFAULT 1)');
        $pdo->exec('CREATE TABLE migration_quarantine_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, source_file TEXT, row_number INTEGER, raw_payload TEXT, error_reason TEXT)');

        // Pass 1: initial load into database
        $report1 = $this->etl->processUmkmSurveyCsv($csvPath, $pdo, false);
        Assert::same(5, $report1['extracted_rows']);
        Assert::same(3, $report1['valid_transformed_rows']);
        Assert::same(3, $report1['loaded_rows']);
        Assert::same(1, $report1['quarantined_rows']);
        Assert::same(1, $report1['quarantine_logged']);
        Assert::same(1, $report1['duplicates_skipped']);
        Assert::true($report1['zero_data_loss']);

        $markersCount = (int) $pdo->query('SELECT COUNT(*) FROM map_markers')->fetchColumn();
        $quarantineCount = (int) $pdo->query('SELECT COUNT(*) FROM migration_quarantine_logs')->fetchColumn();
        Assert::same(3, $markersCount);
        Assert::same(1, $quarantineCount);

        // Pass 2: re-run against same database must skip already-loaded merchants without duplicates
        $report2 = $this->etl->processUmkmSurveyCsv($csvPath, $pdo, false);
        Assert::same(0, $report2['loaded_rows']);
        Assert::same(4, $report2['duplicates_skipped']);
        Assert::true($report2['zero_data_loss']);
        Assert::same(3, (int) $pdo->query('SELECT COUNT(*) FROM map_markers')->fetchColumn());
    }
}
