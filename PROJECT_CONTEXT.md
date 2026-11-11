# PROJECT CONTEXT: JUMIANG SMART TOURISM & UMKM

## 1. Identitas Proyek
- **Nama Proyek:** JUMIANG SMART TOURISM & UMKM (Integrated Tourism Map, Marketplace, Financial Management & Executive Information System)
- **Studi Kasus:** Kawasan Wisata Bahari Pantai Jumiang, Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura, Jawa Timur.
- **Acuan Akademik:** Rencana Pembelajaran Semester (RPS) Manajemen Proyek Sistem Informasi (MANPRO) & Software Quality Assurance (SQA) - 16 Pertemuan.
- **Referensi Konseptual:** Artikel *"Transformasi Digital UMKM: Integrasi Manajemen Keuangan dengan Platform Peta Wisata dan Marketplace"*
- **Status Proyek Saat Ini:** **SELESAI 100% (Pertemuan 1 s/d 16 - Milestone UAS)**

---

## 2. Realisasi Solusi Terintegrasi
1. **Frontend:** Antarmuka publik bertema wisata bahari dan dashboard administratif multi-role berbasis Bootstrap 5.3, Alpine.js, Chart.js, dan Leaflet.js (`public/index.php` + Blade templates).
2. **Backend:** Arsitektur Modular Monolith berbasis Laravel (MVC, 13 Service Classes termasuk `EtlMigrationService`, `OrderProcessingService` FSM guard, `ApiV1Controller`, `SecurityException`, dan RBAC Middleware).
3. **Database:** 23 tabel relasional MySQL 8.4 (8 file migrasi + `DatabaseSeeder`) dengan file inisialisasi instan `database/jumiang_database.sql` (termasuk tabel `migration_quarantine_logs`).
4. **Automated Testing, ETL & Ops:** 23 Automated Unit & Feature HTTP Tests (`tests/run_all_tests.php`), Postman collection (`tests/postman/`), JMeter plan (`tests/jmeter/`), CLI ETL (`scripts/etl_import.php`), Benchmark (`scripts/benchmark_local.php`), Backup Check (`scripts/backup_verify.php`), Nginx config (`docker/nginx/default.conf`), serta GitHub Actions CI/CD.
5. **Dokumentasi & Meeting Notes:** Seluruh 16 catatan pertemuan (`docs/meeting-notes/WEEK_01.md` s/d `WEEK_16.md`), laporan pengujian EP/BVA/FSM/WhiteBox/UTS/Performance/Security/UAT, User Manual, dan Final UAS Report tersedia lengkap.

---

## 3. Matriks 14 Modul Sistem
| No | Kode Modul | Nama Modul | Status Implementasi | Target Role Utama |
|---|---|---|---|---|
| 1 | `MOD-AUTH` | Authentication & RBAC | SELESAI (100%) | Semua Role |
| 2 | `MOD-DASH` | Dashboard Analytics | SELESAI (100%) | Semua Role (Spesifik Role) |
| 3 | `MOD-TOUR` | Tourism Management | SELESAI (100%) | Pengelola Wisata, Wisatawan |
| 4 | `MOD-GMAP` | Digital Tourism Map (GIS) | SELESAI (100%) | Wisatawan, Pengelola Wisata |
| 5 | `MOD-UMKM` | UMKM Management | SELESAI (100%) | Pelaku UMKM, Super Admin |
| 6 | `MOD-PROD` | Product Management | SELESAI (100%) | Pelaku UMKM |
| 7 | `MOD-MKT` | Marketplace & Orders | SELESAI (100%) | Wisatawan, Pelaku UMKM |
| 8 | `MOD-INVN` | Inventory Management | SELESAI (100%) | Pelaku UMKM |
| 9 | `MOD-FIN` | Financial Management | SELESAI (100%) | Pelaku UMKM, Bendahara |
| 10 | `MOD-RESV` | Reservation Management | SELESAI (100%) | Wisatawan, Pengelola Wisata |
| 11 | `MOD-PART` | Partnership Management | SELESAI (100%) | Super Admin, Pimpinan |
| 12 | `MOD-EIS` | Executive Info System | SELESAI (100%) | Pimpinan, Pengelola Wisata |
| 13 | `MOD-REPT` | Reporting & Analytics | SELESAI (100%) | Super Admin, Bendahara, Pimpinan |
| 14 | `MOD-AUDT` | Audit Trail & Activity Log | SELESAI (100%) | Super Admin |

---

## 4. Matriks 6 Role Pengguna
- **Super Admin:** Kontrol penuh modul teknis, verifikasi UMKM, kemitraan, dan audit trail.
- **Pengelola Wisata:** Manajemen destinasi, fasilitas, peta wisata, dan validasi check-in reservasi.
- **Pelaku UMKM:** Manajemen profil toko, produk, mutasi stok, pesanan masuk, dan buku kas mandiri terpisah.
- **Bendahara:** Pengawasan arus kas kawasan, rekonsiliasi pembayaran, dan pembukuan bersama.
- **Pimpinan:** Akses analitik eksekutif EIS (grafik omzet, proporsi sektor, pengambilan kebijakan).
- **Wisatawan:** Navigasi peta spasial, belanja produk UMKM, dan reservasi tiket online.
