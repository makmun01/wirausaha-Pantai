# CATATAN PERTEMUAN MINGGU 06
## Topik: REST API Endpoints & Integration Testing (Postman/Newman)
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Perancangan RESTful API v1 dan standarisasi format respon JSON.
- Pengujian integrasi antarmodul (Inter-module Integration Testing).
- Verifikasi alur data end-to-end: Order -> Mutasi Stok -> Jurnal Kas Keuangan UMKM.

---

### B. Fitur yang Dikembangkan
1. Pembuatan endpoint REST API `/api/v1/map-markers` pada `ApiMapMarkerController` untuk melayani layer peta Leaflet.js.
2. Konfigurasi route API pada `routes/api.php`.
3. Standarisasi struktur response payload (status, data, meta koordinat pusat Jumiang).

---

### C. File yang Ditambah atau Diubah
- `app/Http/Controllers/Api/ApiMapMarkerController.php`
- `app/Http/Controllers/Api/ApiV1Controller.php`
- `routes/api.php` (7 endpoint `/api/v1/*`)
- `public/index.php` (`handleApiRequest()` untuk pratinjau JSON REST API)
- `tests/postman/jumiang_api_collection.json` & `tests/postman/test_env.json`
- `tests/Feature/ApiAndIntegrationTest.php`
- `docs/testing/API_TESTING.md`

---

### D. Pengujian yang Dilakukan
- Pengujian HTTP GET pada endpoint `/api/v1/map-markers`.
- Pengujian filter kategori (`?category=UMKM`, `?category=WISATA`).
- Uji integrasi data saat transaksi lunas: Order tersimpan -> Inventory log terbit -> Jurnal kas tercatat.

---

### E. Expected Result
- Respon API mengembalikan HTTP 200 OK dengan payload JSON valid.
- Data konsisten di seluruh tabel relasional.

---

### F. Actual Result
- Endpoint merespon dengan cepat (< 100 ms pada pengujian lokal).
- Filter kategori mengembalikan data marker sesuai kriteria.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect terbuka.

---

### I. Perbaikan yang Dilakukan
Penambahan metadata koordinat pusat dan level zoom default pada respon API untuk memudahkan integrasi client peta.

---

### J. Progres Keseluruhan Proyek
**37.5% (Minggu 6 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Penyempurnaan Peta Wisata Digital (Leaflet.js), modul Reservasi Tiket, otomatisasi pengujian, dan manajemen defect.
