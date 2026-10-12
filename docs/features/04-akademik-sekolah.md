# Akademik Sekolah

Pengelolaan kelas, jadwal, kehadiran, dan nilai untuk sekolah formal dalam satu tempat.

## Spesifikasi

### Tujuan
Satu tempat untuk mengurus kelas, jadwal, kehadiran, dan nilai siswa sekolah formal, sehingga guru dan wali kelas tidak lagi bergantung pada buku absen atau file terpisah.

### Selesai bila
- Dari satu menu Akademik di sekolah yang sedang aktif, guru dan wali kelas bisa mengatur kelas, menyusun jadwal, mencatat kehadiran, dan mengisi nilai.
- Semua data akademik hanya menampilkan data sekolah yang sedang aktif dan tersimpan pada tahun ajaran yang berlaku.
- Alur kelas → jadwal → presensi → nilai saling terhubung memakai daftar siswa dan kelas yang sama, tanpa entri ulang.
- Wali siswa bisa melihat hasil belajar anaknya (kehadiran dan nilai/rapor).
- Tampilan mengikuti gaya aplikasi (bahasa Indonesia, tabel daftar yang mudah dicari dan difilter).

## Sub-fitur: Kelas & Wali Kelas

Atur pembagian kelas dan penanggung jawabnya di tiap sekolah.

### Tujuan
Menata pembagian kelas tiap sekolah beserta wali kelas yang bertanggung jawab, agar setiap siswa jelas berada di kelas siapa.

### Selesai bila
- Admin/guru bisa menambah, mengubah, dan menonaktifkan kelas beserta tingkatnya pada sekolah dan tahun ajaran yang sedang aktif.
- Setiap kelas bisa ditetapkan satu wali kelas dari daftar guru di sekolah tersebut.
- Siswa dapat ditempatkan ke dalam kelas dan dikeluarkan kembali; daftar kelas menampilkan jumlah siswa terdaftar.
- Daftar kelas tampil rapi (nama kelas, tingkat, wali kelas, jumlah siswa) dan bisa dicari atau difilter per tingkat/wali kelas.

## Sub-fitur: Mata Pelajaran & Jadwal

Susun pelajaran dan jadwal mingguan yang mudah dilihat guru.

### Tujuan
Menyusun daftar mata pelajaran dan jadwal mingguan agar guru tahu kapan dan di mana harus mengajar.

### Selesai bila
- Admin bisa menambah dan mengubah mata pelajaran (nama dan kode) untuk sekolahnya.
- Jadwal dapat disusun per kelas berisi hari, jam mulai–selesai, mata pelajaran, dan guru pengampu.
- Tampilan jadwal mingguan mudah dibaca dan bisa difilter per kelas atau per guru.
- Sistem mencegah atau memberi peringatan bila ada jadwal bentrok untuk kelas atau guru yang sama pada waktu yang bersamaan.

## Sub-fitur: Presensi Siswa

Catat kehadiran siswa setiap pertemuan tanpa buku absen manual.

### Tujuan
Mencatat kehadiran siswa setiap pertemuan dengan cepat, sehingga rekap absensi selalu tersedia tanpa buku absen manual.

### Selesai bila
- Guru bisa membuka daftar siswa satu kelas dan menandai kehadiran per tanggal pertemuan (Hadir/Izin/Sakit/Alpa).
- Setiap catatan kehadiran tersimpan untuk siswa, kelas, dan tanggal tertentu beserta penanda siapa yang mencatat.
- Guru bisa memperbaiki catatan kehadiran yang salah.
- Tersedia rekap kehadiran per siswa dan per kelas (mis. jumlah hadir/izin/sakit/alpa) yang bisa dilihat dan diunduh.

## Sub-fitur: Nilai & Rapor

Input nilai harian sampai rapor siap dibagikan ke wali siswa.

### Tujuan
Menginput nilai harian sampai ujian lalu menyusunnya menjadi rapor yang siap dibagikan ke wali siswa.

### Selesai bila
- Guru bisa mengisi nilai per siswa untuk mata pelajaran yang diampu, dengan jenis penilaian (harian/ujian) dan semester.
- Nilai yang sudah diisi bisa dilihat dan diperbaiki kembali oleh guru sebelum rapor difinalkan.
- Rapor menampilkan ringkasan nilai per mata pelajaran beserta identitas siswa dan kelasnya.
- Wali siswa bisa melihat dan mengunduh hasil belajar anaknya dalam bentuk rapor yang siap cetak.

## Task

### 1. Buat halaman utama menu Akademik dengan data tiruan

### 2. Buat daftar kelas dengan filter tingkat mock

### 3. Buat form tambah ubah kelas dan wali kelas mock

### 4. Buat halaman detail kelas dan penempatan siswa mock

### 5. Buat halaman daftar mata pelajaran dan form mock

### 6. Buat tampilan jadwal mingguan per kelas mock

### 7. Buat form susun jadwal dan peringatan bentrok mock

### 8. Buat halaman presensi siswa per tanggal mock

### 9. Buat halaman rekap kehadiran per siswa dan kelas mock

### 10. Buat halaman input nilai per mata pelajaran mock

### 11. Buat halaman rapor siswa siap cetak mock

### 12. Buat halaman rapor untuk wali siswa mock

### 13. Buat migrasi tabel kelas dan wali kelas

### 14. Buat migrasi tabel mata pelajaran dan jadwal

### 15. Buat migrasi tabel presensi siswa

### 16. Buat migrasi tabel nilai dan rapor

### 17. Buat model dan relasi modul akademik

### 18. Terapkan filter sekolah dan tahun ajaran aktif

### 19. Buat API CRUD kelas dan wali kelas

### 20. Buat API penempatan siswa ke kelas

### 21. Buat API CRUD mata pelajaran

### 22. Buat API jadwal dengan deteksi bentrok

### 23. Buat API presensi siswa per pertemuan

### 24. Buat API rekap dan ekspor kehadiran

### 25. Buat API input dan perbaikan nilai

### 26. Buat API penyusunan dan cetak rapor

### 27. Buat API rapor untuk wali siswa
