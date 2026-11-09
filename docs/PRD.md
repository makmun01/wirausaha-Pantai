# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## JUMIANG SMART TOURISM & UMKM
**Integrated Tourism Map, Marketplace, Financial Management & Executive Information System**

| Dokumen ID | PRD-JSTU-2026-V1.0 |
|---|---|
| Versi | 1.0.0 |
| Tanggal Rilis | 2 November 2026 |
| Status | DRAFT / TAHAP PERENCANAAN MINGGU 1 |
| Pemilik Proyek | Tim Pengembang MANPRO Pantai Jumiang |
| Studi Kasus | Kawasan Wisata Bahari Pantai Jumiang, Desa Tanjung, Kab. Pamekasan |
| Standar Kualitas | ISO/IEC 25010 (SQA) & ISO/IEC/IEEE 29119 (Software Testing) |

---

## 1. PENDAHULUAN & LATAR BELAKANG

### 1.1 Latar Belakang Studi Kasus
Pantai Jumiang merupakan salah satu destinasi wisata bahari unggulan di Kabupaten Pamekasan, Madura, Jawa Timur. Terletak di Desa Tanjung, Kecamatan Pademawu, kawasan ini memiliki karakteristik lanskap tebing karang yang eksotis, perairan tenang, dan sentra komoditas hasil laut. 

Potensi ekonomi Pantai Jumiang ditopang oleh keberadaan puluhan pelaku UMKM lokal yang memproduksi olahan pangan khas (seperti keripik lorjuk, olahan terasi Madura, petis ikan, ikan asap, dan rengginang lorjuk), penyedia cinderamata batik pesisir Pamekasan, serta penginapan/homestay warga. 

Meskipun memiliki potensi tinggi, ekosistem wisata dan UMKM Pantai Jumiang masih menghadapi kesenjangan digital:
1. **Visibilitas Destinasi & Lokasi UMKM yang Terpencar:** Wisatawan kesulitan menemukan lokasi fasilitas penting (toilet, musholla, spot swafoto tebing, titik aman bermain air, area parkir) dan gerai UMKM yang lokasinya menyebar.
2. **Kelemahan Literasi Pembukuan Keuangan UMKM:** Berdasarkan kajian empiris, pelaku UMKM pesisir umumnya mencampuradukkan uang pribadi dan uang operasional usaha tanpa pencatatan kas teratur, sehingga sulit mengetahui margin keuntungan bersih atau mengajukan pembiayaan usaha.
3. **Pemasaran Konvensional:** Belum adanya marketplace lokal terpadu yang memungkinkan wisatawan melakukan pemesanan produk UMKM sebelum atau saat berkunjung ke lokasi.
4. **Ketiadaan Sistem Pendukung Keputusan (EIS):** Pengelola wisata dan instansi dinas terkait tidak memiliki data riil mengenai fluktuasi kunjungan, volume penjualan produk lokal, dan perputaran uang kawasan wisata.

### 1.2 Landasan Konseptual
Proyek ini mengacu pada kajian:
> *"Transformasi Digital UMKM: Integrasi Manajemen Keuangan dengan Platform Peta Wisata dan Marketplace"*

Konsep kunci yang diimplementasikan:
- Digitalisasi pembukuan kas mandiri untuk pemisahan rekening pribadi vs usaha.
- Peta spasial interaktif berbasis web (Web-GIS) untuk penandaan lokasi atraksi dan titik gerai UMKM.
- Etalase e-commerce lokal untuk pemberdayaan ekonomi masyarakat pesisir secara berkelanjutan.
- Simulasi transaksi terintegrasi untuk menjembatani adopsi sistem non-tunai.

### 1.3 Acuan Akademik
Sistem ini dibangun mengikuti silabus **RPS Manajemen Proyek Sistem Informasi (MANPRO)** selama 16 minggu siklus pengembangan dan pengujian perangkat lunak terstruktur.

---

## 2. TUJUAN SISTEM (SYSTEM OBJECTIVES)

1. **Integrasi Ekosistem Pariwisata dan Ekonomi Lokal:** Menghubungkan informasi destinasi Pantai Jumiang dengan etalase digital UMKM lokal dalam satu wadah terpadu.
2. **Pemberdayaan Finansial Mandiri:** Menyediakan modul pembukuan keuangan yang sederhana, memisahkan kas pribadi dan usaha, serta menghitung laba kotor dan laba bersih secara otomatis.
3. **Peningkatan Layanan Wisatawan:** Mempermudah navigasi wisatawan melalui peta interaktif berbasis koordinat WGS84 dan kemudahan pemesanan paket/tiket wisata.
4. **Penyajian Data Strategis (Executive Information System):** Menyediakan ringkasan eksekutif, analitik tren, dan visualisasi performa bisnis bagi Pengelola dan Pimpinan.
5. **Kepatuhan Kualitas Perangkat Lunak:** Memastikan seluruh modul dibangun dengan arsitektur bersih (*Clean Architecture*), teruji secara formal (ISO 29119), dan dapat dipertahankan (*maintainable*).

---

## 3. IDENTIFIKASI STAKEHOLDER & PERSONA PENGGUNA

| Role | Karakteristik Persona | Kebutuhan Utama | Hak Akses Utama |
|---|---|---|---|
| **Super Admin** | Administrator Teknis & TI | Konfigurasi sistem, audit trail, pengelolaan master data, pemulihan sistem | *Full Access* (Semua Modul) |
| **Pengelola Wisata** | Badan Pengelola Wisata Desa Tanjung | Pembaruan profil wisata, pengelolaan fasilitas, tiket masuk, validasi reservasi | Modul Wisata, Peta, Reservasi, Laporan |
| **Pelaku UMKM** | Pedagang olahan laut, souvenir, kuliner | Menjual produk, memantau pesanan masuk, mencatat mutasi stok dan kas usaha | Modul UMKM, Produk, Toko, Stok, Finansial Usaha |
| **Bendahara** | Penanggung Jawab Keuangan Kawasan | Verifikasi rekonsiliasi kas, validasi arus kas masuk/keluar bersama | Modul Finansial Umum, Laporan Keuangan, Audit |
| **Pimpinan** | Kepala Desa / Dinas Pariwisata Pamekasan | Evaluasi performa ekonomi, monitoring tren wisatawan untuk pengambilan kebijakan | Modul Dashboard EIS, Laporan Strategis (Read-Only) |
| **Wisatawan** | Pengunjung domestik / mancanegara | Eksplorasi spot wisata, navigasi peta, reservasi tiket, belanja oleh-oleh lokal | Modul Publik, Katalog, Peta, Keranjang, Reservasi |

---

## 4. KEBUTUHAN FUNGSIONAL SISTEM (FUNCTIONAL REQUIREMENTS)

### 4.1 Modul 1: Authentication & User Management (`MOD-AUTH`)
- **FR-AUTH-01:** Sistem menyediakan autentikasi multi-role (Super Admin, Pengelola Wisata, Pelaku UMKM, Bendahara, Pimpinan, Wisatawan).
- **FR-AUTH-02:** Sistem menyediakan proteksi rute berbasis Role-Based Access Control (RBAC).
- **FR-AUTH-03:** Sistem menyediakan fitur registrasi publik bagi Wisatawan dan formulir pendaftaran mitra Pelaku UMKM.
- **FR-AUTH-04:** Sistem menyediakan fitur reset password dengan token terenkripsi dan verifikasi session.
- **FR-AUTH-05:** Sistem mencatat waktu login terakhir, status akun (aktif/nonaktif), dan profil pengguna.

### 4.2 Modul 2: Dashboard Analytics (`MOD-DASH`)
- **FR-DASH-01:** Menampilkan kartu statistik ringkasan berbasis kueri database aktual: Total UMKM terverifikasi, Total Produk Aktif, Total Transaksi Sukses, dan Total Estimasi Pendapatan.
- **FR-DASH-02:** Menampilkan grafik interaktif pergerakan transaksi bulanan menggunakan Chart.js.
- **FR-DASH-03:** Menampilkan daftar aktivitas transaksi dan reservasi terbaru (*recent activities*).
- **FR-DASH-04:** Menyajikan tampilan dashboard spesifik sesuai kewenangan role yang sedang masuk.

### 4.3 Modul 3: Tourism Management (`MOD-TOUR`)
- **FR-TOUR-01:** Menyajikan informasi lengkap destinasi Pantai Jumiang (sejarah, letak geografis, daya tarik tebing dan pantai).
- **FR-TOUR-02:** Mengelola galeri foto dan video destinasi secara terstruktur.
- **FR-TOUR-03:** Mengelola daftar fasilitas umum (musholla, kamar mandi, gazebo, penyewaan pelampung, spot swafoto).
- **FR-TOUR-04:** Mengelola jam operasional dan tarif retribusi/tiket (dengan penanda data demo sebelum validasi lapangan resmi).

### 4.4 Modul 4: Digital Tourism Map (`MOD-GMAP`)
- **FR-GMAP-01:** Menampilkan peta spasial interaktif menggunakan Leaflet.js dengan tile OpenStreetMap.
- **FR-GMAP-02:** Mengelola titik marker koordinat (Latitude & Longitude berbasis format WGS84).
- **FR-GMAP-03:** Menyediakan filter kategori layer marker: Daya Tarik Wisata, Gerai UMKM, Fasilitas Umum, Area Parkir, dan Homestay.
- **FR-GMAP-04:** Menampilkan popup interaktif berisi nama lokasi, foto thumbnail, jam buka, dan tautan detail/rute.
- **FR-GMAP-05:** Fitur pencarian lokasi marker berdasarkan kata kunci.

### 4.5 Modul 5: UMKM Management (`MOD-UMKM`)
- **FR-UMKM-01:** Pelaku UMKM dapat mendaftarkan profil usaha (nama usaha, nama pemilik, NIK/NIB jika ada, kategori usaha, nomor WhatsApp, deskripsi).
- **FR-UMKM-02:** Mengatur alamat fisik dan pinpoint lokasi koordinat gerai pada peta.
- **FR-UMKM-03:** Super Admin / Pengelola Wisata dapat memverifikasi, menangguhkan, atau menolak pendaftaran UMKM.
- **FR-UMKM-04:** Menampilkan profil publik gerai UMKM yang terhubung langsung dengan katalog produknya.

### 4.6 Modul 6: Product Management (`MOD-PROD`)
- **FR-PROD-01:** Pelaku UMKM dapat menambah, mengubah, dan menghapus data produk usahanya.
- **FR-PROD-02:** Mengelola atribut produk: nama, kategori (Kuliner, Kerajinan, Fashion/Batik, Jasa/Sewa), harga satuan, foto produk, deskripsi, dan status ketersediaan.
- **FR-PROD-03:** Menetapkan jumlah stok awal dan batas minimum peringatan stok (*low-stock threshold*).

### 4.7 Modul 7: Marketplace & Orders (`MOD-MKT`)
- **FR-MKT-01:** Wisatawan dapat menjelajahi katalog produk dengan fitur pencarian dan filter harga/kategori.
- **FR-MKT-02:** Wisatawan dapat menambahkan produk ke keranjang belanja (*Shopping Cart*).
- **FR-MKT-03:** Fitur checkout dengan pemilihan opsi pengambilan (ambil di tempat / diantar ke homestay) dan catatan pesanan.
- **FR-MKT-04:** Sistem simulasi pembayaran digital (Virtual Account Demo / QRIS Demo) yang menghasilkan invoice dan nomor referensi transaksi simulasi.
- **FR-MKT-05:** Pelaku UMKM menerima notifikasi pesanan masuk dan dapat memperbarui status pesanan (Menunggu Pembayaran -> Dibayar -> Diproses -> Siap Diambil -> Selesai -> Dibatalkan).

### 4.8 Modul 8: Inventory Management (`MOD-INVN`)
- **FR-INVN-01:** Sistem mencatat riwayat mutasi stok (Stok Masuk dari produksi/pembelian bahan, Stok Keluar karena penjualan, Penyesuaian stok rusak/kadaluarsa).
- **FR-INVN-02:** Pengurangan stok otomatis terjadi saat pesanan berstatus *Paid* (Lunas).
- **FR-INVN-03:** Pengembalian stok (*restock rollback*) otomatis jika pesanan dibatalkan.
- **FR-INVN-04:** Memberikan peringatan visual jika stok produk berada di bawah batas minimum.

### 4.9 Modul 9: Financial Management (`MOD-FIN`)
- **FR-FIN-01:** Menyediakan pemisahan buku kas: Rekening Kas Usaha UMKM vs Rekening Kas Pribadi.
- **FR-FIN-02:** Pencatatan pemasukan (otomatis dari penjualan marketplace atau manual dari penjualan offline gerai).
- **FR-FIN-03:** Pencatatan pengeluaran (biaya bahan baku, tenaga kerja, sewa tempat, listrik/operasional).
- **FR-FIN-04:** Laporan Arus Kas (*Cash Flow Statement*) dan Laporan Laba Rugi sederhana per periode waktu yang dapat difilter dan dicetak.
- **FR-FIN-05:** Menjamin data keuangan antar UMKM terisolasi penuh (UMKM A tidak dapat melihat pembukuan UMKM B).

### 4.10 Modul 10: Reservation Management (`MOD-RESV`)
- **FR-RESV-01:** Wisatawan dapat melakukan pemesanan paket wisata atau tiket rombongan Pantai Jumiang.
- **FR-RESV-02:** Menginput tanggal rencana kunjungan, jumlah rombongan, dan data kontak pemesan.
- **FR-RESV-03:** Pengelola wisata dapat memvalidasi kedatangan (*check-in barcode/code*) di lokasi pintu masuk wisata.

### 4.11 Modul 11: Partnership Management (`MOD-PART`)
- **FR-PART-01:** Pencatatan basis data kemitraan (instansi pemerintah daerah, universitas/akademisi KKN, asosiasi UMKM, penyedia CSR).
- **FR-PART-02:** Mengarsipkan dokumen nota kesepahaman (MoU), durasi kerja sama, kontak person, dan status keaktifan kemitraan.

### 4.12 Modul 12: Executive Information System (`MOD-EIS`)
- **FR-EIS-01:** Menyajikan ringkasan indikator kinerja utama (KPI) kawasan wisata dan UMKM bagi Pimpinan.
- **FR-EIS-02:** Grafik tren pendapatan kawasan bulanan dan kuartalan.
- **FR-EIS-03:** Grafik tren kategori produk paling laris dan perputaran ekonomi per klaster UMKM.
- **FR-EIS-04:** Komparasi volume kunjungan wisatawan terhadap omzet UMKM untuk pertimbangan pengambilan keputusan dan alokasi anggaran pembangunan fasilitas.

### 4.13 Modul 13: Reporting & Analytics (`MOD-REPT`)
- **FR-REPT-01:** Ekspor laporan transaksi penjualan dalam format PDF dan Excel.
- **FR-REPT-02:** Ekspor laporan mutasi persediaan stok per UMKM.
- **FR-REPT-03:** Ekspor laporan laba rugi dan ringkasan arus kas keuangan.
- **FR-REPT-04:** Ekspor laporan statistik reservasi dan kunjungan wisatawan.

### 4.14 Modul 14: Audit Trail & Activity Log (`MOD-AUDT`)
- **FR-AUDT-01:** Mencatat seluruh aksi penting pengguna (login, perubahan harga, persetujuan UMKM, penghapusan produk, transaksi keuangan).
- **FR-AUDT-02:** Menyimpan riwayat perubahan nilai lama (*old values*) dan nilai baru (*new values*), ID pengguna, alamat IP, dan timestamp.
- **FR-AUDT-03:** Data audit trail bersifat *append-only* dan tidak dapat dihapus oleh pengguna standar.

---

## 5. KEBUTUHAN NON-FUNGSIONAL (NON-FUNCTIONAL REQUIREMENTS)

### 5.1 NFR-01: Performance & Efficiency
- Waktu respons pemuatan halaman rata-rata maksimal 2 detik pada kondisi koneksi normal.
- Query database dioptimalkan dengan indeks pada foreign key dan kolom pencarian utama.
- Dukungan aset gambar terkompresi (WebP/JPEG) untuk menghemat bandwidth koneksi pengunjung di kawasan pesisir.

### 5.2 NFR-02: Security & Integrity
- Proteksi terhadap kerentanan OWASP Top 10 (SQL Injection melalui Eloquent parameterized query, XSS melalui Blade escaping, CSRF protection pada semua form POST/PUT/DELETE).
- Password pengguna dienkripsi menggunakan algoritma bcrypt standar industri.
- Session timeout dan validasi otorisasi ganda pada setiap controller dan service layer.

### 5.3 NFR-03: Usability & Accessibility
- Desain antarmuka responsif (*Mobile First*) yang nyaman digunakan di perangkat smartphone, tablet, dan desktop (Bootstrap 5.3).
- Tata letak UI mengadopsi estetika wisata bahari (palet biru laut, putih pasir, abu-abu netral) dengan navigasi intuitif.
- Komponen interaktif menggunakan micro-animation dan feedback status visual yang jelas.

### 5.4 NFR-04: Reliability & Maintainability
- Arsitektur berbasis Modular Monolith dengan *Service Layer Pattern* sehingga kode mudah dibaca, diuji secara terisolasi, dan dirawat.
- Pengujian otomatis mencakup Unit Testing dan Feature Testing dengan target cakupan jalur kritis.

### 5.5 NFR-05: Portability & Deployment
- Aplikasi siap dijalankan di container Docker dan server Linux standar (Ubuntu 22.04 LTS / 24.04 LTS).
- Konfigurasi aplikasi terpusat pada file `.env` tanpa hardcoded credential.

---

## 6. PENJAMINAN MUTU PERANGKAT LUNAK (SQA) & METRIK ISO/IEC 25010

| Karakteristik Kualitas (ISO 25010) | Metrik Pengukuran | Kriteria Keberhasilan (Acceptance Criteria) |
|---|---|---|
| **Functional Suitability** | Test Case Pass Rate | 100% test case prioritas tinggi (*High*) berstatus PASS |
| **Performance Efficiency** | Response Time & JMeter Load Test | P95 Response time < 1500 ms di bawah 50 virtual users |
| **Compatibility** | Cross-Browser & Mobile Responsiveness | Tampilan dan fungsi konsisten di Chrome, Edge, Safari, Firefox, dan Mobile View |
| **Usability** | UAT Score & Task Completion | Rata-rata kemudahan tugas pada UAT > 85% untuk setiap role |
| **Reliability** | Error Rate pada Stress Test | Error rate < 1% selama simulasi pengujian transaksi |
| **Security** | OWASP Vulnerability Severity | 0 kerentanan kritis (*Critical/High Severity*) pada OWASP ZAP scan |
| **Maintainability** | Cyclomatic Complexity & Clean Code | Kompleksitas siklomatis fungsi inti < 10, ketiadaan duplikasi kode |

---

## 7. MODEL PENGEMBANGAN V-MODEL
Hubungan tahap spesifikasi dengan pengujian:

```
[Kebutuhan Sistem / PRD] <===============================> [User Acceptance Testing (UAT - Pertemuan 11)]
       \                                                              /
    [Desain Arsitektur] <============================> [System & Load Testing (Pertemuan 9 & 10)]
           \                                                /
        [Desain Modul & Database] <===========> [Integration & API Testing (Pertemuan 6)]
               \                                    /
            [Implementasi Kode / Unit] <==> [Unit Testing & Whitebox (Pertemuan 5)]
```

---

## 8. ATURAN INTEGRITAS DATA & PENANDA DATA DEMO
1. Mengingat ketiadaan data riil awal hasil survei langsung, seluruh data destinasi, tarif, nama pedagang, dan katalog produk awal diberi label: `[DATA DEMO / SIMULASI]`.
2. Dilarang mengklaim integrasi gateway pembayaran perbankan nyata sebelum kerja sama resmi; sistem menggunakan **Simulasi Virtual Account & QRIS Demo**.
3. Sistem menyediakan modul pengelolaan data master bagi Pengelola dan Super Admin untuk memperbarui data demo menjadi data definitif setelah observasi lapangan selesai.
