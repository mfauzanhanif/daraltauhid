# Dashboard Lembaga

Halaman utama yang menampilkan ringkasan kondisi lembaga dan jalan pintas ke pekerjaan harian.

## Spesifikasi

### Tujuan
Halaman utama yang memberi gambaran cepat kondisi lembaga dan akses cepat ke pekerjaan harian pengguna sesuai perannya.
### Selesai bila
- Saat pengguna masuk ke sebuah lembaga, dashboard menampilkan nama lembaga aktif, tahun ajaran aktif, dan ringkasan angka penting.
- Angka dan daftar yang tampil hanya berasal dari lembaga aktif, tidak bercampur data lembaga lain.
- Tersedia pintasan tugas harian yang relevan dengan peran pengguna di lembaga tersebut.
- Ada daftar aktivitas terbaru dan sorotan santri yang datanya belum lengkap.
- Tampilan mengikuti gaya Neobrutalism dan bahasa Indonesia, serta termuat kurang dari 3 detik pada koneksi standar.

## Sub-fitur: Ringkasan Angka Penting

Jumlah santri aktif, kelas, dan kegiatan terlihat sekilas dalam satu layar.

### Tujuan
Menampilkan angka penting lembaga agar pengguna memahami kondisi terkini dalam sekali lihat.
### Selesai bila
- Tampil jumlah santri aktif di lembaga aktif, dipisah jelas dari total santri bila ada.
- Tampil jumlah kelas aktif dan jumlah kegiatan pada periode berjalan.
- Setiap angka punya label bahasa Indonesia dan satuan yang jelas; jika data kosong, tampil 0 atau pesan wajar.

## Sub-fitur: Pintasan Tugas Harian

Tombol cepat menuju pekerjaan yang paling sering dilakukan admin, guru, dan musyrif.

### Tujuan
Menyediakan tombol cepat menuju pekerjaan yang paling sering dilakukan agar pengguna tidak mencari menu manual.
### Selesai bila
- Pintasan yang tampil menyesuaikan peran pengguna di lembaga aktif, misalnya Admin, Guru, atau Musyrif.
- Setiap tombol mengarah ke halaman kerja terkait dan hanya muncul bila pengguna berhak mengaksesnya.
- Ada label jelas dan ikon konsisten; pengguna bisa mencapai tugas utama maksimal satu klik dari dashboard.

## Sub-fitur: Aktivitas Terbaru

Daftar kejadian terakhir di lembaga supaya tidak ada informasi yang terlewat.

### Tujuan
Menampilkan kejadian terbaru di lembaga agar informasi penting tidak terlewat.
### Selesai bila
- Daftar aktivitas menampilkan waktu, jenis kejadian, dan pelaku atau objek terkait dalam bahasa Indonesia.
- Isi daftar hanya berasal dari lembaga aktif dan diurutkan dari yang terbaru.
- Bila belum ada aktivitas, tampil pesan kosong yang informatif, bukan halaman error.

## Sub-fitur: Pemeriksaan Data Belum Lengkap

Menyoroti santri yang datanya masih kurang agar bisa segera dilengkapi.

### Tujuan
Menyoroti santri yang datanya belum lengkap agar admin dapat segera melengkapinya.
### Selesai bila
- Dashboard menampilkan daftar atau jumlah santri dengan data belum lengkap di lembaga aktif.
- Setiap item menunjukkan bagian data yang kurang dan menyediakan tautan langsung ke profil atau edit santri.
- Ada penanda visual jelas, misalnya warna atau badge, untuk membedakan santri yang perlu dilengkapi.

## Task

### 1. Bangun halaman dashboard lembaga dengan data mock

### 2. Buat komponen ringkasan angka penting

### 3. Buat komponen pintasan tugas harian per peran

### 4. Buat komponen daftar aktivitas terbaru

### 5. Buat komponen pemeriksaan data santri belum lengkap

### 6. Terapkan gaya Neobrutalism pada dashboard

### 7. Implementasi query multi-tenant untuk dashboard

### 8. Buat endpoint ringkasan angka penting lembaga

### 9. Buat endpoint pintasan tugas berdasarkan peran

### 10. Buat endpoint aktivitas terbaru lembaga

### 11. Buat endpoint santri data belum lengkap

### 12. Buat service agregasi data dashboard lembaga
