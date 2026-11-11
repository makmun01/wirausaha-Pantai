# DEPLOYMENT & CI/CD EXECUTION REPORT
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 14

Dokumen ini mendokumentasikan arsitektur container produksi Docker (PHP 8.3 FPM + Nginx), orkestrasi MySQL 8.4 (`docker-compose.yml`), dan eksekusi pipeline GitHub Actions (`.github/workflows/ci-cd.yml`).

---

## 1. INFRASTRUKTUR KONTAINER DOCKER
1. **Application Image (`Dockerfile`):**
   - Base Image: `php:8.3-fpm-alpine`
   - Ekstensi aktif: `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`, `zip`
   - Web Server Proxy: `nginx` menggunakan konfigurasi site khusus [docker/nginx/default.conf](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/docker/nginx/default.conf) dengan root `/var/www/html/public`, `fastcgi_pass 127.0.0.1:9000`, proteksi dotfile (`\.env`), serta security headers (`X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`).
2. **Service Compose (`docker-compose.yml`):**
   - Service `jumiang_app` (port host `8000` -> port container `80`)
   - Service `jumiang_db` (`mysql:8.4`, auto-init `database/jumiang_database.sql`, healthcheck `mysqladmin ping`)
   - Service `jumiang_phpmyadmin` (opsional debug port `8081`)

---

## 2. PIPELINE CI/CD GITHUB ACTIONS (`.github/workflows/ci-cd.yml`)
Pipeline berjalan otomatis pada setiap `push` / `pull_request` ke branch `main` dan `staging` dengan 4 job terpisah:
1. **`code-lint-and-validation`:** Memeriksa sintaks seluruh file `.php` (`php -l`), memvalidasi `composer.json` dan `docker-compose.yml`, serta mengaudit kebersihan `.gitignore` terhadap `.env`.
2. **`database-and-smoke-tests`:** Menjalankan service `mysql:8.4`, mengimpor 23 tabel dari `database/jumiang_database.sql`, mengeksekusi smoke test multi-rute + `/api/v1/*`, serta menjalankan seluruh Automated Unit & Feature Test (`php tests/run_all_tests.php`), CLI ETL (`scripts/etl_import.php --apply`), dan Backup Verification (`scripts/backup_verify.php`).
3. **`docker-and-security-audit`:** Mem-build image Docker dari `Dockerfile`, memverifikasi container Nginx + PHP-FPM menyajikan `/health` (`HTTP 200`), serta memastikan tidak ada kredensial `.env` atau placeholder kunci bocor.
4. **`deploy-staging`:** Job opsional bersyarat bila GitHub Secret `STAGING_HOST` & `STAGING_SSH_KEY` dikonfigurasi oleh operator.

---

## 3. BUKTI EKSEKUSI AKTUAL GITHUB ACTIONS (`Run #42910953354`)
- **Commit Diuji:** `96dc718` pada branch `main` (`https://github.com/makmun01/wirausaha-Pantai/actions/runs/42910953354`)
- **Job 1 (`1. Code Lint & Config Validation`):** **PASS** (`12s`) — PHP `8.3` `php -l`, validasi `composer.json`, dan `docker compose config --quiet`.
- **Job 2 (`2. MySQL 8.4 Init, Automated Suite & Smoke Tests`):** **PASS** (`41s`) — impor `23` tabel ke `mysql:8.4`, CLI ETL `--dry-run` & `--apply` 2x idempotensi pada MySQL 8.4, `backup_verify.php`, `19/19 PASS` `tests/run_all_tests.php`, serta smoke test `31` rute Web & API preview.
- **Job 3 (`3. Docker Nginx Build & Security Hygiene Audit`):** **PASS** (`2m12s`) — build `Dockerfile` (`php:8.3-fpm-alpine` + Nginx), verifikasi `/health` (`HTTP 200`) & header keamanan `X-Frame-Options: SAMEORIGIN`.
