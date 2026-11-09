# CATATAN PERTEMUAN MINGGU 10
## Topik: Security Testing & OWASP Top 10 Vulnerability Assessment
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Analisis kerentanan keamanan perangkat lunak berdasarkan standar **OWASP Top 10**.
- Pengujian vektor serangan: SQL Injection (SQLi), Cross-Site Scripting (XSS), Cross-Site Request Forgery (CSRF), dan Broken Access Control.
- Enkripsi password menggunakan bcrypt dan penguatan sesi pengguna.

---

### B. Fitur & Penguatan Keamanan yang Diterapkan
1. Proteksi parameterized queries menggunakan Eloquent ORM di seluruh Controller & Service Layer untuk mencegah SQL Injection.
2. Sanitasi output otomatis melalui Blade templating (`{{ $data }}`) untuk mencegah serangan Reflected/Stored XSS.
3. Penerapan token `@csrf` pada seluruh form mutasi data (POST, PUT, DELETE).
4. Penegakan isolasi data tenant ringan (*Tenant Data Isolation*) pada modul keuangan UMKM melalui `FinancialTransaction::scopeForMerchant`.
5. Pembaruan panduan keamanan pada [SECURITY_TESTING.md](file:///c:/Users/core/Documents/twsting%20ERP%20Pariwisata/docs/testing/SECURITY_TESTING.md).

---

### C. File yang Ditambah atau Diubah
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Services/FinancialBookkeepingService.php`
- `docs/testing/SECURITY_TESTING.md`

---

### D. Pengujian yang Dilakukan
- Uji injeksi SQL payload `' OR 1=1 --` pada kolom filter pencarian produk -> Kebal (PASS).
- Uji payload `<script>alert('xss')</script>` pada input nama produk dan review -> Terescape sempurna sebagai string aman (PASS).
- Uji Broken Access Control: User ber-role `wisatawan` mencoba memanggil URL `/admin/financial` -> Ditolak dengan HTTP 403 Forbidden (PASS).

---

### E. Expected Result
- 0 temuan kerentanan berstatus *Critical* atau *High Severity*.
- Integritas data tetap terjaga saat manipulasi request dilakukan.

---

### F. Actual Result
- Seluruh vektor serangan yang diuji berhasil ditangkal oleh mekanisme proteksi sistem.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
0 Kerentanan Kritis ditemukan.

---

### I. Perbaikan yang Dilakukan
Pengecekan otentikasi ganda pada setiap pemanggilan method manipulasi akun keuangan.

---

### J. Progres Keseluruhan Proyek
**62.5% (Minggu 10 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Pelaksanaan User Acceptance Testing (UAT) untuk 6 persona pengguna sistem.
