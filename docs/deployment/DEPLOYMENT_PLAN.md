# DEPLOYMENT PLAN
## JUMIANG SMART TOURISM & UMKM
**Production Rollout, Server Provisioning, and Environment Hardening**

---

## 1. SPESIFIKASI LINGKUNGAN DEPLOYMENT (VPS LINUX)
- **Sistem Operasi:** Ubuntu Server 22.04 LTS / 24.04 LTS
- **Web Server:** Nginx (Reverse Proxy & Static Asset Serving) dengan SSL/TLS (Let's Encrypt HTTPS)
- **Containerization:** Docker & Docker Compose
- **Runtime:** PHP-FPM 8.3 dengan ekstensi OPcache aktif
- **Basis Data:** MySQL 8.4 Server (Managed Instance / Dedicated Container)
- **Domain Target:** `jumiang.pamekasan.go.id` atau domain demo akademik

---

## 2. PROSEDUR DEPLOYMENT PRODUKSI
1. **Provisioning Server:** Pengaturan firewall UFW (hanya buka port 22, 80, 443).
2. **Setup Container Docker:** Menjalankan `docker compose -f docker-compose.prod.yml up -d --build`.
3. **Database Migration & Cache Optimization:**
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. **Verifikasi Smoke Test:** Memastikan endpoint health check merespons HTTP 200 OK.
