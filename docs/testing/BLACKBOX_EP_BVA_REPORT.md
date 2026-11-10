# BLACK BOX TESTING REPORT: EQUIVALENCE PARTITIONING (EP) & BOUNDARY VALUE ANALYSIS (BVA)
## JUMIANG SMART TOURISM & UMKM — PERTEMUAN MINGGU 03

Dokumen ini menyajikan rancangan partisi ekuivalensi (EP), analisis nilai batas (BVA), dan hasil eksekusi pengujian nyata menggunakan `tests/Feature/BlackBoxEpBvaTest.php` serta `tests/Unit/OrderStateTransitionUnitTest.php`.

---

## 1. Equivalence Partitioning & Boundary Value Analysis

### 1.1 Validasi Harga Produk (`ProductService::createProduct`)
Domain nilai: Harga Produk (IDR). Aturan bisnis: `price >= 0`.

| ID Uji | Partisi / Nilai Batas | Input `price` | Expected Result | Realisasi Pengujian Otomatis | Status |
|---|---|---|---|---|---|
| `BVA-PROD-01` | Invalid Lower Boundary (`< 0`) | `-1` | Throw `InvalidArgumentException` | `OrderStateTransitionUnitTest::test_umkm_verification_fsm_and_product_price_bva` | **PASS** |
| `BVA-PROD-02` | Valid Minimum Boundary (`= 0`) | `0` | Produk tersimpan sebagai sampel/gratis (`0.0`) | `OrderStateTransitionUnitTest::test_umkm_verification_fsm_and_product_price_bva` | **PASS** |
| `EP-PROD-03` | Valid Nominal Partition (`> 0`) | `35000` | Produk tersimpan di katalog `MOD-PROD` | Diverifikasi via API `/api/v1/products/1` | **PASS** |

### 1.2 Validasi Kuantitas Pesanan vs Stok (`POST /api/v1/orders/simulate` & `InventoryLedgerService`)
Subjek uji: Produk #4 (*Kain Batik Tulis Motif Bahari Pamekasan*, stok tersedia = `8`).

| ID Uji | Partisi / Nilai Batas | Input `quantity` | Expected Result | Actual Result | Status |
|---|---|---|---|---|---|
| `BVA-ORD-01` | Invalid Partition (`< 0`) | `-1` | HTTP `422 Unprocessable Entity` | HTTP `422` (`{"status":"error"}`) | **PASS** |
| `BVA-ORD-02` | Invalid Lower Boundary (`= 0`) | `0` | HTTP `422 Unprocessable Entity` | HTTP `422` (`{"status":"error"}`) | **PASS** |
| `BVA-ORD-03` | Valid Lower Boundary (`= 1`) | `1` | HTTP `201 Created` (`order_status: PENDING`) | HTTP `201 Created` | **PASS** |
| `BVA-ORD-04` | Valid Upper Stock Boundary (`= 8`) | `8` | HTTP `201 Created` | HTTP `201 Created` | **PASS** |
| `BVA-ORD-05` | Invalid Over-Stock Boundary (`> 8`) | `9` | HTTP `422` + pesan stok tidak mencukupi | HTTP `422` (`status: error`) | **PASS** |

### 1.3 Validasi Akses Halaman Publik & Pelabelan `[DATA DEMO / SIMULASI]`
Seluruh 9 rute publik utama (`/`, `/tentang-jumiang`, `/peta-wisata`, `/katalog-umkm`, `/produk/rengginang-lorjuk-super`, `/keranjang`, `/reservasi-wisata`, `/login`, `/register`) diuji otomatis via `BlackBoxEpBvaTest::test_public_portal_pages_and_demo_label_compliance`:
- **Expected:** HTTP `200 OK` dan mengandung banner `[DATA DEMO / SIMULASI]`.
- **Actual:** 9/9 rute merespons HTTP `200 OK` dan menampilkan penanda `[DATA DEMO / SIMULASI]`.
- **Status:** **PASS** (dieksekusi melalui `php tests/run_all_tests.php`).
