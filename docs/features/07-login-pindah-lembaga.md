# Login & Pindah Lembaga

Satu akun untuk masuk, lalu bebas berpindah lembaga sesuai peran tanpa login ulang.

## Spesifikasi

### Tujuan
Memberi satu pintu masuk bagi semua pengguna yayasan, sehingga cukup sekali login untuk mengakses lembaga mana pun yang diikuti dan berpindah lembaga tanpa login ulang.

### Selesai bila
- Semua pengguna (yayasan, sekolah, pondok) masuk lewat satu halaman login yang sama dengan email dan kata sandi mereka sendiri.
- Setelah login, pengguna hanya melihat lembaga yang diikutinya; lembaga lain tidak muncul dan tetap tidak bisa diakses walau alamatnya ditebak.
- Isi aplikasi otomatis menampilkan data lembaga yang sedang dipilih, dan pilihan lembaga terakhir diingat saat pengguna kembali.
- Tombol pindah lembaga di bagian atas layar langsung mengganti isi halaman tanpa meminta login ulang.
- Pengguna tanpa keanggotaan lembaga aktif ditolak masuk dengan pesan yang jelas agar menghubungi admin yayasan.

## Sub-fitur: Masuk Satu Pintu

Satu halaman login untuk semua pengguna yayasan, sekolah, maupun pondok.

### Tujuan
Menyediakan satu halaman login terpusat agar semua pengguna yayasan, sekolah, dan pondok dapat masuk memakai akun yang sama.

### Selesai bila
- Hanya ada satu halaman login untuk semua pengguna dari lembaga mana pun.
- Pengguna memasukkan email dan kata sandi; jika benar langsung diarahkan ke lembaga yang sesuai, jika salah muncul pesan kesalahan yang mudah dipahami.
- Tersedia tautan "Lupa Kata Sandi" di halaman login.

### Tujuan
Memudahkan pengguna berpindah antar lembaga yang diikutinya lewat satu tombol di bagian atas layar, tanpa login ulang.

### Selesai bila
- Tombol pindah lembaga selalu tampil di bagian atas layar dan hanya memuat lembaga yang diikuti pengguna.
- Memilih lembaga lain langsung mengganti isi halaman tanpa keluar dari akun.
- Lembaga yang sedang aktif terlihat jelas, dan pilihan terakhir tetap tersimpan saat pengguna kembali.

### Tujuan
Memungkinkan satu pengguna memiliki peran berbeda di lembaga berbeda, sehingga hak akses menyesuaikan tempat ia sedang berada.

### Selesai bila
- Saat berpindah lembaga, menu dan tombol yang tampil menyesuaikan peran pengguna di lembaga tersebut.
- Pengguna tidak bisa menjalankan aksi yang bukan haknya di lembaga itu, dan upaya paksa ditolak.
- Di daftar keanggotaannya, peran tiap lembaga terlihat jelas (mis. Admin di SMP, Guru di MA).

### Tujuan
Memberi pengguna cara memulihkan akses sendiri saat lupa kata sandi, tanpa harus menghubungi admin.

### Selesai bila
- Tautan "Lupa Kata Sandi" di halaman login mengarah ke formulir permintaan pemulihan.
- Pengguna memasukkan email, menerima tautan pemulihan, lalu bisa menetapkan kata sandi baru lewat tautan tersebut.
- Kata sandi baru bisa dipakai untuk masuk, dan tautan pemulihan tidak bisa dipakai lagi setelah selesai.

### Tujuan
Menjadi dasar akses pengguna: hanya lembaga tempat ia terdaftar yang muncul di pemilih lembaga dan bisa diakses.

### Selesai bila
- Sistem menyimpan daftar lembaga keanggotaan tiap pengguna, bisa satu atau beberapa lembaga sekaligus.
- Pemilih lembaga hanya menampilkan lembaga yang berstatus aktif di keanggotaan pengguna.
- Pengguna tanpa keanggotaan aktif tidak bisa masuk ke area lembaga mana pun dan mendapat pesan yang jelas.
- Jika keanggotaan dicabut, lembaga itu hilang dari pemilih lembaga namun akun pengguna tetap ada.

## Sub-fitur: Pilih & Pindah Lembaga

Berganti dari pondok ke sekolah cukup lewat tombol di bagian atas layar.

## Sub-fitur: Peran Berbeda Tiap Lembaga

Seseorang bisa jadi admin di sekolah dan guru di lembaga lain sekaligus.

## Sub-fitur: Atur Ulang Kata Sandi

Pengguna bisa memulihkan akses sendiri saat lupa kata sandi.

## Sub-fitur: Keanggotaan Lembaga

Pengguna terdaftar di satu atau beberapa lembaga; hanya lembaga keanggotaannya yang muncul di pemilih lembaga.

## Task

### 1. Bangun halaman login terpusat dengan data tiruan

### 2. Buat komponen tombol pindah lembaga di header

### 3. Buat halaman lupa kata sandi dengan data tiruan

### 4. Buat halaman atur ulang kata sandi dengan data tiruan

### 5. Buat daftar keanggotaan dan peran per lembaga (mock)

### 6. Buat tampilan menu yang menyesuaikan peran tiap lembaga (mock)

### 7. Buat halaman pesan tanpa keanggotaan aktif (mock)

### 8. Buat migrasi dan model lembaga (tenant)

### 9. Buat migrasi dan model keanggotaan lembaga

### 10. Implementasi login terpusat dengan email dan kata sandi

### 11. Simpan dan pulihkan lembaga aktif terakhir pengguna

### 12. Buat middleware otorisasi keanggotaan dan scoping tenant

### 13. Integrasi peran berbeda per lembaga dengan Spatie Permission

### 14. Buat endpoint dan service pindah lembaga

### 15. Buat endpoint daftar keanggotaan aktif pengguna

### 16. Implementasi alur atur ulang kata sandi sekali pakai

### 17. Buat manajemen keanggotaan: pencabutan dan efek ke pemilih lembaga
