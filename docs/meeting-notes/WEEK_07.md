# CATATAN PERTEMUAN MINGGU 07
## Topik: Automated Testing, GIS Leaflet Integration, & Defect Management
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Otomatisasi pengujian perangkat lunak (*Automated Regression Testing*).
- Siklus hidup pengelolaan defect (*Defect Life-Cycle & Management Policy*).
- Integrasi sistem informasi geografis (GIS) web mapping interaktif.

---

### B. Fitur yang Dikembangkan
1. Implementasi peta spasial interaktif Leaflet.js dengan OpenStreetMap tiles pada `resources/views/public/map.blade.php`.
2. Pengelompokan layer marker: Daya Tarik Wisata, Gerai UMKM, Fasilitas Umum, Homestay, dan Area Parkir.
3. Implementasi `ReservationService` dan antarmuka pemesanan tiket wisata online `resources/views/public/reservation.blade.php` serta check-in loket pengelola.
4. Pencatatan dan pembaruan `docs/testing/DEFECT_LOG.md`.

---

### C. File yang Ditambah atau Diubah
- `resources/views/public/map.blade.php`
- `app/Services/ReservationService.php`
- `resources/views/public/reservation.blade.php`
- `resources/views/admin/reservations/index.blade.php`
- `docs/testing/DEFECT_LOG.md`

---

### D. Pengujian yang Dilakukan
- Pengujian rendering peta Leaflet.js pada desktop dan mobile viewport.
- Uji alur pemesanan tiket wisata: generate booking code unik -> simpan di basis data -> simulasi check-in loket.
- Audit defect status lifecycle.

---

### E. Expected Result
- Peta interaktif memuat pin marker dengan popup informasi yang responsif.
- Reservasi tiket menghasilkan kode booking unik dan dapat divalidasi saat pengunjung tiba di lokasi.

---

### F. Actual Result
- Seluruh pin marker terpetakan dengan tepat pada koordinat Pantai Jumiang.
- Proses check-in berhasil mengubah status reservasi menjadi `ATTENDED`.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect terbuka.

---

### I. Perbaikan yang Dilakukan
Penambahan indikator warna spesifik per kategori marker (Biru untuk Wisata, Hijau untuk UMKM, Oranye untuk Homestay).

---

### J. Progres Keseluruhan Proyek
**43.75% (Minggu 7 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
UTS: Audit Mutu Sistem, Demonstrasi Sistem Fase 1, dan Regression Testing menyeluruh.
