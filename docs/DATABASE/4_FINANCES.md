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