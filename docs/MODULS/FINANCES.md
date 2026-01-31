# Modul Keuangan

## Prinsip Dasar (Architecture)

### Integritas Data & Concurrency
- **Atomic Updates:** Update saldo **dilarang keras** hanya mengandalkan observer sederhana. Wajib menggunakan mekanisme atomic (`increment`/`decrement`) di level database untuk menangani *race condition* pada situasi *high concurrency* (misal: saat daftar ulang).
- **Database Locking:** Menggunakan `lockForUpdate()` (Pessimistic Locking) saat memproses transaksi pembayaran untuk memastikan satu rekening dikunci eksklusif sampai transaksi selesai.

### Immutable Ledger (Jurnal Tak Dapat Diubah)
- **No Hard Delete:** Data transaksi keuangan yang sudah terekonsiliasi **haram** dihapus atau diedit nominalnya.
- **Reversal Mechanism (Koreksi) untuk Data Transaksi:** Jika terjadi kesalahan input (misal salah nominal), sistem tidak boleh mengedit baris transaksi lama. Sistem wajib membuat "Transaksi Koreksi" (Reversal Transaction) yang membalikkan nilai tersebut, kemudian membuat transaksi baru yang benar. Hal ini untuk menjaga *Audit Trail*.
- **Soft Deletes untuk Data Master:** Jika fitur hapus mutlak diperlukan untuk data master, wajib menggunakan *Soft Deletes*.

---

## Sub-Modul Master Data Keuangan

### Tahun Buku & Tutup Buku (`period_closings`)
- **Tahun Buku:** Otomatis mengikuti Tahun Ajaran.
- **Monthly Closing (Tutup Buku Bulanan):** Fitur untuk mengunci transaksi pada bulan tertentu (misal: Tanggal 10 bulan berikutnya, transaksi bulan lalu dikunci).
  - Admin tidak bisa input/edit/hapus transaksi di periode yang terkunci.
  - Menjaga konsistensi laporan yang sudah diserahkan ke Yayasan.

### Rekening/Dompet Kas (`financial_accounts`)
- CRUD Rekening/Dompet Kas per lembaga.
- **Balance Caching:** Kolom `balance` hanya digunakan sebagai cache untuk performa read. Update wajib via *Atomic Operations*.
- **Rekonsiliasi:** Fitur untuk mencocokkan saldo sistem dengan saldo fisik/bank (Opname Kas).

### Pos Anggaran (`budget_categories`)
- Hierarki Kode Akun (Chart of Accounts).
- **Budgeting:** Menentukan batas anggaran per Pos untuk keperluan "Laporan Realisasi Anggaran".

---

## Sub-Modul Manajemen Biaya & Pendapatan

### Komponen Biaya (`fee_components`)
- **Tipe:** Bulanan (SPP), Tahunan (Uang Gedung), Sekali (Pendaftaran).
- **Insidentil:** Denda pelanggaran, Ganti rugi barang, Biaya kesehatan mendadak (Manual Bill).
- **Prioritas Pembayaran:** Komponen memiliki level prioritas (misal: 1. SPP, 2. Makan, 3. Ekstra).

### Penyesuaian Biaya (`student_fee_adjustments`)
- **Beasiswa & Subsidi:** Menangani kasus diskon khusus (misal: Anak Yatim diskon 50%, Prestasi diskon 100%).
- **Logic:** Saat tagihan di-generate, sistem mengecek tabel penyesuaian ini.

### Tagihan Santri (`bills`)
- **Auto-Generate:** Massal berdasarkan kelas/status aktif.
- **Manual Creation:** Admin bisa membuat tagihan insidentil per siswa.
- **Status:** Unpaid → Partial → Paid / Cancelled.

---

## Pembayaran & Digitalisasi

### Integrasi Payment Gateway (VA)
- **Virtual Account (Prioritas):** Integrasi (Xendit/Midtrans/Flip) agar wali murid mendapat nomor VA unik (Bank + NIS).
- **Otomatisasi:**
  - Terima Callback/Webhook dari bank.
  - Tagihan lunas detik itu juga.
  - Saldo terupdate otomatis.
  - Notifikasi WA terkirim otomatis.
- **Manual Upload:** Hanya opsi cadangan jika sistem VA error.

### Alokasi Pembayaran (`payment_allocations`)
- **Prioritas Otomatis:** Jika orang tua transfer gelondongan (misal 1jt) tanpa keterangan, sistem melunasi tagihan berdasarkan urutan prioritas (`fee_priorities`).
  1. Tunggakan SPP terlama.
  2. Tunggakan Uang Makan.
  3. Uang Gedung.
  4. Sisa -> Masuk ke Tuition Wallet.

---

## Manajemen Wallet Santri (`student_wallets`)

Pemisahan tegas fungsi dompet untuk mendukung *Cashless Society*.

### 1. Tuition Wallet (Deposit Pendidikan)
- **Fungsi:** Menyimpan kelebihan bayar SPP/Biaya Pendidikan.
- **Restriksi:** Tidak bisa ditarik tunai oleh santri.
- **Auto-Debet:** Sistem otomatis mengambil dana dari sini saat tagihan bulan baru muncul.

### 2. Pocket Money Wallet (Tabungan/Uang Saku)
- **Fungsi:** Uang jajan harian santri.
- **Fitur:**
  - **Top-up:** Via VA atau Admin TU.
  - **Limit Harian:** Orang tua bisa set batas jajan per hari.
  - **POS Integrasi:** Bisa dipakai belanja di Kantin/Koperasi (Scan Kartu/QR).
  - **Withdrawal:** Bisa ditarik tunai jika izin pulang/sakit.

---

## Akuntansi & Laporan (`financial_transactions`)

### Struktur Data Transaksi
- **Financial Transactions (Header):** Mencatat "Event/Kejadian" (No Transaksi, Siapa, Kapan, Status).
- **Journal Entries (Detail):** Mencatat pergerakan akuntansi (Debit Rekening A, Kredit Pos B).

### Pengeluaran Lembaga (`expenses`)
- **Budget Control:** Saat input pengeluaran, sistem memberi warning jika `Actual > Budget` bulan berjalan.
- **Approval:** Bertingkat (Admin Unit -> Kepala -> Yayasan).

### Laporan Keuangan (Yayasan Standard)
1. **Laporan Realisasi Anggaran:** Membandingkan *Budget Plan* vs *Actual Expense*. (Kontrol efisiensi).
2. **Aging Report (Umur Piutang):** Analisa tunggakan Santri (Grouping: 1-3 bulan, 3-6 bulan, >6 bulan) untuk strategi penagihan.
3. **Arus Kas (Cash Flow):** Mutasi detail per rekening.
4. **Rekapitulasi Pemasukan & Pengeluaran.**

---

## WhatsApp Billing & Notifikasi
- Reminder tagihan otomatis (Cron Job).
- Notifikasi Real-time saat: Pembayaran masuk, Tagihan baru, Saldo tabungan menipis.