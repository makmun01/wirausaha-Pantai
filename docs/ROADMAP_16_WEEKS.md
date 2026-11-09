# ROADMAP PENGEMBANGAN DAN PENGUJIAN 16 MINGGU
## Berdasarkan RPS Manajemen Proyek Sistem Informasi (MANPRO) & Software Quality Assurance (SQA)
### Proyek: JUMIANG SMART TOURISM & UMKM

Roadmap ini mengikat seluruh siklus hidup proyek (SDLC & STLC) agar pengembangan aplikasi dan pengujian akademik berjalan beriringan tanpa putus rantai kontinuitas.

---

## Ringkasan Matriks 16 Pertemuan

| Minggu | Materi Pokok RPS MANPRO & SQA | Fokus Pengembangan Aplikasi | Fokus Pengujian & Standar Kualitas | Deliverable Utama |
|---|---|---|---|---|
| **01** | SQA, V-Model, ISO 29119 | Perencanaan Sistem, Analisis Kebutuhan, Fondasi Arsitektur | Menentukan Software Quality Metrics, Acceptance Criteria per modul | `PRD.md`, `SYSTEM_ARCHITECTURE.md`, `DATABASE_DESIGN.md`, `TEST_PLAN.md` |
| **02** | Test Planning & Test Cases | Setup Scaffold Laravel, Authentication & User Management, Database Migration | Penyusunan Test Scenarios & Test Case Matrix untuk Auth, Profile, & Master Data | `TEST_CASE_MATRIX.md`, Auth Feature Test, Seeders |
| **03** | Black Box Testing: EP & BVA | Modul Wisata & UMKM Management (Profil usaha, data pantai, fasilitas) | Pengujian form input (Boundary Value Analysis & Equivalence Partitioning) pada form registrasi & harga | EP & BVA Tables, Test Execution Report Minggu 3 |
| **04** | Decision Table & State Transition | Modul Marketplace, Pemesanan, & Verifikasi UMKM | Pengujian Finite State Machine (Pending -> Paid -> Shipped -> Done; Status Verifikasi UMKM) | Decision Table Matrix, State Transition Diagram & Test Report |
| **05** | White Box & Cyclomatic Complexity | Modul Inventory & Financial Management (Kalkulasi Saldo & Mutasi Stok) | Analisis Control Flow Graph (CFG), Independent Paths (McCabe Complexity) pada Service Classes | Flow Graphs, Cyclomatic Calculations, Unit Test Suite (Pest/PHPUnit) |
| **06** | API & Integration Testing | REST API Endpoints (GIS Map Marker, Catalog, Mobile Feed, Payment Simulator) | Kontrak API, HTTP Status Codes, Integrasi Antarmodul (Order -> Inventory -> Ledger) | Postman Collection, Newman CLI Test Runner, Integration Test Report |
| **07** | Automated Testing & Defect Management | Modul GIS Digital Map (Leaflet.js) & Reservation Management | Otomatisasi pengujian regresi menyeluruh, pencatatan bug aktif pada `DEFECT_LOG.md` | Automated Test Suite, Bug Life-cycle Reports, Defect Log |
| **08** | **UTS: Audit & Demonstrasi Sistem** | Integrasi Modul Fase 1 (Auth, Wisata, Peta, UMKM, Produk, Toko, Transaksi) | Regression Testing menyeluruh, Quality Audit sistem, Checklist verifikasi fungsi | UTS Audit Report, System Demo Checklist, Test Summary UTS |
| **09** | Performance & Load Testing | Modul Executive Information System (EIS) & Caching Layer | Uji beban bersamaan (10, 50, 100, 500 Virtual Users), Throughput, Response Time | JMeter Test Scripts (.jmx), Performance Baseline Report |
| **10** | Security Testing & OWASP Top 10 | Penguatan Keamanan Sistem, Sanitasi Input, Rate Limiting, RBAC Enforcement | Pengujian kerentanan SQL Injection, XSS, CSRF, Session Fixation, Broken Access Control | OWASP ZAP Scan Report, Security Remediation Document |
| **11** | User Acceptance Testing (UAT) | Penyesuaian UI/UX Publik & Portal Dashboard berdasarkan Masukan Pengguna | Uji skenario penerimaan oleh 6 persona pengguna (Admin, Pengelola, UMKM, Bendahara, Pimpinan, Wisatawan) | UAT Plan, Skenario UAT, Form UAT, Sign-Off Matrix |
| **12** | Implementation & Rollout Strategy | Finalisasi Fitur Pendukung (Partnership Management, Notification, Audit Trail) | Analisis strategi transisi (Direct vs Parallel vs Phased vs Pilot), Smoke Testing | Implementation Strategy Plan, Change Management Guide, Smoke Test Report |
| **13** | Data Migration & ETL | Modul Import/Export Data Lapangan (CSV/Excel ke Database MySQL) | Uji validasi integritas data (Extract, Transform, Load), pencegahan duplikasi data | Data Migration Plan, ETL Pipeline Script, Reconciliation Audit Report |
| **14** | CI/CD & Deployment Pipeline | Konfigurasi Docker Container, Nginx, GitHub Actions Workflow | Pengujian otomasi build, linting, migration check, automated testing pada runner CI/CD | Dockerfile, docker-compose.yml, GitHub Actions YAML, Deployment Report |
| **15** | Post Implementation Review | Optimasi Kode, Pembersihan Defect Residual, Hardening Database | Evaluasi pasca-rilis, audit efisiensi sistem, review lessons learned & maintenance SLA | Post Implementation Review (PIR), Maintenance Plan, Final Improvement Backlog |
| **16** | **UAS: Final Project Demonstration** | Presentasi Sistem Terintegrasi Lengkap, Simulasi Disaster Recovery & Backup | Demonstrasi langsung 14 modul operasional, verifikasi kelaikan sistem, live rollback demo | Final Project Report, User Manual (Buku Panduan), Final Test Report, Backup Verification |

---

## Aturan Transisi Mingguan
1. Tidak boleh melompat ke minggu berikutnya sebelum deliverables dan testing pada minggu berjalan selesai dan berstatus `PASS` atau `RETESTED`.
2. Setiap kali perintah `LANJUTKAN PERTEMUAN KE-[N]` diberikan, jalankan checklist:
   - Verifikasi status minggu N-1 pada `CHANGELOG.md`.
   - Implementasikan target modul minggu N.
   - Jalankan uji spesifik materi RPS minggu N.
   - Perbarui catatan pada `docs/meeting-notes/WEEK_[N].md`.
