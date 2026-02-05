# 🛤️ SUPER APP ROADMAP: The Modular Monolith Way

## 🏁 PHASE 1: FONDASI & KEAMANAN (Current Status: 90%)

Tujuan: Menyiapkan infrastruktur agar aplikasi bisa "hidup".

- ✅ Modul Institution: Tabel Lembaga (institutions), Tahun Ajaran, Semester.
- ✅ Modul Auth: Tabel User, Role (Spatie), Permission.
- 🔲 Database Seeding: Mengisi data awal Yayasan, Super Admin, dan Role dasar (Ini penting agar bisa login).
- 🔲 Frontend Setup: Install React/Inertia, Setup Layout Dashboard Utama (Sidebar, Navbar).

👥 PHASE 2: HUMAN RESOURCES (Kepegawaian)
Tujuan: Mendata siapa yang bekerja di yayasan (Prasyarat untuk Modul Akademik & Keuangan).

Backen:

- ✅ Migrasi employees & employee_assignments.
- CRUD Data Pegawai (Input Guru, Staff, Kepala Sekolah).
- Fitur Penugasan (Assign Guru A ke SD, Guru B ke Pondok).

Frontend:

- Page: Daftar Pegawai (Filter per Lembaga).
- Page: Form Input Pegawai & Upload Dokumen.
- Page: Profil Pegawai.

🎓 PHASE 3: ACADEMIC - DATA MASTER (Kesiswaan)
Tujuan: Mendata siapa yang diajar (Santri) dan pengelompokannya.

Backend:

- Migrasi students, student_parents, student_institutions (Sesuai 2_PEOPLE.md).
- Migrasi classrooms (Rombel) & classroom_students.
- Relasi: Menunjuk Guru (dari HR) menjadi Wali Kelas.

Frontend:

- Page: Database Santri (Pencarian NIK/NISN).
- Page: Manajemen Kelas (Mapping Santri ke Kelas).
- Page: Kenaikan Kelas (Bulk Action).

🏫 PHASE 4: ACADEMIC - OPERASIONAL (KBM)
Tujuan: Kegiatan belajar mengajar sehari-hari.

Backend:

- Migrasi subjects (Mata Pelajaran) & schedules (Jadwal).
- Migrasi attendances (Absensi Siswa & Guru).
- Migrasi grades (Penilaian/Rapor).

Frontend:

- Page: Input Jadwal Pelajaran.
- Page: Absensi Harian (Mobile Friendly).
- Page: Input Nilai & Cetak Rapor (PDF).

💰 PHASE 5: FINANCE (Keuangan)
Tujuan: Mengelola arus kas masuk (SPP) dan keluar (Gaji/Belanja).

Backend:

- Migrasi billings (Tagihan SPP) & transactions.
- Migrasi budgets (Anggaran).
- Integrasi: Menarik data Santri (Academic) untuk dibuatkan tagihan.
- Integrasi: Menarik data Pegawai (HR) untuk penggajian.

Frontend:

- Page: Kasir (Input pembayaran manual).
- Page: Laporan Keuangan (Arus Kas/Neraca).
- Page: Wali Santri View (Cek tagihan anak).

🚀 PHASE 6: ADMISSION (PSB) & PORTAL
Tujuan: Wajah publik dan pendaftaran siswa baru.

Backend:

- Modul PSB terpisah (Guest Access).

Frontend:

- Landing Page (psb.daraltauhid.com), Form Pendaftaran Online.
