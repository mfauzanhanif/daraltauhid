# 1. INSTITUTION DATABASES

## 1.1. Tabel `institutions`

| Field           | Type         | Deskripsi                                                                                          |
| --------------- | ------------ | -------------------------------------------------------------------------------------------------- |
| id              | BIGINT       | Primary key, Auto Increment. ID Unik Lembaga.                                                      |
| code            | VARCHAR(20)  | Unique. Kode singkat lembaga (misal: PONTREN, MI SMP).                                             |
| slug            | VARCHAR(100) | Unique. URL-friendly identifier (misal: smp-plus-dar-al-tauhid).                                   |
| domain          | VARCHAR(100) | Unique, Nullable. Domain/Subdomain khusus untuk fitur Multi-Tenant (misal: mis.daraltauhid.com).   |
| name            | VARCHAR(255) | Not Null. Nama resmi lembaga.                                                                      |
| nickname        | VARCHAR(100) | Nullable. Nama panggilan/singkatan umum.                                                           |
| no_statistic    | VARCHAR(50)  | Nullable. Nomor Statistik (NSM/NSS/NSPP).                                                          |
| npsn            | VARCHAR(20)  | Nullable. Nomor Pokok Statistik Nasional.                                                          |
| is_internal     | BOOLEAN      | Default true. true = Milik Yayasan, false = Mitra/Binaan.                                          |
| category        | ENUM         | Not Null. Kategori umum: PESANTREN, FORMAL, NON_FORMAL, SOSIAL.                                    |
| type            | ENUM         | Not Null. Jenjang spesifik (Lihat Daftar Enum di bawah).                                           |
| headmaster_id   | VARCHAR(50)  | Nullable. NIP/NIY Kepala Sekolah.                                                                  |
| headmaster_name | VARCHAR(255) | Nullable. Nama Kepala Sekolah.                                                                     |
| email           | VARCHAR(255) | Nullable. Email resmi lembaga.                                                                     |
| phone           | VARCHAR(50)  | Nullable. Nomor telepon kantor.                                                                    |
| website_url     | VARCHAR(255) | Nullable. URL website profil (jika ada).                                                           |
| address         | TEXT         | Nullable. Alamat lengkap.                                                                          |
| district        | VARCHAR(100) | Nullable. Kecamatan (untuk Kop Surat).                                                             |
| city            | VARCHAR(100) | Nullable. Kota/Kabupaten (untuk Kop Surat).                                                        |
| logo_path       | VARCHAR(255) | Nullable. Lokasi file logo.                                                                        |
| letterhead_path | VARCHAR(255) | Nullable. Lokasi file gambar Kop Surat.                                                            |
| parent_id       | BIGINT       | Foreign Key, Nullable. ID Induk Lembaga (Self-Reference ke institutions.id). NULL = Yayasan Pusat. |
| is_active       | BOOLEAN      | Default true. Status operasional lembaga.                                                          |
| created_at      | TIMESTAMP    | Waktu dibuat.                                                                                      |
| updated_at      | TIMESTAMP    | Waktu terakhir diupdate.                                                                           |
| deleted_at      | TIMESTAMP    | Nullable. Waktu soft delete.                                                                       |

**Unique**: `code`, `slug`, `domain`  
**Index**: `code`, `slug`, `domain`, `email`, `phone`  
**Primary Key**: `id`  
**Foreign Key**: `parent_id` -> `institutions.id` (nullable, self-reference)  
**Relasi**:

- `parent_id` -> `institutions.id` (self-reference untuk hierarki lembaga)

Enum Reference:
type
YAYASAN
PONDOK
TK, SD, MI
SMP, MTS
SMA, MA, SMK, SLB
MDTA, TPQ, Madrasah
LKSA

## 1.2. Tabel `academic_years`

| Field      | Type        | Deskripsi                                                                     |
| ---------- | ----------- | ----------------------------------------------------------------------------- |
| id         | BIGINT      | Primary key, Auto Increment. ID Tahun Ajaran.                                 |
| name       | VARCHAR(20) | Unique. Nama Tahun Ajaran (Contoh: 2025/2026).                                |
| start_date | DATE        | Not Null. Tanggal mulai (biasanya 1 Juli).                                    |
| end_date   | DATE        | Not Null. Tanggal selesai (biasanya 30 Juni tahun depannya).                  |
| is_active  | BOOLEAN     | Default false. Status aktif global. Hanya boleh ada 1 row yang bernilai true. |
| created_at | TIMESTAMP   | Waktu dibuat.                                                                 |
| updated_at | TIMESTAMP   | Waktu terakhir diupdate.                                                      |
| deleted_at | TIMESTAMP   | Nullable. Waktu soft delete.                                                  |

**Unique**: `name`  
**Index**: `name`, `is_active`  
**Primary Key**: `id`

## 1.3. Tabel `academic_periods`

| Field            | Type      | Deskripsi                                           |
| ---------------- | --------- | --------------------------------------------------- |
| id               | BIGINT    | Primary key, Auto Increment. ID Semester.           |
| academic_year_id | BIGINT    | Foreign Key, Not Null. Relasi ke academic_years.id. |
| name             | ENUM      | Not Null. Pilihan: Ganjil, Genap.                   |
| start_date       | DATE      | Not Null. Tanggal mulai semester.                   |
| end_date         | DATE      | Not Null. Tanggal selesai semester.                 |
| is_active        | BOOLEAN   | Default false. Status aktif semester saat ini.      |
| created_at       | TIMESTAMP | Waktu dibuat.                                       |
| updated_at       | TIMESTAMP | Waktu terakhir diupdate.                            |

**Unique**: `academic_year_id`, `name` (kombinasi)  
**Index**: `academic_year_id`, `name`, `is_active`  
**Primary Key**: `id`  
**Foreign Key**: `academic_year_id` -> `academic_years.id`  
**Relasi**:

- `academic_year_id` -> `academic_years.id`

Constraint: Kombinasi (academic_year_id, name) harus unik.

## 1.4. Tabel `fiscal_periods`

| Field      | Type        | Deskripsi                                                                                             |
| ---------- | ----------- | ----------------------------------------------------------------------------------------------------- |
| id         | BIGINT      | Primary key, Auto Increment. ID Tahun Buku.                                                           |
| name       | VARCHAR(20) | Unique. Nama Tahun Buku (Contoh: 2026).                                                               |
| start_date | DATE        | Not Null. Tanggal mulai (biasanya 1 Januari).                                                         |
| end_date   | DATE        | Not Null. Tanggal selesai (biasanya 31 Desember).                                                     |
| is_active  | BOOLEAN     | Default false. Menandakan periode akuntansi yang sedang berjalan.                                     |
| status     | ENUM        | Default OPEN. Status Audit: OPEN (Bisa input), CLOSED (Tutup Sementara), AUDITED (Terkunci Permanen). |
| created_at | TIMESTAMP   | Waktu dibuat.                                                                                         |
| updated_at | TIMESTAMP   | Waktu terakhir diupdate.                                                                              |

**Unique**: `name`  
**Index**: `name`, `is_active`, `status`  
**Primary Key**: `id`
