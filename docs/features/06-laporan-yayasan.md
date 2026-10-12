# Laporan Yayasan

Rekap data lintas lembaga tersusun otomatis, tanpa mencocokkan file Excel lagi.

## Spesifikasi

### Tujuan
Menyediakan rekap data seluruh lembaga yayasan yang tersusun otomatis dari sistem, supaya pengurus tidak lagi merekap dan mencocokkan file Excel secara manual.

### Selesai bila
- Pengurus membuka satu halaman **Laporan Yayasan** dan langsung melihat rekap seluruh lembaga tanpa membuka file terpisah.
- Santri yang terdaftar di dua lembaga tetap dihitung satu kali sebagai individu, namun ditandai lembaga mana saja yang diikutinya.
- Angka pada laporan selalu mengikuti data terbaru di sistem (tanpa tempel-menempel manual).
- Periode laporan yang sedang dipilih (lembaga & tahun ajaran) selalu tertera jelas di bagian atas halaman.
- Tersedia tombol untuk mengunduh laporan dalam bentuk file siap cetak/bagikan.

## Sub-fitur: Rekap Santri Lintas Lembaga

Lihat jumlah santri per lembaga termasuk yang terdaftar di dua lembaga.

### Tujuan
Menampilkan jumlah santri per lembaga, termasuk santri yang terdaftar di lebih dari satu lembaga, sebagai satu sumber angka yang bisa dipercaya bersama.

### Selesai bila
- Halaman menampilkan jumlah santri aktif untuk tiap lembaga (Pondok, SMP Plus, MA Nusantara, MI).
- Santri yang terdaftar di dua lembaga muncul di hitungan masing-masing lembaga, tetapi tetap dihitung sekali pada total individu.
- Pada daftar santri ada penanda yang jelas tentang lembaga mana saja yang diikuti santri tersebut.

## Sub-fitur: Laporan Akademik & Kepesantrenan

Ringkasan kehadiran, nilai, dan kegiatan pondok dalam satu tampilan.

### Tujuan
Menyajikan ringkasan kehadiran, nilai, dan kegiatan pondok dalam satu tampilan agar pengurus cepat menangkap kondisi keseluruhan.

### Selesai bila
- Ringkasan kehadiran, nilai, dan kegiatan pondok tampil dalam satu halaman tanpa berpindah modul.
- Ringkasan hanya menghitung data dari lembaga dan tahun ajaran yang sedang dipilih.
- Bila suatu lembaga belum punya data, tampil keterangan seperti “belum ada data”, bukan angka kosong yang membingungkan.

## Sub-fitur: Saring per Lembaga & Tahun

Pilih lembaga dan tahun ajaran agar laporan sesuai kebutuhan.

### Tujuan
Memungkinkan pengurus memilih lembaga dan tahun ajaran agar isi laporan sesuai kebutuhan yang sedang dicari.

### Selesai bila
- Tersedia pemilih lembaga (termasuk opsi “Semua Lembaga”) dan pemilih tahun ajaran di halaman laporan.
- Mengubah pilihan langsung memperbarui isi laporan tanpa langkah membingungkan.
- Pilihan yang sedang aktif terlihat jelas (lembaga dan tahun apa yang sedang ditampilkan).

## Sub-fitur: Unduh Laporan

Bagikan laporan ke pengurus dalam bentuk file siap cetak.

### Tujuan
Memungkinkan pengurus membagikan laporan ke pihak lain dalam bentuk file siap cetak tanpa menyusun ulang secara manual.

### Selesai bila
- Tersedia tombol “Unduh Laporan” di halaman laporan.
- File yang diunduh memuat lembaga dan tahun ajaran yang sedang dipilih.
- File hasil unduhan berjudul jelas, memuat periode, dan siap langsung dibuka/dicetak.

## Task

### 1. Bangun halaman Laporan Yayasan dengan data tiruan

### 2. Buat komponen rekap santri lintas lembaga

### 3. Buat kontrol saring lembaga dan tahun ajaran

### 4. Buat panel ringkasan akademik dan kepesantrenan

### 5. Buat tombol unduh laporan di halaman

### 6. Buat service rekap santri lintas lembaga

### 7. Buat endpoint API rekap santri lintas lembaga

### 8. Terapkan filter lembaga dan tahun ajaran di query laporan

### 9. Buat service agregasi akademik dan kepesantrenan

### 10. Buat endpoint API laporan akademik dan kepesantrenan

### 11. Buat generator dan endpoint unduh laporan
