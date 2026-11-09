# API & INTEGRATION TESTING DOCUMENTATION
## JUMIANG SMART TOURISM & UMKM
**REST API Specification & Automated Verification Guide (Newman & Postman)**

---

## 1. STRUKTUR ENDPOINT REST API (V1)

Semua endpoint API beralamat dasar: `/api/v1` dengan format pertukaran data JSON standar:
- Header wajib: `Accept: application/json`
- Autentikasi: `Authorization: Bearer {token}` (untuk endpoint terproteksi)

### Daftar Endpoint Utama:
| HTTP Verb | URI Endpoint | Deskripsi | Akses |
|---|---|---|---|
| `GET` | `/api/v1/destinations` | Informasi profil destinasi Pantai Jumiang | Publik |
| `GET` | `/api/v1/map-markers` | Titik koordinat spasial WGS84 untuk Leaflet.js | Publik |
| `GET` | `/api/v1/products` | Katalog produk UMKM terverifikasi | Publik |
| `GET` | `/api/v1/products/{id}` | Detail produk tertentu | Publik |
| `POST` | `/api/v1/orders/simulate` | Endpoint simulasi checkout dan pemesanan | Terautentikasi |
| `POST` | `/api/v1/payments/simulate-pay` | Konfirmasi pembayaran simulasi digital | Terautentikasi |
| `GET` | `/api/v1/umkm/my-finances` | Laporan arus kas buku usaha merchant | Pelaku UMKM |

---

## 2. PENGUJIAN INTEGRASI & OTOMASI NEWMAN
Pengujian integrasi antarmodul pada Pertemuan 6 akan dijalankan menggunakan runner Newman CLI:
```bash
newman run tests/postman/jumiang_api_collection.json -e tests/postman/test_env.json --reporters cli,json --reporter-json-export docs/testing/reports/api_test_report.json
```
Kriteria Uji Integrasi:
1. Konsistensi status HTTP (200 OK, 201 Created, 400 Bad Request, 401 Unauthorized, 403 Forbidden, 422 Unprocessable Entity).
2. Verifikasi rantai data: Order Baru -> Validasi Stok -> Reservasi Stok -> Pembayaran Simulasi -> Pengurangan Stok Aktual -> Jurnal Kas UMKM.
