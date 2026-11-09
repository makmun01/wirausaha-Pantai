# CATATAN PERTEMUAN MINGGU 03
## Topik: Black Box Testing: Equivalence Partitioning (EP) & Boundary Value Analysis (BVA), Modul Wisata & UMKM
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Konsep dan penerapan Black Box Testing: Equivalence Partitioning (EP) dan Boundary Value Analysis (BVA).
- Pengujian validasi form input pengguna (rentang nilai batas atas, batas bawah, dan nilai invalid).
- Implementasi modul pariwisata (profil Pantai Jumiang) dan registrasi gerai UMKM.

---

### B. Fitur yang Dikembangkan
1. Implementasi `TourismService` dan `PublicController@about` menampilkan profil Pantai Jumiang, daya tarik tebing karang, dan fasilitas.
2. Implementasi `UmkmService` dan `UmkmController` untuk registrasi gerai UMKM dan verifikasi mitra.
3. Pembuatan tampilan antarmuka `resources/views/public/about.blade.php` dan `resources/views/admin/umkm/index.blade.php`.
4. Implementasi form input produk dengan pembatasan batas nilai harga (BVA: `price >= 0`) dan stok minimum.

---

### C. File yang Ditambah atau Diubah
- `app/Services/TourismService.php`
- `app/Services/UmkmService.php`
- `app/Http/Controllers/Web/UmkmController.php`
- `resources/views/public/about.blade.php`
- `resources/views/admin/umkm/index.blade.php`

---

### D. Pengujian yang Dilakukan
- Uji Equivalence Partitioning pada input harga:
  - Partisi Valid: Angka positif (misal Rp 25.000) -> Diterima.
  - Partisi Tidak Valid: Angka negatif (misal -Rp 10.000) atau teks string -> Ditolak dengan pesan validasi.
- Uji Boundary Value Analysis pada batas minimum stok (0 unit = ditandai habis, 1-5 unit = ditandai peringatan stok menipis).

---

### E. Expected Result
- Validasi sistem menolak harga negatif atau input tidak valid sebelum tersimpan di basis data.
- Profil gerai UMKM baru berstatus `PENDING` hingga diverifikasi oleh Super Admin.

---

### F. Actual Result
- Validasi form produk dan registrasi merchant menolak data di luar batas partisi ekuivalen.
- Verifikasi UMKM berjalan lancar.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect aktif.

---

### I. Perbaikan yang Dilakukan
Penambahan indikator badge peringatan visual pada produk dengan stok di bawah batas ambang (*low stock threshold*).

---

### J. Progres Keseluruhan Proyek
**18.75% (Minggu 3 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Implementasi modul Marketplace dan Order Processing dengan Decision Table dan State Transition Testing.
