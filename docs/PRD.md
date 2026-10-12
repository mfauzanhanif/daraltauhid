# PRD — Project Requirements Document (Master Hub)

**Nama Proyek:** Dar Al Tauhid Educational Management System (SaaS)  
**Versi:** 2.0 (Modular Documentation)  
**Arsitektur:** Modular Monolith · Logical Multi-Tenancy · Subdomain Routing  
**Batasan Utama:** Hostinger Shared Hosting (PHP 8.5 + MySQL, tanpa Node.js di production)

---

## Indeks Dokumentasi Proyek

Untuk mempermudah pemahaman dan pemeliharaan teknis, detail spesifikasi telah dipecah ke dalam dokumen-dokumen terdedikasi berikut:

1. 🛠️ **[TECHSTACK.md](file:///c:/laragon/www/daraltauhid/docs/TECHSTACK.md)** — Spesifikasi teknologi, runtime PHP 8.5/Laravel 13, Inertia React, batasan shared hosting, dan 13 ketentuan teknis.
2. 📦 **[MODULES.md](file:///c:/laragon/www/daraltauhid/docs/MODULES.md)** — Arsitektur Modular Monolith (`Modules/`), pembagian tanggung jawab, model database, dan batas antar-modul.
3. 🗄️ **[DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md)** — Skema lengkap seluruh tabel, Diagram ER Mermaid, relasi multi-tenancy, dan indeks performa.
4. 🎨 **[DESIGN.md](file:///c:/laragon/www/daraltauhid/docs/DESIGN.md)** — Panduan bahasa desain Neobrutalism, palet warna, tipografi Inter + JetBrains Mono, dan spesifikasi komponen.
5. 🗺️ **[ROADMAP.md](file:///c:/laragon/www/daraltauhid/docs/ROADMAP.md)** — Rencana implementasi 5 fase, target milestone, kriteria penerimaan (DoD), dan mitigasi risiko.
6. 📑 **Spesifikasi Detail Fitur:** Tersedia di direktori [docs/features/](file:///c:/laragon/www/daraltauhid/docs/features/) (Fitur 01 s/d 10).

---

## 1. Overview & Latar Belakang

### Masalah yang Diselesaikan:
Yayasan Dar Al Tauhid menaungi beberapa unit lembaga sekaligus:
- Pondok Pesantren Dar Al Tauhid (Non-formal)
- SMP Plus Dar Al Tauhid (Formal)
- MA Nusantara (Formal)
- MI Dar Al Tauhid (Formal)

Selama ini setiap lembaga mencatat data santrinya sendiri-sendiri di file Excel atau aplikasi terpisah. Akibatnya:
- Santri yang mondok **sekaligus** bersekolah di SMP Plus dicatat dua kali, sehingga data sering berbeda dan tidak sinkron.
- Membuat laporan yayasan mengharuskan admin merekap dan mencocokkan data manual antar file — lambat dan rawan salah.
- Tidak ada satu halaman login maupun satu sumber data yang bisa dipercaya bersama.

### Tujuan Utama Aplikasi:
1. **Satu Biodata Santri untuk Semua Lembaga (Single Source of Truth):** Data diisi sekali, dipakai bersama di pondok maupun sekolah tanpa duplikasi.
2. **Satu Pintu Masuk (Single Sign-On Internal):** Semua pengguna yayasan login sekali, lalu bebas berpindah lembaga sesuai hak aksesnya tanpa login ulang. Keanggotaan pengguna bersifat per lembaga.
3. **Laporan Yayasan Otomatis:** Rekap lintas lembaga tersusun otomatis langsung dari database.
4. **Digitalisasi Operasional Harian:** PSB online, akademik sekolah, kepesantrenan (tahfidz/asrama), sampai keuangan tercatat rapi.
5. **Ringan & Terjangkau:** Berjalan optimal di shared hosting Hostinger standar tanpa biaya server tambahan.

### Pengguna Sasaran (Tenant/Lembaga):
| Lembaga | Jenis | Domain / Akses |
|---|---|---|
| Pondok Pesantren Dar Al Tauhid | Non-formal | `app.daraltauhid.com` |
| SMP Plus Dar Al Tauhid | Formal | `app.daraltauhid.com` |
| MA Nusantara | Formal | `app.daraltauhid.com` |
| MI Dar Al Tauhid | Formal | `app.daraltauhid.com` |

> *Catatan Santri Eksternal:* **MTsN 3 Cirebon bukan tenant**, melainkan atribut penanda pada biodata santri (`external_school`) karena santri pondok dapat bersekolah di luar yayasan.

### Peta Domain:
- `app.daraltauhid.com` — Aplikasi inti (dashboard admin yayasan, kepala sekolah, guru, musyrif, wali santri).
- `psb.daraltauhid.com` — Portal pendaftaran santri baru online.
- `daraltauhid.com` — **Hanya landing page** profil yayasan & pondok (publik, tanpa login, render Inertia + React).
- `mis.daraltauhid.com` — **Hanya landing page** profil MI (publik, tanpa login, render Inertia + React).

---

## 2. Persyaratan Sistem (Requirements)

### A. Persyaratan Fungsional
| Kode | Persyaratan |
|---|---|
| **FR-01** | Semua pengguna login melalui satu halaman login terpusat. |
| **FR-02** | Setiap akun pengguna terdaftar pada satu atau beberapa lembaga; keanggotaan lembaga disimpan eksplisit dan menjadi dasar akses pengguna. |
| **FR-03** | Daftar lembaga pada pemilih workspace hanya berisi lembaga tempat pengguna terdaftar; pengguna tidak dapat mengakses data lembaga yang tidak diikutinya, termasuk bila menebak URL atau parameter lembaga. |
| **FR-04** | Pengguna dapat berpindah lembaga (workspace) tanpa login ulang; sistem mengingat lembaga terakhir. |
| **FR-05** | Setiap pengguna dapat memiliki peran berbeda di lembaga berbeda (mis. Admin di SMP, Guru di MA). |
| **FR-06** | Pengguna yang terdaftar di satu lembaga langsung masuk ke lembaga itu; pengguna tanpa keanggotaan lembaga aktif tidak dapat masuk ke area lembaga mana pun. |
| **FR-07** | Admin yayasan dapat menambah atau mencabut keanggotaan pengguna pada sebuah lembaga tanpa menghapus akun pengguna tersebut. |
| **FR-08** | Biodata santri disimpan satu kali; sistem menolak NIK/NISN yang sudah terdaftar. |
| **FR-09** | Satu santri dapat terdaftar aktif di lebih dari satu lembaga dalam satu tahun ajaran. |
| **FR-10** | Wali santri dapat mendaftar online, mengunggah dokumen, dan mengecek hasil seleksi. |
| **FR-11** | Pendaftar yang lulus otomatis menjadi data santri aktif dan akun wali dibuatkan. |
| **FR-12** | Pemisahan data antar lembaga berjalan otomatis berdasarkan lembaga aktif di sesi pengguna. |
| **FR-13** | Rekap laporan lintas lembaga (termasuk santri yang terdaftar di dua lembaga) dapat dihasilkan dan diunduh. |
| **FR-14** | Halaman publik yayasan/sekolah dapat diperbarui oleh tim humas tanpa bantuan teknis. |

### B. Persyaratan Non-Fungsional
| Aspek | Target Spesifikasi |
|---|---|
| **Platform Hosting** | Hostinger Shared Hosting (PHP 8.5+, MySQL/MariaDB). |
| **Batasan Keras** | **Tidak ada Node.js di server production; tidak ada SSR.** Aset React di-build lokal lalu diunggah. |
| **Routing** | Satu instalasi Laravel melayani semua domain/subdomain via domain routing. |
| **Multi-Tenancy** | **Logical Multi-Tenancy** — satu database, isolasi otomatis via `institution_id` dan Global Scope. |
| **SEO** | Landing page (`daraltauhid.com` & `mis.*`) dikelola menggunakan komponen Inertia `<Head>` (title, meta description, OG tags) agar terindeks optimal. |
| **Performa** | Halaman dashboard inti termuat < 3 detik; query wajib memiliki indeks pada `institution_id`. |
| **Keamanan** | Password di-hash (Bcrypt/Argon2), hak akses diverifikasi di backend, validasi keanggotaan per request. |
| **Desain Antarmuka** | Neobrutalism: border tebal, shadow solid (`shadow-[4px_4px_0_0_#000]`), warna kontras, font Inter/JetBrains Mono. |
| **Bahasa Antarmuka** | Bahasa Indonesia (istilah teknis diperbolehkan dalam bahasa Inggris). |

### C. Batasan & Asumsi
- Upgrade ke VPS hanya dilakukan jika kapasitas shared hosting sudah tidak memadai; fase awal wajib berjalan efisien di shared hosting.
- Tidak ada antrean (queue worker) latar belakang di shared hosting, semua konversi data PSB → santri dijalankan **secara sinkron dalam DB Transaction**.
- Pembayaran via payment gateway bersifat opsional pada fase akhir (fase awal menggunakan pencatatan kasir/transfer manual).
- Arsitektur Core berada di `app/` bawaan Laravel, sedangkan sub-sistem domain berada di `Modules/`.

---

## 3. Ringkasan Fitur Inti (Core Features)

Fitur-fitur sistem dikelompokkan sesuai roadmap yang telah disetujui:

- **Fase 1 — Fondasi Data & Dashboard:**
  - **[3.1 Dashboard Lembaga](file:///c:/laragon/www/daraltauhid/docs/features/01-dashboard-lembaga.md)** — Metrik santri aktif, pintasan tugas cepat, aktivitas terbaru, dan pemeriksaan data belum lengkap.
  - **[3.2 Data Santri Terpadu](file:///c:/laragon/www/daraltauhid/docs/features/02-data-santri-terpadu.md)** — Master biodata satu pintu, anti-duplikasi NIK/NISN, relasi multi-lembaga, dan status santri eksternal.
- **Fase 2 — Layanan Publik & Operasional Harian:**
  - **[3.3 Pendaftaran Santri Baru (PSB)](file:///c:/laragon/www/daraltauhid/docs/features/03-pendaftaran-santri-baru.md)** — Form pendaftaran online, unggah berkas, verifikasi berkas, pengumuman, dan konversi santri otomatis.
  - **[3.4 Akademik Sekolah](file:///c:/laragon/www/daraltauhid/docs/features/04-akademik-sekolah.md)** — Rombel/kelas, mapel, jadwal, presensi siswa, buku nilai, dan rapor sekolah formal.
  - **[3.5 Kepesantrenan](file:///c:/laragon/www/daraltauhid/docs/features/05-kepesantrenan.md)** — Manajemen kamar asrama, pembina/musyrif, buku saku tahfidz, pelanggaran, dan perizinan keluar/pulang.
- **Fase 3 — Lintas Lembaga & Akses:**
  - **[3.6 Laporan Yayasan](file:///c:/laragon/www/daraltauhid/docs/features/06-laporan-yayasan.md)** — Rekapitulasi santri lintas lembaga (akurasi santri ganda), laporan akademik & pondok, ekspor PDF/Excel.
  - **[3.7 Login & Pindah Lembaga](file:///c:/laragon/www/daraltauhid/docs/features/07-login-pindah-lembaga.md)** — Login satu pintu, Workspace Switcher, manajemen keanggotaan multi-lembaga, dan reset password.
- **Fase 4 — Tata Kelola & Publikasi:**
  - **[3.8 Pengaturan Yayasan & Hak Akses](file:///c:/laragon/www/daraltauhid/docs/features/08-pengaturan-yayasan-hak-akses.md)** — Master lembaga, tahun ajaran aktif, manajemen user, dan RBAC per lembaga.
  - **[3.9 Website Yayasan & Sekolah](file:///c:/laragon/www/daraltauhid/docs/features/09-website-yayasan-sekolah.md)** — CMS publik profil yayasan dan MI berbasis Inertia + React, ramah SEO.
- **Fase 5 — Keuangan:**
  - **[3.10 Keuangan & Tagihan](file:///c:/laragon/www/daraltauhid/docs/features/10-keuangan-tagihan.md)** — Tarif SPP/Syahriyah, penerbitan invoice per santri, pencatatan pembayaran kasir/transfer, rekap tunggakan.

---

## 4. Alur Kerja Pengguna (User Flows)

### 4.1 Alur Masuk & Pindah Lembaga (First Win)
1. Pengguna membuka `app.daraltauhid.com` → tampil formulir login terpusat.
2. Pengguna memasukkan email dan kata sandi.
3. Sistem memvalidasi kredensial dan mengambil **daftar keanggotaan lembaga aktif** pengguna dari tabel `institution_user`.
4. Jika tidak terdaftar di lembaga mana pun → akses ditolak dengan petunjuk menghubungi admin yayasan.
5. Jika hanya terdaftar di satu lembaga → langsung dialihkan ke dashboard lembaga tersebut.
6. Jika terdaftar di lebih dari satu lembaga → muncul modal pilihan lembaga keanggotaan.
7. Pilihan disimpan pada sesi (`current_institution_id`); seluruh query otomatis terisolasi ke lembaga tersebut.
8. Pengguna dapat berganti lembaga kapan saja menggunakan **Workspace Switcher** di header tanpa login ulang.

### 4.2 Alur Kerja Harian Admin/Guru/Musyrif
1. Masuk ke dashboard lembaga → meninjau ringkasan statistik dan aktivitas terbaru.
2. Menekan tombol pintasan tugas cepat (mis. Presensi Kelas, Catatan Tahfidz, Perizinan Santri).
3. Melakukan pencatatan; sistem otomatis mengaitkannya pada `institution_id` dan tahun ajaran aktif.
4. Santri dengan biodata belum lengkap disorot pada widget khusus agar dapat segera dilengkapi.

### 4.3 Alur PSB Menjadi Santri Aktif
1. Calon wali santri mengakses `psb.daraltauhid.com`, mengisi data, dan memilih unit lembaga tujuan.
2. Mengunggah dokumen persyaratan (KK, Akta, Pasfoto) dari ponsel.
3. Panitia memeriksa kelengkapan berkas di dashboard verifikasi PSB.
4. Panitia menetapkan status seleksi (Lolos / Tidak Lolos).
5. Pada pendaftar lolos, panitia mengklik **"Jadikan Santri"**:
   - Sistem memeriksa NIK di tabel master `students` (mencegah duplikasi).
   - Menghubungkan santri ke lembaga tujuan pada tabel `student_institution`.
   - Membuat akun wali santri di tabel `users` dan mengaitkannya ke lembaga di `institution_user`.
6. Wali santri menerima akun dan dapat login memantau perkembangan anaknya.

### 4.4 Alur Pencegahan Duplikasi Data Santri
1. Admin memasukkan NIK/NISN santri baru.
2. Sistem mengecek keunikan NIK pada tabel `students`.
3. Bila NIK sudah ada di sistem (misal santri sudah mondok di pesantren dan sekarang ingin didaftarkan ke SMP Plus):
   - Sistem memberi peringatan data telah ada.
   - Sistem menawarkan tombol **"Tautkan Santri ke Lembaga Ini"** alih-alih membuat data baru.
4. Satu record santri kini terhubung ke Pondok dan SMP tanpa duplikasi biodata.

---

## 5. Ringkasan Arsitektur & Database

Sistem memanfaatkan perpaduan teknologi yang efisien dan hemat biaya:

```text
[ Domain Routing ] -> [ Inertia.js + React (Semua Domain & Landing Page) ]
                                          |
                        +-----------------------------------+
                        |         Laravel 13 Backend        |
                        |  app/ (Core Yayasan & Auth)       |
                        |  Modules/ (Domain Fungsional)     |
                        +-----------------------------------+
                                          |
                        [ Logical Multi-Tenancy (TenantScope) ]
                                          |
                              [ MySQL Single Database ]
```

- Rincian teknis framework, package, dan batasan shared hosting dapat dibaca di **[TECHSTACK.md](file:///c:/laragon/www/daraltauhid/docs/TECHSTACK.md)**.
- Rincian arsitektur modul dapat dibaca di **[MODULES.md](file:///c:/laragon/www/daraltauhid/docs/MODULES.md)**.
- Rincian skema database dan relasi dapat dibaca di **[DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md)**.
- Panduan desain Neobrutalism dapat dibaca di **[DESIGN.md](file:///c:/laragon/www/daraltauhid/docs/DESIGN.md)**.
- Rencana eksekusi dan milestone dapat dibaca di **[ROADMAP.md](file:///c:/laragon/www/daraltauhid/docs/ROADMAP.md)**.