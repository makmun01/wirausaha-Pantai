# SECURITY TESTING & OWASP VERIFICATION
## JUMIANG SMART TOURISM & UMKM
**Security Assessment & Vulnerability Remediation Report (OWASP Top 10 - Minggu 10)**

---

## 1. RUANG LINGKUP PENGUJIAN KEAMANAN (PERTEMUAN 10)
Sistem diuji terhadap vektor serangan standar web mengacu pada **OWASP Top 10**:
1. **Broken Access Control (A01:2021):** Pengujian bypass hak akses antar role (misal: Wisatawan mengakses menu Admin/Bendahara atau endpoint `/api/v1/umkm/my-finances`).
2. **Cryptographic Failures (A02:2021):** Proteksi file sensitif `.env` pada `.gitignore` dan hashing password bcrypt pada `AuthService`.
3. **Injection & XSS (A03:2021):** Eloquent ORM parameterized queries (SQLi mitigation) serta escaping output HTML (`htmlspecialchars` & Blade `{{ }}`) terhadap Reflected XSS.
4. **Insecure Design (A04:2021):** Validasi server-side total harga dan kuantitas pesanan di `OrderProcessingService` dan API `POST /api/v1/orders/simulate` (menolak kuantitas `<= 0` atau melebihi stok).
5. **Security Misconfiguration (A05:2021):** Pengamanan HTTP headers (`X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`) baik pada `public/index.php` maupun `docker/nginx/default.conf`.
6. **Vulnerable & Outdated Components (A06:2021):** Validasi manifest dependensi `composer.json` dan audit image Docker pada CI/CD.
7. **Identification & Authentication Failures (A07:2021):** Penolakan permintaan tanpa Bearer token / sesi (`401 Unauthorized`) pada endpoint transaksi terproteksi.
8. **Software & Data Integrity Failures (A08:2021):** Transaksi `DB::transaction` & Finite State Machine guard pada `OrderProcessingService::transitionStatus()` dan `UmkmService`.
9. **Security Logging & Monitoring Failures (A09:2021):** Pencatatan perubahan pada `audit_logs` via `AuditTrailService`.
10. **CSRF Protection (A10:2021 / Web Forms):** Middleware CSRF bawaan Laravel pada route POST/PUT/DELETE di `routes/web.php`.

---

## 2. REALISASI HASIL PENGUJIAN KEAMANAN TEROTOMASI

Eksekusi otomatis melalui `php tests/run_all_tests.php` (`SecurityOwaspTest.php`, `AuthAndRbacTest.php`, `ApiAndIntegrationTest.php`, `FinancialBookkeepingUnitTest.php`):

| No | Kontrol Keamanan OWASP | Test Otomatis Terkait | Hasil Aktual Nyata | Status |
|---|---|---|---|---|
| 1 | **A01 Broken Access Control (Web RBAC)** | `AuthAndRbacTest::test_wisatawan_gets_403_forbidden_on_admin_modules` | Wisatawan mengakses `/admin/dashboard`, `/admin/financial`, `/admin/products`, `/admin/umkm`, `/admin/etl` -> HTTP `403 Forbidden` | **PASS** |
| 2 | **A01 Tenant Data Isolation (`MOD-FIN`)** | `FinancialBookkeepingUnitTest::test_path1_rejects_cross_merchant_account_access` & `ApiAndIntegrationTest` | Akses rekening merchant lain melempar `SecurityException`; `wisatawan` ditolak `403` pada `/api/v1/umkm/my-finances` | **PASS** |
| 3 | **A02/A05 Secrets Hygiene (`.env`)** | `SecurityOwaspTest::test_http_security_hardening_headers_and_gitignore_env_hygiene` + CI Job 3 | `.env` diabaikan pada `.gitignore` dan tidak pernah ter-commit ke Git | **PASS** |
| 4 | **A03 Reflected XSS Sanitization** | `SecurityOwaspTest::test_reflected_xss_payloads_are_escaped` | Payload `<script>alert('xss')</script>` pada `/katalog-umkm?search=...` dan `/produk/...` di-escape menjadi `&lt;script&gt;` | **PASS** |
| 5 | **A05 HTTP Security Headers** | `SecurityOwaspTest` + `docker/nginx/default.conf` | Header `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` terverifikasi aktif | **PASS** |
| 6 | **A07 Unauthenticated API Guard** | `ApiAndIntegrationTest::test_protected_order_payment_and_umkm_finance_apis` | Akses `/api/v1/orders/simulate` tanpa Bearer token ditolak HTTP `401 Unauthorized` | **PASS** |

---

## 3. STATUS DAST EKSTERNAL (OWASP ZAP)
- **Active/Passive Scan via Binary OWASP ZAP (`zap-baseline.py`):** `NOT TESTED` secara lokal karena binary OWASP ZAP tidak terpasang pada lingkungan Windows lokal maupun container default; seluruh kontrol kerentanan inti (XSS, Access Control, Headers, Input Validation) diverifikasi melalui unit/feature test terotomasi serta audit konfigurasi pada GitHub Actions Job `docker-and-security-audit`.
