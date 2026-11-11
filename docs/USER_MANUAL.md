# BUKU PANDUAN PENGGUNA (USER MANUAL)
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 16

> **Penanda Integritas Data:** Seluruh akun demo dan nilai numerik bawaan berstatus **[DATA DEMO / SIMULASI]**. Admin/Pengelola dapat memperbarui data menjadi data operasional lapangan Pantai Jumiang secara bertahap.

---

## 1. CARA MENJALANKAN SISTEM

### A. Mode Preview Instan (Lokal PHP CLI / XAMPP)
```bash
php -S 127.0.0.1:8000 -t public
```
Buka browser ke `http://127.0.0.1:8000`.

### B. Mode Kontainer Docker + MySQL 8.4
```bash
docker compose up -d --build
```
Aplikasi berjalan di `http://localhost:8000` (Nginx + PHP-FPM) dan phpMyAdmin di `http://localhost:8081`.

---

## 2. PANDUAN BERDASARKAN 6 ROLE PENGGUNA

Masuk melalui halaman **`/login`** untuk memilih cepat role demo (`/demo-login?role={nama_role}`):

1. **Wisatawan (`wisatawan`)**
   - Menjelajahi beranda (`/`), profil wisata (`/tentang-jumiang`), dan Peta Digital Leaflet WGS84 (`/peta-wisata`).
   - Memilih produk oleh-oleh khas Jumiang di `/katalog-umkm`, melihat detail `/produk/{slug}`, serta memesan tiket masuk/rombongan di `/reservasi-wisata`.
   - Tidak diperbolehkan membuka rute `/admin/*` (diblokir dengan status HTTP `403 Forbidden`).
2. **Pelaku UMKM (`pelaku_umkm`)**
   - Mengelola produk di **`/admin/products`** (harga minimal `0`, peringatan stok menipis) dan mutasi inventori di **`/admin/inventory`**.
   - Mengelola **Buku Kas Mandiri Terpisah** di **`/admin/financial`** yang memisahkan **Rekening Kas Usaha (`BUSINESS`)** dari **Tabungan Pribadi (`PERSONAL`)**, serta mencetak **Laporan Laba Rugi** di **`/admin/financial/profit-loss`**.
   - Mengawasi pesanan masuk melalui **`/admin/orders`**.
3. **Pengelola Wisata / Pokdarwis (`pengelola_wisata`)**
   - Mengelola kedatangan wisatawan dan validasi check-in reservasi di **`/admin/reservations`** serta memantau pesanan kawasan.
4. **Bendahara Kawasan (`bendahara`)**
   - Memeriksa pembukuan arus kas kawasan (`MOD-FIN`), dashboard ringkasan **`/admin/eis`**, dan laporan **`/admin/reports`**.
5. **Pimpinan / Kepala Desa (`pimpinan`)**
   - Memantau indikator kinerja makro omzet UMKM & tren kunjungan wisatawan pada **`/admin/eis`** serta rekap laporan di **`/admin/reports`**.
6. **Super Admin (`super_admin`)**
   - Kontrol penuh seluruh 14 modul termasuk verifikasi mitra UMKM (**`/admin/umkm`**), arsip MoU kemitraan (**`/admin/partnerships`**), penelusuran **Audit Trail** (**`/admin/audit-logs`**), dan **Modul ETL Import/Export CSV** (**`/admin/etl`**).

---

## 3. OPERASIONAL CLI TESTING, ETL & BACKUP (UNTUK OPERATOR/ADMIN)

- **Menjalankan Seluruh Automated Test Suite (23 Unit & Feature Tests):**
  ```bash
  php tests/run_all_tests.php
  ```
- **Dry-Run & Impor Data Survei UMKM CSV (`scripts/etl_import.php`):**
  ```bash
  php scripts/etl_import.php --dry-run
  php scripts/etl_import.php --apply
  ```
- **Verifikasi Integritas Backup & Restore Basis Data (`scripts/backup_verify.php`):**
  ```bash
  php scripts/backup_verify.php
  ```
- **Pengukuran Benchmark Performa Lokal (`scripts/benchmark_local.php`):**
  ```bash
  php scripts/benchmark_local.php http://127.0.0.1:8000
  ```
