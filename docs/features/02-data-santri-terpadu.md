# Data Santri Terpadu

Satu biodata santri untuk semua lembaga, jadi tidak ada lagi data yang dicatat berulang.

## Spesifikasi

### Tujuan
Menyimpan satu biodata santri yang dipakai bersama oleh semua lembaga, sehingga data santri tidak lagi dicatat berulang di tiap lembaga dan selalu konsisten.

### Selesai bila
- Admin dapat membuka daftar seluruh santri dan menemukan satu santri dengan cepat lewat nama, NIK, atau NISN.
- Admin dapat menambah dan mengubah biodata santri satu kali, lalu data yang sama langsung terlihat dari lembaga mana pun yang menaungi santri tersebut.
- Sistem menolak penambahan santri dengan NIK atau NISN yang sudah terdaftar, dan menawarkan menghubungkan ke data santri yang sudah ada.
- Satu santri dapat tercatat aktif di lebih dari satu lembaga (mis. Pondok dan SMP Plus) dalam satu tahun ajaran tanpa membuat data baru.
- Halaman profil santri menampilkan status, riwayat keanggotaan lembaga, dan catatan penting santri, termasuk penanda bila ia bersekolah di luar yayasan.
- Setiap santri hanya muncul satu kali di daftar, meski terdaftar di beberapa lembaga.

## Sub-fitur: Daftar & Pencarian Santri

Temukan santri dengan cepat lewat nama, NIK, atau NISN.

### Tujuan
Memudahkan admin menemukan santri yang dicari tanpa menelusuri data satu per satu.

### Selesai bila
- Halaman daftar santri menampilkan nama, NIK, NISN, dan lembaga tempat santri aktif.
- Ada kolom pencarian yang menyaring daftar berdasarkan nama, NIK, atau NISN begitu diketik.
- Bila pencarian tidak menemukan hasil, muncul pesan jelas bahwa santri tidak ditemukan.

## Sub-fitur: Tambah & Ubah Biodata

Isi atau perbarui data diri santri satu kali untuk dipakai semua lembaga.

### Tujuan
Memungkinkan admin mengisi atau memperbarui data diri santri satu kali agar dipakai semua lembaga.

### Selesai bila
- Formulir tambah dan ubah biodata memuat data diri, data orang tua/wali, alamat, dan foto santri dengan label berbahasa Indonesia.
- Setelah disimpan, perubahan langsung terlihat pada data santri yang sama di semua lembaga yang menaunginya.
- Data wajib yang kosong atau tidak valid ditolak dengan pesan yang menjelaskan bagian mana yang perlu diperbaiki.

## Sub-fitur: Pencegahan Data Dobel

Sistem menolak NIK atau NISN yang sudah terdaftar supaya tidak ada data kembar.

### Tujuan
Mencegah tercatatnya satu santri lebih dari sekali karena NIK atau NISN yang sama.

### Selesai bila
- Saat menyimpan santri dengan NIK atau NISN yang sudah terdaftar, sistem menolak dan menampilkan pesan bahwa data tersebut sudah ada.
- Sistem menawarkan pilihan untuk menghubungkan santri lama ke lembaga baru alih-alih membuat data baru.
- Pengecekan dobel dilakukan juga saat pendaftar PSB dikonversi menjadi santri aktif.

## Sub-fitur: Keanggotaan di Banyak Lembaga

Satu santri bisa dicatat aktif di pondok sekaligus di sekolah formal.

### Tujuan
Memungkinkan satu santri tercatat aktif di beberapa lembaga sekaligus tanpa menggandakan biodatanya.

### Selesai bila
- Pada profil santri, admin dapat menambahkan keanggotaan ke lembaga lain beserta tahun ajaran dan tanggal mulai.
- Daftar keanggotaan santri menampilkan lembaga, tahun ajaran, dan status (aktif, lulus, berhenti) untuk tiap lembaga.
- Santri yang terdaftar di dua lembaga tetap dihitung sebagai satu individu, dengan penanda lembaga tempat ia aktif.

## Sub-fitur: Profil & Riwayat Santri

Halaman profil berisi status, riwayat lembaga, dan catatan penting santri.

### Tujuan
Memberi satu halaman rujukan berisi keadaan dan riwayat santri untuk dibaca admin, guru, maupun musyrif.

### Selesai bila
- Halaman profil menampilkan biodata, foto, status, dan skor kelengkapan data santri.
- Halaman profil menampilkan riwayat keanggotaan santri di tiap lembaga beserta tahun ajarannya.
- Halaman profil menyediakan bagian catatan penting santri yang bisa dilihat kembali, termasuk penanda bila ia bersekolah di luar yayasan.

## Sub-fitur: Penandaan Santri Sekolah Luar Yayasan

Tandai santri yang bersekolah di luar yayasan, misalnya di MTsN 3 Cirebon.

### Tujuan
Mencatat bahwa seorang santri pondok juga bersekolah di luar yayasan, misalnya MTsN 3 Cirebon.

### Selesai bila
- Pada formulir biodata tersedia kolom untuk mengisi nama sekolah luar yayasan yang bersifat opsional.
- Santri yang bersekolah di luar yayasan ditandai jelas di profil dan daftar santri, tanpa dianggap sebagai keanggotaan lembaga.
- Sekolah luar yayasan tidak muncul sebagai pilihan lembaga pada pemilih workspace.

## Task

### 1. Bangun halaman daftar santri tiruan

### 2. Buat pencarian nama NIK NISN

### 3. Tampilkan pesan santri tidak ditemukan

### 4. Bangun formulir tambah ubah biodata

### 5. Buat validasi form biodata mock

### 6. Tambahkan komponen unggah foto santri

### 7. Bangun modal konfirmasi data dobel

### 8. Buat alur hubungkan santri lama

### 9. Bangun panel keanggotaan lembaga santri

### 10. Buat form tambah keanggotaan lembaga

### 11. Tampilkan status keanggotaan tiap lembaga

### 12. Bangun halaman profil santri tiruan

### 13. Tampilkan riwayat keanggotaan tahun ajaran

### 14. Buat bagian catatan penting santri

### 15. Buat penanda sekolah luar yayasan

### 16. Sembunyikan sekolah luar dari pemilih workspace

### 17. Rancang skema santri dan migrasi

### 18. Buat endpoint daftar cari santri

### 19. Buat query pencarian nama NIK NISN

### 20. Buat endpoint simpan ubah biodata

### 21. Buat validasi biodata wajib valid

### 22. Buat penyimpanan foto santri

### 23. Implementasi unik NIK NISN santri

### 24. Buat endpoint cek dobel dan hubungkan

### 25. Integrasi cek dobel konversi PSB

### 26. Buat tabel keanggotaan lembaga santri

### 27. Buat endpoint tambah keanggotaan lembaga

### 28. Buat endpoint daftar keanggotaan santri

### 29. Buat endpoint profil dan riwayat santri

### 30. Buat skema catatan penting santri

### 31. Buat perhitungan skor kelengkapan data

### 32. Tambah kolom sekolah luar yayasan

### 33. Buat logika penanda luar yayasan

### 34. Kecualikan sekolah luar dari daftar lembaga
