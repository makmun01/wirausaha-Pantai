# DATA MIGRATION PLAN
## JUMIANG SMART TOURISM & UMKM
**Data Extraction, Transformation, Validation, and Loading Strategy**

---

## 1. TUJUAN MIGRASI DATA (PERTEMUAN 13)
Memindahkan data hasil observasi dan survei lapangan awal (data master pelaku UMKM, katalog produk kerajinan/kuliner lorjuk, data fasilitas wisata, dan data kemitraan) dari format lembar kerja spreadsheet (CSV/Excel) ke dalam skema basis data relasional MySQL 8.4 secara nir-cacat (*zero data loss*).

---

## 2. TAHAPAN PROSES MIGRASI DATA
1. **Ekstraksi (Extract):** Pembacaan data mentah dari file template Excel/CSV yang telah diisi enumerator lapangan di Pantai Jumiang.
2. **Transformasi & Pembersihan (Transform & Clean):**
   - Normalisasi nomor telepon ke format internasional (`+62...`).
   - Format penulisan mata uang dibersihkan dari simbol (misal `Rp 25.000` menjadi `25000.00`).
   - Verifikasi rentang koordinat geografis (Latitude: -7.0 s/d -7.5, Longitude: 113.3 s/d 113.8 untuk wilayah Pamekasan).
3. **Pemuatan (Load):** Penyisipan data secara transaksional (`DB::transaction`) ke tabel `destinations`, `facilities`, `umkm_merchants`, `products`, dan `map_markers`.
4. **Verifikasi & Rekonsiliasi (Verification & Audit):**
   - Penghitungan jumlah baris sebelum vs sesudah migrasi (*Row Count Check*).
   - Validasi ketiadaan duplikasi NIK/Nama Usaha.
