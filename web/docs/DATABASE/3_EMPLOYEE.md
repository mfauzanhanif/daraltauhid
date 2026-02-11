# 3. EMPLOYEE DATABASES

## 3.1. Tabel `employees`

| Field | Type | Deskripsi |
|---|---|---|
| `id` | BIGINT | Primary key, Auto Increment. |
| `user_id` | BIGINT | Foreign Key, Nullable. Link ke akun login (users.id). |
| `nik` | VARCHAR(16) | Unique. Primary Identifier Personal (KTP). |
| `nip` | VARCHAR(30) | Unique, Nullable. NIP Yayasan/PNS. |
| `nuptk` | VARCHAR(30) | Nullable. Penting untuk sekolah. |
| `npwp` | VARCHAR(30) | Nullable. Nomor Pokok Wajib Pajak. |
| `name` | VARCHAR | Not Null. Nama Lengkap. |
| `place_of_birth` | VARCHAR | Not Null. Tempat Lahir. |
| `date_of_birth` | DATE | Not Null. Tanggal Lahir. |
| `gender` | ENUM | Not Null. Pilihan: `L`, `P`. |
| `address` | TEXT | Not Null. Alamat Lengkap. |
| `phone` | VARCHAR(20) | Not Null. Nomor Telepon/WA. |
| `email` | VARCHAR | Unique. Email Pribadi/Kontak. |
| `last_education` | VARCHAR | Nullable. Pendidikan Terakhir (S1, S2, dll). |
| `major` | VARCHAR | Nullable. Jurusan/Prodi. |
| `university` | VARCHAR | Nullable. Universitas/Institusi Pendidikan. |
| `bank_name` | VARCHAR | Nullable. Nama Bank. |
| `bank_account` | VARCHAR | Nullable. Nomor Rekening. |
| `bank_account_holder` | VARCHAR | Nullable. Atas Nama Rekening. |
| `photo_path` | VARCHAR | Nullable. Path foto profil. |
| `documents_path` | JSON | Nullable. Path dokumen (Scan KTP, Ijazah, KK). |
| `created_at` | TIMESTAMP | Waktu dibuat. |
| `updated_at` | TIMESTAMP | Waktu terakhir diupdate. |
| `deleted_at` | TIMESTAMP | Nullable. Waktu soft delete. |

**Unique**: `nik`, `nip`, `email`  
**Index**: `nik`, `email`  
**Primary Key**: `id`  
**Foreign Key**: `user_id` -> `users.id` (nullable, nullOnDelete)  
**Relasi**:

- `user_id` -> `users.id` (User account)

## 3.2. Tabel `employee_assignments`

| Field | Type | Deskripsi |
|---|---|---|
| `id` | BIGINT | Primary key, Auto Increment. |
| `employee_id` | BIGINT | Foreign Key. Relasi ke employees.id. |
| `institution_id` | BIGINT | Foreign Key. Relasi ke institutions.id (Tempat tugas). |
| `position` | VARCHAR | Not Null. Jabatan (Guru Kelas, Kepala Sekolah, Staff TU). |
| `employment_status` | ENUM | Not Null. Pilihan: `PERMANENT`, `CONTRACT`, `HONORARY`, `INTERN`. |
| `start_date` | DATE | Not Null. Tanggal mulai bertugas. |
| `end_date` | DATE | Nullable. Tanggal selesai bertugas (Null jika permanent). |
| `is_active` | BOOLEAN | Default true. Status aktif jabatan ini. |
| `basic_salary` | DECIMAL(15, 2) | Default 0. Gaji Pokok per penugasan. |
| `allowance_fixed` | DECIMAL(15, 2) | Default 0. Tunjangan Jabatan tetap. |
| `created_at` | TIMESTAMP | Waktu dibuat. |
| `updated_at` | TIMESTAMP | Waktu terakhir diupdate. |

**Primary Key**: `id`  
**Foreign Key**: `employee_id` -> `employees.id` (cascade delete)  
**Foreign Key**: `institution_id` -> `institutions.id` (cascade delete)  
**Relasi**:

- `employee_id` -> `employees.id`
- `institution_id` -> `institutions.id`
