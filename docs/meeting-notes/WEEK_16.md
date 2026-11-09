# CATATAN PERTEMUAN MINGGU 16
## Topik: UAS: Final Project Demonstration, Live Backup, & Rollback Disaster Recovery
**Status:** SELESAI (COMPLETED - FINAL RELEASE)

---

### A. Materi RPS Minggu Ini
- Evaluasi akhir semester (UAS): Demonstrasi menyeluruh sistem terintegrasi skala penuh.
- Presentasi arsitektur Modular Monolith, ERD basis data, dan ketercapaian kualitas ISO 29119.
- Simulasi pemulihan bencana (*Disaster Recovery, Database Backup & Version Rollback*).

---

### B. Fitur & Modul yang Ditampilkan pada Demonstrasi Final
1. **MOD-01 Auth & RBAC:** Multi-role login untuk 6 peran pengguna lengkap dengan tombol demo quick-fill.
2. **MOD-02 Dashboard Analytics:** Ringkasan metrik statistik real-time berbasis kueri database.
3. **MOD-03 Tourism Info:** Profil destinasi Pantai Jumiang, daya tarik tebing karang, fasilitas, dan jadwal operasional.
4. **MOD-04 Digital Map (GIS):** Peta spasial interaktif Leaflet.js dengan filter kategori marker (Wisata, UMKM, Fasilitas, Parkir, Homestay).
5. **MOD-05 UMKM Management:** Profil merchant lokal, pendaftaran, dan verifikasi status usaha.
6. **MOD-06 Product Management:** CRUD produk, validasi harga positif (BVA), foto, dan penanda stok menipis.
7. **MOD-07 Marketplace & Orders:** Katalog produk, filter kategori, keranjang belanja dinamis, checkout, dan simulator QRIS/VA.
8. **MOD-08 Inventory Management:** Buku mutasi stok barang (masuk, keluar, penyesuaian) dan auto-deduct saat order lunas.
9. **MOD-09 Financial Management:** Pembukuan kas mandiri terpisah (Akun Usaha vs Pribadi), arus kas masuk/keluar, dan laporan laba rugi yang dapat dicetak.
10. **MOD-10 Reservation Management:** Pemesanan tiket masuk/paket wisata rombongan dan validasi check-in kedatangan.
11. **MOD-11 Partnership Management:** Arsip kerja sama kemitraan institusi (akademisi, pemerintah daerah, sponsor).
12. **MOD-12 Executive Information System (EIS):** Intelijen bisnis eksekutif, grafik tren omzet bulanan, dan proporsi sektor usaha (Chart.js).
13. **MOD-13 Reporting & Analytics:** Rekapitulasi laporan transaksi, keuangan, dan reservasi terpadu.
14. **MOD-14 Audit Trail:** Log mutasi data sensitif (JSON diff inspector old vs new values, user, IP, timestamp).

---

### C. File yang Ditambah atau Diubah
- Penyelesaian seluruh file backend, frontend, database, skrip docker, dan dokumentasi akademik.
- Pembaruan dokumen [CHANGELOG.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/CHANGELOG.md) dan [PROJECT_CONTEXT.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/PROJECT_CONTEXT.md).

---

### D. Pengujian yang Dilakukan
- Demonstrasi live alur sistem dari sisi wisatawan, pedagang UMKM, bendahara, pengelola, hingga pimpinan.
- Simulasi disaster recovery: Eksekusi backup database `jumiang_database.sql` dan verifikasi rollback versi stabil.

---

### E. Expected Result
- Seluruh 14 modul operasional 100% tanpa kendala teknis.
- Integritas data keuangan UMKM terisolasi sempurna.
- Tampilan antarmuka responsif dan ramah pengguna di perangkat mobile maupun desktop.

---

### F. Actual Result
- Sistem berhasil berjalan secara utuh (Status PASS 100%).
- Bukti uji dan dokumentasi akademik lengkap tersedia di folder `docs/`.

---

### G. Status
**PASS (LULUS VERIFIKASI AKHIR UAS)**

---

### H. Defect yang Ditemukan
0 Defect (Seluruh defect telah ditutup dan lolos retest).

---

### I. Perbaikan yang Dilakukan
Penyediaan server preview lokal instan terintegrasi pada `public/index.php` sehingga pengguna dapat langsung menjalankan demonstrasi tanpa kendala dependensi CLI.

---

### J. Progres Keseluruhan Proyek
**100.0% (SELESAI PENUH 16 PERTEMUAN)**

---

### K. Rencana Tindak Lanjut
- Penyerahan dokumentasi final kepada Dosen Pengampu Mata Kuliah MANPRO.
- Pelaksanaan survei lapangan lanjutan di Pantai Jumiang bersama instansi terkait untuk memperbarui data demo menjadi data definitif lapangan.
