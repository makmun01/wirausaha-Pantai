# PERFORMANCE & LOAD TESTING PLAN
## JUMIANG SMART TOURISM & UMKM
**Stress, Load & Concurrency Verification with Apache JMeter**

---

## 1. TUJUAN PENGUJIAN PERFORMA
Mengukur ketahanan dan waktu respons aplikasi di bawah beban lalu lintas bersamaan (*concurrent users*) sesuai materi RPS Pertemuan 9:
- Memverifikasi halaman publik beranda dan peta wisata Leaflet.
- Memverifikasi katalog belanja marketplace produk UMKM.
- Memverifikasi endpoint REST API geospasial.
- Mengidentifikasi potensi bottleneck pada kueri basis data dan cache.

---

## 2. SKENARIO PENGUJIAN BEBAN BERTINGKAT (LOAD PROFILE)

| Skenario | Virtual Users (Threads) | Ramp-Up Period | Durasi Pengujian | Target Response Time (P95) | Target Max Error Rate |
|---|---|---|---|---|---|
| **Tier 1 - Baseline** | 10 Users | 5 detik | 60 detik | < 800 ms | 0.00% |
| **Tier 2 - Moderate** | 50 Users | 15 detik | 120 detik | < 1200 ms | < 0.50% |
| **Tier 3 - Peak Load** | 100 Users | 30 detik | 180 detik | < 1800 ms | < 1.00% |
| **Tier 4 - Stress Test** | 500 Users (Kapasitas Server) | 60 detik | 300 detik | < 2500 ms | < 2.00% |

---

## 3. METRIK YANG DIAMATI
1. **Average & 95th Percentile Response Time:** Waktu tempuh request hingga respon selesai diterima browser.
2. **Throughput (Requests per Second):** Jumlah transaksi per detik yang mampu diproses server web dan database.
3. **Error Rate Percentage:** Persentase respons gagal (HTTP 500/502/504).
4. **Server Resource Utilization:** Utilisasi CPU dan Memory pada container/server saat pengujian beban berlangsung.
