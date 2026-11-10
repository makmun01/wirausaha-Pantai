# POST-IMPLEMENTATION REVIEW (PIR) & MAINTENANCE PLAN
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 15

---

## 1. EVALUASI PASCA-IMPLEMENTASI (POST-IMPLEMENTATION REVIEW)

### A. Pencapaian Tujuan Bisnis & Akademik
| Dimensi Evaluasi | Target Awal (`PRD.md` / RPS) | Realisasi Saat Ini | Catatan Review |
|---|---|---|---|
| **Integrasi Ekosistem Wisata & UMKM** | Portal informasi, GIS Leaflet, Marketplace, Pembukuan Terpisah, EIS | **Terwujud penuh (14 modul Web + 7 endpoint REST API v1)** | Teruji dengan 19 automated test & CLI ETL |
| **Pemisahan Keuangan Usaha vs Pribadi** | Tabel rekening `BUSINESS` & `PERSONAL` + isolasi tenant | **Terwujud & diverifikasi (`SecurityException`)** | Laba Rugi otomatis memfilter akun `umkm_merchant_id` |
| **Kesiapan Operasional Lapangan** | Mudah didemokan tanpa instalasi kompleks + siap Docker | **Dual mode: Preview ringan (`public/index.php`) & Docker + Laravel** | Mempermudah pelatihan awal Pokdarwis/UMKM |

### B. Lessons Learned
1. **Celah Preview vs Produksi harus disinkronkan:** Ditemukan lebih awal (`DEF-CI-001`, `DEF-AUD-002`) bahwa entrypoint standalone `public/index.php` harus ikut menegakkan RBAC `403`, JSON API `/api/v1/*`, dan sanitasi XSS agar pengujian lokal maupun CI mencerminkan kontrak keamanan Laravel.
2. **Kualitas Data Lapangan Memerlukan Karantina:** Survei UMKM nyata sering memiliki format nomor HP & koordinat tidak standar; penambahan modul `EtlMigrationService` + `migration_quarantine_logs` mencegah data kotor masuk ke tabel produksi.

---

## 2. SERVICE LEVEL AGREEMENT (SLA) & RENCANA PEMELIHARAAN

| Kategori Insiden | Deskripsi Contoh | Target Respon Awal | Target Pemulihan | Penanggung Jawab |
|---|---|---|---|---|
| **P1 - Kritis** | Web/Database mati total, kerusakan korup data keuangan | <= 1 jam | <= 4 jam (restore snapshot `jumiang_database.sql`) | Super Admin / Tim Teknis |
| **P2 - Tinggi** | Error transaksi checkout atau API peta gagal dimuat | <= 4 jam | <= 24 jam | Developer / Pengelola |
| **P3 - Sedang** | Penyesuaian data profil gerai UMKM atau tarif demo | <= 1 hari kerja | <= 3 hari kerja | Admin Pokdarwis |
| **P4 - Rendah** | Permintaan laporan baru atau penyempurnaan tata letak | <= 3 hari kerja | Masuk sprint rilis minor berikutnya | Tim Pengembang |

---

## 3. FINAL IMPROVEMENT BACKLOG (PASCA-UAS)
1. **Survei Data Definitif Pantai Jumiang:** Mengganti seluruh label `[DATA DEMO / SIMULASI]` dengan profil gerai UMKM, foto asli, harga resmi, dan tarif retribusi terverifikasi Pokdarwis Desa Tanjung via `/admin/etl` (`scripts/etl_import.php`).
2. **Integrasi Payment Gateway Nyata (QRIS Dinamis/Midtrans):** Mengganti simulator pembayaran pada `OrderProcessingService::confirmSimulationPayment()` setelah memperoleh legalitas rekening BUMDes/Pokdarwis.
3. **Eksekusi Uji Beban Penuh 500 VU & DAST Eksternal:** Menjalankan `tests/jmeter/jumiang_load_test.jmx` dan OWASP ZAP CLI di server staging VPS berspesifikasi produksi.
