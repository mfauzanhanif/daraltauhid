# Tabel Core & System

## 1.1 `institutions` - Data Lembaga

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
| `is_active`    | boolean   | Status aktif                                  |
| `deleted_at`   | timestamp | Soft delete                                   |

> type: `yayasan`, `pondok`, `madrasah`, `smp`, `ma`, `mi`, `mts`, `slb`, `tk`, `mdta`, `lksa`
**Unique**: `code`
**Index**: `category`, `type`

---

## 1.2 `academic_years` - Tahun Ajaran

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

## 1.3 `academic_periods` - Tahun Ajaran

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

## 1.4 `users` - User Admin

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
| `deleted_at`                | timestamp | Soft delete                    |

**Unique**: `email`
**Index**: `email`
**Relasi**:
- `users` 1:N `student_parents` (User sebagai Wali Murid)
- `users` 1:N `employees` (User sebagai Pegawai)
- `users` 1:N `payments` (User sebagai Petugas)

---

## 1.5. `permissions` - by spatie

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

## 1.6. `roles` - by spatie

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

## 1.7. `model_has_permissions` - by spatie

| Field           | Type    | Deskripsi                      |
| --------------- | ------- | ------------------------------ |
| `permission_id` | bigint  | Foreign key ke permissions.id  |
| `model_type`    | varchar | Tipe model (App\\Models\\User) |
| `model_id`      | bigint  | ID model                       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `permission_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

## 1.8. `model_has_roles` - by spatie

| Field        | Type    | Deskripsi                      |
| ------------ | ------- | ------------------------------ |
| `role_id`    | bigint  | Foreign key ke roles.id        |
| `model_type` | varchar | Tipe model (App\\Models\\User) |
| `model_id`   | bigint  | ID model                       |

**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `role_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

## 1.9. `role_has_permissions` - by spatie

| Field           | Type   | Deskripsi                     |
| --------------- | ------ | ----------------------------- |
| `permission_id` | bigint | Foreign key ke permissions.id |
| `role_id`       | bigint | Foreign key ke roles.id       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Primary Key**: `permission_id`, `role_id`