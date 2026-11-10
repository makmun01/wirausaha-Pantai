# DATA MIGRATION RECONCILIATION AUDIT REPORT
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 13

Dokumen ini mencatat hasil verifikasi nyata pipeline **Extract - Transform - Load (ETL)** menggunakan `App\Services\EtlMigrationService`, skrip CLI `scripts/etl_import.php`, dan tabel karantina `migration_quarantine_logs` terhadap berkas sampel survei lapangan [database/samples/raw_umkm_survey.csv](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/database/samples/raw_umkm_survey.csv).

---

## 1. ATURAN TRANSFORMASI & VALIDASI DATA
1. **Normalisasi Nomor WhatsApp:** Konversi awalan `08...` atau `62...` serta penghapusan tanda hubung menjadi format baku `+62...`.
2. **Pembersihan Format Rupiah:** Pengubahan string `"Rp 25.000"`, `"Rp 225.000"`, atau `"45000"` menjadi nilai numerik presisi `float(25000.00)`. Nominal negatif ditolak ke karantina.
3. **Validasi Batas Koordinat WGS84 Pamekasan:** Koordinat diterima hanya jika berada pada rentang Latitude `-7.5` s/d `-7.0` dan Longitude `113.3` s/d `113.8`. Koordinat di luar wilayah (misal Jakarta `-6.10, 106.80`) otomatis dikarantina.
4. **Deduplikasi Berulang:** Baris duplikat berdasarkan kunci komposit `(business_name + phone_number)` atau yang sudah terdaftar di tabel `umkm_merchants` / `map_markers` dilewati tanpa merusak data eksisting.

---

## 2. HASIL EKSEKUSI AKTUAL REKONSILIASI ETL

| Parameter Audit | Dry-Run (`--dry-run`) | Initial Load (`--apply` Run #1) | Idempotency Re-Run (`--apply` Run #2) | Automated Test (`EtlMigrationUnitTest`) |
|---|---|---|---|---|
| **Source File** | `raw_umkm_survey.csv` | `raw_umkm_survey.csv` | `raw_umkm_survey.csv` | `raw_umkm_survey.csv` (`sqlite::memory:`) |
| **Extracted Rows ($E$)** | `5` | `5` | `5` | `5` |
| **Valid Transformed ($T$)** | `3` | `3` | `3` | `3` |
| **Loaded to Database ($L$)** | `3` *(simulasi)* | `3` | `0` *(sudah ada di DB)* | `3` (Pass #1) / `0` (Pass #2) |
| **Quarantined Rows ($Q$)** | `1` *(nama/alamat kosong, koordinat Jakarta, harga `-15000`)* | `1` *(tersimpan di `migration_quarantine_logs`)* | `1` | `1` |
| **Duplicates Skipped ($D$)** | `1` *(duplikat baris #5)* | `1` | `4` *(1 batch + 3 eksisting DB)* | `1` / `4` |
| **Zero Data Loss Check** | **PASS** | **PASS** | **PASS** | **PASS** |

Rumus rekonsiliasi terverifikasi:
$$\text{Extracted } (5) = \text{Valid Transformed } (3) + \text{Batch Quarantined } (1) + \text{Batch Duplicates } (1) = 5 \quad (\text{100\% Zero Data Loss})$$

**Status Akhir Migrasi & ETL:** **PASS**
