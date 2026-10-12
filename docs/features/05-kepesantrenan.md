# Kepesantrenan

Pengelolaan asrama, hafalan, dan perizinan santri pondok dalam satu tempat.

## Spesifikasi

### Tujuan
Memberi musyrif dan pengurus pondok satu tempat untuk mengelola asrama, memantau hafalan Al-Quran, mencatat pelanggaran dan pembinaan, serta mengatur izin keluar-pulang santri, sehingga seluruh kehidupan pondok tercatat rapi dan mudah dipantau.

### Selesai bila
- Pengelola pondok dapat melihat daftar kamar beserta penghuni dan pembinanya dalam satu halaman.
- Capaian hafalan setiap santri (surah/ayat/juz, nilai, tanggal, penilai) dapat dicatat dan dilihat perkembangannya dari waktu ke waktu.
- Pelanggaran santri beserta poin dan tindak pembinaannya tercatat, dan bisa ditelusuri kembali.
- Pengajuan izin keluar atau pulang dapat diajukan, disetujui/ditolak, dan statusnya terlihat jelas (pending/disetujui/ditolak/kembali).
- Semua data kepesantrenan hanya tampil untuk lembaga aktif pengguna dan tersimpan pada tahun ajaran yang berlaku.
- Tampilan mengikuti gaya Neobrutalism (border tebal, shadow solid, warna kontras) dengan label berbahasa Indonesia.

## Sub-fitur: Kamar & Asrama

Atur pembagian kamar dan penghuni asrama dengan rapi.

### Tujuan
Membantu pengurus pondok mengatur pembagian kamar asrama beserta penghuninya agar penempatan santri jelas dan rapi.

### Selesai bila
- Pengurus dapat membuat, mengubah, dan menonaktifkan kamar dengan nama, kapasitas, dan jenis kelamin yang jelas.
- Santri dapat ditempatkan ke sebuah kamar untuk tahun ajaran berjalan, dan sistem menolak penempatan jika kapasitas kamar sudah penuh atau jenis kelamin tidak sesuai.
- Daftar kamar menampilkan jumlah penghuni saat ini dibanding kapasitasnya, serta daftar santri yang menempatinya.

## Sub-fitur: Pembina & Musyrif

Tetapkan pembina yang bertanggung jawab atas setiap kamar.

### Tujuan
Menetapkan pembina atau musyrif yang bertanggung jawab atas setiap kamar agar pembinaan santri tidak lepas pantau.

### Selesai bila
- Pengurus dapat menetapkan satu atau beberapa pengguna sebagai pembina/musyrif pada sebuah kamar dengan peran yang jelas (mis. Pembina, Musyrif).
- Halaman kamar menampilkan siapa saja pembina yang bertanggung jawab atas kamar tersebut.
- Pengurus dapat mengganti atau melepas penugasan pembina tanpa menghapus data kamar maupun akun pembina.

## Sub-fitur: Catatan Tahfidz

Pantau capaian hafalan Al-Quran tiap santri dari waktu ke waktu.

### Tujuan
Memantau capaian hafalan Al-Quran setiap santri dari waktu ke waktu agar perkembangan tahfidz terlihat jelas.

### Selesai bila
- Pembina dapat mencatat setoran hafalan santri berupa surah, rentang ayat, juz, nilai, tanggal penilaian, dan catatan.
- Riwayat hafalan seorang santri dapat dilihat secara berurutan berdasarkan tanggal, lengkap dengan total capaian (mis. jumlah juz).
- Daftar hafalan dapat dicari/difilter per santri atau per kamar agar pembina mudah menelusuri.

## Sub-fitur: Pelanggaran & Pembinaan

Catat pelanggaran dan tindak pembinaan supaya tetap terpantau.

### Tujuan
Mencatat pelanggaran santri beserta tindak pembinaannya supaya perilaku santri tetap terpantau dan ditindaklanjuti dengan rapi.

### Selesai bila
- Pembina dapat mencatat pelanggaran santri berupa jenis, deskripsi, poin, tanggal, dan tindakan pembinaan yang diberikan.
- Riwayat pelanggaran dan pembinaan seorang santri dapat dilihat kembali, termasuk total poin pelanggarannya.
- Daftar pelanggaran dapat disaring per santri atau per kamar agar mudah dipantau pengurus.

## Sub-fitur: Perizinan Keluar & Pulang

Ajukan dan setujui izin keluar atau pulang santri secara tercatat.

### Tujuan
Mengelola pengajuan izin keluar atau pulang santri pondok secara tercatat agar kepergian dan kepulangan santri terkontrol.

### Selesai bila
- Pengguna dapat mengajukan izin santri dengan jenis (keluar/pulang), alasan, dan rentang tanggal.
- Pengurus dapat menyetujui atau menolak pengajuan, dan status izin terlihat jelas (pending/disetujui/ditolak/kembali).
- Daftar izin menampilkan santri, jenis, tanggal, dan statusnya sehingga pengurus tahu siapa yang sedang di luar pondok.

## Task

### 1. Bangun halaman utama Kepesantrenan dengan tab navigasi

### 2. Buat halaman daftar kamar & asrama dengan data tiruan

### 3. Buat halaman detail kamar: penghuni & pembina

### 4. Buat form tambah dan ubah kamar dengan data tiruan

### 5. Buat halaman pengelolaan pembina & musyrif per kamar

### 6. Buat halaman catatan tahfidz santri dengan data tiruan

### 7. Buat form setoran hafalan dengan data tiruan

### 8. Buat halaman pelanggaran & pembinaan santri dengan data tiruan

### 9. Buat form catat pelanggaran dan tindak pembinaan

### 10. Buat halaman daftar perizinan keluar & pulang dengan data tiruan

### 11. Buat form pengajuan izin dan aksi setujui/tolak

### 12. Tambah filter dan pencarian per santri atau kamar di daftar

### 13. Buat migrasi tabel kamar asrama & penghuni kamar

### 14. Buat migrasi tabel pembina kamar

### 15. Buat migrasi tabel catatan tahfidz

### 16. Buat migrasi tabel pelanggaran & pembinaan

### 17. Buat migrasi tabel perizinan keluar & pulang

### 18. Buat model dan relasi kepesantrenan

### 19. Buat endpoint CRUD kamar dengan validasi kapasitas & jenis kelamin

### 20. Buat endpoint penempatan santri ke kamar tahun ajaran berjalan

### 21. Buat endpoint penugasan pembina dan musyrif kamar

### 22. Buat endpoint catatan tahfidz dan rekap capaian juz

### 23. Buat endpoint pelanggaran dan total poin santri

### 24. Buat endpoint perizinan: pengajuan, setujui/tolak, kembali

### 25. Terapkan scoping lembaga aktif & tahun ajaran pada query kepesantrenan
