# USER ACCEPTANCE TESTING (UAT)
## JUMIANG SMART TOURISM & UMKM
**User Acceptance Testing Plan, Scenarios, & Simulation Matrix (Pertemuan 11)**

---

## 1. PENDEKATAN UAT (PERTEMUAN 11)
UAT bertujuan memverifikasi kesesuaian alur kerja end-to-end 14 modul sistem terhadap kebutuhan operasional 6 pemangku kepentingan kawasan Pantai Jumiang, Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan.

*Catatan Integritas Data (Sesuai `AGENTS.md`): Karena validasi tatap muka langsung dengan pelaku UMKM dan pengelola lapangan di Pantai Jumiang dijadwalkan pada tahap implementasi pasca-rilis akademik, seluruh skenario di bawah ini dijalankan dalam mode `[SIMULASI PERSONA UAT]` menggunakan data awal `[DATA DEMO / SIMULASI]`.*

---

## 2. HASIL EKSEKUSI SKENARIO 6 PERSONA `[SIMULASI PERSONA UAT]`

| Kode UAT | Persona Role | Alur Skenario yang Diuji | Verifikasi Teknis & HTTP | Catatan Penerimaan Simulasi | Status |
|---|---|---|---|---|---|
| **UAT-01** | **Wisatawan** (`wisatawan`) | Eksplorasi beranda, peta Leaflet WGS84, katalog oleh-oleh, detail `/produk/rengginang-lorjuk-super`, order simulasi API & bayar QRIS | `BlackBoxEpBvaTest` + `ApiAndIntegrationTest` + `AuthAndRbacTest` -> `200`, `201`, & `403` saat membuka `/admin/*` | Diterima (Alur belanja, filter peta, & proteksi role berjalan mulus) | **PASS** `[SIMULASI PERSONA UAT]` |
| **UAT-02** | **Pelaku UMKM** (`pelaku_umkm`) | Akses `/admin/products`, `/admin/financial`, API `/api/v1/umkm/my-finances`, pemisahan akun `BUSINESS` vs `PERSONAL` | `AuthAndRbacTest` + `FinancialBookkeepingUnitTest` -> `200`, isolasi `403` di `/admin/umkm` | Diterima (Pembukuan usaha & tabungan pribadi terpisah tegas) | **PASS** `[SIMULASI PERSONA UAT]` |
| **UAT-03** | **Pengelola Wisata** (`pengelola_wisata`) | Login quick-demo role `pengelola_wisata`, akses dashboard & `/admin/reservations`, peta `/peta-wisata` | `AuthAndRbacTest` -> HTTP `200` pada `/dashboard` dan `/admin/reservations` (smoke route check) | Diterima (Navigasi peta & check-in reservasi terstruktur) | **PASS** `[SIMULASI PERSONA UAT]` |
| **UAT-04** | **Bendahara** (`bendahara`) | Login role `bendahara`, akses `/admin/financial`, `/admin/financial/profit-loss`, `/admin/reports` | `AuthAndRbacTest` -> Login & dashboard `200` | Diterima (Rekap laba rugi dan arus kas tersedia) | **PASS** `[SIMULASI PERSONA UAT]` |
| **UAT-05** | **Pimpinan / Kepala Desa** (`pimpinan`) | Login role `pimpinan`, akses `/admin/eis` & grafik Chart.js tren omzet kawasan | `AuthAndRbacTest` -> `/demo-login?role=pimpinan` (`200`), EIS diakses | Diterima (Grafik omzet makro mudah dibaca) | **PASS** `[SIMULASI PERSONA UAT]` |
| **UAT-06** | **Super Admin** (`super_admin`) | Akses penuh `/admin/umkm`, `/admin/partnerships`, `/admin/audit-logs`, `/admin/etl`, export CSV template | `AuthAndRbacTest::test_pelaku_umkm_and_super_admin_role_boundaries` -> seluruh rute `200` | Diterima (Kontrol verifikasi UMKM, audit, dan ETL berjalan) | **PASS** `[SIMULASI PERSONA UAT]` |

---

## 3. STATUS TANDA TANGAN RESPONDEN LAPANGAN RIIL
- **Validasi Responden Fisik Pantai Jumiang (Pokdarwis & UMKM Desa Tanjung):** `NOT TESTED` (Menunggu agenda survei lapangan lanjutan untuk konversi `[DATA DEMO / SIMULASI]` ke data riil operasional).
