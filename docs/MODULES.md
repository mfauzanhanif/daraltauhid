# ARSITEKTUR MODUL SUPER APP DAR AL TAUHID

## 1. INSTITUTION MODULE (Fondasi)

### 1.1. Institution Registry (Pusat Data Lembaga)

Fitur ini mencatat struktur organisasi Yayasan Dar Al Tauhid yang cukup kompleks (Internal/Eksternal, Formal/Non-Formal).

- Hierarki Lembaga: Sistem harus bisa membedakan Yayasan Pusat (Parent) dengan unit di bawahnya seperti MI atau Pondok (Children).
- Multi-Tenancy (Domain Mapping): Sistem harus mengenali akses domain.
  - Jika user mengakses mis.daraltauhid.com -> Sistem memuat data MI.
  - Jika user mengakses psb.daraltauhid.com -> Sistem memuat data PSB Pusat.
- Kategorisasi: Memisahkan lembaga Internal (Milik Sendiri) dan Eksternal (Mitra/Binaan).

### 1.2. App Settings (Pengaturan Waktu)

Sesuai standar pendidikan dan keuangan:

- Academic Year Control: Mengatur Tahun Ajaran (Juli - Juni). Wajib ada validasi hanya satu tahun ajaran yang aktif dalam satu waktu.
- Semester Control: Mengatur Ganjil/Genap di dalam rentang tahun ajaran tersebut.
- Fiscal Year Control: Mengatur Tahun Buku Keuangan (Januari - Desember) untuk keperluan laporan pajak dan audit yayasan.

## 2. AUTH MODULE (Identity & Access Management)

Gerbang keamanan. Mengatur siapa yang boleh masuk dan apa yang boleh mereka lakukan.

### 2.1. Authentication (Autentikasi)

- Unified Login: Single Sign-On (SSO)
- Multi-Role User: Satu akun user bisa memiliki banyak peran (misal: Seorang user adalah "Guru" di MA sekaligus Guru di Pondok dan "Wali Santri" di MI).

### 2.2. Authorization (Otorisasi)

- RBAC (Role-Based Access Control):
  - Global Roles: Admin Yayasan (Akses lintas lembaga).
  - Scoped Roles: Operator Sekolah, Kepala Lembaga (Terbatas pada lembaga tertentu).
- Permission Matrix: Konfigurasi hak akses (CRUD) per modul.
- Wali Access Logic: Logika khusus untuk Wali Santri agar bisa melihat data seluruh anaknya di berbagai jenjang dalam satu dashboard.

## 3. Human Resources MODULE (Kepegawaian)

Manajemen sumber daya manusia yang menggerakkan operasional.

### 3.1. Employee Management

- Staff Database: Biodata Pendidik dan Tenaga Kependidikan.
- Assignments: Penugasan guru di lembaga tertentu (bisa lintas lembaga).
- Attendance: Rekap kehadiran untuk dasar gaji.
- Payroll:
  - Komponen Gaji: Gaji Pokok, Tunjangan Jabatan, Tunjangan Mengajar (per jam).
  - Slip Gaji: Generate slip gaji bulanan.
  - Integration: Setelah gaji Approved, modul ini kirim data ke Finance Module untuk mencatat pengeluaran kas.

## 4. FACILITY & ASSET MODULE (Sarana Prasarana)

Modul baru untuk manajemen aset fisik.

### 4.1. Inventory Management (Inventaris)

- Asset Registry: CRUD Barang (Meja, Kursi, Proyektor, Kendaraan, AC).
- Daftar Aset (Nama, Kode Barang/QR, Tanggal Beli, Lokasi Ruangan, PIC).
  - Status Aset: Enum (Good, Broken, Lost, Disposed). Cukup update status manual jika ada kerusakan.
- Fitur: QR Code Generator untuk label aset. Scan QR untuk melihat detail barang.
- Stock Opname: Fitur untuk cek fisik berkala (audit aset) apakah barang masih ada/hilang.

### 4.2. Room Management

- Data Master Ruangan (Kelas, Lab, Kamar Asrama).
  - Note: Modul ini hanya mendata fisik ruangan (Kapasitas, Lokasi, Fasilitas). Penggunaan jadwalnya diatur oleh Modul Akademik.

### 4.3. Procurement (Pengadaan)

- Purchase Request: Unit mengajukan permintaan pengadaan barang baru di anggaran.
- Budget Check: Terintegrasi dengan Modul Finance (Pos Anggaran) untuk memastikan ada budget.

## 5. ADMISSION MODULE (Penerimaan Peserta Didik Baru)

Pintu gerbang data siswa. Modul ini sering memiliki siklus hidup yang berbeda (musiman).

### 5.1. Registration Portals

- Multi-Channel: Menangani pendaftaran via psb.daraltauhid.com (Pondok) dan halaman dari subdomain sekolah (/ppdb).
- Data Collection: Formulir biodata, upload berkas (KK, Akta), dan validasi dokumen.
- Registration Flow: Formulir & Upload Berkas.
- Admission Settings (Informasi PSB):
  - Pengaturan Gelombang Pendaftaran (Tanggal Buka/Tutup).
  - Informasi Alur & Prosedur (Tampil di Frontend).

### 5.2. Enrollment

- Billing PSB: Generate tagihan formulir & uang pangkal otomatis.
- Selection Process (Manual):
  - Input Jadwal Wawancara/Tes Fisik.
  - Input Hasil Seleksi (Lulus/Tidak) secara manual oleh admin.
- Migration (Lanjut Jenjang): Fitur untuk memindahkan data siswa internal (misal: dari MI ke SMP) tanpa input ulang.

## 6. ACADEMIC & STUDENT MODULE (Operasional Utama)

Modul inti operasional pendidikan.

### 6.1. Student Affairs (Kesiswaan)

- Student Master Data: Profil lengkap santri, NIS, NISN.
- Class Management: Manajemen Rombel (Rombongan Belajar) dan Wali Kelas.
- Attandence: Kehadiran Siswa.
- Mutasi Siswa: Kenaikan kelas, tinggal kelas, atau pindah sekolah.

### 6.2. Boarding Management (Kesantrian)

- Dormitory: Manajemen kamar asrama dan penempatan santri.
- Permission: Perizinan (pulang/sakit)
- Discipline: Pencatatan poin pelanggaran/prestasi.

### 6.3. Curriculum (Kurikulum)

- Kurikulum
  - Struktur Kurikulum
  - Manajemen Mata Pelajaran
  - Modul Ajar
  - Jadwal KBM.
  - Academic Calendar: Input jadwal kegiatan akademik (UTS, UAS, Pembagian Rapor).
- Kokurikuler
- Ekstrakurikuler
- Grading: Input nilai harian, UTS, UAS, dan cetak Rapor.

## 7. FINANCE MODULE (Keuangan & Akuntansi)

Modul dengan logika paling ketat dan kompleks. Wajib terisolasi untuk keamanan data.

### 7.1. Billing (Tagihan)

- Fee Components: Manajemen komponen biaya (SPP, Uang Gedung, Katering) dengan frekuensi (Bulanan/Tahunan/Sekali).
- Invoice Generator: Job otomatis untuk menerbitkan tagihan massal.

### 7.2. Wallet System (Dompet Digital)

- Tuition Wallet: Dompet deposit pendidikan (Restricted: hanya untuk bayar tagihan).
- Pocket Money: Dompet uang saku santri (Transactional: limit harian, pin protection).

### 7.3. Treasury (Kasir & Pembayaran)

- Payment Processor: Input pembayaran manual (Tunai/Transfer) dan verifikasi bukti transfer.
- Payment Allocation: Algoritma prioritas pelunasan otomatis (Prioritas: Madrasah -> Sekolah/Pondok).
- Roadmap: Integrasi Payment Gateway (VA) di masa depan.

### 7.4. Accounting (Akuntansi)

- Automated Journaling: Sistem jurnal otomatis (Event-driven) saat transaksi terjadi (Tagihan terbit -> Piutang bertambah).
- Budget Control: Kontrol anggaran (RAB) dengan fitur Soft Limit Alert.
- Reporting: Laporan Neraca, Arus Kas, dan Realisasi Anggaran.
- Integrated Payroll: Menerima tagihan gaji dari Modul Human Resources untuk dicairkan.

## 8. PUBLISHING MODULE (Content Management System)

Wajah depan aplikasi untuk publik.

- News Manager:
  - CRUD Berita/Artikel/Pengumuman.
  - Tagging: Global (Tampil di semua), Institution Specific (Hanya lembaga tertentu).
- News Aggregator (sarung.daraltauhid.com):
  - Menampilkan feed berita gabungan dari semua lembaga.
- Institution Pages: Menyediakan konten dinamis untuk subdomain (misal: mis.daraltauhid.com mengambil data Identitas dari Institution + Berita dari News Manager difilter where institution_id = MI).
- Internal Announcements (Informasi Insidentil):
  - Fitur pengumuman yang bisa di-set targetnya: Public (Web) atau Internal (Dashboard Guru/Wali). Contoh: "Himbauan Sholat Gerhana", "Rapat Koordinasi Yayasan".
