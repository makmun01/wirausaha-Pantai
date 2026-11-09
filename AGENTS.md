# AGENTS.md - ATURAN DAN PEDOMAN AGENT AI

File ini berisi instruksi wajib dan aturan tata kelola (governance rules) untuk seluruh AI Assistant / Developer Agent yang bekerja pada proyek **JUMIANG SMART TOURISM & UMKM**.

==================================================
10 ATURAN MUTLAK PENGEMBANGAN PROYEK
==================================================

1. **Selalu baca PROJECT_CONTEXT.md** sebelum mengambil tindakan atau menulis kode apa pun, untuk memahami status proyek terkini dan batas modul.
2. **Selalu baca ROADMAP_16_WEEKS.md** untuk mengetahui target capaian minggu berjalan dan keterhubungannya dengan RPS Manajemen Proyek Sistem Informasi.
3. **Selalu periksa CHANGELOG.md** untuk melihat histori perubahan sebelumnya agar tidak terjadi duplikasi atau regresi kode.
4. **JANGAN PERNAH MENGULANG PROYEK DARI AWAL.** Bangun secara bertahap di atas fondasi yang telah dikerjakan pada pertemuan-pertemuan sebelumnya.
5. **JANGAN MENGHAPUS FITUR SEBELUMNYA.** Setiap fitur yang telah lolos uji pada minggu terdahulu wajib dipertahankan dan dilakukan regression testing.
6. **JANGAN MENGGANTI TECH STACK TANPA IZIN.** Gunakan stack yang telah ditetapkan (Laravel, Bootstrap 5.3, Alpine.js, Leaflet.js, Chart.js, MySQL 8.4, Docker). Jika ada kendala dependensi, laporkan dan berikan analisis sebelum mengajukan alternatif.
7. **KERJAKAN HANYA MINGGU YANG DIMINTA.** Jika instruksi menyatakan "LANJUTKAN PERTEMUAN KE-[N]", fokuskan pekerjaan 100% pada cakupan pertemuan N. Jangan melompat ke minggu lain tanpa instruksi eksplisit pengguna.
8. **SELALU JALANKAN PENGUJIAN YANG RELEVAN.** Setiap penambahan modul atau fitur wajib disertai bukti uji nyata (Unit/Feature/Integration/Blackbox/Performance/Security).
9. **CATAT HASIL PENGUJIAN SEBENARNYA.** Dilarang keras memalsukan hasil uji (fake test results). Jika fitur belum diuji, cantumkan status `NOT TESTED`. Jika gagal, beri status `FAIL` dan catat dalam `DEFECT_LOG.md`.
10. **UPDATE DOKUMENTASI SETIAP PERTEMUAN.** Setiap akhir sesi pertemuan, wajib memperbarui `CHANGELOG.md`, `PROJECT_CONTEXT.md`, dokumen testing terkait, dan file catatan pertemuan `docs/meeting-notes/WEEK_[XX].md`.

==================================================
ATURAN DATA DAN INTEGRITAS LAPANGAN
==================================================
1. Kasus nyata adalah Pantai Jumiang, Desa Tanjung, Kecamatan Pademawu, Kabupaten Pamekasan, Madura, Jawa Timur.
2. Dilarang mengarang data statistik lapangan (misal: jumlah pengunjung aktual, harga tiket resmi yang belum diverifikasi pengelola, jumlah UMKM terdaftar secara riil).
3. Seluruh data awal wajib dilabeli secara jelas sebagai **DATA DEMO / SIMULASI**.
4. Sediakan antarmuka bagi Admin/Pengelola untuk memperbarui data demo menjadi data riil hasil observasi/survei lapangan.

==================================================
STANDAR STATUS PENGUJIAN (ISO 29119)
==================================================
Setiap test case dan defect hanya boleh memiliki salah satu status berikut:
- `NOT TESTED` : Belum dieksekusi.
- `PASS`       : Memenuhi expected result secara penuh.
- `FAIL`       : Terjadi deviasi atau error antara actual result dan expected result.
- `BLOCKED`    : Tidak dapat diuji karena adanya dependensi yang rusak atau belum siap.
- `FIXED`      : Defect telah diperbaiki namun belum diuji ulang.
- `RETESTED`   : Defect telah diuji kembali dan dipastikan selesai (lolos verifikasi).

==================================================
FORMAT LAPORAN MINGGUAN WAJIB
==================================================
Setiap kali menyelesaikan pengerjaan pertemuan mingguan, Agent wajib menyajikan laporan dengan struktur berikut:
A. Materi RPS Minggu Ini
B. Fitur yang Dikembangkan
C. File yang Ditambah atau Diubah
D. Pengujian yang Dilakukan
E. Expected Result
F. Actual Result
G. Status (PASS / FAIL / BLOCKED)
H. Defect yang Ditemukan
I. Perbaikan yang Dilakukan
J. Progres Keseluruhan Proyek (%)
K. Rencana Pertemuan Selanjutnya
