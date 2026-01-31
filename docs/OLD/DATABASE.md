# Struktur Database - Super App Dar Al Tauhid

## 1. Tabel Core & System

### 1.1 `institutions` - Data Lembaga

| Field          | Type    | Deskripsi                                     |
| -------------- | ------- | --------------------------------------------- |
| `id`           | bigint  | Primary key                                   |
| `code`         | varchar | Kode unik lembaga                             |
| `name`         | varchar | Nama lembaga                                  |
| `nickname`     | varchar | Nama panggilan/singkatan lembaga              |
| `no_statistic` | varchar | Nomor Statistik Pondok/Sekolah/Madrasah       |
| `npsn`         | varchar | Nomor Pokok Statistik Nasional                |
| `category`     | enum    | `pesantren`, `formal`, `non-formal`, `sosial` |
| `type`         | enum    | terlampir                                     |
| `is_internal`  | boolean | Lembaga Internal                              |
| `address`      | text    | Alamat lengkap                                |
| `logo_path`    | varchar | Path logo                                     |
| `website_url`  | varchar | URL website                                   |
| `phone`        | varchar | Nomor telepon                                 |
| `email`        | varchar | Email                                         |
| `is_active`    | boolean | Status aktif                                  |

> type: `yayasan`, `pondok`, `madrasah`, `smp`, `ma`, `mi`, `mts`, `slb`, `tk`, `mdta`, `lksa`
**Unique**: `code`
**Index**: `category`, `type`

---

### 1.2 `academic_years` - Tahun Ajaran

| Field        | Type    | Deskripsi           |
| ------------ | ------- | ------------------- |
| `id`         | bigint  | Primary key         |
| `name`       | varchar | Contoh: `2025/2026` |
| `is_active`  | boolean | Status aktif        |
| `start_date` | date    | Tanggal mulai       |
| `end_date`   | date    | Tanggal selesai     |

**Unique**: `name`
**Index**: `is_active`

---

### 1.3 `academic_periods` - Tahun Ajaran

| Field                 | Type    | Deskripsi                                       |
| -----------------     | ------- | ----------------------------------------------- |
| `id`                  | bigint  | Primary key                                     |
| `academic_year_id`    | bigint  | Relasi ke academic_years.id                     |
| `name`                | enum    | `Ganjil`, `Genap`                               |
| `is_active`           | boolean | Menandakan semester yang berjalan saat ini      |
| `start_date`          | date    | Harus di dalam rentang start_date tahun ajaran  |
| `end_date`            | date    | Harus di dalam rentang start_date tahun ajaran  |

**Foreign Key**: `academic_year_id` -> `academic_years.id`
**Unique**: `academic_year_id`, `name`

---

### 1.4 `users` - User Admin

| Field                       | Type      | Deskripsi                      |
| --------------------------- | --------- | ------------------------------ |
| `id`                        | bigint    | Primary key                    |
| `name`                      | varchar   | Nama                           |
| `email`                     | varchar   | Email (unique)                 |
| `email_verified_at`         | timestamp | Waktu verifikasi email         |
| `password`                  | varchar   | Password (hashed)              |
| `two_factor_secret`         | text      | Secret key 2FA (Fortify)       |
| `two_factor_recovery_codes` | text      | Kode recovery 2FA              |
| `two_factor_confirmed_at`   | timestamp | Waktu konfirmasi 2FA           |
| `phone_number`              | varchar   | NO WA                          |
| `remember_token`            | varchar   | Token remember me              |

**Unique**: `email`
**Index**: `email`
**Relasi**:
- `users` 1:N `student_parents` (User sebagai Wali Murid)
- `users` 1:N `employees` (User sebagai Pegawai)
- `users` 1:N `payments` (User sebagai Petugas)

---

### 1.5. `permissions` - by spatie

| Field        | Type      | Deskripsi                  |
| ------------ | --------- | -------------------------- |
| `id`         | bigint    | Primary key                |
| `name`       | varchar   | Nama permission            |
| `guard_name` | varchar   | Guard name (web, api, dll) |
| `created_at` | timestamp | Waktu dibuat               |
| `updated_at` | timestamp | Waktu diupdate             |

**Unique**: `name`, `guard_name`
**Index**: `name`

---

### 1.6. `roles` - by spatie

| Field              | Type      | Deskripsi                   |
| ------------------ | --------- | --------------------------- |
| `id`               | bigint    | Primary key                 |
| `institution_id`   | bigint    | ID Lembaga                  |
| `name`             | varchar   | Nama role                   |
| `guard_name`       | varchar   | Guard name (web, api, dll)  |
| `created_at`       | timestamp | Waktu dibuat                |
| `updated_at`       | timestamp | Waktu diupdate              |

**Unique**: `institution_id`, `name`, `guard_name`
**Relasi**:
- `institution_id` -> `institutions.id`

---

### 1.7. `model_has_permissions` - by spatie

| Field           | Type    | Deskripsi                      |
| --------------- | ------- | ------------------------------ |
| `permission_id` | bigint  | Foreign key ke permissions.id  |
| `model_type`    | varchar | Tipe model (App\\Models\\User) |
| `model_id`      | bigint  | ID model                       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `permission_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

### 1.8. `model_has_roles` - by spatie

| Field        | Type    | Deskripsi                      |
| ------------ | ------- | ------------------------------ |
| `role_id`    | bigint  | Foreign key ke roles.id        |
| `model_type` | varchar | Tipe model (App\\Models\\User) |
| `model_id`   | bigint  | ID model                       |

**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `role_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

### 1.9. `role_has_permissions` - by spatie

| Field           | Type   | Deskripsi                     |
| --------------- | ------ | ----------------------------- |
| `permission_id` | bigint | Foreign key ke permissions.id |
| `role_id`       | bigint | Foreign key ke roles.id       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Primary Key**: `permission_id`, `role_id`

## 2. Tabel Data Master (People)

### 2.1 `students` - Data Santri

| Field                 | Type    | Deskripsi                                                      |
| --------------------- | ------- | -------------------------------------------------------------- |
| `id`                  | bigint  | Primary key                                                    |
| `institution_id`      | FK      | Lembaga aktif saat ini                                         |
| `registration_number` | varchar | Nomor pendaftaran unik (format: `YYXXXX`)                      |
| `nisy`                | varchar | NIS Yayasan - Buku Induk (unique, permanen)                    |
| `nisn`                | varchar | Nomor Induk Siswa Nasional                                     |
| `nik`                 | varchar | NIK KTP (unique)                                               |
| `full_name`           | varchar | Nama lengkap                                                   |
| `gender`              | enum    | `L`, `P`                                                       |
| `place_of_birth`      | varchar | Tempat lahir                                                   |
| `date_of_birth`       | date    | Tanggal lahir                                                  |
| `child_number`        | int     | Anak ke-                                                       |
| `total_siblings`      | int     | Jumlah saudara kandung                                         |
| `address_street`      | text    | Alamat jalan                                                   |
| `village`             | varchar | Desa/Kelurahan                                                 |
| `district`            | varchar | Kecamatan                                                      |
| `city`                | varchar | Kabupaten/Kota                                                 |
| `province`            | varchar | Provinsi                                                       |
| `postal_code`         | varchar | Kode pos                                                       |
| `photo_path`          | varchar | Path foto                                                      |
| `status`              | enum    | `draft`, `verified`, `active`, `graduated`, `moved`, `dropped` |

**Relasi**:

- `institutions` 1:N `students` (lembaga utama/aktif)
- `students` 1:N `student_institutions` (multi-lembaga)

---

### 2.2 `student_institutions` - Keanggotaan Lembaga

> Tabel pivot untuk santri yang terdaftar di beberapa lembaga sekaligus (misal: Pondok + MTs + Diniyah)

| Field             | Type    | Deskripsi                                      |
| ----------------- | ------- | ---------------------------------------------- |
| `id`              | bigint  | Primary key                                    |
| `student_id`      | FK      | Referensi ke students                          |
| `institution_id`  | FK      | Referensi ke institutions                      |
| `nis`             | varchar | NIS untuk lembaga ini (unique per institution) |
| `enrolled_at`     | date    | Tanggal masuk ke lembaga                       |
| `graduated_at`    | date    | Tanggal kelulusan (nullable)                   |
| `graduation_year` | varchar | Tahun lulus, contoh: `2025` (nullable)         |
| `alumni_notes`    | text    | Catatan alumni (pekerjaan, kuliah, dll)        |
| `status`          | enum    | `active`, `graduated`, `moved`, `dropped`      |

**Relasi**:

- `students` 1:N `student_institutions`
- `institutions` 1:N `student_institutions`

---

### 2.3 `student_parents` - Data Orang Tua

| Field                 | Type    | Deskripsi                       |
| --------------------- | ------- | ------------------------------- |
| `id`                  | bigint  | Primary key                     |
| `student_id`          | FK      | Relasi ke students              |
| `user_id`             | FK      | Account Login (Nullable)        |
| `type`                | enum    | `father`, `mother`, `guardian`  |
| `name`                | varchar | Nama                            |
| `life_status`         | enum    | `alive`, `deceased`, `unknown`  |
| `nik`                 | varchar | NIK                             |
| `place_of_birth`      | varchar | Tempat lahir                    |
| `date_of_birth`       | date    | Tanggal lahir                   |
| `education`           | varchar | Pendidikan terakhir             |
| `pesantren_education` | varchar | Pendidikan pesantren (opsional) |
| `job`                 | varchar | Pekerjaan                       |
| `income`              | varchar | Rentang penghasilan             |
| `phone_number`        | varchar | Nomor telepon                   |

**Relasi**:

- `students` 1:N `student_parents`
- `users` 1:N `student_parents` (Satu User Wali bisa terhubung ke banyak data ortu/siswa di berbagai lembaga)

---

### 2.4 `student_documents` - Dokumen Upload

| Field           | Type    | Deskripsi                                       |
| --------------- | ------- | ----------------------------------------------- |
| `id`            | bigint  | Primary key                                     |
| `student_id`    | FK      | Relasi ke students                              |
| `type`          | varchar | `kk`, `akta`, `ijazah`, `foto`, `skhu`, `rapor` |
| `file_path`     | varchar | Path file di storage                            |
| `original_name` | varchar | Nama file asli                                  |
| `status`        | enum    | `pending`, `valid`, `invalid`                   |
| `notes`         | text    | Catatan jika invalid                            |

**Relasi**: `students` 1:N `student_documents`

---

### 2.5 `employees` - Data PTK

| Field               | Type          | Deskripsi                                                             |
| ------------------- | ------------- | --------------------------------------------------------------------- |
| `id`                | bigint        | Primary key                                                           |
| `institution_id`    | FK            | Lembaga                                                               |
| `user_id`           | FK            | Relasi ke users (1 User bisa memiliki banyak profile pegawai/kontrak) |
| `nip`               | varchar       | NIP                                                                   |
| `name`              | varchar       | Nama lengkap                                                          |
| `nik`               | varchar       | NIK                                                                   |
| `gender`            | enum          | `L`, `P`                                                              |
| `place_of_birth`    | varchar       | Tempat lahir                                                          |
| `date_of_birth`     | date          | Tanggal lahir                                                         |
| `address`           | text          | Alamat                                                                |
| `phone`             | varchar       | Nomor telepon                                                         |
| `email`             | varchar       | Email                                                                 |
| `position`          | varchar       | Jabatan                                                               |
| `employment_status` | enum          | `permanent`, `contract`, `honorary`                                   |
| `join_date`         | date          | Tanggal bergabung                                                     |
| `basic_salary`      | decimal(15,2) | Gaji pokok                                                            |
| `bank_name`         | varchar       | Nama bank                                                             |
| `bank_account`      | varchar       | Nomor rekening                                                        |
| `photo_path`        | varchar       | Path foto                                                             |
| `documents_path`    | json          | Path dokumen (SK, Ijazah)                                             |
| `is_active`         | boolean       | Status aktif                                                          |

---

## 3. Tabel PSB

### 3.1 `registrations` - Detail Pendaftaran

| Field                        | Type    | Deskripsi                                           |
| ---------------------------- | ------- | --------------------------------------------------- |
| `id`                         | bigint  | Primary key                                         |
| `student_id`                 | FK      | Relasi ke students                                  |
| `academic_year_id`           | FK      | Tahun ajaran                                        |
| `previous_school_level`      | varchar | Jenjang sekolah asal (SD, MI, SMP, MTs)             |
| `previous_school_name`       | varchar | Nama sekolah asal                                   |
| `previous_school_npsn`       | varchar | NPSN sekolah asal                                   |
| `previous_school_address`    | text    | Alamat sekolah asal                                 |
| `destination_institution_id` | FK      | Lembaga tujuan (Pondok/SMP/MA/MI)                   |
| `destination_class`          | varchar | Kelas tujuan (7, 10, dll)                           |
| `registration_track`         | varchar | Jalur: `reguler`, `prestasi`, `tahfidz`, `pindahan` |
| `funding_source`             | varchar | Sumber biaya: `orang_tua`, `beasiswa`, dll          |
| `registered_at`              | date    | Tanggal pendaftaran                                 |

**Relasi**: `students` 1:1 `registrations`

---

## 4. Tabel Keuangan

### 4.1 `financial_accounts` - Rekening/Dompet Kas

> Kita harus tahu uangnya "nongkrong" di mana. Apakah di Laci TU, di Brankas Bendahara, atau di BSI?

| Field            | Type          | Deskripsi                                      |
| ---------------- | ------------- | ---------------------------------------------- |
| `id`             | bigint        | Primary key                                    |
| `institution_id` | FK            | Pemilik rekening (Lembaga SMP / Yayasan Pusat) |
| `name`           | varchar       | Cth: "Kas Tunai TU", "Bank BSI", "Bank BJB"    |
| `account_number` | varchar       | No Rekening (jika bank), atau kosong           |
| `balance`        | decimal(15,2) | Saldo Saat Ini (Cache)                         |
| `is_active`      | boolean       | Aktif/Tidak                                    |
| `created_at`     | timestamp     | Waktu dibuat                                   |
| `updated_at`     | timestamp     | Waktu diupdate                                 |

> [!IMPORTANT]
> **Strategi Balance Caching:**
>
> - Kolom `balance` adalah **cache** dari `SUM(cash_mutations)` untuk performa dashboard.
> - Update via **Laravel Observer** (`CashMutationObserver`) saat ada mutasi baru.
> - Gunakan **DB Transaction** saat input mutasi untuk konsistensi data.
> - Sediakan fitur tersembunyi **"Recalculate Balance"** di Settings untuk reset manual jika ada selisih/bug.

---

### 4.2 `budget_categories` - Pos Anggaran / Chart of Account

> Label untuk setiap uang masuk/keluar agar laporan keuangan rapi.

| Field            | Type      | Deskripsi                                       |
| ---------------- | --------- | ----------------------------------------------- |
| `id`             | bigint    | Primary key                                     |
| `institution_id` | FK        | Lembaga pemilik kategori                        |
| `code`           | varchar   | Kode Akun (cth: 4.1 Pemasukan, 5.1 Pengeluaran) |
| `name`           | varchar   | Cth: "Belanja ATK", "Honor Guru", "Uang Makan"  |
| `type`           | enum      | `income` (Masuk), `expense` (Keluar)            |
| `created_at`     | timestamp | Waktu dibuat                                    |
| `updated_at`     | timestamp | Waktu diupdate                                  |

---

### 4.3 `fee_components` - Komponen Biaya

| Field              | Type          | Deskripsi                                         |
| ------------------ | ------------- | ------------------------------------------------- |
| `id`               | bigint        | Primary key                                       |
| `institution_id`   | FK            | Lembaga pemilik                                   |
| `academic_year_id` | FK            | Tahun ajaran                                      |
| `name`             | varchar       | Nama komponen (Pendaftaran, SPP, Gedung, Seragam) |
| `type`             | enum          | `yearly`, `monthly`, `once`                       |
| `amount`           | decimal(15,2) | Nominal                                           |
| `target_level`     | int           | Target Kelas (7, 8, 9). Null = Semua              |
| `target_gender`    | enum          | Target Gender (`L`, `P`). Null = Semua            |
| `is_active`        | boolean       | Status aktif                                      |

---

### 4.4 `bills` - Tagihan

| Field              | Type          | Deskripsi                                          |
| ------------------ | ------------- | -------------------------------------------------- |
| `id`               | bigint        | Primary key                                        |
| `student_id`       | FK            | Santri pemilik tagihan                             |
| `institution_id`   | FK            | Lembaga penerima tagihan                           |
| `fee_component_id` | FK            | Komponen biaya (opsional)                          |
| `period_month`     | int           | Bulan tagihan (untuk SPP bulanan)                  |
| `period_year`      | int           | Tahun tagihan                                      |
| `initial_amount`   | decimal(15,2) | Jumlah tagihan asli (sebelum diskon)               |
| `discount_amount`  | decimal(15,2) | Jumlah potongan                                    |
| `amount`           | decimal(15,2) | Tagihan akhir (`initial_amount - discount_amount`) |
| `remaining_amount` | decimal(15,2) | Sisa tagihan                                       |
| `status`           | enum          | `unpaid`, `partial`, `paid`, `cancelled`           |
| `description`      | text          | Rincian komponen                                   |
| `due_date`         | date          | Jatuh tempo                                        |

> **Auto-generated** saat santri mendaftar via `Student::generateBills()`

---

### 4.5 `payments` - Pembayaran

| Field                | Type          | Deskripsi                         |
| -------------------- | ------------- | --------------------------------- |
| `id`                 | bigint        | Primary key                       |
| `code`               | varchar       | Kode transaksi unik               |
| `student_id`         | FK            | Santri yang membayar              |
| `user_id`            | FK            | Petugas input                     |
| `amount`             | decimal(15,2) | Jumlah bayar                      |
| `payment_method`     | enum          | `cash`, `transfer`                |
| `payment_date`       | timestamp     | Tanggal & waktu transaksi         |
| `status`             | enum          | `pending`, `success`, `failed`    |
| `proof_file`         | varchar       | Bukti pembayaran (path)           |
| `notes`              | text          | Catatan                           |
| `payment_location`   | enum          | `PANITIA`, `LEMBAGA`              |
| `is_settled`         | boolean       | Sudah didistribusikan ke lembaga? |
| `verification_token` | varchar(64)   | Token verifikasi kwitansi         |

---

### 4.6 `expenses` - Pengeluaran Lembaga

| Field                | Type          | Deskripsi                             |
| -------------------- | ------------- | ------------------------------------- |
| `id`                 | bigint        | Primary key                           |
| `institution_id`     | FK            | Pengeluaran Lembaga mana?             |
| `budget_category_id` | FK            | Pos Anggaran (Misal: Listrik, ATK)    |
| `user_id`            | FK            | Siapa yang belanja/input              |
| `title`              | varchar       | Judul singkat: "Beli Kertas F4 5 Rim" |
| `description`        | text          | Detail (nullable)                     |
| `amount`             | decimal(15,2) | Jumlah pengeluaran                    |
| `transaction_date`   | date          | Tanggal transaksi                     |
| `payment_method`     | varchar       | Tunai / Transfer                      |
| `status`             | enum          | `pending`, `approved`, `rejected`     |
| `proof_file`         | varchar       | Foto Nota Toko (nullable)             |
| `created_at`         | timestamp     | Waktu dibuat                          |
| `updated_at`         | timestamp     | Waktu diupdate                        |

---

### 4.7 `incomes` - Pemasukan Non-Santri

> Untuk mencatat pemasukan selain dari pembayaran santri (hibah, donasi, dll)

| Field                | Type          | Deskripsi                                    |
| -------------------- | ------------- | -------------------------------------------- |
| `id`                 | bigint        | Primary key                                  |
| `institution_id`     | FK            | Lembaga penerima pemasukan                   |
| `budget_category_id` | FK            | Pos: Hibah/Donasi                            |
| `user_id`            | FK            | Penerima uang                                |
| `source_name`        | varchar       | Dari mana? (Misal: "Hamba Allah", "Pemprov") |
| `title`              | varchar       | "Sumbangan Pembangunan Masjid"               |
| `amount`             | decimal(15,2) | Jumlah pemasukan                             |
| `transaction_date`   | date          | Tanggal transaksi                            |
| `payment_method`     | varchar       | Tunai / Transfer                             |
| `notes`              | text          | Catatan (nullable)                           |
| `created_at`         | timestamp     | Waktu dibuat                                 |
| `updated_at`         | timestamp     | Waktu diupdate                               |

---

### 4.8 `payment_allocations` - Alokasi Pembayaran

> Tabel ini SANGAT PENTING. 1x bayar (misal Rp 1.000.000) bisa untuk:
>
> - SPP Januari (200rb)
> - SPP Februari (200rb)
> - Uang Gedung - Cicilan (600rb)

| Field        | Type          | Deskripsi                          |
| ------------ | ------------- | ---------------------------------- |
| `id`         | bigint        | Primary key                        |
| `payment_id` | FK            | Relasi ke payments                 |
| `bill_id`    | FK            | Relasi ke bills (SPP Jan / Gedung) |
| `amount`     | decimal(15,2) | Alokasi dana untuk tagihan ini     |

---

### 4.9 `cash_mutations` - Arus Kas / Jurnal

> Inilah tabel inti Arus Kas. Setiap pergerakan uang (kecil maupun besar) tercatat di sini.

| Field                  | Type          | Deskripsi                                  |
| ---------------------- | ------------- | ------------------------------------------ |
| `id`                   | bigint        | Primary key                                |
| `institution_id`       | FK            | Lembaga lembaga (untuk indexing cepat)     |
| `financial_account_id` | FK            | Rekening mana yang berubah? (Kas TU / BSI) |
| `user_id`              | FK            | Siapa yang input (Bendahara)?              |
| `amount`               | decimal(15,2) | Jumlah uang                                |
| `type`                 | enum          | `in` (Debet/Masuk), `out` (Kredit/Keluar)  |
| `transaction_date`     | date          | Tanggal transaksi                          |
| `budget_category_id`   | FK            | Masuk pos anggaran mana? (Nullable)        |
| `description`          | text          | Keterangan detail ("Beli spidol 5 pack")   |
| `proof_file`           | varchar       | Foto nota/kuitansi belanja                 |
| `sourceable_id`        | bigint        | Polymorphic ID (Kunci Integrasi)           |
| `sourceable_type`      | string        | Polymorphic Type (Kunci Integrasi)         |
| `balance_after`        | decimal(15,2) | Saldo setelah transaksi ini (Snapshot)     |
| `created_at`           | timestamp     | Waktu dibuat                               |
| `updated_at`           | timestamp     | Waktu diupdate                             |

> **Polymorphic Relation**: `sourceable` bisa merujuk ke `payments`, `expenses`, `incomes`, `fund_transfers`, dll.

---

### 4.10 `student_wallets` - Saldo Santri

| Field          | Type          | Deskripsi      |
| -------------- | ------------- | -------------- |
| `id`           | bigint        | Primary key    |
| `student_id`   | FK            | Santri         |
| `balance`      | decimal(15,2) | Saldo saat ini |
| `last_updated` | timestamp     | Waktu update   |

---

### 4.11 `fund_transfers` - Distribusi Dana

| Field             | Type          | Deskripsi                                      |
| ----------------- | ------------- | ---------------------------------------------- |
| `id`              | bigint        | Primary key                                    |
| `institution_id`  | FK            | Lembaga tujuan transfer                        |
| `student_id`      | FK            | Santri terkait                                 |
| `bill_id`         | FK            | Tagihan yang dibayar                           |
| `payment_id`      | FK            | Transaksi asal dana                            |
| `user_id`         | FK            | Pembuat request                                |
| `amount`          | decimal(15,2) | Jumlah transfer                                |
| `transfer_date`   | date          | Tanggal transfer                               |
| `transfer_method` | varchar       | Metode: `cash`, `bank_transfer`                |
| `notes`           | text          | Catatan                                        |
| `status`          | enum          | `PENDING`, `APPROVED`, `COMPLETED`, `REJECTED` |
| `approved_at`     | timestamp     | Waktu approval                                 |
| `approved_by`     | FK            | User yang approve                              |
| `received_at`     | timestamp     | Waktu terima                                   |
| `received_by`     | FK            | User yang terima                               |

> **Flow**: Distribusi dana dari Panitia Pusat ke Lembaga

---

## 5. Tabel Akademik

### 5.1 `classrooms` - Kelas/Rombel

| Field                 | Type    | Deskripsi                      |
| --------------------- | ------- | ------------------------------ |
| `id`                  | bigint  | Primary key                    |
| `institution_id`      | FK      | Lembaga                        |
| `academic_year_id`    | FK      | Tahun ajaran                   |
| `name`                | varchar | Nama kelas (7A, 10 IPA 1, dll) |
| `homeroom_teacher_id` | FK      | Wali kelas                     |

---

### 5.2 `student_classes` - Mapping Siswa-Kelas

| Field          | Type    | Deskripsi         |
| -------------- | ------- | ----------------- |
| `id`           | bigint  | Primary key       |
| `student_id`   | FK      | Santri            |
| `classroom_id` | FK      | Kelas             |
| `status`       | varchar | `Active`, `Moved` |

---

### 5.3 `subjects` - Mata Pelajaran

| Field            | Type    | Deskripsi   |
| ---------------- | ------- | ----------- |
| `id`             | bigint  | Primary key |
| `institution_id` | FK      | Lembaga     |
| `name`           | varchar | Nama mapel  |
| `code`           | varchar | Kode mapel  |

---

### 5.4 `schedules` - Jadwal Pelajaran

| Field          | Type    | Deskripsi           |
| -------------- | ------- | ------------------- |
| `id`           | bigint  | Primary key         |
| `classroom_id` | FK      | Kelas               |
| `subject_id`   | FK      | Mata pelajaran      |
| `employee_id`  | FK      | Guru pengampu       |
| `day`          | varchar | Hari (Senin-Minggu) |
| `start_time`   | time    | Jam mulai           |
| `end_time`     | time    | Jam selesai         |

---

## 6. Tabel Presensi & Kesantrian

### 6.1 `student_attendances` - Presensi Santri

| Field        | Type      | Deskripsi                                    |
| ------------ | --------- | -------------------------------------------- |
| `id`         | bigint    | Primary key                                  |
| `student_id` | FK        | Santri                                       |
| `date`       | date      | Tanggal                                      |
| `status`     | enum      | `Present`, `Late`, `Alpha`, `Sick`, `Permit` |
| `method`     | varchar   | `Manual`, `Fingerprint`, `FaceID`            |
| `time_in`    | time      | Jam masuk                                    |
| `time_out`   | time      | Jam keluar                                   |
| `created_at` | timestamp | Waktu dibuat                                 |
| `updated_at` | timestamp | Waktu diupdate                               |

---

### 6.2 `employee_attendances` - Presensi GTK

| Field          | Type     | Deskripsi                                    |
| -------------- | -------- | -------------------------------------------- |
| `id`           | bigint   | Primary key                                  |
| `employee_id`  | FK       | Pegawai                                      |
| `date`         | date     | Tanggal                                      |
| `time_in`      | datetime | Jam masuk                                    |
| `time_out`     | datetime | Jam keluar                                   |
| `latitude_in`  | decimal  | Latitude (GPS)                               |
| `longitude_in` | decimal  | Longitude (GPS)                              |
| `status`       | enum     | `Present`, `Late`, `Alpha`, `Sick`, `Permit` |

---

### 6.3 `student_permissions` - Perizinan Santri

| Field        | Type     | Deskripsi             |
| ------------ | -------- | --------------------- |
| `id`         | bigint   | Primary key           |
| `student_id` | FK       | Santri                |
| `type`       | varchar  | `Sick`, `Permit`      |
| `start_date` | datetime | Tanggal mulai         |
| `end_date`   | datetime | Tanggal selesai       |
| `reason`     | text     | Alasan                |
| `status`     | varchar  | `Pending`, `Approved` |

---

### 6.4 `violations` - Pelanggaran/Takzir

| Field            | Type    | Deskripsi        |
| ---------------- | ------- | ---------------- |
| `id`             | bigint  | Primary key      |
| `student_id`     | FK      | Santri           |
| `date`           | date    | Tanggal          |
| `violation_name` | varchar | Nama pelanggaran |
| `points`         | integer | Poin pelanggaran |
| `punishment`     | varchar | Hukuman/Takzir   |

---

## 7. Tabel Tahfidz & Penilaian

### 7.1 `tahfidz_records` - Catatan Hafalan

| Field         | Type    | Deskripsi          |
| ------------- | ------- | ------------------ |
| `id`          | bigint  | Primary key        |
| `student_id`  | FK      | Santri             |
| `date`        | date    | Tanggal            |
| `surah_start` | varchar | Surah awal         |
| `ayat_start`  | integer | Ayat awal          |
| `surah_end`   | varchar | Surah akhir        |
| `ayat_end`    | integer | Ayat akhir         |
| `quality`     | varchar | `Lancar`, `Kurang` |
| `teacher_id`  | FK      | Guru penguji       |

---

### 7.2 `assessments` - Nilai Akademik

| Field              | Type    | Deskripsi          |
| ------------------ | ------- | ------------------ |
| `id`               | bigint  | Primary key        |
| `student_class_id` | FK      | Relasi siswa-kelas |
| `subject_id`       | FK      | Mata pelajaran     |
| `type`             | varchar | `UH`, `UTS`, `UAS` |
| `score`            | integer | Nilai              |

---

## 8. Tabel Payroll

### 8.1 `payrolls` - Penggajian

| Field              | Type    | Deskripsi             |
| ------------------ | ------- | --------------------- |
| `id`               | bigint  | Primary key           |
| `employee_id`      | FK      | Pegawai               |
| `month`            | integer | Bulan                 |
| `year`             | integer | Tahun                 |
| `attendance_count` | integer | Jumlah kehadiran      |
| `base_salary`      | decimal | Gaji pokok            |
| `total_allowance`  | decimal | Total tunjangan       |
| `total_deduction`  | decimal | Total potongan        |
| `net_salary`       | decimal | Gaji bersih           |
| `details`          | json    | Rincian komponen gaji |
| `status`           | varchar | `Draft`, `Paid`       |

> **Snapshot System**: Data gaji disimpan statis setelah di-generate (tidak berubah meski data master berubah)

---

## 9. Tabel Modul Surat Menyurat & Arsip

### 9.1 `archive_classifications` - Klasifikasi Arsip (JRA)

| Field                    | Type    | Deskripsi                                 |
| ------------------------ | ------- | ----------------------------------------- |
| `id`                     | bigint  | Primary key                               |
| `institution_id`         | FK      | Lembaga pemilik klasifikasi               |
| `code`                   | varchar | Kode Klasifikasi (cth: KP.01, KU.02)      |
| `name`                   | varchar | Nama (cth: Surat Tugas, Laporan Keuangan) |
| `description`            | text    | Deskripsi detail                          |
| `retention_period`       | int     | Masa retensi aktif (dalam tahun)          |
| `action_after_retention` | enum    | `destroy`, `permanent`, `review`          |
| `is_active`              | boolean | Status aktif                              |

---

### 9.2 `letters` - Data Surat (Masuk & Keluar)

| Field               | Type        | Deskripsi                                                 |
| ------------------- | ----------- | --------------------------------------------------------- |
| `id`                | bigint      | Primary key                                               |
| `institution_id`    | FK          | Lembaga pemilik surat                                     |
| `classification_id` | FK          | Relasi ke Klasifikasi (untuk penomoran/retensi)           |
| `creator_id`        | FK          | User yang menginput/membuat surat                         |
| `type`              | enum        | `incoming` (Masuk), `outgoing` (Keluar)                   |
| `reference_number`  | varchar     | Nomor Surat (Manual utk Masuk, Auto utk Keluar)           |
| `agenda_number`     | varchar     | Nomor Agenda Buku (untuk surat masuk)                     |
| `date`              | date        | Tanggal surat                                             |
| `received_date`     | date        | Tanggal diterima (khusus surat masuk)                     |
| `sender`            | varchar     | Pengirim (Instansi Luar / Unit Internal)                  |
| `recipient`         | varchar     | Penerima (Kepada Siapa)                                   |
| `subject`           | varchar     | Perihal surat                                             |
| `description`       | text        | Ringkasan isi surat                                       |
| `priority`          | enum        | `normal`, `segera`, `sangat_segera`                       |
| `confidentiality`   | enum        | `biasa`, `rahasia`, `sangat_rahasia`                      |
| `file_path`         | varchar     | Path file di Google Drive                                 |
| `file_id`           | varchar     | Google Drive File ID (untuk akses API cepat)              |
| `status`            | enum        | `draft`, `approval_process`, `signed`, `sent`, `archived` |
| `verify_hash`       | varchar(64) | Token unik untuk QR Code validasi (Surat Keluar)          |
| `retention_date`    | date        | Tanggal kadaluarsa arsip (Auto-calc dari JRA)             |
| `created_at`        | timestamp   | Waktu dibuat                                              |
| `updated_at`        | timestamp   | Waktu diupdate                                            |

> **Strategi Indexing (Smart Search):**
>
> - Index pada `subject` dan `reference_number` untuk pencarian cepat.
> - Index pada `type` dan `date` untuk filter dashboard.

---

### 9.3 `dispositions` - Disposisi Digital

| Field            | Type      | Deskripsi                                      |
| ---------------- | --------- | ---------------------------------------------- |
| `id`             | bigint    | Primary key                                    |
| `letter_id`      | FK        | Relasi ke surat yang didisposisikan            |
| `parent_id`      | FK        | ID Disposisi atasan (Null jika disposisi awal) |
| `sender_id`      | FK        | User pengirim disposisi (Pimpinan)             |
| `recipient_id`   | FK        | User penerima (Bawahan/Staf)                   |
| `instruction`    | varchar   | Cth: "Tindak Lanjuti", "Hadiri", "Arsipkan"    |
| `notes`          | text      | Catatan tambahan pimpinan                      |
| `deadline`       | date      | Batas waktu penyelesaian (opsional)            |
| `status`         | enum      | `unread`, `read`, `in_progress`, `completed`   |
| `read_at`        | timestamp | Kapan dibaca oleh penerima                     |
| `completed_at`   | timestamp | Kapan diselesaikan                             |
| `follow_up_note` | text      | Laporan bawahan setelah selesai                |

> **Logic Notifikasi:**
>
> - `created` → Trigger WA Notif ke `recipient_id`.
> - `completed` → Trigger WA Notif balik ke `sender_id`.

---

### 9.4 `letter_approvals` - Alur Persetujuan (Paraf)

| Field         | Type      | Deskripsi                               |
| ------------- | --------- | --------------------------------------- |
| `id`          | bigint    | Primary key                             |
| `letter_id`   | FK        | Relasi ke surat keluar (status draft)   |
| `approver_id` | FK        | User yang harus menyetujui (Kabag/Waka) |
| `order`       | int       | Urutan persetujuan (1, 2, 3)            |
| `status`      | enum      | `pending`, `approved`, `rejected`       |
| `comments`    | text      | Catatan revisi jika ditolak             |
| `approved_at` | timestamp | Waktu persetujuan                       |

---

### 9.5 `letter_templates` - Template Surat

| Field            | Type     | Deskripsi                                        |
| ---------------- | -------- | ------------------------------------------------ |
| `id`             | bigint   | Primary key                                      |
| `institution_id` | FK       | Lembaga pemilik template                         |
| `name`           | varchar  | Nama (cth: "Surat Keterangan Aktif", "Undangan") |
| `content`        | longtext | Struktur HTML/Blade template                     |
| `variables`      | json     | Daftar variabel (cth: ["nama", "nis", "kelas"])  |
| `is_active`      | boolean  | Status aktif                                     |

---

### 9.6 `mail_logs` - Audit Trail Arsip

| Field        | Type      | Deskripsi                             |
| ------------ | --------- | ------------------------------------- |
| `id`         | bigint    | Primary key                           |
| `letter_id`  | FK        | Surat yang diakses                    |
| `user_id`    | FK        | Siapa yang mengakses                  |
| `action`     | varchar   | `view`, `download`, `print`, `delete` |
| `ip_address` | varchar   | Alamat IP user                        |
| `user_agent` | text      | Info browser/device                   |
| `created_at` | timestamp | Waktu akses                           |

---

## 10. Tabel Wilayah (Regional)

### 10.1 `provinces` - Provinsi

| Field  | Type    | Deskripsi       |
| ------ | ------- | --------------- |
| `id`   | bigint  | Primary key     |
| `code` | char(2) | Kode Wilayah    |
| `name` | varchar | Nama Provinsi   |
| `meta` | text    | Metadata (JSON) |

---

### 10.2 `cities` - Kabupaten/Kota

| Field           | Type    | Deskripsi            |
| --------------- | ------- | -------------------- |
| `id`            | bigint  | Primary key          |
| `province_code` | char(2) | FK ke provinces.code |
| `code`          | char(4) | Kode Wilayah         |
| `name`          | varchar | Nama Kabupaten/Kota  |
| `meta`          | text    | Metadata (JSON)      |

---

### 10.3 `districts` - Kecamatan

| Field       | Type    | Deskripsi         |
| ----------- | ------- | ----------------- |
| `id`        | bigint  | Primary key       |
| `city_code` | char(4) | FK ke cities.code |
| `code`      | char(7) | Kode Wilayah      |
| `name`      | varchar | Nama Kecamatan    |
| `meta`      | text    | Metadata (JSON)   |

---

### 10.4 `villages` - Desa/Kelurahan

| Field           | Type     | Deskripsi            |
| --------------- | -------- | -------------------- |
| `id`            | bigint   | Primary key          |
| `district_code` | char(7)  | FK ke districts.code |
| `code`          | char(10) | Kode Wilayah         |
| `name`          | varchar  | Nama Desa/Kelurahan  |
| `meta`          | text     | Metadata (JSON)      |

---

## Enum Reference

| Enum                          | Values                                             |
| ----------------------------- | -------------------------------------------------- |
| `semester_enum`               | Ganjil, Genap                                      |
| `gender_enum`                 | L (Laki-laki), P (Perempuan)                       |
| `student_status_enum`         | draft, verified, active, graduated, moved, dropped |
| `parent_type_enum`            | father, mother, guardian                           |
| `life_status_enum`            | alive, deceased, unknown                           |
| `doc_status_enum`             | pending, valid, invalid                            |
| `employment_status_enum`      | permanent, contract, honorary                      |
| `fee_type_enum`               | yearly, monthly, once                              |
| `target_gender_enum`          | L (Laki-laki), P (Perempuan)                       |
| `bill_status_enum`            | unpaid, partial, paid, cancelled                   |
| `payment_method_enum`         | cash, transfer                                     |
| `payment_status_enum`         | pending, success, failed                           |
| `payment_location_enum`       | PANITIA, LEMBAGA                                   |
| `fund_transfer_status_enum`   | PENDING, APPROVED, COMPLETED, REJECTED             |
| `budget_category_type_enum`   | income, expense                                    |
| `cash_mutation_type_enum`     | in (Debet/Masuk), out (Kredit/Keluar)              |
| `expense_status_enum`         | pending, approved, rejected                        |
| `attendance_status_enum`      | Present, Late, Alpha, Sick, Permit                 |
| `retention_action_enum`       | destroy, permanent, review                         |
| `letter_type_enum`            | incoming, outgoing                                 |
| `letter_priority_enum`        | normal, segera, sangat_segera                      |
| `letter_confidentiality_enum` | biasa, rahasia, sangat_rahasia                     |
| `letter_status_enum`          | draft, approval_process, signed, sent, archived    |
| `disposition_status_enum`     | unread, read, in_progress, completed               |
| `approval_status_enum`        | pending, approved, rejected                        |

---

## Table Groups

| Group                     | Tables                                                                                                                                                          |
| ------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Core**                  | institutions, academic_years, users                                                                                                                             |
| **People**                | students, student_parents, student_documents, employees                                                                                                         |
| **PSB**                   | registrations                                                                                                                                                   |
| **Finance**               | fee_components, bills, payments, payment_allocations, student_wallets, fund_transfers, financial_accounts, budget_categories, cash_mutations, expenses, incomes |
| **Academic**              | classrooms, student_classes, subjects, schedules                                                                                                                |
| **Presence & Discipline** | student_attendances, employee_attendances, student_permissions, violations                                                                                      |
| **Tahfidz & Grades**      | tahfidz_records, assessments                                                                                                                                    |
| **HR & Payroll**          | payrolls                                                                                                                                                        |
| **Surat & Arsip**         | archive_classifications, letters, dispositions, letter_approvals, letter_templates, mail_logs                                                                   |
| **Regional**              | provinces, cities, districts, villages                                                                                                                          |

---

_Dokumentasi ini terakhir diperbarui pada: Januari 2026_
