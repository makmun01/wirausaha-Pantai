# ETL DOCUMENTATION (EXTRACT - TRANSFORM - LOAD)
## JUMIANG SMART TOURISM & UMKM
**Technical Mapping, Cleansing Rules, and Import Procedures**

---

## 1. PEMETAAN KOLOM DATA (MAPPING MATRIX)

### 1.1 Migrasi Profil UMKM (`raw_umkm_survey.csv` -> `umkm_merchants`)
| Kolom CSV Sumber | Tipe Sumber | Kolom Target Database | Transformasi yang Diterapkan |
|---|---|---|---|
| `Nama_Toko` | String | `business_name` | Trim whitespace, Title Case |
| `Nama_Pemilik` | String | `owner_name` | Trim whitespace |
| `Kategori_Usaha` | String | `category` | Mapping enum: Kuliner, Kerajinan, dll |
| `Kontak_WA` | String | `phone_number` | Standarisasi prefix `+62` |
| `Alamat_Fisik` | String | `address` | Validasi kelengkapan teks |
| `Titik_Koordinat` | Lat,Lng string | `map_markers.latitude`, `longitude` | Split string ke decimal 8 presisi |

---

## 2. PENANGANAN ANOMALI DATA
- Baris data yang memiliki nilai wajib kosong (*null constraint violation*) akan dilempar ke tabel log karantina `migration_quarantine_logs` untuk ditinjau manual tanpa menghentikan pemrosesan baris lainnya.
