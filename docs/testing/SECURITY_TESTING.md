# SECURITY TESTING & OWASP VERIFICATION
## JUMIANG SMART TOURISM & UMKM
**Security Assessment & Vulnerability Remediation Guide (OWASP Top 10)**

---

## 1. RUANG LINGKUP PENGUJIAN KEAMANAN (PERTEMUAN 10)
Sistem diuji terhadap vektor serangan standar web mengacu pada **OWASP Top 10**:
1. **Broken Access Control (A01:2021):** Pengujian bypass hak akses antar role (misal: Wisatawan mengakses menu Admin atau UMKM lain).
2. **Cryptographic Failures (A02:2021):** Enkripsi password menggunakan bcrypt, proteksi file sensitif `.env`.
3. **Injection (A03:2021):** SQL Injection pada kolom filter katalog, form pencarian, dan input keuangan.
4. **Insecure Design (A04:2021):** Validasi manipulasi harga atau stok saat checkout pesanan.
5. **Security Misconfiguration (A05:2021):** Menonaktifkan `APP_DEBUG=true` pada lingkungan produksi, pengaturan CORS yang ketat.
6. **Vulnerable & Outdated Components (A06:2021):** Audit dependensi composer dan npm (`composer audit` dan `npm audit`).
7. **Identification & Authentication Failures (A07:2021):** Proteksi Brute Force login, pencegahan session hijacking.
8. **Software & Data Integrity Failures (A08:2021):** Validasi integritas upload gambar produk/struk kas (MIME-type check).
9. **Security Logging & Monitoring Failures (A09:2021):** Kelengkapan pencatatan mutasi pada `audit_logs`.
10. **Server-Side Request Forgery / CSRF (A10:2021):** Verifikasi token CSRF pada seluruh request non-GET.

---

## 2. METODOLOGI & ALAT BANTU
- **Dynamic Application Security Testing (DAST):** OWASP ZAP (Zed Attack Proxy) untuk automated active and passive scanning pada staging server.
- **Static Analysis:** PHPStan / Larastan untuk pendeteksian potensi celah logika kode.
- **Manual Penetration Testing:** Pengujian manipulasi parameter HTTP (*Parameter Tampering*).
