# CATATAN PERTEMUAN MINGGU 02
## Topik: Setup Scaffold Laravel, Database Migrations, dan Autentikasi Multi-Role RBAC
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Test Planning dan Penyusunan Test Case Matrix sesuai ISO/IEC/IEEE 29119.
- Scaffolding arsitektur MVC Modular Monolith pada framework Laravel.
- Implementasi basis data relasional melalui migration dan seeders.
- Autentikasi pengguna dan otorisasi berbasis peran (Role-Based Access Control - RBAC).

---

### B. Fitur yang Dikembangkan
1. Pembuatan 7 file migration lengkap mencakup 20 tabel relasional pada `database/migrations/`.
2. Pembuatan 20 model Eloquent dengan relasi lengkap pada `app/Models/`.
3. Implementasi autentikasi multi-role (`AuthService` dan `AuthController`) mencakup 6 role: Super Admin, Pengelola Wisata, Pelaku UMKM, Bendahara, Pimpinan, dan Wisatawan.
4. Implementasi `RoleMiddleware` untuk proteksi rute administratif berdasarkan hak akses role.
5. Pembuatan halaman antarmuka Login (`resources/views/auth/login.blade.php`) dilengkapi fitur *1-Click Demo Account Quick Fill* untuk kemudahan demonstrasi akademik.
6. Pembuatan script import SQL instan `database/jumiang_database.sql`.

---

### C. File yang Ditambah atau Diubah
- `database/migrations/2026_11_02_000001_create_roles_and_users_tables.php` s/d `000008_create_migration_quarantine_logs_table.php`
- `database/seeders/DatabaseSeeder.php`
- `app/Models/User.php`, `Role.php`, `UserProfile.php`
- `app/Services/AuthService.php`
- `app/Http/Controllers/Auth/AuthController.php` & `app/Http/Controllers/Controller.php`
- `app/Http/Middleware/RoleMiddleware.php`
- `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`
- `database/jumiang_database.sql`
- `tests/Feature/AuthAndRbacTest.php` & `docs/testing/TEST_CASE_MATRIX.md`

---

### D. Pengujian yang Dilakukan
- Pengujian autentikasi valid dan invalid untuk seluruh 6 persona role pengguna.
- Pengujian proteksi rute `RoleMiddleware` (Boundary check hak akses role).
- Verifikasi pembentukan tabel pada skema database MySQL.

---

### E. Expected Result
- Pengguna dapat masuk menggunakan email dan password terdaftar.
- Setiap role dialihkan secara tepat ke panel dashboard masing-masing.
- Pengguna tanpa hak akses ditolak dengan kode status HTTP 403 Forbidden.

---

### F. Actual Result
- Login berhasil untuk seluruh akun demo (Super Admin, Pengelola, UMKM, Bendahara, Pimpinan, Wisatawan).
- Proteksi RBAC berjalan sempurna menolak akses wisatawan ke menu admin.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ditemukan defect pada flow autentikasi dan otorisasi.

---

### I. Perbaikan yang Dilakukan
Penambahan tombol jalan pintas (Quick Fill Demo) pada form login untuk memudahkan proses audit pengujian.

---

### J. Progres Keseluruhan Proyek
**12.5% (Minggu 2 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Implementasi modul Tourism Management, profil Pantai Jumiang, dan pengujian Black Box (EP & BVA) pada form pendaftaran gerai UMKM dan input harga.
