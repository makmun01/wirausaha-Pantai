# CHANGELOG - JUMIANG SMART TOURISM & UMKM

Semua perubahan penting pada proyek ini didokumentasikan di file ini dengan mengacu pada prinsip Keep a Changelog dan Semantic Versioning.

---

## [1.1.2] - 2026-11-11 (Audit Jalankan Aplikasi, Sinkronisasi Interaktif UI & 23/23 Tests)

### Fixed & Improved
- **Sinkronisasi Mode Pratinjau (`public/index.php`):** Memperbaiki `DEF-UX-001` s/d `DEF-UX-005` hasil inspeksi alur aplikasi langsung:
  - Menambahkan tombol `navbar-toggler` responsif mobile, ikon SVG inline, badge keranjang dinamis, menu dashboard kontekstual per role, serta halaman `HTTP 404` (`renderNotFound`) untuk rute yang tidak dikenal.
  - Melengkapi 5 produk katalog SQL di `/katalog-umkm`, kotak pencarian UI, filter kategori produk, filter 6 tab layer & warna marker di `/peta-wisata`, serta grafik Chart.js (Line & Doughnut) di `/admin/eis`.
  - Menyinkronkan jurnal pengeluaran `/admin/financial` (`Rp 400.000` dengan 4 transaksi) agar konsisten 100% dengan `/admin/financial/profit-loss` (`+ Rp 525.000`), serta mengaktifkan form interaktif POST/Redirect/Get untuk keranjang, checkout (`INV-...`), reservasi (`RSV-...`), registrasi, tambah produk BVA, mutasi stok, buku kas, FSM pesanan, check-in tiket, verifikasi UMKM, dan unggah CSV dry-run `/admin/etl`.
- **Perluasan Suite Pengujian (`tests/`):** Menambahkan `HttpClient::postForm()` (perbaikan redirect `302` cURL POST-to-GET) dan 4 test end-to-end baru di `tests/Feature/InteractiveUiFlowsTest.php` (**Total `23 / 23 PASS`** termasuk penyapuan 23 rute web bebas *warning*/*notice*).

---

## [1.1.1] - 2026-11-11 (Verifikasi Hijau MySQL 8.4 & Penutupan Defect CI)

### Fixed & Verified
- **`DEF-CI-002` (`CLOSED`):** Mengutip identifier SQL dengan backtick (`` `row_number` ``, dll.) di `app/Services/EtlMigrationService.php` untuk kompatibilitas penuh dengan keyword cadangan window function pada MySQL 8.4.
- **GitHub Actions CI Run `42910953354` (`PASS`):** Seluruh 3 job CI utama (`Code Lint & Config Validation`, `MySQL 8.4 Init, Automated Suite & Smoke Tests`, dan `Docker Nginx Build & Security Hygiene Audit`) lulus hijau di `origin/main` (commit `96dc718`), menutup total `6` defect terdokumentasi di `docs/testing/DEFECT_LOG.md`.

---

## [1.1.0] - 2026-11-10 (Audit RPS 16 Minggu, Penambahan Test Suite, ETL & Laporan Final)

### Added
- **Automated Test Suite (`tests/`):** Menambahkan `phpunit.xml`, `tests/bootstrap.php`, `tests/HttpClient.php`, `tests/run_all_tests.php`, 3 Unit Test classes (`FinancialBookkeepingUnitTest`, `OrderStateTransitionUnitTest`, `EtlMigrationUnitTest`), 4 Live-HTTP Feature Test classes (`AuthAndRbacTest`, `BlackBoxEpBvaTest`, `ApiAndIntegrationTest`, `SecurityOwaspTest`), koleksi Postman v2.1 (`tests/postman/`), dan Test Plan Apache JMeter Tier 1–4 (`tests/jmeter/jumiang_load_test.jmx`).
- **REST API v1 Lengkap & Server Preview Hardening:** Menambahkan `app/Http/Controllers/Api/ApiV1Controller.php`, 7 endpoint REST API `/api/v1/*` di `routes/api.php`, router JSON `/api/v1/*`, endpoint `/health`, RBAC guard `requireRole()` (`403 Forbidden`), panel `/admin/etl`, sanitasi XSS, dan HTTP Security Headers di `public/index.php`.
- **Modul ETL & Migrasi Survei UMKM:** Menambahkan `app/Services/EtlMigrationService.php`, migrasi `2026_11_02_000008_create_migration_quarantine_logs_table.php`, tabel `migration_quarantine_logs` (tabel ke-23 di `database/jumiang_database.sql`), `database/seeders/DatabaseSeeder.php`, berkas contoh `database/samples/raw_umkm_survey.csv`, dan utilitas CLI `scripts/etl_import.php`, `scripts/benchmark_local.php`, & `scripts/backup_verify.php`.
- **Konfigurasi Nginx & Controller/Exception Inti:** Menambahkan `docker/nginx/default.conf` (di-copy di `Dockerfile`), `app/Http/Controllers/Controller.php`, dan `app/Exceptions/SecurityException.php`.
- **Deliverable Lengkap RPS Minggu 03–16:** Menambahkan `BLACKBOX_EP_BVA_REPORT.md`, `STATE_TRANSITION_DECISION_TABLE.md`, `WHITE_BOX_TESTING.md`, `UTS_AUDIT_REPORT.md`, `PERFORMANCE_BASELINE_REPORT.md`, `IMPLEMENTATION_STRATEGY_PLAN.md`, `RECONCILIATION_AUDIT_REPORT.md`, `DEPLOYMENT_REPORT.md`, `PIR_AND_MAINTENANCE_PLAN.md`, `USER_MANUAL.md`, dan `FINAL_PROJECT_REPORT.md`.

### Fixed & Updated
- **Order & UMKM FSM Guard:** Menambahkan validasi transisi status (`VALID_TRANSITIONS` & rollback stok `READY_FOR_PICKUP`) pada `OrderProcessingService.php` + `OrderController.php`, serta `VALID_VERIFICATION_TRANSITIONS` pada `UmkmService.php`.
- **DEF-AUD-001 s/d DEF-AUD-003 (`CLOSED`):** Memperbaiki referensi `\SecurityException`, mengaktifkan RBAC `403` + escaping XSS di `public/index.php`, serta memperbaiki idempotensi rekonsiliasi ETL pada eksekusi ulang database.
- **Kepatuhan Dokumentasi ISO 29119:** Memperbarui `TEST_CASE_MATRIX.md`, `TEST_SUMMARY.md`, `SECURITY_TESTING.md`, `UAT_DOCUMENTATION.md`, `WEEK_02..16.md`, `README.md`, dan `PROJECT_CONTEXT.md` hanya berdasarkan hasil uji nyata (`19/19 PASS` suite otomatis, ETL & backup verification `PASS`, serta pelabelan jujur `NOT TESTED` untuk tool eksternal yang belum terpasang lokal).

---

## [1.0.1] - 2026-11-09 (Aktivasi CI/CD GitHub Actions & Perbaikan Smoke Test)

### Added
- **GitHub Actions CI/CD Workflow:** Menambahkan [.github/workflows/ci-cd.yml](../.github/workflows/ci-cd.yml) dengan 4 job (`code-lint-and-validation`, `database-and-smoke-tests`, `docker-and-security-audit`, dan `deploy-staging` opsional) pada event `push`/`pull_request` ke `main` & `staging`.
- **Dokumentasi CI/CD:** Memperbarui [deployment/CI_CD_DOCUMENTATION.md](deployment/CI_CD_DOCUMENTATION.md) dengan referensi workflow aktif.

### Fixed
- **Handler Detail Produk (`public/index.php`):** Menambahkan fungsi `renderProductDetail()` untuk rute `/produk/{slug}` yang sebelumnya memicu `Fatal error` saat uji smoke test (`DEF-CI-001`, status `CLOSED` setelah retest 29 rute `PASS`).

---

## [1.0.0] - 2026-11-02 (Rilis Penuh 16 Pertemuan Selesai - Milestone UAS)

### Added (Ditambahkan Secara Komprehensif)
- **Modul 01 Authentication & RBAC:** Multi-role login, registrasi wisatawan, proteksi rute `RoleMiddleware`, dan tombol demo quick-fill untuk 6 peran pengguna.
- **Modul 02 Dashboard Analytics:** Ringkasan statistik agregat database riil untuk seluruh role pengguna.
- **Modul 03 Tourism Management:** Profil destinasi Pantai Jumiang, daya tarik tebing karang, fasilitas umum, dan jam operasional.
- **Modul 04 Digital Tourism Map (GIS):** Peta spasial interaktif berbasis Leaflet.js & OpenStreetMap (WGS84) dengan filter layer kategoris (Wisata, UMKM, Fasilitas, Homestay, Parkir) dan popups.
- **Modul 05 UMKM Management:** Pendaftaran gerai pedagang lokal, profil merchant, verifikasi legalitas, dan pinpoint koordinat.
- **Modul 06 Product Management:** CRUD produk UMKM, galeri foto, validasi harga positif (BVA), satuan, dan batas minimum stok.
- **Modul 07 Marketplace & Orders:** Katalog belanja publik, filter kategori, keranjang belanja, form checkout, dan gateway simulasi pembayaran digital QRIS/Virtual Account.
- **Modul 08 Inventory Management:** Buku mutasi stok barang (masuk, keluar, penyesuaian), pengurangan stok otomatis saat transaksi lunas, dan restock rollback jika pesanan dibatalkan.
- **Modul 09 Financial Management:** Pembukuan kas mandiri UMKM terpisah secara arsitektural (Rekening Kas Usaha vs Rekening Tabungan Pribadi), pencatatan arus kas, dan Laporan Laba Rugi sederhana yang dapat dicetak (Print PDF).
- **Modul 10 Reservation Management:** Pemesanan paket wisata rombongan / tiket masuk online, penerbitan kode booking unik, dan loket validasi check-in kedatangan.
- **Modul 11 Partnership Management:** Arsip kerja sama kemitraan institusi (akademisi KKN, dinas pemerintah daerah, komunitas, dan sponsor).
- **Modul 12 Executive Information System (EIS):** Intelijen bisnis eksekutif, grafik tren omzet bulanan, diagram proporsi sektor usaha (Chart.js), dan matriks kinerja gerai UMKM.
- **Modul 13 Reporting & Analytics:** Rekapitulasi laporan transaksi, arus kas keuangan, dan reservasi terpadu dengan filter rentang tanggal.
- **Modul 14 Audit Trail:** Pencatatan log aktivitas pengguna penting dengan JSON inspector perubahan data lama vs baru, user ID, IP address, dan timestamp.
- **Basis Data:** 7 file Laravel Migration, 20 model Eloquent dengan relasi lengkap, serta file inisialisasi basis data siap pakai `database/jumiang_database.sql`.
- **Infrastruktur DevOps:** Konfigurasi `Dockerfile` (PHP 8.3 FPM + Nginx) dan `docker-compose.yml` (App + MySQL 8.4).
- **Dokumentasi Akademik Lengkap:** Seluruh 16 file catatan pertemuan `docs/meeting-notes/WEEK_01.md` hingga `WEEK_16.md` telah diselesaikan secara utuh sesuai silabus RPS MANPRO.

### Verified & Tested (Diverifikasi)
- Server preview lokal terintegrasi aktif pada `http://127.0.0.1:8000`.
- Seluruh 14 modul fungsional merespons dengan HTTP status 200 OK.
- Ketiadaan kebocoran data antar-merchant UMKM melalui *Tenant Data Isolation*.
- Seluruh data awal berlabel transparan `[DATA DEMO / SIMULASI]`.
