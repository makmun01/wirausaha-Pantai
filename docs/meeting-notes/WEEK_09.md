# CATATAN PERTEMUAN MINGGU 09
## Topik: Performance & Load Testing dengan Apache JMeter & Modul EIS
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Analisis kinerja dan pengujian beban aplikasi web (*Performance & Load Testing*).
- Pengukuran metrik kualitas: Waktu respons (*Response Time*), Throughput (RPS), dan Tingkat Kesalahan (*Error Rate*).
- Simulasi beban bertingkat (*Tiered Concurrency*: 10, 50, 100, hingga 500 virtual users).

---

### B. Fitur yang Dikembangkan
1. Implementasi modul Executive Information System (`ExecutiveAnalyticsService` dan `EisController`).
2. Visualisasi analitik makro menggunakan Chart.js (grafik tren penjualan bulanan dan diagram proporsi sektor usaha).
3. Penyusunan rancangan skrip uji beban Apache JMeter pada [PERFORMANCE_TESTING.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/docs/testing/PERFORMANCE_TESTING.md).

---

### C. File yang Ditambah atau Diubah
- `app/Services/ExecutiveAnalyticsService.php`
- `app/Http/Controllers/Web/EisController.php`
- `resources/views/admin/dashboard/pimpinan.blade.php`
- `resources/views/admin/eis/index.blade.php`
- `tests/jmeter/jumiang_load_test.jmx` (Skrip JMeter Tier 1–4: 10, 50, 100, 500 VU)
- `scripts/benchmark_local.php`
- `docs/testing/PERFORMANCE_TESTING.md` & `docs/testing/PERFORMANCE_BASELINE_REPORT.md`

---

### D. Pengujian yang Dilakukan
- Eksekusi nyata `scripts/benchmark_local.php` menggunakan multi-cURL paralel terhadap 5 rute (`/`, `/peta-wisata`, `/katalog-umkm`, `/api/v1/map-markers`, `/api/v1/products`) pada beban Tier 1 (5 conc/20 req), Tier 2 (10 conc/50 req), dan Tier 3 (20 conc/100 req).
- Penyiapan skrip Apache JMeter `tests/jmeter/jumiang_load_test.jmx` hingga 500 VU (status CLI `jmeter` lokal: `NOT TESTED`).

---

### E. Expected Result
- Waktu respons rata-rata di bawah 1.500 ms pada beban normal (NFR-01).
- Error rate 0% selama pengujian beban lokal.

---

### F. Actual Result
- Tier 1 (5 conc, 20 req): Avg `30.74 ms`, P95 `50.01 ms`, RPS `83.74`, `0.00%` error (**PASS**).
- Tier 2 (10 conc, 50 req): Avg `45.12 ms`, P95 `95.06 ms`, RPS `98.39`, `0.00%` error (**PASS**).
- Tier 3 (20 conc, 100 req): Avg `52.29 ms`, P95 `93.29 ms`, RPS `177.79`, `0.00%` error (**PASS**).
- Eksekusi JMeter CLI 500 VU pada mesin lokal: **NOT TESTED** (binary Apache JMeter tidak terpasang lokal).

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect aktif.

---

### I. Perbaikan yang Dilakukan
Optimasi kueri eager loading (`with(['merchant', 'category'])`) untuk mencegah masalah *N+1 Query Problem*.

---

### J. Progres Keseluruhan Proyek
**56.25% (Minggu 9 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Pengujian Keamanan Sistem mengacu pada standar OWASP Top 10 (SQL Injection, XSS, CSRF, dan Broken Access Control).
