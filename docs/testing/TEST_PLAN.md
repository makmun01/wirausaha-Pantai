# MASTER TEST PLAN (ISO/IEC/IEEE 29119)
## JUMIANG SMART TOURISM & UMKM
**Software Quality Assurance & Verification/Validation Strategy**

---

## 1. PENDAHULUAN & TUJUAN PENGUJIAN

### 1.1 Tujuan
Dokumen Master Test Plan ini mendefinisikan strategi, ruang lingkup, lingkungan, metodologi, dan kriteria evaluasi kualitas untuk sistem **JUMIANG SMART TOURISM & UMKM**. Rencana ini disusun sesuai standar **ISO/IEC/IEEE 29119** untuk menjamin keterpenuhan spesifikasi fungsional dan non-fungsional pada setiap tahap perkuliahan RPS MANPRO (16 Pertemuan).

### 1.2 Prinsip Integritas Pengujian
1. **No Fake Results:** Dilarang mencatat hasil pengujian tanpa eksekusi riil.
2. **Definisi Status Resmi:**
   - `NOT TESTED`: Belum dieksekusi.
   - `PASS`: Seluruh kriteria keberhasilan terpenuhi tanpa anomali.
   - `FAIL`: Ditemukan deviasi dari expected result.
   - `BLOCKED`: Terhalang oleh komponen prasyarat yang belum siap/gagal.
   - `FIXED`: Perbaikan telah di-commit ke repositori, menunggu retest.
   - `RETESTED`: Pengujian ulang berhasil memverifikasi perbaikan.

---

## 2. RUANG LINGKUP PENGUJIAN (TEST SCOPE)

### 2.1 Fitur Dalam Ruang Lingkup (In-Scope)
- **Modul 01 - Auth & RBAC:** Multi-role login, registrasi, reset password, autorisasi 6 role.
- **Modul 02 - Dashboard:** Akurasi kalkulasi metrik database dan rendering Chart.js.
- **Modul 03 - Tourism Info:** Kelola konten profil Pantai Jumiang, atraksi tebing, dan fasilitas.
- **Modul 04 - Digital Tourism Map (GIS):** Titik marker koordinat WGS84, filter layer Leaflet.js, popup interaktif.
- **Modul 05 - UMKM Management:** Pendaftaran usaha, verifikasi status, profil merchant.
- **Modul 06 - Product Management:** CRUD produk, validasi harga dan foto, stok minimum.
- **Modul 07 - Marketplace:** Keranjang, checkout pesanan, simulasi QRIS & Virtual Account.
- **Modul 08 - Inventory:** Mutasi stok masuk/keluar, auto-deduction saat lunas, restock rollback saat batal.
- **Modul 09 - Financial Management:** Pemisahan buku kas pribadi vs usaha, laporan laba rugi, isolasi transaksi merchant.
- **Modul 10 - Reservation:** Pemesanan tiket masuk/paket rombongan, validasi check-in.
- **Modul 11 - Partnership:** Pencatatan arsip MoU dan status kerja sama institusi.
- **Modul 12 - EIS:** KPI eksekutif, grafik tren omzet UMKM, laporan pimpinan.
- **Modul 13 - Reporting:** Filter tanggal laporan, ekspor PDF dan format tabular.
- **Modul 14 - Audit Trail:** Pencatatan log aktivitas krusial, ketahanan data append-only.

### 2.2 Fitur di Luar Ruang Lingkup (Out-of-Scope)
- Integrasi riil ke Payment Gateway perbankan komersial (Midtrans/Xendit) pada fase ini — sepenuhnya menggunakan gateway simulasi internal.
- Pelacakan GPS real-time dinamis wisatawan bergerak di atas air (hanya titik stasioner darat/pesisir WGS84).

---

## 3. STRATEGI & METODOLOGI PENGUJIAN (V-MODEL MAPPING)

| Level Pengujian | Metodologi & Alat | Jadwal Pelaksanaan | Fokus Verifikasi |
|---|---|---|---|
| **Unit Testing** | PHPUnit / Pest | Pertemuan 5 & Kontinu | Pengujian fungsi bisnis inti: kalkulasi saldo, validasi kuantitas stok, diskon pesanan |
| **Black Box Testing** | EP (Equivalence Partitioning) & BVA (Boundary Value Analysis) | Pertemuan 3 | Form input harga, kuantitas stok, formulir registrasi UMKM, form login |
| **Behavior / State Testing** | Decision Table & State Transition | Pertemuan 4 | Status transisi pesanan (*Pending -> Paid -> Processing -> Ready -> Completed/Cancelled*) |
| **White Box Testing** | Control Flow Graph & Cyclomatic Complexity | Pertemuan 5 | Analisis jalur independen logika bisnis pada Service Classes |
| **API & Integration Testing** | Postman, Newman CLI, Laravel HTTP Tests | Pertemuan 6 | Konsistensi antarmodul: Order -> Inventory Deduction -> Ledger Entry |
| **Regression Testing** | Automated Feature Test Suite | Pertemuan 7, 8 (UTS), 15 | Memastikan modul terdahulu tidak mengalami kerusakan akibat penambahan fitur baru |
| **Performance Testing** | Apache JMeter | Pertemuan 9 | Waktu respons, throughput, dan daya tahan pada 10, 50, 100, hingga 500 virtual users |
| **Security Testing** | OWASP ZAP & Security Audit | Pertemuan 10 | SQLi, XSS, CSRF, Session Fixation, Broken Access Control (Bypass role) |
| **Acceptance Testing (UAT)** | Skenario Role Terpandu | Pertemuan 11 | Uji penerimaan pengguna oleh 6 persona (Admin, Pengelola, UMKM, Bendahara, Pimpinan, Wisatawan) |
| **Disaster Recovery Testing**| Script Backup & Restore Rollback | Pertemuan 16 (UAS) | Prosedur pemulihan data jika terjadi kegagalan sistem |

---

## 4. LINGKUNGAN PENGUJIAN (TEST ENVIRONMENT)

- **OS Server Testing:** Windows 10/11 & Linux Ubuntu 22.04 LTS (Containerized via Docker)
- **Web Server:** Nginx / Apache
- **PHP Version:** PHP 8.3+ (CLI & FPM)
- **Database:** MySQL 8.4 (Testing Database: `jumiang_tourism_test`)
- **Browser Targets:** Google Chrome (Desktop & Mobile Emulation), Mozilla Firefox, Microsoft Edge.
- **Testing Tools:** Pest/PHPUnit, Postman/Newman, Apache JMeter, OWASP ZAP.

---

## 5. KRITERIA MASUK & KELUAR PENGUJIAN (ENTRY & EXIT CRITERIA)

### 5.1 Kriteria Masuk (Entry Criteria)
- Kode fitur telah lolos kompilasi/linting tanpa *syntax error*.
- Migrasi dan seeder database untuk lingkungan pengujian berhasil dijalankan (`php artisan migrate:fresh --seed`).
- Dokumen test scenario dan test case telah disiapkan di `TEST_CASE_MATRIX.md`.

### 5.2 Kriteria Keluar (Exit Criteria)
- 100% test case fungsional kritis (*Severity: High / Critical*) berstatus `PASS` atau `RETESTED`.
- 0 defect berstatus `FAIL` yang belum ditangani.
- Semua defect yang diperbaiki telah diverifikasi ulang dengan status `RETESTED`.
- Laporan ringkasan pengujian mingguan selesai dan tercatat di file catatan pertemuan `WEEK_[XX].md`.
