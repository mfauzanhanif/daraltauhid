# Pengaturan Yayasan & Hak Akses

Atur identitas lembaga, tahun ajaran, dan siapa saja yang boleh mengakses apa.

## Spesifikasi

### Tujuan
Satu tempat bagi pengurus yayasan untuk mengatur identitas tiap lembaga, periode tahun ajaran yang berlaku, data pengguna, serta batas akses setiap orang di lembaga masing-masing.

### Selesai bila
- Halaman pengaturan menyajikan daftar lembaga, tahun ajaran aktif, dan daftar pengguna dalam satu menu yang mudah dipahami.
- Perubahan identitas lembaga langsung tersimpan dan tampil konsisten di seluruh aplikasi (misalnya nama lembaga pada header).
- Hanya pengguna dengan peran admin yayasan yang dapat membuka dan mengubah halaman ini; pengguna lain tidak melihat menunya.
- Setiap perubahan (lembaga, tahun ajaran, pengguna, hak akses) tercatat siapa pelakunya dan kapan dilakukan.
- Ada konfirmasi sebelum menyimpan perubahan penting agar tidak terjadi salah ubah.

## Sub-fitur: Data Lembaga

Daftar dan identitas tiap lembaga dalam yayasan dapat diubah kapan saja.

### Tujuan
Menyimpan dan memperbarui identitas tiap lembaga (nama, jenis, alamat, status aktif) supaya informasi lembaga selalu benar di seluruh sistem.

### Selesai bila
- Daftar lembaga menampilkan nama, jenis (formal/non-formal), dan status aktif atau nonaktif.
- Perubahan nama, alamat, atau status lembaga bisa disimpan dan langsung berlaku di aplikasi, termasuk pada pemilih lembaga saat login.
- Lembaga yang dinonaktifkan tidak lagi muncul di pemilih workspace pengguna.

## Sub-fitur: Tahun Ajaran Aktif

Tentukan periode berjalan agar data tersimpan pada tahun yang benar.

### Tujuan
Menetapkan tahun ajaran yang sedang berjalan untuk tiap lembaga agar data akademik tersimpan pada periode yang benar.

### Selesai bila
- Admin dapat menambah tahun ajaran baru (misalnya "2025/2026") untuk lembaga tertentu.
- Hanya satu tahun ajaran yang bisa berstatus aktif per lembaga; mengaktifkan yang baru otomatis menonaktifkan yang lama.
- Semua pencatatan baru (kelas, presensi, tagihan) otomatis memakai tahun ajaran aktif, sementara tahun ajaran lama tetap tersimpan sebagai arsip.

## Sub-fitur: Kelola Pengguna

Tambah, nonaktifkan, atau ubah data pengguna aplikasi beserta keanggotaan lembaganya di satu atau beberapa lembaga sekaligus.

### Tujuan
Mengelola akun pengguna aplikasi beserta keanggotaannya di satu atau beberapa lembaga, lengkap dengan perannya.

### Selesai bila
- Admin dapat menambah pengguna baru dan langsung menetapkan keanggotaannya di satu atau beberapa lembaga sekaligus, beserta perannya di tiap lembaga (misalnya Admin di SMP, Guru di MA).
- Daftar pengguna menampilkan lembaga dan peran masing-masing, serta bisa dinonaktifkan atau dicabut keanggotaannya tanpa menghapus akun.
- Perubahan keanggotaan langsung memengaruhi pilihan lembaga yang muncul saat pengguna login berikutnya.

## Sub-fitur: Hak Akses per Lembaga

Tentukan siapa yang boleh melihat dan mengubah data di tiap lembaga.

### Tujuan
Mengatur izin tiap peran pengguna di setiap lembaga, sehingga hanya orang berwenang yang bisa melihat atau mengubah data.

### Selesai bila
- Admin dapat memilih lembaga, lalu mengatur peran mana yang boleh melihat (baca) dan mengubah (ubah) data di lembaga itu.
- Perubahan hak akses langsung berlaku dan diperiksa di sisi server; pengguna tanpa izin tidak bisa membuka halaman atau menyimpan data.
- Tampilan daftar peran beserta izinnya memudahkan pengecekan, dan keanggotaan lembaga tetap menjadi syarat utama akses.

## Task

### 1. Bangun halaman pengaturan utama dengan mock

### 2. Buat panel data lembaga dengan data tiruan

### 3. Buat panel tahun ajaran aktif dengan data tiruan

### 4. Buat panel kelola pengguna dan keanggotaan dengan mock

### 5. Buat panel hak akses per lembaga dengan mock

### 6. Tambah dialog konfirmasi dan state perubahan mock

### 7. Buat migrasi dan model tabel lembaga

### 8. Buat migrasi dan model tabel tahun ajaran

### 9. Buat migrasi tabel keanggotaan pengguna lembaga

### 10. Buat migrasi tabel hak akses peran per lembaga

### 11. Buat tabel audit log dan pencatat perubahan

### 12. Buat endpoint CRUD data lembaga

### 13. Buat endpoint tahun ajaran dengan aktivasi tunggal

### 14. Buat endpoint kelola pengguna dan keanggotaan

### 15. Buat endpoint hak akses peran per lembaga

### 16. Terapkan middleware dan policy otorisasi akses

### 17. Catat audit log pada tiap perubahan pengaturan

### 18. Sediakan konteks lembaga aktif untuk header aplikasi
