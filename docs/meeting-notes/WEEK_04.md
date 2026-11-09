# CATATAN PERTEMUAN MINGGU 04
## Topik: Decision Table & State Transition Testing pada Siklus Pemesanan Marketplace
**Status:** SELESAI (COMPLETED)

---

### A. Materi RPS Minggu Ini
- Decision Table Testing untuk mengevaluasi kombinasi kondisi bisnis kompleks.
- State Transition Testing untuk memodelkan Finite State Machine (FSM) status pesanan.
- Pencegahan transisi status yang tidak valid (*illegal state transitions*).

---

### B. Fitur yang Dikembangkan
1. Implementasi alur pemesanan marketplace: Keranjang belanja -> Checkout -> Penerbitan Invoice -> Konfirmasi Pembayaran Simulasi.
2. Implementasi `OrderProcessingService` yang memvalidasi transisi status:
   `PENDING` -> `PAID` -> `PROCESSING` -> `READY_FOR_PICKUP` -> `COMPLETED` / `CANCELLED`.
3. Pembuatan antarmuka `resources/views/public/cart.blade.php`, `resources/views/public/checkout.blade.php`, `resources/views/public/order_success.blade.php`, dan `resources/views/admin/orders/index.blade.php`.
4. Mekanisme rollback stok otomatis jika pesanan dibatalkan.

---

### C. File yang Ditambah atau Diubah
- `app/Services/OrderProcessingService.php`
- `app/Http/Controllers/Web/OrderController.php`
- `resources/views/public/cart.blade.php`, `checkout.blade.php`, `order_success.blade.php`
- `resources/views/admin/orders/index.blade.php`

---

### D. Pengujian yang Dilakukan
- Uji matriks State Transition:
  - Transisi Valid: PENDING ke PAID -> PASS.
  - Transisi Valid: PAID ke PROCESSING ke READY ke COMPLETED -> PASS.
  - Transisi Rollback: PAID ke CANCELLED -> Stok otomatis dikembalikan ke produk (PASS).
  - Transisi Invalid: COMPLETED ke PENDING -> Ditolak (PASS).

---

### E. Expected Result
- Status pesanan bergerak sesuai aturan bisnis.
- Stok berkurang secara atomik saat pesanan lunas dan kembali saat batal.

---

### F. Actual Result
- Seluruh transisi status pesanan tereksekusi sesuai rancangan tanpa anomali.

---

### G. Status
**PASS**

---

### H. Defect yang Ditemukan
Tidak ada defect terbuka.

---

### I. Perbaikan yang Dilakukan
Penguatan penanganan pengecualian (*exception handling*) saat stok produk tidak mencukupi saat proses checkout.

---

### J. Progres Keseluruhan Proyek
**25.0% (Minggu 4 dari 16 Pertemuan)**

---

### K. Rencana Pertemuan Selanjutnya
Implementasi White Box Testing, Control Flow Graph, Cyclomatic Complexity pada modul Inventori dan Manajemen Keuangan UMKM Terpisah.
