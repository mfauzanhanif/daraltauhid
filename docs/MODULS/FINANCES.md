# Modul Keuangan

## Sub Modul Master Data Keuangan

### Tahun Buku

- financial year menggunakan tahun ajaran sebagai tahun buku. 
- Tahun Buku Otomatis tergenerate berdasarkan Tahun Ajaran.
- Jika tahun ajaran tidak aktif, maka tahun buku tidak bisa CRUD.
- Tahun Buku hanya bisa di hapus jika tidak ada data transaksi.

### Rekening/Dompet Kas (`financial_accounts`)

- CRUD Rekening/Dompet Kas per lembaga.
- Rekening/Dompet Kas harus di aktifkan sebelum bisa digunakan.
- Lembaga bisa memiliki lebih dari 1 rekening/dompet kas.
- Kolom `balance` adalah cache dari `SUM(cash_mutations)` untuk performa dashboard.
- Balance Caching. Saldo otomatis update via Laravel Observer (`CashMutationObserver`) saat ada mutasi baru.
- Recalculate. Fitur reset saldo dari total mutasi (hidden in Settings)

### Pos Anggaran (`budget_categories`)

- CRUD Pos Anggaran per lembaga. Kode akun hierarkis (4.1 Pemasukan, 5.1 Pengeluaran)
- Type: Income (Masuk) / Expense (Keluar)
- Per-Lembaga: Setiap lembaga punya pos anggaran sendiri

## Komponen Biaya (`fee_components`)

| Field         | Contoh                          |
| ------------- | ------------------------------- |
| Nama          | SPP, Uang Gedung, Seragam, Buku |
| Tipe          | Monthly, Yearly, Once           |
| Target Kelas  | Kelas 7 / 8 / 9 / Semua         |
| Target Gender | L / P / Semua                   |

## Tagihan Santri (`bills`)

- **Auto-Generate:** Saat santri mendaftar via `Student::generateBills()`
- **Discount:** `initial_amount` - `discount_amount` = `amount`
- **Status:** Unpaid → Partial → Paid / Cancelled
- **Periode:** Bulan & Tahun tagihan (untuk SPP bulanan)

## Pembayaran (`payments`)

| Fitur            | Deskripsi                               |
| ---------------- | --------------------------------------- |
| **Lokasi Input** | PANITIA (Pusat) atau LEMBAGA (Langsung) |
| **Metode**       | Cash / Transfer                         |
| **Status**       | Pending → Success / Failed              |
| **Kwitansi**     | Cetak PDF dengan QR Code verifikasi     |
| **Bukti**        | Upload foto bukti transfer              |

## Alokasi Pembayaran (`payment_allocations`)

> **PENTING:** 1x bayar bisa dialokasikan ke banyak tagihan!

Contoh: Bayar Rp 1.000.000 dialokasikan ke:

- SPP Januari: Rp 200.000
- SPP Februari: Rp 200.000
- Uang Gedung (Cicilan): Rp 600.000

## Saldo Santri (`student_wallets`)

- **Kelebihan Bayar:** Jika bayar lebih, masuk ke saldo
- **Potong Otomatis:** Saldo bisa dipotong untuk tagihan berikutnya

## Pengeluaran Lembaga (`expenses`)

- **Input:** Judul, deskripsi, nominal, bukti nota
- **Approval:** Pending → Approved / Rejected
- **Pos Anggaran:** Kategorisasi (Listrik, ATK, Honor, dll)

## Pemasukan Non-Santri (`incomes`)

- **Sumber:** Hibah, Donasi, Bantuan Pemerintah
- **Tracking:** Dari siapa, untuk apa, berapa

## Arus Kas / Jurnal (`cash_mutations`)

> **Tabel Inti:** Semua pergerakan uang tercatat di sini!

| Fitur                | Deskripsi                                                   |
| -------------------- | ----------------------------------------------------------- |
| **Polymorphic**      | Link ke `payments`, `expenses`, `incomes`, `fund_transfers` |
| **Balance Snapshot** | `balance_after` = saldo setelah transaksi                   |
| **Bukti**            | Foto nota/kuitansi                                          |

## Distribusi Dana (`fund_transfers`)

> **Flow:** Dana dari Panitia Pusat → Lembaga

- **Status:** Pending → Approved → Completed / Rejected
- **Approval:** Siapa yang approve, kapan
- **Receipt:** Siapa yang terima, kapan

## WhatsApp Billing

- **Auto-Reminder:** Kirim tagihan via Fonnte (Cron Job)
- **Template:** Salam, detail tagihan, total, link bayar

## Laporan Keuangan

| Laporan                  | Deskripsi                        |
| ------------------------ | -------------------------------- |
| Rekapitulasi Pemasukan   | Per hari/minggu/bulan/tahun      |
| Rekapitulasi Pengeluaran | Per pos anggaran                 |
| Tunggakan Santri         | List santri dengan sisa tagihan  |
| Arus Kas                 | Jurnal masuk-keluar per rekening |
| Neraca Saldo             | Total saldo semua rekening       |

---