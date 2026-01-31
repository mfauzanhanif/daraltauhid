# Folder Structure

Berikut struktur folder proyek dalam format yang mudah dibaca.

```text
/daraltauhid-app
├── app/
│   ├── Console/                           # Scheduled commands (cron jobs cPanel)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                       # Login/Logout Controller
│   │   │   ├── Public/                     # Controller untuk Landing Page (Non-Volt)
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   └── DocumentController.php  <-- (Secure Stream Google Drive)
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── IdentifyInstitution.php    <-- (Cek domain: 'manuscirebon.com' punya ID berapa?)
│   │   │   └── CheckRole.php               # Middleware Role Spatie
│   │   └── Requests/                       # Form Request Validation (jika tidak pakai Volt)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Institution.php                # Tabel Lembaga (MI, MA, Pondok)
│   │   ├── Student.php                    # Tabel Santri
│   │   ├── StudentDocument.php            # Tabel Link File (menyimpan path Google Drive)
│   │   └── Finance/                       # Grouping model keuangan
│   │       ├── Invoice.php
│   │       └── Payment.php
│   └── Providers/
│       └── AppServiceProvider.php         # Booting konfigurasi global
├── config/
│   ├── app.php
│   ├── filesystems.php                    <-- (Config Google Drive Disk disini)
│   ├── permission.php                     # Config Spatie Permission
│   └── domain.php                         # Custom config mapping domain -> ID Lembaga
├── database/
│   ├── migrations/                        # Struktur Database
│   └── seeders/                           # Data Dummy awal (Admin User, Daftar Lembaga)
├── public/
│   ├── build/                             # Hasil compile Vite (CSS/JS) --> Upload ini ke Hosting
│   ├── images/                            # Aset statis umum (Logo Yayasan default)
│   ├── index.php                          # Entry point
│   └── robots.txt
├── resources/
│   ├── css/
│   │   └── app.css                        # @tailwind base; @tailwind components;
│   ├── js/
│   │   └── app.js                         # import 'flowbite';
│   ├── views/
│   │   ├── components/                    # BLADE COMPONENTS (Layout & UI Wrapper)
│   │   │   ├── layouts/
│   │   │   │   ├── admin.blade.php        # Layout Dashboard (Sidebar Flowbite)
│   │   │   │   ├── public.blade.php       # Layout Landing Page (Navbar biasa)
│   │   │   │   └── auth.blade.php         # Layout Login
│   │   │   ├── ui/                        # Komponen UI Reusable (non-logic)
│   │   │   │   ├── card.blade.php
│   │   │   │   ├── badge.blade.php
│   │   │   │   └── alert.blade.php
│   │   │   └── forms/                     # Komponen Form Flowbite
│   │   │       ├── input.blade.php
│   │   │       └── select.blade.php
│   │   ├── livewire/                      # VOLT COMPONENTS (Logic + View jadi satu)
│   │   │   ├── auth/                      # Halaman Login/Register
│   │   │   │   └── login.blade.php
│   │   │   ├── pages/                     # FULL PAGE COMPONENTS (Halaman Admin)
│   │   │   │   ├── dashboard.blade.php
│   │   │   │   ├── students/
│   │   │   │   │   ├── index.blade.php    # Tabel Data Santri
│   │   │   │   │   ├── create.blade.php   # Form Tambah (Upload Google Drive disini)
│   │   │   │   │   └── edit.blade.php
│   │   │   │   ├── finance/
│   │   │   │   │   ├── spp.blade.php
│   │   │   │   │   └── report.blade.php
│   │   │   │   └── settings/
│   │   │   │       └── institution.blade.php
│   │   │   └── widgets/                    # SMALL COMPONENTS (Pecahan UI logic)
│   │   │       ├── stats-card.blade.php   # "Total Santri: 500"
│   │   │       ├── recent-trx.blade.php   # "Pembayaran Terakhir"
│   │   │       └── upload-zone.blade.php  # Komponen Upload Drag-drop
│   │   └── public/                        # VIEWS LANDING PAGE (Non-Volt/Standard Blade)
│   │       ├── home.blade.php
│   │       ├── news.blade.php
│   │       └── themes/                     # Folder tema per lembaga (Opsional)
│   │           ├── mi/                     # Tema khusus MI
│   │           └── ma/                     # Tema khusus MA
│   └── lang/                               # Bahasa Indonesia (id)
├── routes/
│   ├── web.php                            # Routing utama (Multi-Domain logic disini)
│   ├── console.php
│   └── auth.php                           # Rute otentikasi
├── storage/                               # Logs & Cache (Bukan untuk simpan KTP/KK)
├── .env                                   # Kredensial Database & Google Drive API
├── composer.json                          # Daftar Library
├── package.json                           # Daftar dependency JS (Flowbite, Tailwind)
├── tailwind.config.js                     # Config Tailwind & Plugin Flowbite
└── vite.config.js                         # Config Build System
```
