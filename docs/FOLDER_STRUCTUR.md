# Folder Structure

## 1. Struktur Folder Backend

Dibangun secara modular di root

```text
super-app/
├── app/                  <-- Kode Global/Framework
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── Modules/              <-- DISINI KEKUATANNYA (8 Modul Anda)
│   ├── Institution/
│   ├── Auth/
│   ├── HumanResource/
│   ├── Facility/
│   ├── Admission/
│   ├── Academic/
│   ├── Finance/
│   └── Publishing/
├── composer.json
└── ...
```

### Bedah Anatomi Satu Modul (Contoh: Institution)

```text
Modules/Institution/
├── Config/               <-- Konfigurasi khusus modul (misal: passing grade)
├── Model/             <-- (Atau Models) Model Eloquent
│   ├── Student.php
│   ├── Classroom.php
│   └── Subject.php
├── Http/
│   ├── Controllers/      <-- Logika Request/Response
│   └── Requests/         <-- Form Request Validation
├── Providers/            <-- Service Provider (Booting modul)
│   ├── AcademicServiceProvider.php
│   └── RouteServiceProvider.php
├── Routes/
│   ├── api.php           <-- API Routes
│   └── web.php           <-- Web Routes
├── Services/             <-- LOGIKA BISNIS (Penting!)
│   ├── SchedulingService.php
│   └── GradingService.php
└── Tests/                <-- Unit/Feature Test khusus modul ini
```

## 3. Penjelasan Folder Penting & Aturan Main

Agar arsitektur tetap bersih, terapkan aturan ketat pada folder-folder berikut:

### A. Entities (atau Models)

Berisi Model Eloquent.

- Aturan: Model di sini JANGAN memanggil Model dari modul lain secara langsung (misal: Student hasMany Finance\Invoice). Gunakan Interface atau Service jika butuh data lintas modul.
- Alasan: Agar jika modul Finance dicopot/diubah, modul Academic tidak error (Loose Coupling).

### B. Services (Service Layer)

Ini wajib ada untuk agar Controller tetap kurus (Skinny Controller).

- Contoh: AdmissionService.php.
- Di dalamnya ada fungsi registerNewStudent(). Fungsi ini yang melakukan:
  - Simpan data siswa.
  - Upload berkas.
  - Panggil FinanceService untuk generate tagihan.

### C. Database/Migrations

Setiap modul punya folder migrasinya sendiri. Saat Anda menjalankan php artisan module:migrate, Laravel akan menjalankan migrasi Institution dulu, lalu Auth, Human Resources, dst (sesuai urutan prioritas yang kita bahas sebelumnya).

## 2. Struktur Folder Frontend

Semua Pages React tetap di resources/js/Pages/, tapi dikelompokkan folder:

```text
super-app/
├── resources/
│   ├── js/
│   │   ├── Components/      <-- Komponen Global (Button, Input)
│   │   ├── Layouts/         <-- Layout Utama
│   │   └── Pages/
│   │       ├── Auth/        <-- Login/Register bawaan
│   │       ├── Institution/        <-- BUAT FOLDER INI (Untuk Modul Institution)
│   │       │   └── Dashboard.jsx
│   │       ├── Academic/    <-- Nanti buat ini
│   │       ├── Finance/     <-- Nanti buat ini
│   │       └── ...
```
