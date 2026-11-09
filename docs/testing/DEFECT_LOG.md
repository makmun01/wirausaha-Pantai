# DEFECT LOG (LAPORAN DAN PELACAKAN DEFECT/BUG)
## JUMIANG SMART TOURISM & UMKM
**Defect Life-Cycle & Resolution Registry (ISO/IEC/IEEE 29119-4)**

---

## 1. KEBIJAKAN PENGELOLAAN DEFECT (DEFECT MANAGEMENT POLICY)
1. Setiap anomali yang ditemukan selama pengujian manual maupun otomatis wajib dicatat dalam dokumen ini.
2. Status defect yang diakui:
   - `NEW`: Bug baru dilaporkan oleh tester/developer.
   - `IN_PROGRESS`: Bug sedang dianalisis dan diperbaiki.
   - `FIXED`: Perbaikan telah dibuat dan di-commit ke repositori.
   - `RETESTED`: Pengujian ulang telah dilakukan pada lingkungan testing.
   - `CLOSED`: Terbukti tuntas dan tidak menimbulkan efek regresi.
3. **Aturan Mutlak:** Dilarang menutup defect (`CLOSED`) sebelum dilakukan retest nyata dengan hasil `PASS`.

---

## 2. TABEL REGISTER DEFECT

| Defect ID | Tanggal Temuan | Modul | Severity | Deskripsi Kerusakan | Langkah Reproduksi | Hasil Aktual vs Harapan | Status | Solusi / Commit Ref |
|---|---|---|---|---|---|---|---|---|
| *DEF-INIT* | 2026-11-02 | Setup | Minor | Belum ada dependensi terpasang (repositori inisiasi baru) | Jalankan `composer install` pada repo baru | Belum ada vendor; Harapan: vendor terpasang | CLOSED | Inisiasi arsitektur dan dokumentasi Week 1 selesai |

*(Defect selanjutnya akan dicatat secara kronologis pada setiap pertemuan pengujian).*
