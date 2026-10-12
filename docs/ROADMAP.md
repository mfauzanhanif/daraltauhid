# Roadmap Pengembangan — Dar Al Tauhid EMS

**Dokumen Terkait:** [PRD.md](file:///c:/laragon/www/daraltauhid/docs/PRD.md) · [TECHSTACK.md](file:///c:/laragon/www/daraltauhid/docs/TECHSTACK.md) · [MODULES.md](file:///c:/laragon/www/daraltauhid/docs/MODULES.md) · [DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md) · [DESIGN.md](file:///c:/laragon/www/daraltauhid/docs/DESIGN.md)

---

## 1. Ikhtisar Roadmap & Strategi Rilis

Pengembangan Dar Al Tauhid EMS disusun dalam **5 Fase Bertahap** dengan pendekatan *First-Win* (menghadirkan nilai manfaat tercepat pada operasional inti terlebih dahulu) dan dirancang untuk diuji secara inkremental.

```
[ Fase 1: Fondasi & Dashboard ] 
             |
             v
[ Fase 2: Layanan Publik & Operasional Harian ]
   (PSB Online, Akademik Sekolah, Kepesantrenan)
             |
             v
[ Fase 3: Lintas Lembaga & Akses Terpadu ]
   (Laporan Rekapitulasi Yayasan, Multi-Tenant RBAC)
             |
             v
[ Fase 4: Tata Kelola & Publikasi ]
   (CMS Landing Page Yayasan & Profil MI via React)
             |
             v
[ Fase 5: Keuangan & Tagihan Santri ]
   (Master Tagihan, Invoicing, Pencatatan Pembayaran)
```

---

## 2. Rincian Fase Pengembangan

---

### FASE 1 — Fondasi Data, Autentikasi & Dashboard
> **Target Utama:** Memberikan solusi atas masalah inti yayasan: login terpusat, satu biodata santri (Single Source of Truth), dan dashboard kerja harian lembaga.

#### Milestone 1.1: Setup Proyek & Multi-Tenancy Dasar
- [ ] Konfigurasi arsitektur Modular Monolith (`nwidart/laravel-modules`) di Laravel 13.
- [ ] Implementasi migrasi skema database inti: `institutions`, `users`, `academic_years`, `institution_user`.
- [ ] Setup Laravel Fortify headless authentication (login, logout, reset password).
- [ ] Implementasi trait Global Scope `institution_id` untuk isolasi data otomatis.
- [ ] Middleware verifikasi keanggotaan lembaga aktif (`EnsureActiveInstitutionMembership`).

#### Milestone 1.2: Workspace Switcher & Multi-Membership
- [ ] Pembuatan komponen UI Workspace Switcher Neobrutalism pada header aplikasi.
- [ ] Pengambilan daftar lembaga aktif yang hanya menjadi hak akses pengguna.
- [ ] Logika pergantian sesi lembaga (`current_institution_id`) tanpa perlu logout ulang.
- [ ] Penanganan kasus akun tanpa lembaga aktif (halaman fallback / kontak admin yayasan).

#### Milestone 1.3: Master Data Santri Terpadu (Single Source of Truth)
- [ ] Migrasi tabel `students` dan pivot relasi `student_institution`.
- [ ] Form CRUD santri terpadu dengan validasi unik NIK dan NISN.
- [ ] Mekanisme penautan santri lama ke lembaga baru (mencegah duplikasi data santri yang mondok sekaligus sekolah).
- [ ] Dukungan kolom penanda sekolah eksternal (`external_school`, mis. santri pondok yang sekolah di MTsN 3 Cirebon).
- [ ] Algoritma penghitungan skor persentase kelengkapan data santri (`data_completeness`).

#### Milestone 1.4: Dashboard Lembaga
- [ ] Tampilan ringkasan metrik (jumlah santri aktif, jumlah rombel/kamar, kegiatan harian).
- [ ] Widget *Pemeriksaan Data Belum Lengkap* (menampilkan daftar santri dengan skor < 100%).
- [ ] Pintasan tugas cepat (Quick Action buttons) sesuai lembaga aktif.

**Kriteria Penerimaan (Definition of Done) Fase 1:**
- Pengguna dapat login dan berpindah antar lembaga sesuai keanggotaannya.
- Tidak ada santri dengan NIK kembar; satu santri dapat terdaftar aktif di Pondok dan SMP secara bersamaan.
- Dashboard menampilkan data spesifik lembaga aktif tanpa kebocoran data lembaga lain.

---

### FASE 2 — Layanan Publik & Operasional Harian
> **Target Utama:** Digitalisasi alur masuk santri baru via web dan pencatatan operasional sekolah formal serta pondok pesantren.

#### Milestone 2.1: Portal PSB (`psb.daraltauhid.com`)
- [ ] Domain routing untuk subdomain `psb.daraltauhid.com`.
- [ ] Formulir online pendaftaran santri baru yang responsif di smartphone.
- [ ] Fitur unggah dokumen persyaratan (KK, Akta Kelahiran, Pasfoto) ke local disk via `spatie/laravel-medialibrary`.
- [ ] Portal pencarian status hasil seleksi pendaftar via nomor registrasi.

#### Milestone 2.2: Verifikasi & Konversi Otomatis Santri
- [ ] Dashboard panitia PSB untuk memeriksa berkas dan memberi status kelulusan.
- [ ] Aksi **"Jadikan Santri"** yang berjalan sinkron dalam satu database transaction:
  - Membuat data santri di tabel `students` (cek duplikasi NIK).
  - Mengaitkan santri ke lembaga tujuan pada tabel `student_institution`.
  - Membuat akun pengguna untuk wali santri pada tabel `users`.
  - Mengaitkan akun wali ke lembaga terkait di tabel `institution_user`.

#### Milestone 2.3: Operasional Akademik Sekolah Formal (SMP/MA/MI)
- [ ] Manajemen rombongan belajar (kelas) dan penugasan wali kelas.
- [ ] Penempatan siswa ke dalam kelas (`class_student`).
- [ ] Manajemen mata pelajaran dan penyusunan jadwal mingguan.
- [ ] Fitur presensi harian siswa (hadir, izin, sakit, alpa) oleh guru/wali kelas.
- [ ] Input penilaian (tugas, UTS, UAS) dan kalkulasi nilai rapor.

#### Milestone 2.4: Operasional Kepesantrenan (Pondok)
- [ ] Manajemen kamar asrama putra dan putri beserta kapasitasnya.
- [ ] Penempatan santri ke kamar (`room_members`) dan penugasan musyrif kamar.
- [ ] Buku catatan setoran tahfidz Al-Qur'an (surah, ayat, juz, nilai, musyrif).
- [ ] Pencatatan pelanggaran disiplin dan bobot poin pelanggaran.
- [ ] Modul pengajuan dan persetujuan izin santri (izin keluar / pulang).

**Kriteria Penerimaan Fase 2:**
- Wali santri dapat mendaftar mandiri via web; pendaftar yang diluluskan panitia otomatis menjadi santri tanpa input ulang data oleh TU.
- Guru dapat mengisi absensi kelas dan nilai dari dashboard sekolah.
- Musyrif dapat mencatat setoran hafalan Al-Qur'an dan menyetujui izin santri dari dashboard pondok.

---

### FASE 3 — Lintas Lembaga & Akses Terpadu
> **Target Utama:** Rekapitulasi laporan yayasan otomatis dari database dan pematangan kontrol hak akses (RBAC).

#### Milestone 3.1: Laporan Eksekutif Yayasan
- [ ] Agregasi data santri lintas lembaga (menghitung santri ganda secara akurat sebagai 1 individu, dengan breakdown per lembaga).
- [ ] Rekap kehadiran dan pencapaian akademik sekolah.
- [ ] Rekap perkembangan tahfidz dan ketertiban asrama pondok.
- [ ] Filter dinamis berdasarkan lembaga dan tahun ajaran menggunakan `spatie/laravel-query-builder`.
- [ ] Ekspor laporan ke format PDF dan spreadsheet siap cetak.

#### Milestone 3.2: Pematangan RBAC Spatie Teams
- [ ] Konfigurasi permissions granular per lembaga (`institution_id`).
- [ ] Validasi hak akses dinamis (misal: user bertindak sebagai Guru di MA, namun sebagai Kepala Sekolah di SMP).
- [ ] Audit log sederhana untuk perubahan data krusial.

**Kriteria Penerimaan Fase 3:**
- Pengurus yayasan dapat mengunduh rekap santri seluruh yayasan dengan satu klik tanpa perlu rekonsiliasi manual di Excel.
- Hak akses pengguna terisolasi sempurna sesuai perannya di masing-masing lembaga.

---

### FASE 4 — Tata Kelola & Publikasi (CMS)
> **Target Utama:** Menghadirkan portal profil publik yang cepat, modern, SEO-friendly, dan mudah dikelola tim humas yayasan menggunakan React.

#### Milestone 4.1: CMS Profil Yayasan & Pondok (`daraltauhid.com`)
- [ ] Domain routing untuk domain utama `daraltauhid.com`.
- [ ] Halaman statis profil, visi-misi, sejarah, dan kontak menggunakan **Inertia.js + React 19**.
- [ ] Pengelolaan artikel berita, dokumentasi kegiatan, dan pengumuman publik.
- [ ] Optimasi metadata SEO, OpenGraph tags, dan sitemap via komponen Inertia `<Head>`.

#### Milestone 4.2: Landing Page MI Profil (`mis.daraltauhid.com`)
- [ ] Domain routing untuk `mis.daraltauhid.com` (Inertia + React, 100% landing page publik tanpa rute aplikasi internal).
- [ ] Profil kurikulum, fasilitas, dan ekstrakurikuler MI Dar Al Tauhid berbasis komponen React.
- [ ] Tautan langsung ke portal pendaftaran santri baru `psb.daraltauhid.com`.

**Kriteria Penerimaan Fase 4:**
- Halaman publik termuat instan (< 1.5 detik), ramah SEO via tag `<Head>`, dan tidak memiliki celah akses ke area login/dashboard.
- Tim humas dapat memposting berita kegiatan baru dari dashboard yayasan.

---

### FASE 5 — Keuangan & Integrasi
> **Target Utama:** Pencatatan kewajiban iuran, SPP, dan syahriyah santri secara tertib dan transparan.

#### Milestone 5.1: Konfigurasi Tarif & Pembuatan Tagihan
- [ ] Master jenis tagihan (SPP bulanan, uang asrama/syahriyah, uang kegiatan) per lembaga.
- [ ] Penagihan massal berkala kepada santri aktif per tahun ajaran.

#### Milestone 5.2: Pencatatan Pembayaran & Rekap Tunggakan
- [ ] Input pembayaran kasir manual/transfer beserta nomor referensi.
- [ ] Pembuatan kuitansi digital pembayaran.
- [ ] Laporan rekapitulasi tunggakan santri per kelas atau per asrama untuk keperluan penagihan.

#### Milestone 5.3: Pengembangan Masa Depan (Post-Launch Horizons)
- Integrasi Payment Gateway (Midtrans/Xendit) untuk pembayaran online mandiri oleh wali santri.
- Integrasi WhatsApp Gateway untuk notifikasi otomatis pengumuman PSB, presensi, dan tagihan.
- Progressive Web App (PWA) untuk kemudahan akses guru dan musyrif di smartphone.

---

## 3. Matriks Ketergantungan Arsitektur

```text
[app/ (Core Yayasan)] ────────┬───────────────────────────────┐
       │                      │                               │
       ▼                      ▼                               ▼
[Modules/Academic]    [Modules/Dormitory]             [Modules/Admission]
       │                      │                               │
       │                      │                               ▼
       └──────────────┬───────┘                    (Konversi ke app/Santri)
                      ▼                                       │
              [Modules/Finance] <─────────────────────────────┘
                      │
                      ▼
             [Modules/CMS (React)]
```

---

## 4. Manajemen Risiko Teknis & Mitigasi

| Risiko Potensial | Dampak | Strategi Mitigasi |
|---|---|---|
| **Limit CPU/Memori Shared Hosting** | Request timeout jika memproses rekap data besar. | Gunakan eager loading `with()`, index kolom `institution_id`, dan optimasi query via chunking. |
| **Ketiadaan Queue Worker di Hosting** | Proses pembuatan akun santri & wali gagal sebagian. | Jalankan seluruh proses konversi PSB di dalam `DB::transaction` secara sinkron. |
| **Kebocoran Data Antar Lembaga** | Staf SMP melihat data santri pondok yang tidak relevan. | Penerapan wajib Laravel Global Scope dan middleware verifikasi keanggotaan lembaga aktif di setiap rute. |
| **Data Santri Ganda (Duplikasi)** | Laporan rekap yayasan tidak akurat. | Kunci `nik` dan `nisn` dibuat unik pada database; sistem otomatis menyarankan penautan lembaga alih-alih membuat santri baru. |
