# Spesifikasi Modul & Struktur Aplikasi — Dar Al Tauhid EMS

**Dokumen Terkait:** [PRD.md](file:///c:/laragon/www/daraltauhid/docs/PRD.md) · [TECHSTACK.md](file:///c:/laragon/www/daraltauhid/docs/TECHSTACK.md) · [DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md) · [DESIGN.md](file:///c:/laragon/www/daraltauhid/docs/DESIGN.md) · [ROADMAP.md](file:///c:/laragon/www/daraltauhid/docs/ROADMAP.md)

---

## 1. Arsitektur: Laravel Native Core + Modular Domain

Sistem Dar Al Tauhid EMS menerapkan kombinasi arsitektur **Laravel Native Core (`app/`)** dan **Modular Domain (`Modules/`)** berbasis `nwidart/laravel-modules`.

### Prinsip Pembagian Tanggung Jawab:
1. **Core Diimplementasikan Langsung di `app/` Bawaan Laravel:**
   - Tidak ada modul terpisah `Modules/Core`.
   - Seluruh fondasi organisasi yayasan, multi-tenancy, autentikasi pengguna, manajemen keanggotaan lembaga, master biodata santri terpadu (SSOT), dashboard lembaga, dan laporan yayasan diimplementasikan langsung pada struktur standar Laravel (`app/Models`, `app/Http/Controllers`, `app/Services`, `app/Scopes`).
2. **Sub-Sistem Domain Ditempatkan di `Modules/`:**
   - Direktori `Modules/` hanya memuat modul operasional spesifik: `Academic` (sekolah formal), `Dormitory` (pondok pesantren), `Admission` (portal PSB), `Finance` (keuangan santri), dan `CMS` (konten publik).
3. **100% Antarmuka Pengguna Menggunakan Inertia.js + React:**
   - Seluruh halaman (dashboard internal, pendaftaran PSB, hingga landing page publik) dibangun menggunakan React melalui Inertia.js (tidak ada halaman berbasis Blade views).

---

## 2. Struktur Direktori Aplikasi

```text
daraltauhid/
├── app/                        # CORE IMPLEMENTATION (Bawaan Laravel)
│   ├── Actions/                # Fortify Actions & Core Domain Actions (PromoteStudent, dll)
│   ├── Http/
│   │   ├── Controllers/        # Core Controllers (Dashboard, Workspace, StudentMaster, Report)
│   │   └── Middleware/         # EnsureActiveInstitutionMembership, HandleInertiaRequests
│   ├── Models/                 # Core Models (Institution, User, Student, AcademicYear, dll)
│   ├── Scopes/                 # TenantScope (Global Scope filtering institution_id)
│   └── Services/               # Core Services (StudentSyncService, CompletenessCalculator)
│
├── Modules/                    # DOMAIN MODULES (Sub-Sistem Spesifik)
│   ├── Academic/               # Kelas, Mapel, Jadwal, Presensi, Nilai & Rapor (Formal)
│   ├── Dormitory/              # Asrama/Kamar, Musyrif, Tahfidz, Pelanggaran, Perizinan (Pondok)
│   ├── Admission/              # Portal PSB (psb.*), Gelombang, Verifikasi, Pengumuman
│   ├── Finance/                # Master Tagihan, Invoicing, Pembayaran, Rekap Tunggakan
│   └── CMS/                    # Konten Halaman Publik, Artikel, Pengumuman (Inertia + React)
│
├── resources/
│   ├── js/
│   │   ├── components/         # Komponen UI Neobrutalism (Buttons, Cards, Inputs, dll)
│   │   ├── layouts/            # Layouts React (AppLayout, PublicLayout, PsbLayout)
│   │   └── pages/              # Halaman Inertia React (Aplikasi, PSB, & Landing Page)
│   └── views/
│       └── app.blade.php       # Satu-satunya file Blade (Root Shell HTML untuk Inertia)
└── docs/                       # Dokumentasi Lengkap Proyek
```

---

## 3. Rincian Core Sistem (`app/`)

Seluruh kapabilitas inti yayasan bertempat di `app/`:

- **Domain/Akses:** `app.daraltauhid.com`
- **Pengguna Terkait:** Super Admin Yayasan, Admin Lembaga, Seluruh Pengguna Terotentikasi.
- **Tanggung Jawab Utama:**
  1. **Master Lembaga / Tenant (`app/Models/Institution.php`):** Pendataan unit pendidikan (Pondok, SMP Plus, MA Nusantara, MI).
  2. **Pengguna & Keanggotaan Lembaga (`app/Models/User.php`, `app/Models/InstitutionUser.php`):** Pengelolaan akun terpusat, pencatatan keanggotaan pengguna pada satu atau beberapa lembaga, dan penyimpanan sesi `current_institution_id`.
  3. **Workspace Switcher & Middleware Tenant (`app/Http/Middleware/EnsureActiveInstitutionMembership.php`):** Logika perpindahan workspace lembaga tanpa relogin, disertai validasi keamanan ketat di level request.
  4. **Master Data Santri Terpadu / Single Source of Truth (`app/Models/Student.php`):**
     - Penyimpanan biodata unik (anti-duplikasi NIK dan NISN).
     - Penautan santri ke lembaga melalui tabel pivot `student_institution` (`app/Models/StudentInstitution.php`).
     - Dukungan penanda santri bersekolah di luar yayasan (`external_school`, mis. MTsN 3 Cirebon).
     - Kalkulasi persentase kelengkapan isian berkas santri (`data_completeness`).
  5. **Dashboard Lembaga Terintegrasi:** Ringkasan statistik, pintasan kerja harian, dan widget audit kelengkapan santri.
  6. **Laporan Eksekutif Yayasan:** Agregasi data santri lintas lembaga (menghitung santri ganda secara akurat sebagai 1 individu), rekap akademik, dan rekap kepesantrenan.
  7. **Global Tenant Scope (`app/Scopes/TenantScope.php`):** Trait otomatis untuk memfilter query Eloquent berdasarkan `institution_id` yang sedang aktif.
- **Referensi Fitur:**
  - [docs/features/01-dashboard-lembaga.md](file:///c:/laragon/www/daraltauhid/docs/features/01-dashboard-lembaga.md)
  - [docs/features/02-data-santri-terpadu.md](file:///c:/laragon/www/daraltauhid/docs/features/02-data-santri-terpadu.md)
  - [docs/features/06-laporan-yayasan.md](file:///c:/laragon/www/daraltauhid/docs/features/06-laporan-yayasan.md)
  - [docs/features/07-login-pindah-lembaga.md](file:///c:/laragon/www/daraltauhid/docs/features/07-login-pindah-lembaga.md)
  - [docs/features/08-pengaturan-yayasan-hak-akses.md](file:///c:/laragon/www/daraltauhid/docs/features/08-pengaturan-yayasan-hak-akses.md)

---

## 4. Rincian Modul Domain (`Modules/`)

### 4.1 Modul Akademik (`Modules/Academic`)
Operasional akademik untuk sekolah formal (SMP Plus, MA Nusantara, dan MI).
- **Domain/Akses:** `app.daraltauhid.com` (konteks lembaga formal)
- **Tanggung Jawab:**
  - Manajemen rombel/kelas (`classes`) dan penugasan wali kelas dari tabel `users`.
  - Penempatan siswa ke rombel (`class_student`).
  - Mata pelajaran (`subjects`) dan jadwal mingguan (`schedules`).
  - Presensi siswa per pertemuan (`attendances`).
  - Penilaian harian, ujian, dan rapor siswa (`grades`).
- **Referensi Fitur:** [docs/features/04-akademik-sekolah.md](file:///c:/laragon/www/daraltauhid/docs/features/04-akademik-sekolah.md)

---

### 4.2 Modul Kepesantrenan (`Modules/Dormitory`)
Operasional asrama dan pembinaan santri di Pondok Pesantren Dar Al Tauhid.
- **Domain/Akses:** `app.daraltauhid.com` (konteks lembaga pondok)
- **Tanggung Jawab:**
  - Manajemen kamar asrama putra/putri dan kuota penghuni (`dorm_rooms`).
  - Penempatan santri kamar (`room_members`) dan penugasan musyrif (`room_supervisors`).
  - Buku saku setoran tahfidz Al-Qur'an (`tahfidz_records`).
  - Pencatatan poin pelanggaran tata tertib dan tindakan pembinaan (`violations`).
  - Pengajuan dan persetujuan izin keluar/pulang santri (`permits`).
- **Referensi Fitur:** [docs/features/05-kepesantrenan.md](file:///c:/laragon/www/daraltauhid/docs/features/05-kepesantrenan.md)

---

### 4.3 Modul Penerimaan Santri Baru (`Modules/Admission`)
Penerimaan calon santri baru di seluruh jenjang lembaga yayasan.
- **Domain/Akses:** `psb.daraltauhid.com` (antarmuka publik React) & area panitia di `app.daraltauhid.com`
- **Tanggung Jawab:**
  - Konfigurasi gelombang PSB per lembaga dan syarat berkas.
  - Formulir pendaftaran calon santri online (antarmuka Inertia + React responsif mobile).
  - Unggah berkas syarat (KK, Akta, Pasfoto) via `spatie/laravel-medialibrary`.
  - Verifikasi kelengkapan berkas panitia dan penentuan kelulusan.
  - Aksi **"Jadikan Santri"**: Konversi sinkron pendaftar yang lulus menjadi santri di tabel `students` (Core), penautan ke lembaga di `student_institution`, pembuatan akun wali santri di `users`, dan penugasan peran wali.
- **Referensi Fitur:** [docs/features/03-pendaftaran-santri-baru.md](file:///c:/laragon/www/daraltauhid/docs/features/03-pendaftaran-santri-baru.md)

---

### 4.4 Modul Keuangan & Tagihan (`Modules/Finance`)
Pengelolaan kewajiban pembayaran santri dan rekonsiliasi kasir.
- **Domain/Akses:** `app.daraltauhid.com`
- **Tanggung Jawab:**
  - Master tarif tagihan rutin (SPP/Syahriyah) dan insidental (`invoice_types`).
  - Penerbitan invoice santri berkala (`invoices`).
  - Pencatatan pembayaran kasir/transfer manual dan bukti setor (`payments`).
  - Laporan rekapitulasi tunggakan santri per kelas atau asrama.
- **Referensi Fitur:** [docs/features/10-keuangan-tagihan.md](file:///c:/laragon/www/daraltauhid/docs/features/10-keuangan-tagihan.md)

---

### 4.5 Modul CMS & Publikasi Publik (`Modules/CMS`)
Publikasi profil institusi, kegiatan, dan pengumuman kepada masyarakat luas.
- **Domain/Akses:** `daraltauhid.com` (Yayasan & Pondok) dan `mis.daraltauhid.com` (Profil MI)
- **Teknologi Render:** **Inertia.js + React 19** (Semua komponen halaman ditulis dalam React, dengan tag SEO dikelola via `<Head>`).
- **Tanggung Jawab:**
  - Manajemen halaman profil yayasan dan sekolah (`pages`).
  - Publikasi artikel berita dan dokumentasi kegiatan (`articles`).
  - Banner pengumuman publik (`announcements`).
  - Pengelolaan media publik (galeri foto, thumbnail) via media library.
  - Penegasan isolasi: Subdomain landing page tidak memiliki antarmuka login internal.
- **Referensi Fitur:** [docs/features/09-website-yayasan-sekolah.md](file:///c:/laragon/www/daraltauhid/docs/features/09-website-yayasan-sekolah.md)

---

## 5. Diagram Hubungan Komponen Sistem

```mermaid
graph TD
    subgraph Laravel Native Core [app/ - Core Yayasan & Shared Foundation]
        Inst[Institution Master]
        User[Users & Memberships]
        Stud[Master Students - SSOT]
        Scope[TenantScope institution_id]
        Dash[Unified Dashboard]
        Rep[Executive Cross-Tenant Reports]
    end

    subgraph Admission Module [Modules/Admission]
        Reg[PSB Registrations]
        Docs[Uploaded Documents]
    end

    subgraph Academic Module [Modules/Academic]
        Class[Classes & Schedule]
        Att[Attendance & Grades]
    end

    subgraph Dormitory Module [Modules/Dormitory]
        Dorm[Rooms & Musyrif]
        Tahf[Tahfidz & Permits]
    end

    subgraph Finance Module [Modules/Finance]
        Inv[Invoices & SPP]
        Pay[Payment Records]
    end

    subgraph CMS Module [Modules/CMS - Inertia + React]
        Web[Public Landing Pages & Articles]
    end

    %% Hubungan antar komponen
    Reg -.->|Promote (Sync DB Transaction)| Stud
    Reg -.->|Create Guardian User| User
    Stud -->|Belongs to via Pivot| Inst

    Class -->|Filtered by| Scope
    Class -->|Foreign Key| Stud

    Dorm -->|Filtered by| Scope
    Dorm -->|Foreign Key| Stud

    Inv -->|Filtered by| Scope
    Inv -->|Foreign Key| Stud

    Rep -->|Aggregate Data| Stud
    Rep -->|Aggregate Data| Class
    Rep -->|Aggregate Data| Dorm
    Rep -->|Aggregate Data| Inv

    Web -->|Fetch Profile & Articles| Inst
```

### Aturan Komunikasi:
1. **Semua Modul Merujuk Master Core:** Modul `Academic`, `Dormitory`, `Admission`, dan `Finance` tidak pernah membuat tabel santri sendiri. Seluruh data santri merujuk ke model `App\Models\Student` di `app/`.
2. **Konteks Lembaga:** Setiap query di modul domain disaring secara otomatis oleh `TenantScope` di `app/Scopes/TenantScope.php`.
3. **Semua Halaman Adalah Komponen React:** Seluruh view dari `app/` maupun `Modules/` mengembalikan respon `Inertia::render(...)` ke halaman React di `resources/js/pages/`.
