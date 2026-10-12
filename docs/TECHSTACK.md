# Spesifikasi Tech Stack — Dar Al Tauhid EMS

**Dokumen Terkait:** [PRD.md](file:///c:/laragon/www/daraltauhid/docs/PRD.md) · [MODULES.md](file:///c:/laragon/www/daraltauhid/docs/MODULES.md) · [DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md) · [DESIGN.md](file:///c:/laragon/www/daraltauhid/docs/DESIGN.md) · [ROADMAP.md](file:///c:/laragon/www/daraltauhid/docs/ROADMAP.md)

---

## 1. Ringkasan Eksekutif Tech Stack

Dar Al Tauhid Educational Management System (EMS) dirancang dengan arsitektur **Modular Monolith** berbasis **Laravel 13** dan **PHP 8.5**. Seluruh antarmuka pada semua domain dan portal (aplikasi internal, portal PSB, maupun landing page publik) dibangun **100% menggunakan Laravel dan Inertia.js + React 19** dengan styling **Tailwind CSS v4** berkarakter **Neobrutalism** (tidak ada halaman antarmuka yang menggunakan Blade).

Seluruh fungsionalitas inti (Core: identitas organisasi, autentikasi, keanggotaan lembaga, master biodata santri, dashboard, dan laporan yayasan) diimplementasikan langsung pada direktori bawaan Laravel (**`app/`**), sedangkan sub-sistem domain fungsional spesifik ditempatkan di dalam folder **`Modules/`**.

```
                      +------------------------------------------+
                      |         Domain Routing (Laravel)         |
                      +------------------------------------------+
                               /              |             \
                              /               |              \
           app.daraltauhid.com   psb.daraltauhid.com   daraltauhid.com & mis.*
                    |                     |                     |
           (Aplikasi Inti)           (Portal PSB)          (Landing Page Publik)
                    |                     |                     |
                    +---------------------+---------------------+
                                          |
                              [Inertia.js v3 + React 19]
                                  (100% React UI)
                                          |
                    +-------------------------------------------+
                    |           Laravel 13 Backend              |
                    |-------------------------------------------|
                    |  app/ (Core Yayasan, Auth, Tenant, SSOT)  |
                    |  Modules/ (Academic, Dorm, Adm, Fin, CMS) |
                    +-------------------------------------------+
                                          |
                    +-------------------------------------------+
                    |    Logical Multi-Tenancy (TenantScope)    |
                    +-------------------------------------------+
                                          |
                    +-------------------------------------------+
                    |         MySQL / MariaDB (Single DB)       |
                    +-------------------------------------------+
```

---

## 2. Rincian Lapisan Teknologi

| Lapisan | Teknologi | Versi | Alasan Pemilihan & Kegunaan |
|---|---|---|---|
| **Bahasa Backend** | PHP | `8.5` (min `^8.3`) | Modern, performa tinggi, didukung penuh Hostinger shared hosting. |
| **Framework Backend** | Laravel Framework | `13.x` | Routing domain bawaan, ekosistem auth, ORM Eloquent, dan tooling artisan lengkap. |
| **Fondasi Inti (Core)** | Laravel Native Directory | `app/` | Seluruh fondasi core (Institusi/Tenant, Pengguna, Keanggotaan Lembaga, Master Santri, Dashboard, Laporan) diimplementasikan langsung pada struktur bawaan Laravel (`app/Models`, `app/Http/Controllers`, `app/Services`, `app/Scopes`). |
| **Modular Domain** | Modular Monolith | `nwidart/laravel-modules` | Memisahkan sub-domain spesifik ke folder `Modules/` mandiri (`Academic`, `Dormitory`, `Admission`, `Finance`, `CMS`). |
| **Database Engine** | MySQL / MariaDB | `8.0+` / `10.5+` | Database relasional standar Hostinger, ACID-compliant, efisien untuk indexing multi-tenancy. |
| **Multi-Tenancy Engine** | Logical Multi-Tenancy | Custom `TenantScope` di `app/` | Pemisahan data per lembaga menggunakan `institution_id` otomatis di level Eloquent, tanpa perlu multi-database. |
| **Autentikasi (Backend)** | Laravel Fortify | `^1.37` | Backend autentikasi headless: login satu pintu, reset kata sandi, 2FA/passkeys, dan verifikasi email. |
| **Keanggotaan Workspace** | Laravel Teams Scaffold | Bawaan Installer | Memetakan tim 1:1 ke lembaga (`institution`), menyediakan logika `current_team_id` / `current_institution_id`, keanggotaan pengguna, dan Workspace Switcher. |
| **Otorisasi & Hak Akses** | spatie/laravel-permission | Latest (Teams Mode) | RBAC fleksibel dengan dukungan tim/lembaga (`team_id` = `institution_id`), memfasilitasi peran berbeda per lembaga. |
| **Frontend Framework (Semua Halaman)** | Inertia.js (React Adapter) | `v3.0` | **Digunakan di seluruh aplikasi, PSB, hingga landing page.** Tidak menggunakan Blade views untuk antarmuka pengguna. |
| **Frontend Library** | React & React DOM | `19.x` | Library komponen reaktif berkinerja tinggi untuk semua halaman. |
| **SEO & Head Management** | Inertia `<Head>` Component | `v3.0` | Pengelolaan tag judul, deskripsi meta, OpenGraph, dan canonical URL di landing page publik (`daraltauhid.com` dan `mis.*`) langsung dari komponen React. |
| **Styling & CSS Engine** | Tailwind CSS | `v4.x` | Utility-first CSS modern berbasis CSS variables, kompilasi cepat via `@tailwindcss/vite`. |
| **Design Language** | Neobrutalism | Custom CSS & Utility | Estetika berkarakter: border tebal solid, hard box-shadows (`4px 4px 0 0 #000`), kontras tinggi, font Inter & JetBrains Mono. |
| **Komponen UI Primitif** | Radix UI + shadcn/ui | Primitives | Aksesibilitas keyboard (a11y), dialog, dropdown, popover, tabs yang mudah dikustomisasi styling-nya. |
| **Type Checking** | TypeScript | `^5.7` | Type-safety untuk komponen React, props Inertia, dan integrasi controller via Wayfinder. |
| **Route Generation TS** | Laravel Wayfinder | `^0.1.14` | Menghasilkan fungsi TypeScript bertipe data untuk endpoint controller dan named routes Laravel (`@/actions`, `@/routes`). |
| **Query Filtering & Search** | spatie/laravel-query-builder | Latest | Abstraksi deklaratif untuk filter, sort, dan pencarian pada endpoint listing santri dan laporan. |
| **Media & File Storage** | spatie/laravel-medialibrary | Latest | Pengelolaan berkas PSB, foto santri, dan thumbnail berita dengan konversi ukuran di disk lokal hosting. |
| **Asset Bundler** | Vite & Vite Plus | `v8.x` / `vp` | Build lokal aset frontend TypeScript, JSX, dan Tailwind CSS ke folder `public/build/`. |
| **Testing Suite** | Pest PHP | `^5.3` | Framework pengujian unit dan fitur yang ekspresif, cepat, dan mudah dipelihara. |
| **Code Style Formatter** | Laravel Pint | `^1.27` | Formatter otomatis standar PSR-12 dan konvensi Laravel. |

---

## 3. Lingkungan Server & Batasan Hostinger Shared Hosting

Aplikasi dirancang agar berjalan stabil di lingkungan **Hostinger Shared Hosting**:

### 3.1 Batasan Keras & Strategi Arsitektur
1. **Tidak Ada Runtime Node.js di Server Production:**
   - Server tidak menjalankan Node.js daemon.
   - **Tanpa Inertia SSR di server.** Seluruh render React dijalankan di browser klien (Client-Side Rendering).
   - Seluruh aset frontend di-build di lingkungan lokal developer via `npm run build`, lalu folder `public/build/` diunggah ke server hosting.
2. **Ketiadaan Background Worker / Daemon Queue:**
   - Tidak tersedia supervisor daemon untuk menjalankan worker `php artisan queue:work` secara persisten.
   - Semua alur kerja operasional (seperti proses konversi pendaftar PSB yang lulus menjadi santri, pembuatan akun wali santri, dan penautan lembaga) dijalankan **secara sinkron (synchronous)** di dalam DB Transaction saat aksi admin dilakukan.
3. **Konfigurasi Domain Tunggal ke Direktori Publik:**
   - Semua subdomain (`app.daraltauhid.com`, `psb.daraltauhid.com`, `mis.daraltauhid.com`, serta domain utama `daraltauhid.com`) diparkir ke document root yang sama (`public_html` atau symlink ke `public/`).
   - Laravel membedakan routing menggunakan domain routing di `routes/web.php`.
4. **Semua Halaman Berbasis React (Inertia):**
   - Halaman publik profil yayasan dan MI juga dirender menggunakan `Inertia::render('Public/Home')`, `Inertia::render('Public/MiProfile')`, dsb.
   - File Blade hanya digunakan untuk satu file shell root bawaan Inertia (`resources/views/app.blade.php`) sebagai tempat mounting script dan stylesheet React.

---

## 4. Konfigurasi Domain Routing (Semua Inertia + React)

```php
// routes/web.php
use Inertia\Inertia;

// 1. Landing Page Yayasan & Pondok (Inertia + React)
Route::domain('daraltauhid.com')->group(function () {
    Route::get('/', [LandingController::class, 'home'])->name('landing.home');
    Route::get('/profil', [LandingController::class, 'about'])->name('landing.about');
    Route::get('/berita/{slug}', [LandingController::class, 'article'])->name('landing.article');
});

// 2. Landing Page MI Profil (Inertia + React - Publik, Tanpa Login)
Route::domain('mis.daraltauhid.com')->group(function () {
    Route::get('/', [MiLandingController::class, 'index'])->name('mi.home');
    Route::get('/profil', [MiLandingController::class, 'profile'])->name('mi.profile');
    Route::get('/kegiatan/{slug}', [MiLandingController::class, 'activity'])->name('mi.activity');
});

// 3. Portal PSB (Inertia + React)
Route::domain('psb.daraltauhid.com')->group(function () {
    Route::get('/', [PsbController::class, 'index'])->name('psb.landing');
    Route::get('/daftar', [PsbController::class, 'create'])->name('psb.register');
    Route::post('/daftar', [PsbController::class, 'store'])->name('psb.store');
    Route::get('/status', [PsbController::class, 'checkStatus'])->name('psb.status');
});

// 4. Aplikasi Utama Inti (Inertia + React, Middleware Auth & Keanggotaan Lembaga)
Route::domain('app.daraltauhid.com')->group(function () {
    Route::middleware(['auth', 'verified', 'ensure.institution'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/switch-workspace/{institution}', [WorkspaceController::class, 'switch'])->name('workspace.switch');
        // Rute Modul Akademik, Asrama, Keuangan, dll...
    });
});
```

---

## 5. 13 Ketentuan Teknis Implementasi

1. **Seluruh Antarmuka Menggunakan React:** Semua halaman (aplikasi, PSB, profil yayasan, profil MI) dibangun dengan React melalui Inertia.js. Tidak ada pembuatan view Blade kustom (kecuali `app.blade.php` sebagai root shell Inertia).
2. **Core Diimplementasikan di `app/` Bawaan:** Modul core tidak dijadikan modul terpisah di `Modules/Core`, melainkan ditempatkan langsung di `app/Models`, `app/Http/Controllers`, `app/Services`, dan `app/Scopes` bawaan Laravel.
3. **Tanpa SSR (No Server-Side Rendering Node.js):** Render React berjalan murni di sisi klien (Client-Side Rendering) agar ramah shared hosting tanpa kebutuhan Node.js daemon.
4. **SEO Via Inertia `<Head>`:** Metadata title, meta description, dan OpenGraph pada landing page publik dikelola menggunakan komponen `<Head>` dari Inertia di halaman React.
5. **Aset Statis Permanen:** Kompilasi frontend dilakukan secara lokal (`npm run build`), menghasilkan bundel di `public/build/` yang diunggah ke server.
6. **Mandatory Global Scope `institution_id`:** Semua model Eloquent terikat tenant wajib menggunakan trait scope tenant (`app/Scopes/TenantScope.php`) agar query terisolasi per lembaga aktif.
7. **Eksekusi Sinkron PSB:** Konversi pendaftar ke santri, penautan pivot `student_institution`, dan pembuatan akun wali dieksekusi secara sinkron dalam satu `DB::transaction`.
8. **Standar Runtime:** Basis runtime minimal PHP 8.3 dengan target PHP 8.5 dan framework Laravel 13.
9. **Autentikasi Headless Fortify:** Login, forgot password, reset password, dan 2FA ditangani oleh backend Fortify dengan halaman antarmuka React Neobrutalism.
10. **Pengelolaan Media Terpusat:** Gunakan `spatie/laravel-medialibrary` untuk penyimpanan dokumen PSB, foto santri, dan media CMS pada disk lokal hosting (`storage/app/public`).
11. **Filtering Aman:** Gunakan `spatie/laravel-query-builder` untuk endpoint listing data, dengan filter `institution_id` selalu diinjeksi paling awal sebelum filter dari pengguna.
12. **Validasi Keanggotaan Lembaga:** Middleware memeriksa apakah `current_institution_id` benar-benar terdaftar di tabel keanggotaan pengguna (`institution_user` dengan status `active`) sebelum mengizinkan akses ke rute aplikasi.
13. **Isolasi Rute Publik:** Subdomain landing page publik (`daraltauhid.com` dan `mis.daraltauhid.com`) tidak memiliki antarmuka login internal; akses diarahkan secara eksplisit ke `app.daraltauhid.com`.
