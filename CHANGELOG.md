# CHANGELOG - JUMIANG SMART TOURISM & UMKM

Semua perubahan penting pada proyek ini didokumentasikan di file ini dengan mengacu pada prinsip Keep a Changelog dan Semantic Versioning.

---

## [1.0.1] - 2026-11-09 (Aktivasi CI/CD GitHub Actions & Perbaikan Smoke Test)

### Added
- **GitHub Actions CI/CD Workflow:** Menambahkan [.github/workflows/ci-cd.yml](.github/workflows/ci-cd.yml) dengan 4 job (`code-lint-and-validation`, `database-and-smoke-tests`, `docker-and-security-audit`, dan `deploy-staging` opsional) pada event `push`/`pull_request` ke `main` & `staging`.
- **Dokumentasi CI/CD:** Memperbarui [docs/deployment/CI_CD_DOCUMENTATION.md](docs/deployment/CI_CD_DOCUMENTATION.md) dengan referensi workflow aktif.

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
