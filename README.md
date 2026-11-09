# JUMIANG SMART TOURISM & UMKM
> **Integrated Tourism Map, Marketplace, Financial Management & Executive Information System**
> Studi Kasus: Ekosistem Wisata Bahari Pantai Jumiang, Kabupaten Pamekasan, Jawa Timur.

[![Academic Project](https://img.shields.io/badge/Academic-MANPRO%20%26%20SQA-blue.svg)](#acuan-akademik)
[![Framework](https://img.shields.io/badge/Framework-Laravel-red.svg)](https://laravel.com)
[![Architecture](https://img.shields.io/badge/Architecture-Modular%20Monolith-green.svg)](#arsitektur-sistem)
[![GIS](https://img.shields.io/badge/GIS-Leaflet.js%20%7C%20OSM-brightgreen.svg)](#geographic-information-system)
[![Status](https://img.shields.io/badge/Project%20Status-Week%2001%20Planning-orange.svg)](#status-proyek)

---

## 1. Tentang Proyek
**JUMIANG SMART TOURISM & UMKM** adalah sistem informasi terintegrasi berskala enterprise yang dirancang untuk mendukung transformasi digital ekosistem wisata bahari Pantai Jumiang di Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura.

Aplikasi ini memadukan 4 pilar fungsional utama:
1. **Interactive Tourism Map (GIS):** Peta digital interaktif berbasis Leaflet.js & OpenStreetMap untuk navigasi titik daya tarik bahari, gerai UMKM, spot foto tebing, homestay, dan fasilitas umum.
2. **UMKM Digital Marketplace:** Etalase produk lokal khas Pamekasan (kuliner olahan laut seperti lorjuk, rengginang, keripik ikan, souvenir batik pamekasan) dilengkapi keranjang belanja dan simulasi transaksi digital.
3. **Dedicated Financial Management:** Pembukuan digital mandiri bagi pelaku UMKM yang memisahkan rekening/arus kas pribadi dengan usaha, menghasilkan laporan laba rugi sederhana secara akurat.
4. **Executive Information System (EIS):** Dashboard analitik visual (Chart.js) bagi Pengelola Wisata dan Pimpinan Daerah untuk memantau perputaran ekonomi, tren kunjungan wisatawan, dan kinerja penjualan UMKM.

Proyek ini dibangun berdasarkan acuan akademik **Rencana Pembelajaran Semester (RPS) Manajemen Proyek Sistem Informasi (MANPRO)** yang berjalan selama **16 pertemuan perkuliahan** terstruktur.

---

## 2. Matriks 14 Modul Sistem
| Kode | Nama Modul | Cakupan Fitur Utama |
|---|---|---|
| `MOD-01` | **Authentication & RBAC** | Multi-role login, user management, secure session, password reset. |
| `MOD-02` | **Dashboard Analytics** | Kartu statistik agregat database riil, grafik ringkasan aktivitas terkini. |
| `MOD-03` | **Tourism Management** | Profil Pantai Jumiang, daya tarik bahari, tebing, galeri foto, jam operasional. |
| `MOD-04` | **Digital Tourism Map** | Peta GIS interaktif Leaflet.js, marker kategoris (Wisata, UMKM, Fasilitas, Parkir). |
| `MOD-05` | **UMKM Management** | Registrasi UMKM, verifikasi status, profil merchant, koordinat toko fisik. |
| `MOD-06` | **Product Management** | CRUD produk UMKM, galeri foto, harga, satuan, batas minimum stok. |
| `MOD-07` | **Marketplace & Orders** | Katalog belanja, filter kategori, keranjang, checkout, simulasi payment. |
| `MOD-08` | **Inventory Management** | Pencatatan stok masuk, stok keluar, auto-deduct transaksi, low-stock warning. |
| `MOD-09` | **Financial Management** | Pembukuan kas UMKM terpisah, pencatatan pemasukan/pengeluaran, laba rugi. |
| `MOD-10` | **Reservation Management** | Reservasi paket wisata/rombongan, tiket masuk, validasi check-in kedatangan. |
| `MOD-11` | **Partnership Management** | Pencatatan data kerja sama institusi, akademisi, instansi dinas, dan sponsor. |
| `MOD-12` | **Executive Information System** | Ringkasan kinerja makro, grafik tren pendapatan, data pendukung keputusan pimpinan. |
| `MOD-13` | **Reporting & Analytics** | Laporan berkala (transaksi, stok, keuangan, reservasi), export PDF/Excel. |
| `MOD-14` | **Audit Trail & System Logs** | Log aktivitas pengguna krusial, riwayat perubahan data, proteksi integritas. |

---

## 3. Matriks 6 Role Pengguna
1. **Super Admin:** Kontrol penuh konfigurasi aplikasi, manajemen hak akses, dan audit trail.
2. **Pengelola Wisata:** Pengelolaan informasi destinasi, fasilitas, peta wisata, dan reservasi pengunjung.
3. **Pelaku UMKM:** Pengelolaan profil toko, etalase produk, mutasi stok, dan pembukuan arus kas usaha.
4. **Bendahara:** Pengawasan arus kas wisata, verifikasi pembayaran reservasi, dan laporan keuangan bersama.
5. **Pimpinan:** Akses eksekutif terhadap visualisasi dashboard EIS dan analitik strategis.
6. **Wisatawan:** Eksplorasi peta digital, belanja produk lokal, dan pemesanan paket wisata online.

---

## 4. Tech Stack Utama
- **Backend:** PHP 8.3+, Laravel (Modular Monolith MVC, Service Layer Pattern, Form Request, Eloquent ORM).
- **Frontend:** Laravel Blade, Bootstrap 5.3 (Mobile-First Responsive), Alpine.js, Bootstrap Icons.
- **GIS Engine:** Leaflet.js, OpenStreetMap tiles, WGS84 Geo-coordinates.
- **Visualisasi Data:** Chart.js (Interactive Bar, Line, Doughnut Charts).
- **Database:** MySQL 8.4 / MariaDB 10.4+, Migrations, Seeders, Factories.
- **Quality Assurance & Testing:** PHPUnit/Pest, Postman/Newman, Playwright, Apache JMeter, OWASP ZAP.
- **Infrastruktur:** Docker Container, GitHub Actions CI/CD Pipeline.

---

## 5. Struktur Dokumentasi Proyek (`docs/`)
```text
docs/
├── PRD.md                         # Product Requirements Document & ISO 25010 Quality Metrics
├── PROJECT_CONTEXT.md             # Gambaran umum, batas modul, dan identitas proyek
├── SYSTEM_ARCHITECTURE.md         # Desain arsitektur Modular Monolith, Service Layer, & Diagram
├── DATABASE_DESIGN.md             # Entity Relationship Diagram (ERD), skema tabel MySQL & Kamus Data
├── ROADMAP_16_WEEKS.md            # Roadmap komprehensif 16 pertemuan kuliah sesuai RPS MANPRO
├── CHANGELOG.md                   # Log kronologis perubahan versi aplikasi
│
├── testing/                       # Dokumentasi Pengujian Sistem (STLC & ISO 29119)
│   ├── TEST_PLAN.md               # Master Test Plan
│   ├── TEST_CASE_MATRIX.md        # Matriks Test Case terperinci seluruh modul
│   ├── DEFECT_LOG.md              # Log pencatatan dan siklus hidup bug/defect
│   ├── TEST_SUMMARY.md            # Rekapitulasi hasil pengujian berkala
│   ├── API_TESTING.md             # Dokumentasi pengujian REST API & Postman
│   ├── PERFORMANCE_TESTING.md     # Perencanaan & hasil pengujian beban Apache JMeter
│   ├── SECURITY_TESTING.md        # Laporan pengujian keamanan OWASP Top 10
│   └── UAT_DOCUMENTATION.md       # Skenario & lembar User Acceptance Testing
│
├── migration/                     # Perencanaan Migrasi & ETL Data
│   ├── DATA_MIGRATION_PLAN.md     # Strategi ekstraksi dan validasi data UMKM/Wisata
│   └── ETL_DOCUMENTATION.md       # Dokumentasi skrip transformasi data
│
├── deployment/                    # Infrastruktur & Operasional
│   ├── DEPLOYMENT_PLAN.md         # Prosedur deployment ke Production/Staging VPS
│   ├── CI_CD_DOCUMENTATION.md     # Konfigurasi pipeline otomatis GitHub Actions
│   └── BACKUP_ROLLBACK.md         # Prosedur pemulihan bencana (Disaster Recovery)
│
└── meeting-notes/                 # Catatan Progres & Bukti Pertemuan 1 - 16
    ├── WEEK_01.md                 # Perencanaan, V-Model, dan Inisiasi SQA
    ├── ...                        # (Catatan per minggu berjalan)
    └── WEEK_16.md                 # Final UAS & Penyerahan Sistem
```

---

## 6. Prasyarat Lingkungan Pengembangan (Prerequisites)
Sebelum menjalankan instalasi Laravel, pastikan perangkat pengembang telah dilengkapi:
1. **PHP:** Versi 8.2 atau 8.3+ dengan ekstensi aktif: `php-pdo`, `php-mysql`, `php-mbstring`, `php-xml`, `php-curl`, `php-gd`, `php-zip`.
2. **Composer:** Versi 2.6+ (Dependency Manager PHP).
3. **Node.js & NPM:** Node.js v18+ atau v20+ LTS dan NPM v9+.
4. **Database Server:** MySQL 8.4 atau MariaDB 10.4+ (misal via XAMPP/Laragon atau Docker).
5. **Git:** Versi 2.40+ untuk version control dan integrasi CI/CD.

---

## 7. Aturan Pengembangan (Governance)
Semua kontributor dan AI Assistant wajib mematuhi panduan pada [AGENTS.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/AGENTS.md):
- Dilarang mengulang proyek dari awal pada tiap pertemuan.
- Dilarang menghapus fitur minggu sebelumnya.
- Dilarang memalsukan hasil pengujian (wajib cantumkan `PASS`, `FAIL`, atau `NOT TESTED`).
- Setiap penambahan kode wajib dihubungkan dengan materi RPS minggu terkait.
