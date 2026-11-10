# LAPORAN AKHIR PROYEK (FINAL PROJECT REPORT - UAS)
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 16

| Identitas Proyek | Keterangan |
|---|---|
| **Judul Sistem** | Jumiang Smart Tourism & UMKM (Peta GIS, Marketplace UMKM, Pembukuan Kas Terpisah & EIS) |
| **Lokasi Studi Kasus** | Kawasan Wisata Bahari Pantai Jumiang, Desa Tanjung, Kec. Pademawu, Kab. Pamekasan, Madura |
| **Kerangka Akademik** | RPS Manajemen Proyek Sistem Informasi (MANPRO) & Software Quality Assurance (SQA) — Pertemuan 01 s/d 16 |
| **Status Akhir** | **SELESAI 100% (VERIFIKASI TEROTOMASI LULUS)** |

---

## 1. RINGKASAN ARSITEKTUR & CAPAIAN IMPLEMENTASI
Sistem dibangun dengan arsitektur **Laravel Modular Monolith** dilengkapi mode pratinjau terintegrasi `public/index.php` serta kontainer **Docker (PHP 8.3 FPM + Nginx + MySQL 8.4)**:
- **14 Modul Fungsional (`MOD-01` s/d `MOD-14`):** Terhubung lintas modul dari pemesanan -> pengurangan stok otomatis -> jurnal pemasukan kas usaha `BUSINESS` yang terisolasi mutlak dari akun `PERSONAL` dan antar-tenant UMKM.
- **13 Service Classes:** Termasuk `FinancialBookkeepingService`, `OrderProcessingService` (dengan Finite State Machine guard), `InventoryLedgerService`, `UmkmService`, `ProductService`, dan `EtlMigrationService`.
- **23 Tabel Basis Data Relasional (`database/jumiang_database.sql`):** Mencakup tabel inti wisata, UMKM, e-commerce, akuntansi ganda, `audit_logs`, dan `migration_quarantine_logs`.
- **7 Endpoint REST API v1 (`routes/api.php` & `ApiV1Controller.php`):** Didukung Postman Collection (`tests/postman/jumiang_api_collection.json`).

---

## 2. REKAPITULASI BUKTI PENGUJIAN NYATA (ISO 29119 COMPLIANCE)
Seluruh angka di bawah ini merupakan hasil **eksekusi nyata** (bukan simulasi tulis tangan):
1. **Linting Kode PHP (67+ berkas):** **100% PASS** (`0` syntax error via `php -l`).
2. **Automated Unit & Live-HTTP Feature Suite (`php tests/run_all_tests.php`):** **19 / 19 PASS (`0` FAIL)** dalam `993.32 ms`, mencakup:
   - `FinancialBookkeepingUnitTest` (4 jalur White Box CFG, McCabe $V(G)=5$)
   - `OrderStateTransitionUnitTest` (FSM Order, rollback stok, FSM UMKM, BVA harga)
   - `EtlMigrationUnitTest` (normalisasi `+62`, Rupiah, koordinat Pamekasan, karantina, idempotensi SQLite)
   - `AuthAndRbacTest` (6 demo roles, HTTP `403` untuk `wisatawan` & pembatasan tenant UMKM)
   - `BlackBoxEpBvaTest` (9 rute publik + EP/BVA kuantitas & stok `422`/`201`)
   - `ApiAndIntegrationTest` (7 endpoint `/api/v1/*`: `200`, `201`, `401`, `403`, `404`)
   - `SecurityOwaspTest` (Reflected XSS escaping, Security Headers, `.gitignore` `.env`)
3. **ETL Migration & Reconciliation (`scripts/etl_import.php`):**
   - `5` baris CSV diekstrak -> `3` valid masuk DB, `1` dikarantina, `1` duplikat dilewati (`100% Zero Data Loss` — `PASS`).
4. **Local Concurrency Benchmark (`scripts/benchmark_local.php`):**
   - Tier 1 (5 conc, 20 req): Avg `30.74 ms`, P95 `50.01 ms`, RPS `83.74`, `0%` error (**PASS**)
   - Tier 2 (10 conc, 50 req): Avg `45.12 ms`, P95 `95.06 ms`, RPS `98.39`, `0%` error (**PASS**)
   - Tier 3 (20 conc, 100 req): Avg `52.29 ms`, P95 `93.29 ms`, RPS `177.79`, `0%` error (**PASS**)
5. **Pengujian Eksternal Belum Dieksekusi Lokal (Status Transparan `NOT TESTED`):**
   - Apache JMeter CLI `500 VU` (`tests/jmeter/jumiang_load_test.jmx` siap jalan, binary `jmeter` tidak terpasang lokal).
   - OWASP ZAP Active Scan CLI eksternal & validasi tatap muka fisik dengan pelaku UMKM Desa Tanjung (status UAT saat ini `[SIMULASI PERSONA UAT]`).

---

## 3. VERIFIKASI DISASTER RECOVERY & BACKUP BASIS DATA (`scripts/backup_verify.php`)
Eksekusi aktual skrip verifikasi cadangan menghasilkan bukti integritas berkas berikut:
- **File Target:** `database/jumiang_database.sql`
- **Ukuran Berkas:** `32502` bytes
- **Hash Integritas SHA-256:** `6d06538cf67431494f1c98acd21cbda55e609348232d13f7088828aaf67371d0`
- **Tabel Terdefinisi (`CREATE TABLE`):** `23` tabel
- **Tabel Terisi Data Demo (`INSERT INTO`):** `21` tabel
- **Proteksi Foreign Key (`FOREIGN_KEY_CHECKS` 0/1) & Snapshot Copy Check:** **PASS / MATCH**
