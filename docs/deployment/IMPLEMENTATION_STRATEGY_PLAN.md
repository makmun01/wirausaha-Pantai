# IMPLEMENTATION & ROLLOUT STRATEGY PLAN
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 12

---

## 1. ANALISIS PERBANDINGAN STRATEGI TRANSISI (CUTOVER)

| Strategi Implementasi | Deskripsi | Kelebihan untuk Konteks Pantai Jumiang | Risiko Utama | Keputusan |
|---|---|---|---|---|
| **Direct Cutover (Big Bang)** | Menghentikan pencatatan manual/kertas seketika dan beralih penuh ke sistem baru | Biaya operasional ganda rendah | Risiko tinggi bila literasi digital pelaku UMKM pesisir belum merata | Ditolak |
| **Parallel Running** | Sistem digital berjalan berdampingan dengan pembukuan manual UMKM selama 1 bulan | Aman secara akuntansi, mudah rekonsiliasi | Beban kerja ganda bagi pedagang saat jam ramai wisatawan | Alternatif terbatas untuk Bendahara |
| **Phased Implementation** | Rilis modul bertahap (Fase 1 Portal/Peta -> Fase 2 Marketplace -> Fase 3 Keuangan) | Mengurangi guncangan adaptasi | Membutuhkan manajemen versi antarmodul lebih lama | Dipadukan dengan Pilot |
| **Pilot Rollout (TERPILIH)** | Uji coba terkendali pada 3–5 gerai UMKM percontohan (*Sentra Lorjuk, Batik Pesisir, Homestay*) & Pos Tiket Pokdarwis sebelum perluasan kawasan | Risiko kegagalan terlokalisasi, dukungan pendampingan intensif oleh KKN/Tim IT | Perlu kesabaran pedagang non-pilot menunggu gelombang berikutnya | **DIPILIH SEBAGAI STRATEGI UTAMA** |

---

## 2. RENCANA MANAJEMEN PERUBAHAN (CHANGE MANAGEMENT GUIDE)
1. **Pendampingan On-Site Pokdarwis & UMKM:** Pelatihan 2 jam per kelompok (input stok, penggunaan simulator kas usaha terpisah dari kas rumah tangga).
2. **Transisi Data Demo ke Data Riil:** Admin mengganti seluruh entitas bertanda `[DATA DEMO / SIMULASI]` dengan hasil survei resmi menggunakan fitur **ETL Import CSV** (`MOD-ETL` pada `/admin/etl`) dan form CRUD `MOD-UMKM`/`MOD-TOUR`.
3. **Saluran Dukungan Cepat:** Hotline WhatsApp Koordinator Teknis Desa Tanjung + User Manual dwifungsi (admin & pelaku usaha) di [docs/USER_MANUAL.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/docs/USER_MANUAL.md).

---

## 3. SMOKE TEST REPORT PRA-ROLLOUT (MINGGU 12)
Pengujian asap seluruh rute publik, API, login 6 peran, `/admin/partnerships`, `/admin/audit-logs`, dan RBAC dijalankan melalui `php tests/run_all_tests.php`:
- **Total Pengujian Terotomasi:** 19 Skenario (`10 Unit` + `9 Feature Live HTTP`)
- **Hasil Eksekusi Aktual:** **19 PASS, 0 FAIL** dalam `993.32 ms` (PHP 8.0.30 & HTTP `127.0.0.1:18085`).
- **Status Kesiapan Pra-Rollout:** **PASS**
