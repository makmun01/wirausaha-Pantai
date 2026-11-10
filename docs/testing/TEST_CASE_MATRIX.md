# TEST CASE MATRIX
## JUMIANG SMART TOURISM & UMKM
**Master Test Execution & Verification Matrix (ISO/IEC/IEEE 29119-3)**

Catatan Integritas: Nilai `Actual Result` dan `Status` pada dokumen ini dicatat berdasarkan hasil eksekusi nyata (`php tests/run_all_tests.php`, `scripts/etl_import.php`, `scripts/backup_verify.php`, `scripts/benchmark_local.php`). Kasus uji yang membutuhkan paket `vendor/` Laravel penuh atau binary eksternal yang tidak terpasang dicatat transparan sebagai `NOT TESTED`.

---

## 1. Matriks Kasus Uji Fungsional Inti (Master Matrix - 21 Skenario)

| Test ID | Modul | Skenario Pengujian | Metode / Alat Uji | Input Data | Expected Result | Actual Result | Status | Severity |
|---|---|---|---|---|---|---|---|---|
| **TC-AUTH-01** | `MOD-AUTH` | Login role Demo (6 peran termasuk Super Admin) | Automated HTTP (`AuthAndRbacTest::test_demo_login_switches_session_for_all_six_roles`) | Role `super_admin` s/d `wisatawan` via `/demo-login` | Pengguna masuk ke `/dashboard` sesuai role aktif | 6/6 role mengembalikan HTTP `200 OK` & nama role aktif tampil pada `/dashboard` | **PASS** | High |
| **TC-AUTH-02** | `MOD-AUTH` | Login form Laravel dengan password salah | Manual / Laravel `AuthService` (memerlukan `vendor/`) | Email valid + password salah | Menampilkan error "Kredensial tidak cocok" | Kode `AuthService` tersedia & lulus `php -l`; eksekusi DB auth penuh belum dijalankan tanpa `vendor/` | **NOT TESTED** | High |
| **TC-AUTH-03** | `MOD-AUTH` | Akses rute terlarang (Role Boundary Check) | Automated HTTP (`AuthAndRbacTest::test_wisatawan_gets_403_forbidden_on_admin_modules`) | Sesi role `wisatawan` -> `/admin/dashboard`, `/admin/financial`, `/admin/etl` | HTTP `403 Forbidden` | Seluruh 5 rute `/admin/*` menolak `wisatawan` dengan HTTP `403` & banner `Akses Ditolak` | **PASS** | Critical |
| **TC-AUTH-04** | `MOD-AUTH` | Registrasi akun wisatawan baru ke DB Laravel | Form `/register` (`AuthService`) | Nama + Email baru + Password | Akun `wisatawan` tersimpan di tabel `users` | Form `/register` merender HTTP `200 OK`; simpan DB `users` belum diuji tanpa `vendor/` | **NOT TESTED** | High |
| **TC-TOUR-01** | `MOD-TOUR` | Menampilkan profil Pantai Jumiang | Automated HTTP (`BlackBoxEpBvaTest` + `/api/v1/destinations`) | Akses `/tentang-jumiang` & API destinations | Menampilkan profil Pantai Jumiang & label `[DATA DEMO / SIMULASI]` | HTTP `200 OK`, teks `Puncak Tebing Jumiang`, `Desa Tanjung`, penanda demo tampil | **PASS** | Medium |
| **TC-GMAP-01** | `MOD-GMAP` | Pemuatan peta interaktif Leaflet.js (WGS84) | Automated HTTP (`BlackBoxEpBvaTest` + `ApiAndIntegrationTest`) | `/peta-wisata` & `/api/v1/map-markers` | Peta center `-7.228912, 113.538120` & 7 marker WGS84 | HTTP `200 OK`, `meta.coordinate_system === 'WGS84'`, 7 marker tersedia | **PASS** | High |
| **TC-GMAP-02** | `MOD-GMAP` | Filter layer marker kategori `UMKM` | Automated API (`ApiAndIntegrationTest`) | `GET /api/v1/map-markers?category=UMKM` | Hanya pin gerai UMKM yang dikembalikan | HTTP `200 OK`, mengembalikan 2 marker bertipe `UMKM` | **PASS** | Medium |
| **TC-UMKM-01** | `MOD-UMKM` | Pendaftaran gerai UMKM baru (`PENDING`) | Unit/Service (`UmkmService`) | Data toko UMKM baru | Tersimpan dengan `verification_status = PENDING` + 2 rekening | `OrderStateTransitionUnitTest` memverifikasi entitas `PENDING` & struktur rekening (`PASS` logika) | **PASS** | High |
| **TC-UMKM-02** | `MOD-UMKM` | Verifikasi pendaftaran UMKM & isolasi transisi FSM | Unit Test (`OrderStateTransitionUnitTest::test_umkm_verification_fsm_and_product_price_bva`) | `PENDING -> VERIFIED`; ilegal `VERIFIED -> PENDING` | Transisi sah diterima, transisi mundur ilegal ditolak | `PENDING -> VERIFIED` berhasil; `VERIFIED -> PENDING` melempar `InvalidArgumentException` | **PASS** | High |
| **TC-PROD-01** | `MOD-PROD` | Katalog & tambah produk baru dengan batas stok | Unit + API (`ProductService` + `ApiAndIntegrationTest`) | `/api/v1/products`, `price = 0` (`createProduct`) | Produk aktif tampil di katalog & API | 5 produk pada API `/api/v1/products`; `ProductService::createProduct` menyimpan sampel `price = 0` | **PASS** | High |
| **TC-PROD-02** | `MOD-PROD` | Validasi input harga negatif (BVA) | Unit Test (`OrderStateTransitionUnitTest`) | `price = -1` | Ditolak sebelum tersimpan ke DB | Melempar `\InvalidArgumentException` sesuai batas `price >= 0` | **PASS** | High |
| **TC-MKT-01** | `MOD-MKT` | Akses katalog, detail produk & keranjang belanja | Automated HTTP (`BlackBoxEpBvaTest`) | `/katalog-umkm`, `/produk/rengginang-lorjuk-super`, `/keranjang` | HTTP `200 OK` + detail produk aman dari XSS | Seluruh rute HTTP `200 OK`, XSS slug ter-escape aman | **PASS** | High |
| **TC-MKT-02** | `MOD-MKT` | Checkout simulasi, bayar QRIS & integrasi stok-kas | Unit + API (`OrderStateTransitionUnitTest` & `ApiAndIntegrationTest`) | `POST /api/v1/orders/simulate` & `simulate-pay` | Order `PENDING` (`201`) -> `PAID` (`200`), stok `-qty`, kas `BUSINESS` `+total` | Verifikasi unit (`OrderProcessingService`) & API (`201`/`200`) keduanya lulus penuh | **PASS** | Critical |
| **TC-INVN-01** | `MOD-INVN` | Pengurangan stok otomatis & rollback saat batal `PAID` | Unit Test (`OrderStateTransitionUnitTest::test_cancel_paid_order_rolls_back_inventory`) | Stok awal `20`, beli `5` -> `15`, lalu `CANCELLED` | Stok berkurang jadi `15`, kembali `20` saat batal | Stok aktual `20 -> 15 -> 20` beserta mutasi `InventoryLog` tercatat | **PASS** | Critical |
| **TC-INVN-02** | `MOD-INVN` | Penolakan pesanan jika `qty <= 0` atau melebihi stok | Automated HTTP (`BlackBoxEpBvaTest`) | Qty `-1`, `0`, `9` untuk stok `8` | HTTP `422 Unprocessable Entity` | Ketiga kasus batas invalid ditolak dengan HTTP `422` (`qty=1` & `qty=8` HTTP `201`) | **PASS** | High |
| **TC-FIN-01** | `MOD-FIN` | Pemisahan mutlak akun kas `BUSINESS` vs `PERSONAL` | Unit + API (`FinancialBookkeepingUnitTest::test_path3...` & `/api/v1/umkm/my-finances`) | `INCOME` Rp `350.000` ke akun `BUSINESS` | Saldo `BUSINESS` bertambah, `PERSONAL` tidak tersentuh | `BUSINESS` `1.000.000 -> 1.350.000`, `PERSONAL` tetap `500.000`; API mengembalikan 2 akun terpisah | **PASS** | Critical |
| **TC-FIN-02** | `MOD-FIN` | Pencatatan pengeluaran & kalkulasi Laba Rugi (`P&L`) | Unit Test (`FinancialBookkeepingUnitTest::test_path4...`) | Income `600.000`, Expense `225.000` | Laba bersih `375.000` (`is_profit = true`) | Kalkulasi `getProfitAndLoss()` tepat: `600000 - 225000 = 375000` | **PASS** | High |
| **TC-FIN-03** | `MOD-FIN` | Isolasi data finansial antar-UMKM & role non-UMKM | Unit + API (`FinancialBookkeepingUnitTest::test_path1...` & `ApiAndIntegrationTest`) | Merchant 1 akses rekening Merchant 2; `wisatawan` panggil `/api/v1/umkm/my-finances` | Lempar `SecurityException` / HTTP `403` | `SecurityException` terlempar; API `my-finances` membalas HTTP `403 Forbidden` | **PASS** | Critical |
| **TC-RESV-01** | `MOD-RESV` | Halaman reservasi wisata & panel admin check-in | Smoke Route & `AuthAndRbacTest` | `/reservasi-wisata`, `/admin/reservations` | HTTP `200 OK` bagi wisatawan/pengelola sesuai hak | Halaman reservasi & admin merender HTTP `200 OK`; simpan DB MySQL `ReservationService` penuh belum dieksekusi tanpa `vendor/` | **NOT TESTED** | High |
| **TC-EIS-01** | `MOD-EIS` | Dashboard EIS Pimpinan & pembatasan akses role | `AuthAndRbacTest` + Smoke Test | `/admin/eis` sebagai `super_admin` & `wisatawan` | `200 OK` untuk otorisasi sah, `403` untuk wisatawan | RBAC `/admin/eis` lolos uji; agregasi MySQL `ExecutiveAnalyticsService` penuh menunggu runtime Laravel | **PASS** *(Preview & RBAC)* | High |
| **TC-AUDT-01** | `MOD-AUDT` | Pencatatan Audit Trail mutasi ke tabel `audit_logs` | `AuthAndRbacTest` (`/admin/audit-logs`) & `AuditTrailService` | Akses viewer `/admin/audit-logs` | Admin melihat log; wisatawan ditolak `403` | Viewer `/admin/audit-logs` (`200` Admin / `403` Wisatawan) PASS; auto-write DB Eloquent menunggu `vendor/` | **NOT TESTED** *(DB Write)* | Medium |

---

## 2. Rekapitulasi Eksekusi Terotomasi & CLI (`tests/run_all_tests.php` & `scripts/*`)

| Suite / Skrip | Jumlah Item Uji | Hasil Aktual | Status |
|---|---|---|---|
| **Unit Test Suite (`FinancialBookkeeping`, `OrderStateTransition`, `EtlMigration`)** | 10 unit test | 10 PASS, 0 FAIL (`25.1 ms`) | **PASS** |
| **Feature HTTP Suite (`AuthAndRbac`, `BlackBoxEpBva`, `ApiAndIntegration`, `SecurityOwasp`)** | 9 feature HTTP test | 9 PASS, 0 FAIL (Total suite `993.32 ms`) | **PASS** |
| **Pipeline ETL CSV (`scripts/etl_import.php --dry-run` & `--apply`)** | 5 baris CSV uji | 3 valid loaded, 1 quarantined, 1 duplicate (`Zero Data Loss = PASS`) | **PASS** |
| **Backup Verification (`scripts/backup_verify.php`)** | 23 tabel DDL, 21 seed | Size `32502` B, SHA-256 cocok, FK guard PASS | **PASS** |
| **Local Concurrency Benchmark (`scripts/benchmark_local.php`)** | Tier 1 (5c), Tier 2 (10c), Tier 3 (20c) | Avg `30.74–52.29 ms`, P95 `50.01–95.06 ms`, `0.00%` errors | **PASS** |
| **External Tools (JMeter 500 VU CLI, OWASP ZAP CLI, Lapangan Vendor UAT)** | 3 aktivitas eksternal | Binary eksternal & sesi survei fisik belum dijalankan lokal | **NOT TESTED** |
