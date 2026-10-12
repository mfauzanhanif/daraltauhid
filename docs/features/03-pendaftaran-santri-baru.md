# Pendaftaran Santri Baru

Wali santri mendaftar online, dan pendaftar yang lulus otomatis menjadi data santri.

## Spesifikasi

### Tujuan
Membuat satu alur pendaftaran santri baru secara daring supaya wali santri bisa mendaftar dari rumah dan pendaftar yang lulus langsung tercatat sebagai santri aktif tanpa input ulang.

### Selesai bila
- Wali santri dapat mengisi formulir pendaftaran daring dan memilih lembaga tujuan (Pondok/SMP/MA/MI) tanpa datang ke kantor.
- Berkas syarat (KK, akta, foto) dapat diunggah dari ponsel dan menempel pada data pendaftaran.
- Panitia dapat memeriksa kelengkapan berkas dan menandai pendaftar Lolos atau Tidak Lolos.
- Wali santri dapat mengecek status kelulusan anaknya lewat halaman pengumuman.
- Pendaftar yang ditandai lulus berubah menjadi santri aktif di lembaga tujuan dan akun wali otomatis dibuatkan, dengan NIK/NISN yang sudah ada ditolak agar data tidak dobel.

## Sub-fitur: Formulir Pendaftaran Online

Wali santri mengisi data dan memilih lembaga tujuan langsung dari rumah.

### Tujuan
Menyediakan formulir pendaftaran daring agar wali santri dapat mengisi data calon santri dan memilih lembaga tujuan langsung dari rumah.

### Selesai bila
- Halaman formulir pendaftaran dapat dibuka publik dari portal PSB tanpa login, berisi isian data calon santri, data wali, dan pilihan lembaga tujuan (Pondok/SMP/MA/MI).
- Formulir menolak isian yang kosong atau salah format dan menampilkan pesan peringatan dalam bahasa Indonesia yang mudah dipahami.
- Setelah dikirim, wali santri menerima nomor pendaftaran sebagai bukti dan datanya tersimpan dengan status "terkirim".

## Sub-fitur: Unggah Dokumen Syarat

Unggah KK, akta, dan berkas lain langsung dari ponsel tanpa datang ke kantor.

### Tujuan
Memungkinkan wali santri mengunggah berkas syarat (KK, akta, foto) langsung dari ponsel tanpa harus datang ke kantor.

### Selesai bila
- Tersedia tombol unggah untuk tiap jenis dokumen wajib (KK, akta, foto) dan berkas pendukung lain pada formulir pendaftaran.
- Berkas yang sudah diunggah tampil sebagai daftar dengan nama dan jenis dokumen, serta dapat dibuka kembali oleh wali santri maupun panitia.
- Sistem menolak berkas yang kosong atau berukuran/format tidak didukung, disertai pesan yang mudah dipahami.

## Sub-fitur: Verifikasi Pendaftar

Panitia memeriksa kelengkapan berkas dan menandai pendaftar yang lolos seleksi.

### Tujuan
Memberi panitia satu tempat untuk memeriksa kelengkapan berkas dan menandai hasil seleksi tiap pendaftar.

### Selesai bila
- Panitia dapat melihat daftar pendaftar beserta status dan kelengkapan dokumennya, lalu membuka halaman detail tiap pendaftar.
- Panitia dapat menandai tiap dokumen sudah atau belum terverifikasi dan menulis catatan untuk pendaftar.
- Panitia dapat mengubah status pendaftar menjadi Lolos atau Tidak Lolos, dan perubahan itu langsung tersimpan.

## Sub-fitur: Pengumuman Kelulusan

Hasil seleksi diumumkan agar wali santri bisa mengecek status anaknya.

### Tujuan
Menampilkan hasil seleksi secara terbuka supaya wali santri dapat mengecek status anaknya kapan saja.

### Selesai bila
- Wali santri dapat mencari status kelulusan memakai nomor pendaftaran pada halaman pengumuman publik.
- Halaman menampilkan status dengan jelas: Lulus, Tidak Lulus, atau Masih Diproses, beserta catatan panitia bila ada.
- Hasil yang tampil selalu mengikuti keputusan terbaru panitia tanpa perlu dibuat ulang.

## Sub-fitur: Pendaftar Lulus Jadi Santri

Pendaftar yang lulus langsung tercatat sebagai santri aktif dan akun wali dibuatkan.

### Tujuan
Mengubah pendaftar yang lulus menjadi santri aktif di lembaga tujuan sekaligus membuatkan akun untuk wali santri, tanpa input data ulang.

### Selesai bila
- Panitia dapat menjalankan satu aksi "Jadikan Santri" pada pendaftar berstatus Lolos.
- Sistem membuat atau menghubungkan biodata santri ke lembaga tujuan, menolak NIK/NISN yang sudah terdaftar agar tidak dobel, lalu menandai pendaftaran selesai diproses.
- Akun wali santri otomatis dibuatkan dan langsung terhubung ke data anaknya saat wali masuk ke aplikasi, sedangkan pendaftar yang belum Lolos tidak dapat diproses menjadi santri.

## Task

### 1. Bangun halaman formulir pendaftaran publik dengan data tiruan

### 2. Tambahkan validasi isian dan tampilkan nomor pendaftaran tiruan

### 3. Buat komponen unggah dokumen syarat dengan pratinjau lokal

### 4. Tampilkan daftar dokumen terunggah dengan nama dan jenisnya

### 5. Buat halaman daftar pendaftar untuk panitia dengan data tiruan

### 6. Bangun halaman detail pendaftar dan kontrol verifikasi berkas

### 7. Buat halaman pengumuman cek kelulusan berdasarkan nomor pendaftaran

### 8. Tambahkan aksi Jadikan Santri dan umpan balik status di detail pendaftar

### 9. Buat skema database dan model pendaftaran beserta dokumen

### 10. Buat endpoint simpan pendaftaran dengan nomor pendaftaran otomatis

### 11. Buat endpoint unggah dokumen dengan validasi ukuran dan format

### 12. Buat endpoint daftar dan detail pendaftar untuk panitia

### 13. Buat endpoint verifikasi dokumen dan perubahan status seleksi

### 14. Buat endpoint publik pengumuman status kelulusan pendaftar

### 15. Buat service Jadikan Santri dengan akun wali dan cek duplikat NIK/NISN
