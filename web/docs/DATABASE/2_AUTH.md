# 2. AUTH DATABASES

## 2.1 `users` - User Admin

| Field                       | Type      | Deskripsi                |
| --------------------------- | --------- | ------------------------ |
| `id`                        | BIGINT    | Primary key              |
| `name`                      | VARCHAR   | Nama                     |
| `email`                     | VARCHAR   | Email (unique)           |
| `email_verified_at`         | TIMESTAMP | Waktu verifikasi email   |
| `password`                  | VARCHAR   | Password (hashed)        |
| `two_factor_secret`         | TEXT      | Secret key 2FA (Fortify) |
| `two_factor_recovery_codes` | TEXT      | Kode recovery 2FA        |
| `two_factor_confirmed_at`   | TIMESTAMP | Waktu konfirmasi 2FA     |
| `phone_number`              | VARCHAR   | NO WA                    |
| `remember_token`            | VARCHAR   | Token remember me        |
| `deleted_at`                | TIMESTAMP | Soft delete              |

**Unique**: `email`  
**Index**: `email`  
**Primary Key**: `id`  
**Relasi**:

- `users` 1:N `student_parents` (User sebagai Wali Murid)
- `users` 1:N `employees` (User sebagai Pegawai)
- `users` 1:N `payments` (User sebagai Petugas)

---

## 2.2. `permissions` - by spatie

| Field        | Type      | Deskripsi                  |
| ------------ | --------- | -------------------------- |
| `id`         | BIGINT    | Primary key                |
| `name`       | VARCHAR   | Nama permission            |
| `guard_name` | VARCHAR   | Guard name (web, api, dll) |
| `created_at` | TIMESTAMP | Waktu dibuat               |
| `updated_at` | TIMESTAMP | Waktu diupdate             |

**Unique**: `name`, `guard_name`  
**Index**: `name`  
**Primary Key**: `id`

---

## 2.3. `roles` - by spatie

| Field            | Type      | Deskripsi                  |
| ---------------- | --------- | -------------------------- |
| `id`             | BIGINT    | Primary key                |
| `institution_id` | BIGINT    | ID Lembaga                 |
| `name`           | VARCHAR   | Nama role                  |
| `guard_name`     | VARCHAR   | Guard name (web, api, dll) |
| `created_at`     | TIMESTAMP | Waktu dibuat               |
| `updated_at`     | TIMESTAMP | Waktu diupdate             |

**Unique**: `institution_id`, `name`, `guard_name`  
**Index**: `institution_id`, `name`  
**Primary Key**: `id`  
**Foreign Key**: `institution_id` -> `institutions.id`  
**Relasi**:

- `institution_id` -> `institutions.id`

---

## 2.4. `model_has_permissions` - by spatie

| Field           | Type    | Deskripsi                      |
| --------------- | ------- | ------------------------------ |
| `permission_id` | BIGINT  | Foreign key ke permissions.id  |
| `model_type`    | VARCHAR | Tipe model (App\\Models\\User) |
| `model_id`      | BIGINT  | ID model                       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `permission_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

## 2.5. `model_has_roles` - by spatie

| Field        | Type    | Deskripsi                      |
| ------------ | ------- | ------------------------------ |
| `role_id`    | BIGINT  | Foreign key ke roles.id        |
| `model_type` | VARCHAR | Tipe model (App\\Models\\User) |
| `model_id`   | BIGINT  | ID model                       |

**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Index**: `model_id`, `model_type`  
**Primary Key**: `role_id`, `model_id`, `model_type` (atau dengan team_foreign_key jika teams enabled)

---

## 2.6. `role_has_permissions` - by spatie

| Field           | Type   | Deskripsi                     |
| --------------- | ------ | ----------------------------- |
| `permission_id` | BIGINT | Foreign key ke permissions.id |
| `role_id`       | BIGINT | Foreign key ke roles.id       |

**Foreign Key**: `permission_id` -> `permissions.id` (cascade delete)  
**Foreign Key**: `role_id` -> `roles.id` (cascade delete)  
**Primary Key**: `permission_id`, `role_id`
