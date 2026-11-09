# CATATAN PERTEMUAN MINGGU 05
## Topik: White Box Testing & Cyclomatic Complexity pada Modul Keuangan & Inventori
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Konsep dasar White Box Testing dan pengujian struktur logika internal program.
- Control Flow Graph (CFG) dan kalkulasi Cyclomatic Complexity (McCabe's Complexity: $V(G) = E - N + 2P$).
- Penentuan Independent Paths untuk menjamin branch coverage pada kalkulasi laba rugi dan mutasi stok.

---

### B. Fitur yang Dikembangkan
1. Implementasi `InventoryLedgerService` untuk audit mutasi stok masuk (`IN`), keluar (`OUT`), dan penyesuaian opname (`ADJUSTMENT`).
2. Implementasi `FinancialBookkeepingService` untuk pencatatan buku kas terpisah:
   - Rekening Kas Usaha (`BUSINESS`) terisolasi dari Tabungan Pribadi (`PERSONAL`).
   - Pencatatan pemasukan otomatis dari order marketplace.
   - Pencatatan pengeluaran operasional (bahan baku, kemasan, tenaga kerja).
   - Perhitungan Laporan Laba Rugi sederhana: Laba Bersih = Total Pemasukan - Total Pengeluaran.
3. Pembuatan tampilan antarmuka `resources/views/admin/financial/index.blade.php`, `resources/views/admin/financial/profit_loss.blade.php`, dan `resources/views/admin/inventory/index.blade.php`.

---

### C. File yang Ditambah atau Diubah
- `app/Services/InventoryLedgerService.php`
- `app/Services/FinancialBookkeepingService.php`
- `app/Http/Controllers/Web/FinancialController.php`
- `app/Http/Controllers/Web/InventoryController.php`
- `resources/views/admin/financial/index.blade.php`, `profit_loss.blade.php`
- `resources/views/admin/inventory/index.blade.php`

---

### D. Pengujian yang Dilakukan
- Analisis Control Flow Graph pada method `recordTransaction()`:
  - Jalur 1: Validasi rekening bukan milik merchant -> Lempar Exception.
  - Jalur 2: Nominal <= 0 -> Lempar Exception.
  - Jalur 3: Tipe INCOME -> Tambah saldo berjalan.
  - Jalur 4: Tipe EXPENSE -> Kurangi saldo berjalan.
  Semua 4 jalur independen teruji secara menyeluruh (*100% Branch Coverage*).

---

### E. Expected Result
- Arus kas usaha dan kas pribadi tercatat pada tabel rekening yang terpisah.
- Laporan laba rugi menghitung selisih pendapatan dan beban secara tepat sesuai rentang tanggal.

---

### F. Actual Result
- Pembukuan kas UMKM berjalan presisi.
- Isolasi data transaksi antar-merchant terjaga penuh melalui query scope.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect terbuka.

---

### I. Perbaikan yang Dilakukan
Penyediaan tombol Cetak Laporan (Print PDF) dengan styling cetak bersih (`d-print-none`).

---

### J. Progres Keseluruhan Proyek
**31.25% (Minggu 5 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Penyusunan REST API Spasial dan Pengujian Integrasi menggunakan Postman/Newman.
