# BACKUP & ROLLBACK DISASTER RECOVERY PROCEDURES
## JUMIANG SMART TOURISM & UMKM
**Contingency, Data Preservation, and Version Rollback Manual**

---

## 1. KEBIJAKAN BACKUP OTOMATIS (RPO & RTO)
- **Recovery Point Objective (RPO):** Maksimal 24 jam untuk transaksi non-kritis, dan per snapshot transaksi untuk jurnal keuangan.
- **Recovery Time Objective (RTO):** Sistem harus dapat dipulihkan dalam waktu < 30 menit.

---

## 2. SKENARIO PEMULIHAN ROLLBACK (DISASTER RECOVERY)

### 2.1 Rollback Kode Aplikasi
Jika rilis versi terbaru menimbulkan regresi fatal:
```bash
git checkout tags/v1.X.X-stable
php artisan migrate:rollback --step=1  # jika ada perubahan skema
php artisan optimize:clear
php artisan optimize
```

### 2.2 Restore Basis Data MySQL
```bash
mysql -u root -p jumiang_tourism < /var/backups/db/jumiang_backup_latest.sql
```
