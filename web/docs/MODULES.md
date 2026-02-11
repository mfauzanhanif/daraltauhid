# ARSITEKTUR MODUL SUPER APP DAR AL TAUHID

## 1. INSTITUTION MODULE (Fondasi)

### 1.1. Institution Registry (Pusat Data Lembaga)

Fitur ini mencatat struktur organisasi Yayasan Dar Al Tauhid yang cukup kompleks (Internal/Eksternal, Pondok/Formal/Non-Formal/Sosial).

- **Hierarki Lembaga:** Sistem membedakan Yayasan Pusat (*Parent*) dengan lembaga di bawahnya seperti MI, Pondok, atau Masjid (*Children*).
- **domain mapping:** 
  1. [daraltauhid.com](https://daraltauhid.com) (Landing Page Pondok Pesantren Dar Al Tauhid)
  2. [psb.daraltauhid.com](https://psb.daraltauhid.com) (Portal Penerimaan Santri Baru)
  3. [app.daraltauhid.com](https://app.daraltauhid.com) (Super App)
  4. [sarung.daraltauhid.com](https://news.daraltauhid.com) (Halaman berita)
  5. [yayasan.daraltauhid.com](https://yayasan.daraltauhid.com) (Landing Page Yayasan Dar Al Tauhid Pusat)
  6. [tk-islam.wathaniyah.sch.id](https://tk-islam.wathaniyah.sch.id) (Landing Page TK Islam Wathaniyah)
  7. [mis.daraltauhid.com](https://mis.daraltauhid.com) (Landing Page MI Dar Al Tauhid)
  8. [smp-plus.daraltauhid.com](https://smp-plus.daraltauhid.com) (Landing Page SMP Plus Dar Al Tauhid)
  9. [manuscirebon.com](https://manuscirebon.com) (Landing Page MA Nusantara)
  10. [slb-abc.wathaniyah.sch.id](https://slb-abc.wathaniyah.sch.id) (Landing Page SLB ABC Wathaniyah)
  11. [madrasah.daraltauhid.com](https://mdarasah.daraltauhid.com) (Landing Page Madrasah Dar Al Tauhid)
  12. [tpq.daraltauhid.com](https://tpq.daraltauhid.com) (Landing Page TPQ Dar Al Tauhid)
  13. [mdta.daraltauhid.com](https://mdta.daraltauhid.com) (Landing Page MDTA Dar Al Tauhid)
  14. [lksa.daraltauhid.com](https://lksa.daraltauhid.com) (Landing Page LKSA Dar Al Tauhid)
  15. [alumni.daraltauhid.com](https://alumni.daraltauhid.com) (Landing Page Alumni Dar Al Tauhid)

  > Pastikan di routes/web.php Anda menggunakan Domain Routing yang ketat untuk Landing Page, sebelum masuk ke logic app.daraltauhid.com

  ```
  // Group App Utama
  Route::domain('app.daraltauhid.com')->group(...);

  // Group Portal Pendaftaran Santri Baru
  Route::domain('psb.daraltauhid.com')->group(...);

  // Group Portal Berita
  Route::domain('sarung.daraltauhid.com')->group(...);
  
  // Group Landing Pages
  Route::domain('daraltauhid.com')->group(...);
  Route::domain('manuscirebon.com')->group(...);
  Route::domain('mis.daraltauhid.com')->group(...);
  Route::domain('smp-plus.daraltauhid.com')->group(...);
  Route::domain('tk-islam.wathaniyah.sch.id')->group(...);
  Route::domain('slb-abc.wathaniyah.sch.id')->group(...);
  Route::domain('madrasah.daraltauhid.com')->group(...);
  Route::domain('tpq.daraltauhid.com')->group(...);
  Route::domain('mdta.daraltauhid.com')->group(...);
  Route::domain('lksa.daraltauhid.com')->group(...);
  Route::domain('alumni.daraltauhid.com')->group(...);
  Route::domain('yayasan.daraltauhid.com')->group(...);
  ```

- **Path-Based Multi-Tenancy:** 
  - Sistem menggunakan **Satu Domain Utama** (`app.daraltauhid.com`) untuk seluruh operasional dashboard.
  - Pembedaan tenant menggunakan **Slug URL**. Slug diambil dari kolom `code` pada tabel `institutions`. Contoh:
    - Dashboard Yayasan (global scope): `app.daraltauhid.com/ydtp/dashboard` 
    - Dashboard MI: `app.daraltauhid.com/misdt/dashboard`
    - Dashboard Pondok: `app.daraltauhid.com/ppdt/dashboard`
  - Path Wali Santri/Siswa: `app.daraltauhid.com/wali/{student_id}/dashboard`
- **Kategorisasi:** Memisahkan lembaga Internal (Milik Sendiri) dan Eksternal (Mitra/Binaan). Lembaga Eksternal tidak memiliki akses ke aplikasi.

### 1.2. App Settings (Pengaturan Waktu)

Sesuai standar pendidikan dan keuangan:

- **Academic Year Control:** Mengatur Tahun Ajaran (Juli - Juni). Wajib ada validasi hanya satu tahun ajaran yang aktif dalam satu waktu (dikendalikan Yayasan).
- **Semester Control:** Mengatur Ganjil/Genap di dalam rentang tahun ajaran tersebut.
- **Fiscal Year Control:** Mengatur Tahun Buku Keuangan (Januari - Desember) untuk keperluan laporan pajak dan audit yayasan.

---

## 2. AUTH MODULE (Identity & Access Management)

### 2.1. Authentication (Autentikasi)

- **Unified Login (SSO):** User hanya perlu login satu kali di `app.daraltauhid.com` untuk mengakses seluruh layanan. (tidak perlu login lagi, ketika switch lembaga).
- **Multi-Role-Institution User:** Satu akun user dapat memiliki banyak peran di berbagai lembaga sekaligus.
  > *Contoh: Seorang user tercatat sebagai "Kepala" di MA, sekaligus "Guru" di Pondok, dan "Wali Santri" di MI.*
- **Institution Selection:** jika user adalah internal employee, maka setelah login akan diarahkan ke halaman pilih lembaga untuk memilih masuk ke lembaga mana (misal: Pondok, MI, MA).
- **Student Selection:** jika user adalah wali santri, maka setelah login akan diarahkan ke halaman pilih siswa untuk memilih masuk ke siswa mana (misal: Pondok, MI, MA).

### 2.2. Authorization (Otorisasi)

- **RBAC (Role-Based Access Control):**
  - **Global Roles:** Peran tingkat tinggi yang tidak terikat lembaga (contoh: *Operator Yayasan*).
  - **Scoped Roles:** Peran yang terbatas pada lembaga tertentu (contoh: *Operator Sekolah*, *Kepala Lembaga*).

- **Session Injection Logic:**
  Ketika login dan memilih lembaga (atau mengakses URL lembaga), sistem mengecek kepemilikan *scoped role*. Jika valid, sistem menyuntikkan konteks lembaga tersebut ke dalam session user.

- **Permission Matrix:** Konfigurasi granular hak akses (CRUD) per modul dan per role. 

- **Institution Switcher:** Fitur bagi user (selain wali santri) untuk berpindah konteks antar lembaga yang menjadi hak aksesnya tanpa perlu login ulang.
  > Middleware `CheckInstitutionAccess` wajib berjalan setiap kali request switch lembaga atau mengakses URL lembaga secara manual, bukan hanya saat login awal. Pastikan logicnya: "User ini mengakses URL /ppdt/. Apakah User punya role di Institution ID milik ppdt? Jika TIDAK -> Abort 403."
- **Student Switcher:** Fitur bagi wali santri untuk berpindah konteks antar siswa yang menjadi hak aksesnya tanpa perlu login ulang.
  > Gunakan NanoID (public_id) 10 digit pada tabel siswa dan pastikan Middleware `CheckStudentAccess` sangat ketat (memastikan public_id siswa tersebut benar-benar anak dari user yang sedang login).

- **Ketentuan Khusus (The Two Hats Rule):**
  > Admin Yayasan (Global Role) tetap dapat mengakses *Scoped Role* jika akun tersebut memang memiliki penugasan spesifik di lembaga. 
  > Contoh: Akun user Fauzan adalah **Operator Yayasan**. Namun, user Fauzan juga memiliki role **Guru** di MI. Maka, user Fauzan bisa melakukan *switch* ke Portal MI dan bertindak sebagai **Guru MI** (bukan sebagai Operator Yayasan).

---

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

## 4. ASSET MODULE (Sarana Prasarana)

Modul ini berfungsi untuk manajemen aset fisik secara menyeluruh (Lifecycle), mulai dari pengadaan, operasional harian, perawatan, hingga penghapusan aset.

### 4.1. Inventory Management (Inventaris)

Menangani pencatatan master data aset.

- **Asset Registry (CRUD)**
  - Pencatatan barang fisik (Meja, Kursi, Proyektor, Kendaraan, AC).
  - Data Points: Nama, Kode (Auto-generated), Kategori, Tanggal Beli, Harga Perolehan, Sumber Dana.
  - Tagging: Support QR Code (UUID) untuk identifikasi unik.

- **Stock Opname (Audit)**
  - Fitur audit berkala (Bulanan/Tahunan).
  - Proses: Scan fisik barang di ruangan -> Bandingkan dengan data sistem -> Flagging barang hilang/tidak sesuai lokasi.

## 4.2. Room Management (Manajemen Ruangan)

Master Data lokasi fisik.

- **Hierarki Lokasi**
  - Mendata Gedung -> Lantai -> Ruangan.
- **Atribut Ruangan**
  - Kapasitas, Fasilitas Default, dan PIC Ruangan.

## 4.3. Asset Operations (Operasional Aset)

Menangani dinamika pergerakan aset sehari-hari.

- **Asset Mutation (Mutasi Lokasi)**
  - Mencatat perpindahan aset dari satu ruangan ke ruangan lain.
  - History Log: Melacak riwayat perjalanan aset (siapa yang memindahkan, kapan, dan alasan pindah).
  - Contoh: Memindahkan Kursi dari Aula ke Kelas A.

- **Asset Lending (Peminjaman)**
  - Fitur untuk santri/guru meminjam aset jangka pendek.
  - Flow: Request Pinjam -> Approval Sarpras -> Serah Terima -> Pengembalian.
  - Contoh: Peminjaman Proyektor untuk kegiatan OSIS/Ekskul.

## 4.4. Maintenance & Repair (Perawatan & Perbaikan)

Menangani kesehatan aset dan biaya perbaikan.

- **Ticketing System (Lapor Kerusakan)**
  - User (Guru/Santri) scan QR -> Klik "Lapor Rusak" -> Tiket masuk ke Admin Sarpras.
  - Status Flow: Reported -> In Review -> In Repair -> Resolved.

- **Repair Execution**
  - Pencatatan tindakan perbaikan (Teknisi Internal vs Vendor Luar).
  - Cost Tracking: Input biaya perbaikan (Sparepart + Jasa).
  - Integrasi Finance: Biaya perbaikan otomatis tercatat sebagai pengeluaran di pos anggaran pemeliharaan.

## 4.5. Procurement (Pengadaan)

Menangani pembelian aset baru.

- **Purchase Request (PR)**
  - Unit mengajukan kebutuhan barang.
- **Budget Check & Approval**
  - Integrasi otomatis dengan Modul Finance untuk validasi sisa pagu anggaran.
  - Workflow persetujuan berjenjang (Kepala Bagian -> Bendahara -> Ketua Yayasan).

---

## 4.6. Asset Disposal (Penghapusan Aset)

Menangani aset yang sudah habis masa pakainya atau rusak total.

- **Disposal Request**
  - Mengubah status aset menjadi "Disposed" (Dihapuskan) agar tidak muncul di stok aktif, namun data *history*-nya tetap tersimpan.
  - Alasan: Rusak Total, Hilang, Dijual/Lelang, Hibah.
- **Asset Value Adjustment**
  - Pencatatan nilai akhir aset (Nilai Residu) jika aset dijual (Pemasukan Kas).

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

## 4. ASSET MODULE (Sarana Prasarana)

Modul ini berfungsi untuk manajemen aset fisik secara menyeluruh (Lifecycle), mulai dari pengadaan, operasional harian, perawatan, hingga penghapusan aset.

### 4.1. Inventory Management (Inventaris)

Menangani pencatatan master data aset.

- **Asset Registry (CRUD)**
  - Pencatatan barang fisik (Meja, Kursi, Proyektor, Kendaraan, AC).
  - Data Points: Nama, Kode (Auto-generated), Kategori, Tanggal Beli, Harga Perolehan, Sumber Dana.
  - Tagging: Support QR Code (UUID) untuk identifikasi unik.

- **Stock Opname (Audit)**
  - Fitur audit berkala (Bulanan/Tahunan).
  - Proses: Scan fisik barang di ruangan -> Bandingkan dengan data sistem -> Flagging barang hilang/tidak sesuai lokasi.

## 4.2. Room Management (Manajemen Ruangan)

Master Data lokasi fisik.

- **Hierarki Lokasi**
  - Mendata Gedung -> Lantai -> Ruangan.
- **Atribut Ruangan**
  - Kapasitas, Fasilitas Default, dan PIC Ruangan.

## 4.3. Asset Operations (Operasional Aset)

Menangani dinamika pergerakan aset sehari-hari.

- **Asset Mutation (Mutasi Lokasi)**
  - Mencatat perpindahan aset dari satu ruangan ke ruangan lain.
  - History Log: Melacak riwayat perjalanan aset (siapa yang memindahkan, kapan, dan alasan pindah).
  - Contoh: Memindahkan Kursi dari Aula ke Kelas A.

- **Asset Lending (Peminjaman)**
  - Fitur untuk santri/guru meminjam aset jangka pendek.
  - Flow: Request Pinjam -> Approval Sarpras -> Serah Terima -> Pengembalian.
  - Contoh: Peminjaman Proyektor untuk kegiatan OSIS/Ekskul.

## 4.4. Maintenance & Repair (Perawatan & Perbaikan)

Menangani kesehatan aset dan biaya perbaikan.

- **Ticketing System (Lapor Kerusakan)**
  - User (Guru/Santri) scan QR -> Klik "Lapor Rusak" -> Tiket masuk ke Admin Sarpras.
  - Status Flow: Reported -> In Review -> In Repair -> Resolved.

- **Repair Execution**
  - Pencatatan tindakan perbaikan (Teknisi Internal vs Vendor Luar).
  - Cost Tracking: Input biaya perbaikan (Sparepart + Jasa).
  - Integrasi Finance: Biaya perbaikan otomatis tercatat sebagai pengeluaran di pos anggaran pemeliharaan.

## 4.5. Procurement (Pengadaan)

Menangani pembelian aset baru.

- **Purchase Request (PR)**
  - Unit mengajukan kebutuhan barang.
- **Budget Check & Approval**
  - Integrasi otomatis dengan Modul Finance untuk validasi sisa pagu anggaran.
  - Workflow persetujuan berjenjang (Kepala Bagian -> Bendahara -> Ketua Yayasan).

---

## 4.6. Asset Disposal (Penghapusan Aset)

Menangani aset yang sudah habis masa pakainya atau rusak total.

- **Disposal Request**
  - Mengubah status aset menjadi "Disposed" (Dihapuskan) agar tidak muncul di stok aktif, namun data *history*-nya tetap tersimpan.
  - Alasan: Rusak Total, Hilang, Dijual/Lelang, Hibah.
- **Asset Value Adjustment**
  - Pencatatan nilai akhir aset (Nilai Residu) jika aset dijual (Pemasukan Kas).

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
