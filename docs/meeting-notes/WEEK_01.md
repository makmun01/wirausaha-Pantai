# CATATAN PERTEMUAN MINGGU 01
## Topik: SQA, V-Model, ISO 29119, dan Analisis Kebutuhan Sistem
**Tanggal Sesi:** 2 November 2026
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Konsep dasar Software Quality Assurance (SQA) dan standar kualitas ISO/IEC 25010.
- Siklus hidup pengujian perangkat lunak (STLC) berbasis V-Model.
- Standar dokumentasi pengujian internasional ISO/IEC/IEEE 29119.
- Analisis kebutuhan fungsional dan non-fungsional proyek sistem informasi pariwisata terintegrasi.

---

### B. Fitur & Fondasi yang Dikembangkan
1. Perumusan Product Requirements Document (PRD) mencakup 14 modul dan 6 role pengguna.
2. Perancangan arsitektur sistem Modular Monolith dengan Service Layer MVC Pattern.
3. Perancangan skema basis data relasional MySQL 8.4 (20 tabel terintegrasi) dan ERD.
4. Penyusunan Master Test Plan (ISO 29119) dan matriks skenario pengujian awal.
5. Pembentukan tata kelola AI Agent pada `AGENTS.md` (10 Aturan Mutlak Pengembangan).
6. Inisiasi struktur scaffold repositori dan struktur folder dokumentasi lengkap.

---

### C. File yang Ditambah atau Diubah
- `AGENTS.md`
- `README.md`
- `PROJECT_CONTEXT.md` & `docs/PROJECT_CONTEXT.md`
- `ROADMAP_16_WEEKS.md` & `docs/ROADMAP_16_WEEKS.md`
- `CHANGELOG.md` & `docs/CHANGELOG.md`
- `docs/PRD.md`
- `docs/SYSTEM_ARCHITECTURE.md`
- `docs/DATABASE_DESIGN.md`
- `docs/testing/TEST_PLAN.md`
- `docs/testing/TEST_CASE_MATRIX.md`
- `docs/testing/DEFECT_LOG.md`
- `docs/testing/TEST_SUMMARY.md`
- `docs/testing/API_TESTING.md`
- `docs/testing/PERFORMANCE_TESTING.md`
- `docs/testing/SECURITY_TESTING.md`
- `docs/testing/UAT_DOCUMENTATION.md`
- `docs/migration/DATA_MIGRATION_PLAN.md`
- `docs/migration/ETL_DOCUMENTATION.md`
- `docs/deployment/DEPLOYMENT_PLAN.md`
- `docs/deployment/CI_CD_DOCUMENTATION.md`
- `docs/deployment/BACKUP_ROLLBACK.md`
- `docs/meeting-notes/WEEK_01.md`

---

### D. Pengujian yang Dilakukan
- Review konsistensi kebutuhan sistem (*Requirements Traceability & Consistency Review*).
- Validasi integritas normalisasi skema basis data (3NF) dan integritas referensial foreign keys.
- Verifikasi keterhubungan antara matriks test case dengan kebutuhan PRD.

---

### E. Expected Result
- Seluruh 14 modul fungsional terpetakan secara utuh ke dalam rancangan basis data, arsitektur, dan rencana pengujian.
- Ketiadaan kontradiksi antara peran 6 stakeholder (Super Admin, Pengelola Wisata, Pelaku UMKM, Bendahara, Pimpinan, Wisatawan).
- Pemisahan pembukuan keuangan UMKM terisolasi secara arsitektural.

---

### F. Actual Result
- Dokumen PRD, Desain Sistem, ERD, dan Test Plan selesai disusun secara komprehensif.
- 20 entitas tabel relasional dirancang dengan relasi kardinalitas yang presisi.
- Seluruh aturan tata kelola agen dan batasan data demo terimplementasi.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect logika atau arsitektur yang ditemukan pada tahap perancangan konseptual.

---

### I. Perbaikan yang Dilakukan
Penyempurnaan aturan isolasi multi-tenant ringan pada Service Layer untuk memastikan data transaksi keuangan UMKM tidak bocor ke pedagang lain.

---

### J. Progres Keseluruhan Proyek
**6.25% (Minggu 1 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya (Minggu 02)
- Scaffold implementasi kode Laravel dasar.
- Setup konfigurasi database dan migration awal.
- Implementasi modul `MOD-AUTH` (Authentication & User Management) dan seeder multi-role.
- Penyusunan Feature Test otomatis untuk flow login, registrasi, dan proteksi rute RBAC.
