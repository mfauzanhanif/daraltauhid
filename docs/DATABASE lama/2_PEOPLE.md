## 2. Tabel Data Master (People)

### 2.1 `students` - Data Santri

| Field                 | Type      | Deskripsi                                            |
| --------------------- | -------   | ---------------------------------------------------- |
| `id`                  | bigint    | Primary key                                          |
| `institution_id`      | FK        | Lembaga aktif saat ini                               |
| `nisy`                | varchar   | NIS Yayasan - Buku Induk (unique, permanen)          |
| `nisn`                | varchar   | Nomor Induk Siswa Nasional                           |
| `nik`                 | varchar   | NIK KTP (unique)                                     |
| `full_name`           | varchar   | Nama lengkap                                         |
| `gender`              | enum      | `L`, `P`                                             |
| `place_of_birth`      | varchar   | Tempat lahir                                         |
| `date_of_birth`       | date      | Tanggal lahir                                        |
| `child_number`        | int       | Anak ke-                                             |
| `total_siblings`      | int       | Jumlah saudara kandung                               |
| `address_street`      | text      | Alamat jalan                                         |
| `village`             | varchar   | Desa/Kelurahan                                       |
| `district`            | varchar   | Kecamatan                                            |
| `city`                | varchar   | Kabupaten/Kota                                       |
| `province`            | varchar   | Provinsi                                             |
| `postal_code`         | varchar   | Kode pos                                             |
| `photo_path`          | varchar   | Path foto                                            |
| `status`              | enum      | `draft`, `verified`, `active`, `moved`, `dropped`    |
| `deleted_at`          | timestamp | Soft delete                                          |

**Unique**: `nisy`, `nik`
**Index**: `full_name`, `status`
**Relasi**:
- `institution_id` -> `institutions.id` (RESTRICT: Institution cannot be deleted if it has students)
- `institutions` 1:N `students`
- `students` 1:N `student_institutions`

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

**Unique**: `institution_id`, `nis`
**Relasi**:
- `student_id` -> `students.id`
- `institution_id` -> `institutions.id`
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

**Index**: `nik`, `phone_number`, `name`
**Relasi**:
- `student_id` -> `students.id`
- `user_id` -> `users.id` (SET NULL: If User is deleted, Parent data remains)
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
| `created_by`    | bigint  | User pengupload                                 |
| `updated_by`    | bigint  | User pengubah                                   |

**Foreign Key**: `student_id` -> `students.id`
**Index**: `type`
**Relasi**: `students` 1:N `student_documents` (RESTRICT)

**Foreign Key**: `student_id` -> `students.id`
**Index**: `type`
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
| `deleted_at`        | timestamp     | Soft delete                                                           |

**Unique**: `nip`, `nik`, `email`
**Index**: `name`, `is_active`
**Relasi**:
- `institution_id` -> `institutions.id`
- `user_id` -> `users.id` (SET NULL: If User is deleted, Employee data remains)
