# DEFECT LOG (LAPORAN DAN PELACAKAN DEFECT/BUG)
## JUMIANG SMART TOURISM & UMKM
**Defect Life-Cycle & Resolution Registry (ISO/IEC/IEEE 29119-4)**

---

## 1. KEBIJAKAN PENGELOLAAN DEFECT (DEFECT MANAGEMENT POLICY)
1. Setiap anomali yang ditemukan selama pengujian manual maupun otomatis wajib dicatat dalam dokumen ini.
2. Status defect yang diakui: `NEW`, `IN_PROGRESS`, `FIXED`, `RETESTED`, `CLOSED`.
3. **Aturan Mutlak:** Dilarang menutup defect (`CLOSED`) sebelum dilakukan retest nyata dengan hasil `PASS`.

---

## 2. TABEL REGISTER DEFECT

| Defect ID | Tanggal Temuan | Modul | Severity | Deskripsi Kerusakan | Langkah Reproduksi | Hasil Aktual vs Harapan | Status | Solusi / Commit Ref |
|---|---|---|---|---|---|---|---|---|
| *DEF-INIT* | 2026-11-02 | Setup | Minor | Belum ada dependensi terpasang pada repositori baru | Jalankan `composer install` tanpa `artisan` | Tidak ada `vendor/`; Harapan: terinstall bersih / fallback preview | CLOSED | Server preview mandiri & CI validation tanpa ketergantungan `artisan` |
| *DEF-CI-001* | 2026-11-09 | `MOD-MKT` (`public/index.php`) | Major | Rute `/produk/{slug}` memanggil `renderProductDetail()` yang belum terdefinisi pada server preview | Akses `GET /produk/rengginang-lorjuk-super` | Aktual: PHP `Fatal error: Call to undefined function renderProductDetail()`; Harapan: HTTP `200` detail produk | CLOSED | Commit `c605f45`: Implementasi `renderProductDetail()` di `public/index.php` + retest `PASS` |
| *DEF-AUD-001* | 2026-11-10 | `MOD-FIN` & Controllers | Major | `FinancialBookkeepingService` melempar `\SecurityException` yang tidak didefinisikan & base class `App\Http\Controllers\Controller` tidak ada | Eksekusi Unit Test Path 1 `recordTransaction()` untuk rekening lintas merchant | Aktual: Class `\SecurityException` tidak ditemukan; Harapan: `App\Exceptions\SecurityException` tertangkap | CLOSED | Penambahan `app/Exceptions/SecurityException.php` & `Controller.php`, retest Unit Test `PASS` |
| *DEF-AUD-002* | 2026-11-10 | `MOD-AUTH` & `MOD-MKT` (`public/index.php`) | Major | Server preview `public/index.php` mengizinkan role `wisatawan` mengakses `/admin/*` (`200` bukan `403`), belum menyediakan endpoint `/api/v1/*`, dan menyisakan refleksi `?search=` tanpa escaping | Login `/demo-login?role=wisatawan` lalu buka `/admin/financial` | Aktual: HTTP `200`; Harapan: HTTP `403 Forbidden`, XSS escaping, & JSON API `/api/v1/*` aktif | CLOSED | Penambahan `requireRole()`, `handleApiRequest()`, `renderAdminEtl()`, dan `htmlspecialchars` pada `public/index.php` (`AuthAndRbacTest` & `SecurityOwaspTest` `PASS`) |
| *DEF-AUD-003* | 2026-11-10 | `MOD-ETL` (`EtlMigrationService.php`) | Minor | Pada eksekusi `--apply` kedua (idempotency check), pencatatan duplikat DB ke array `$duplicates` menyebabkan rumus `zero_data_loss` gagal | Jalankan `php scripts/etl_import.php --apply` dua kali berurutan pada DB yang sama | Aktual: `extracted != reconciledTotal`; Harapan: `loaded + db_duplicates == valid_transformed` & cek ke `map_markers` + `umkm_merchants` | CLOSED | Pemisahan penghitung `$batchDuplicatesCount` vs `$dbDuplicatesCount` & pengecekan `map_markers`, retest `EtlMigrationUnitTest` `PASS` |
| *DEF-CI-002* | 2026-11-10 | `MOD-ETL` (`EtlMigrationService.php`) | Major | Kueri `INSERT INTO migration_quarantine_logs (..., row_number, ...)` memicu `SQLSTATE[42000]` syntax error pada MySQL 8.4 karena `ROW_NUMBER` merupakan reserved keyword window function | Jalankan `php scripts/etl_import.php --apply` terhadap MySQL 8.4 (CI Run `42847398228`) | Aktual: `PDOException 1064 near 'row_number'`; Harapan: Baris anomali tersimpan aman di tabel `migration_quarantine_logs` | FIXED | Pengutipan backtick identifier SQL (`` `row_number` ``, dll.) pada `EtlMigrationService::processUmkmSurveyCsv()` agar kompatibel dengan MySQL 8.4 & SQLite |
