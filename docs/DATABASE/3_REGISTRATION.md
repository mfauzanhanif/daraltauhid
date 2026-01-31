## 3. Tabel PSB

### 3.1 `registrations` - Detail Pendaftaran

| Field                        | Type    | Deskripsi                                           |
| ---------------------------- | ------- | --------------------------------------------------- |
| `id`                         | bigint  | Primary key                                         |
| `student_id`                 | FK      | Relasi ke students                                  |
| `registration_number`        | varchar | Nomor pendaftaran unik (format: `YYXXXX`)           |
| `category`                   | enum    | `baru`, `pindahan`, `lanjut`                        |
| `academic_year_id`           | FK      | Tahun ajaran                                        |
| `previous_school_level`      | varchar | Jenjang sekolah asal (SD, MI, SMP, MTs)             |
| `previous_school_name`       | varchar | Nama sekolah asal                                   |
| `previous_school_npsn`       | varchar | NPSN sekolah asal                                   |
| `previous_school_address`    | text    | Alamat sekolah asal                                 |
| `previous_institution_id`    | FK      | Jika lanjut jenjang, ambil ID lembaga sebelumnya (Nullable) |
| `destination_institution_id` | FK      | Lembaga tujuan (Pondok/SMP/MA/MI)                   |
| `destination_class`          | varchar | Kelas tujuan (7, 10, dll)                           |
| `registration_track`         | varchar | Jalur: `reguler`, `prestasi`, `tahfidz`, `pindahan` |
| `funding_source`             | varchar | Sumber biaya: `orang_tua`, `beasiswa`, dll          |
| `path`                       | enum    | `psb`, `sekolah`                                    |
| `via`                        | enum    | `online`, `offline`, `internal`                     |
| `marketing_source`           | enum    | `Sosial Media`, `Alumni`, `Website`, `Lainnya`      |
| `status`                     | enum    | `draft`, `submitted`, `verified`, `accepted`, `rejected`, `enrolled` |
| `registered_at`              | date      | Tanggal pendaftaran                                 |
| `deleted_at`                 | timestamp | Soft delete                                         |
| `created_by`                 | bigint    | User pembuat                                        |
| `updated_by`                 | bigint    | User pengubah                                       |

**Unique**: `registration_number`
**Index**: `status`, `category`, `via`, `registration_track`
**Relasi**:
- `student_id` -> `students.id` (CASCADE: If Student is hard-deleted, Registration is deleted)
- `academic_year_id` -> `academic_years.id`
- `previous_institution_id` -> `institutions.id` (Lembaga Asal jika internal)
- `destination_institution_id` -> `institutions.id` (Lembaga Tujuan)