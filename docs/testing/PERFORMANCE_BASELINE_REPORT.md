# PERFORMANCE BASELINE & LOAD TESTING REPORT
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 09

Dokumen ini mencatat hasil pengukuran baseline performa **nyata** yang dieksekusi pada lingkungan runtime lokal (`scripts/benchmark_local.php`), serta rancangan skrip pengujian beban skala besar untuk Apache JMeter (`tests/jmeter/jumiang_load_test.jmx`).

---

## 1. Hasil Pengukuran Baseline Nyata (`scripts/benchmark_local.php`)

- **Lingkungan Uji:** PHP `8.0.30` Development Server (`http://127.0.0.1:18085`), client multi-cURL PHP paralel lokal pada Windows.
- **Target Endpoint Rotasi (5 rute):** `/`, `/peta-wisata`, `/katalog-umkm`, `/api/v1/map-markers`, `/api/v1/products`.

| Skenario Beban | Total Requests | Concurrency | Avg Latency | P95 Latency | Max Latency | Throughput (RPS) | Error Rate | Status vs SLA (<2000ms, <1% err) |
|---|---|---|---|---|---|---|---|---|
| **Tier 1 - Baseline** | 20 | 5 | 30.74 ms | 50.01 ms | 54.48 ms | 83.74 req/s | 0 (0.00%) | **PASS** |
| **Tier 2 - Moderate** | 50 | 10 | 45.12 ms | 95.06 ms | 135.40 ms | 98.39 req/s | 0 (0.00%) | **PASS** |
| **Tier 3 - Peak Demo** | 100 | 20 | 52.29 ms | 93.29 ms | 102.44 ms | 177.79 req/s | 0 (0.00%) | **PASS** |

### Catatan Kejujuran Teknis
- Angka di atas diukur terhadap handler preview PHP ringan (`public/index.php`). Saat terhubung ke query MySQL penuh pada mode Laravel produksi, latensi bergantung pada indexing tabel (lihat skema indeks `idx_status`, `idx_category`, `idx_date` pada `database/jumiang_database.sql`) dan PHP OPcache + Nginx FastCGI (`docker/nginx/default.conf`).

---

## 2. Skrip Pengujian Beban Apache JMeter (Tier 1 s/d Tier 4: 10–500 VU)

Skrip XML Test Plan lengkap telah disiapkan di [tests/jmeter/jumiang_load_test.jmx](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/tests/jmeter/jumiang_load_test.jmx) mencakup 4 Thread Group sesuai RPS Minggu 09:
1. `Tier 1 - Baseline (10 VU)` -> `/api/v1/map-markers`
2. `Tier 2 - Moderate (50 VU)` -> `/api/v1/products`
3. `Tier 3 - Peak Demo (100 VU)` -> `/`
4. `Tier 4 - Stress Test (500 VU)` -> `/health`

- **Status Eksekusi `jmeter` CLI 500 VU Lokal:** `NOT TESTED` (Binary Apache JMeter tidak terpasang pada mesin pengembangan Windows lokal). Skrip `.jmx` siap dijalankan di lingkungan staging Linux/Windows yang memiliki Apache JMeter >= 5.6:
  ```bash
  jmeter -n -t tests/jmeter/jumiang_load_test.jmx -l docs/testing/reports/jmeter_results.jtl
  ```
