# Keuangan & Tagihan

Catat tagihan SPP dan syahriyah santri serta pantau pembayarannya.

## Spesifikasi

### Tujuan
Mencatat tagihan SPP dan syahriyah santri serta memantau pembayarannya agar keuangan tiap lembaga rapi, akurat, dan mudah dicek.

### Selesai bila
- Pengurus dapat menentukan jenis tagihan beserta nominalnya per lembaga, dan jenis itu tersedia saat membuat tagihan santri.
- Setiap santri memiliki daftar tagihan yang menampilkan jenis, nominal, jatuh tempo, serta status belum bayar/sebagian/lunas.
- Pembayaran yang dicatat langsung memperbarui status tagihan terkait dan tersimpan dalam riwayat lengkap (tanggal, jumlah, metode, penerima).
- Tersedia rekap tunggakan yang menampilkan santri belum lunas, dapat disaring per lembaga dan tahun ajaran, serta dapat diunduh.
- Semua nilai uang ditampilkan dalam format Rupiah dan hanya menampilkan data lembaga yang sedang aktif.

## Sub-fitur: Jenis Tagihan & Nominal

Tentukan jenis tagihan dan besarannya untuk tiap lembaga.

### Tujuan
Menyediakan daftar jenis tagihan (mis. SPP, syahriyah) beserta nominalnya untuk tiap lembaga agar pembuatan tagihan konsisten.

### Selesai bila
- Pengurus dapat menambah, mengubah, dan menonaktifkan jenis tagihan beserta nominal dan periodenya.
- Jenis tagihan yang dibuat di satu lembaga tidak muncul atau tidak dapat dipakai di lembaga lain.
- Daftar jenis tagihan menampilkan nama, nominal dalam format Rupiah, dan status aktif/tidak aktif.

## Sub-fitur: Tagihan per Santri

Setiap santri punya daftar tagihan yang jelas dan mudah dicek.

### Tujuan
Memberi setiap santri daftar tagihan yang jelas agar pengurus dan wali mudah mengecek kewajiban pembayarannya.

### Selesai bila
- Pengurus dapat membuat tagihan untuk seorang santri dari jenis tagihan yang tersedia, lengkap dengan nominal, jatuh tempo, dan tahun ajaran.
- Halaman tagihan santri menampilkan seluruh tagihannya beserta status belum bayar/sebagian/lunas dan sisa yang harus dibayar.
- Daftar tagihan dapat disaring per lembaga, tahun ajaran, dan status, serta dicari berdasarkan nama santri.

## Sub-fitur: Pencatatan Pembayaran

Catat pembayaran masuk agar riwayat keuangan tidak tercecer.

### Tujuan
Mencatat setiap pembayaran masuk supaya riwayat keuangan santri tidak tercecer dan status tagihan selalu akurat.

### Selesai bila
- Pengurus dapat mencatat pembayaran pada sebuah tagihan (jumlah, tanggal, metode, nomor referensi) dan sistem memperbarui status tagihan secara otomatis.
- Setiap tagihan menampilkan riwayat pembayarannya, termasuk total yang sudah dibayar dan sisa tagihan.
- Pembayaran yang melebihi nilai tagihan ditolak dengan pesan yang jelas.

## Sub-fitur: Rekap Tunggakan

Lihat siapa saja yang belum membayar untuk ditindaklanjuti.

### Tujuan
Menampilkan siapa saja yang belum melunasi tagihan agar bisa segera ditindaklanjuti.

### Selesai bila
- Halaman rekap menampilkan daftar santri dengan tagihan belum lunas beserta sisa tunggakan dan jatuh temponya.
- Rekap dapat disaring per lembaga dan tahun ajaran, serta diurutkan berdasarkan jumlah tunggakan atau lama keterlambatan.
- Rekap tunggakan dapat diunduh sebagai berkas siap cetak dengan angka dalam format Rupiah.

## Task

### 1. Bangun halaman utama Keuangan & Tagihan dengan data tiruan

### 2. Buat halaman daftar jenis tagihan dengan mock

### 3. Buat halaman daftar tagihan per santri dengan mock

### 4. Buat form tambah tagihan santri dengan mock

### 5. Buat halaman detail tagihan dan riwayat pembayaran mock

### 6. Buat form pencatatan pembayaran dengan data tiruan

### 7. Buat halaman rekap tunggakan dengan filter dan tombol unduh mock

### 8. Buat migrasi dan model jenis tagihan per lembaga

### 9. Buat endpoint CRUD jenis tagihan terisolasi per lembaga

### 10. Buat migrasi dan model tagihan santri beserta relasi

### 11. Buat endpoint buat dan daftar tagihan dengan filter pencarian

### 12. Buat migrasi dan model pembayaran tagihan

### 13. Buat service pencatatan pembayaran dengan kalkulasi status dan validasi kelebihan bayar

### 14. Buat endpoint catat pembayaran dan riwayat per tagihan

### 15. Buat endpoint rekap tunggakan dengan filter lembaga tahun ajaran dan pengurutan

### 16. Buat unduhan rekap tunggakan siap cetak format Rupiah
