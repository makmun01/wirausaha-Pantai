# CATATAN PERTEMUAN MINGGU 14
## Topik: CI/CD Pipeline & Docker Containerization
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Otomasi integrasi berkelanjutan dan pengiriman berkelanjutan (CI/CD Pipeline).
- Konfigurasi Containerization menggunakan Docker dan Docker Compose.
- Arsitektur deployment staging & production pada Linux VPS.

---

### B. Fitur yang Dikembangkan
1. Pembuatan `Dockerfile` berbasis `php:8.3-fpm-alpine` dan web server Nginx.
2. Pembuatan `docker-compose.yml` multi-container (Layanan App Laravel + Layanan Basis Data MySQL 8.4 dengan auto-init volume).
3. Dokumentasi alur GitHub Actions pada `docs/deployment/CI_CD_DOCUMENTATION.md` dan `DEPLOYMENT_PLAN.md`.

---

### C. File yang Ditambah atau Diubah
- `Dockerfile`
- `docker-compose.yml`
- `docs/deployment/CI_CD_DOCUMENTATION.md`
- `docs/deployment/DEPLOYMENT_PLAN.md`

---

### D. Pengujian yang Dilakukan
- Uji validasi sintaks Dockerfile dan konfigurasi port binding (8000:80, 3307:3306).
- Uji eksekusi container independen.

---

### E. Expected Result
- Aplikasi dapat dibangun dan dijalankan dalam lingkungan container terisolasi secara konsisten di berbagai sistem operasi.

---

### F. Actual Result
- Konfigurasi Docker valid dan siap dijalankan dengan perintah `docker compose up -d`.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect terbuka.

---

### I. Perbaikan yang Dilakukan
Penyertaan skrip otomatis `database/jumiang_database.sql` ke dalam folder `/docker-entrypoint-initdb.d/` agar basis data langsung terinisiasi saat container pertama kali dinyalakan.

---

### J. Progres Keseluruhan Proyek
**87.5% (Minggu 14 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Pelaksanaan Post Implementation Review (PIR), evaluasi performa pasca-rilis, dan penyusunan Maintenance SLA Plan.
