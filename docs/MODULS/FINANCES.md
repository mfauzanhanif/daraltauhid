# Modul Keuangan

## Prinsip Dasar (Architecture)

### Integritas Data & Concurrency

- **Atomic Updates:** Update saldo **dilarang keras** hanya mengandalkan observer sederhana. Wajib menggunakan mekanisme atomic (`increment`/`decrement`) di level database untuk menangani *race condition* pada situasi *high concurrency* (misal: saat daftar ulang).
- **Database Locking:** Menggunakan `lockForUpdate()` (Pessimistic Locking) saat memproses transaksi pembayaran untuk memastikan satu rekening dikunci eksklusif sampai transaksi selesai.

### Immutable Ledger (Jurnal Tak Dapat Diubah)

- **No Hard Delete:** Data transaksi keuangan yang sudah terekonsiliasi **haram** dihapus atau diedit nominalnya.
- **Reversal Mechanism (Koreksi) untuk Data Transaksi:** Jika terjadi kesalahan input (misal salah nominal), sistem tidak boleh mengedit baris transaksi lama. Sistem wajib membuat "Transaksi Koreksi" (Reversal Transaction) yang membalikkan nilai tersebut, kemudian membuat transaksi baru yang benar. Hal ini untuk menjaga *Audit Trail*.
- **Soft Deletes untuk Data Master:** Jika fitur hapus mutlak diperlukan untuk data master, wajib menggunakan *Soft Deletes*.

### Tipe Data Presisi (Precision Handling)

- **Decimal:** Semua kolom nominal uang wajib menggunakan tipe data `DECIMAL`. Dilarang keras menggunakan `FLOAT` atau `DOUBLE`.
- **Timezone Consistency:** Semua timestamp transaksi wajib disimpan dalam format UTC di database. Konversi ke waktu lokal (WIB) hanya dilakukan di Application Layer saat penyajian data.

### Idempotency (Pencegahan Double Posting)

- **Idempotency Key:** Setiap request mutasi (POST) wajib menyertakan header unik (UUID).
- **Handling:** Middleware wajib mengecek kunci tersebut. Jika kunci sudah pernah diproses, sistem harus mengembalikan respon sukses yang sama (cached response) tanpa mengeksekusi ulang logika bisnis/saldo.

---

## Sub-Modul Master Data Keuangan

### Tahun Buku (`fiscal_periods`)

- **Dual Period System:** Sistem wajib mengakomodasi dua jenis periode pelaporan:
  - **Tahun Ajaran (Academic Year):** Juli - Juni (Basis tagihan SPP & Akademik).
  - **Tahun Fiskal (Fiscal Year):** Januari - Desember (Basis Laporan Keuangan Yayasan & Pajak).
- **Audit Locking (Hard Lock):** Super Admin mengunci permanen periode yang telah diaudit. Data di periode ini menjadi Read-Only total.

### Tutup Buku (`period_closings`)

- **Monthly Soft Lock:** Mekanisme penguncian operasional bulanan (misal: tiap tanggal 10 bulan berikutnya).
  - **Efek:** Bendahara/Operator tidak bisa input/edit/hapus transaksi bulan lalu.
  - **Override:** Hanya bisa dibuka sementara oleh level Kepala Bagian/Admin Yayasan jika ada revisi mendesak.

### Rekening/Dompet Kas (`financial_accounts`)

- **Tipe Akun:** Klasifikasi akun minimal terdiri dari: CASH (Tunai) dan BANK (Rekening). soon: VIRTUAL (Payment Gateway/E-Wallet).
- **Metadata:** Menyimpan detail Bank, No. Rek, dan Atas Nama. soon: detail Virtual Account.
- **Strict Initial Balance:** Saldo awal wajib melalui transaksi "Saldo Awal", tidak boleh injek database langsung.
- **Balance Caching:** Kolom `balance` hanya digunakan sebagai cache untuk performa read. Update wajib via *Atomic Operations*.
- **Verifikasi Saldo:**
  - **Cash Opname:** Fitur catat fisik uang tunai vs sistem -> Auto-generate jurnal selisih (Cash Shortage/Overage).
  - **Bank Reconciliation:** Fitur checklist mutasi sistem vs rekening koran bank.

### Pos Anggaran (`budget_categories`)
- **Standard Coding:** Menggunakan kode akun hierarki (Contoh: 1.1.01 Kas Tunai).
- **Normal Balance:** Menentukan posisi normal (DEBIT/CREDIT) untuk validasi jurnal otomatis.
- **Budget Control (Realisasi Anggaran):**
  - **Soft Limit Alert:** Jika pengajuan pengeluaran melebihi sisa anggaran, sistem tetap mengizinkan tapi memberikan notifikasi/tanda khusus (Warning).
  - **Over-Budget Reporting:** Transaksi yang melebihi anggaran akan ditandai merah di laporan "Realisasi Anggaran" untuk evaluasi Yayasan.

---

## Sub-Modul Manajemen Biaya

### Komponen Biaya (`fee_components`)

- **Frequency Logic:** Mengatur siklus tagihan secara sistematis:
  - **MONTHLY:** (SPP, Catering) - Digenerate otomatis setiap bulan.
  - **YEARLY:** (Daftar Ulang, Kegiatan Tahunan) - Digenerate sekali di awal tahun ajaran.
  - **ONE_TIME:** (Uang Pangkal/Masuk) - Hanya sekali seumur hidup santri.
  - **INCIDENTAL:** (Denda, Ganti Rugi, Buku) - Dibuat manual sesuai kejadian.
- **Priority Level:** Kolom priority_order (Integer) untuk menentukan urutan pelunasan otomatis. (Contoh: 1. SPP, 2. Makan, 3. Gedung).
- **Target Audience:** Bisa diset berlaku untuk "Semua Santri", "Per Jenjang (SD/MI/MTs/MA)", "Per Tahun Masuk", Per Tingkat Kelas", "Per Jenis Kelamin (Laki-laki/Perempuan)", "Per Asrama".
- **Penanganan Tagihan Insidentil:**
  - **Manual Entry:** Admin bisa membuat tagihan khusus untuk satu santri (misal: Tagihan pemecahan kaca jendela).
  - **Approval:** Untuk tagihan insidentil di atas nominal tertentu, wajib approval Kepala TU.

### Penyesuaian Biaya (`student_fee_adjustments`)
- **Discount vs Subsidy:** Sistem harus membedakan dua jenis pengurangan biaya:
  - **Diskon (Potongan):** Mengurangi nominal tagihan dan pendapatan (Contoh: Diskon Anak Guru). Secara akuntansi dicatat sebagai Contra-Revenue.
  - **Subsidi (Bantuan):** Tagihan tetap penuh, tapi sebagian dibayar oleh pihak ketiga/Yayasan (Contoh: Subsidi Yatim). Secara akuntansi dicatat sebagai Piutang Yayasan atau Beban Bantuan.
- **Scope:** Beasiswa bisa diset menempel pada komponen tertentu (misal: Bebas SPP 100%, tapi Uang Gedung tetap bayar).

### Manajemen Tagihan (`invoices` & `invoice_items`)

- **Structure:** Menggunakan konsep Header-Detail.
  - **Invoice Header:** Berisi No. Invoice, Total Tagihan, Sisa Tagihan, Tanggal Jatuh Tempo, Status Global.
  - **Status Global:** UNPAID (Belum lunas), PARTIAL (Cicil), PAID (Lunas), VOID (Dibatalkan - Pengganti Delete).
  - **Invoice Items:** Rincian per item (misal: Item 1: SPP Januari Rp 100rb, Item 2: Denda Rp 10rb). Ini penting agar saat pelunasan parsial, sistem tahu item mana yang lunas duluan.
- **Unique Invoice Number:** Format yang mudah dibaca manusia (Contoh: INV/2026/01/SANTRI001).
- **Bulk Generation (Job Queue):** Pembuatan tagihan massal (ribuan santri) wajib menggunakan Background Job agar server tidak timeout.
- **Safety Check:** Sistem wajib mengecek apakah tagihan untuk periode tersebut sudah ada sebelum membuat baru (mencegah tagihan ganda).

---

## Sub-Modul Pembayaran & Transaksi

### Kasir & Pembayaran Manual (Cash dan Transfer)

- **Role:** Bendahara/kasir/panitia/petugas keuangan. 

- **Fungsi:** Interface khusus untuk admin/panitia yang menerima uang tunai dan transfer.
- **Scheduling Shift Kasir:** disetting oleh admin di menu "Shift Kasir".
- **Data Input:** Bank Pengirim, Nama Pemilik Rekening, Nominal, Tanggal, dan Foto Bukti.
- **Bukti Transaksi:**
  - Integrasi Thermal untuk cetak bukti fisik.
  - Kirim Struk via WA (link pdf) untuk hemat kertas.
- **Dashboard Verifikasi:**
  - Admin mencocokkan data di form konfirmasi dengan mutasi rekening koran (Internet Banking).
  - Action: Approve (Lanjut ke Alokasi Dana), Reject (Bukti tidak valid), atau Correction (Jika nominal berbeda).
- **Cash Drawer Session (Sesi Kasir):**
  - **Open Register:** Saat mulai shift, kasir wajib input Modal Awal Tunai (Uang kembalian/Float) yang ada di laci. Tidak berlaku untuk saldo Bank.
  - **Close Register:** Di akhir shift, kasir wajib input total uang fisik yang ada di laci.
  - **Discrepancy Handling:** Sistem otomatis menghitung selisih (Sistem vs Fisik).
    - **Shortage (Kurang):** Dicatat sebagai Piutang Karyawan (Potong gaji) atau Beban Selisih.
    - **Overage (Lebih):** Dicatat sebagai Pendapatan Lain-lain.


- **Khusus Pembayaran Online (Transfer)**

  - **Identifikasi Transfer (SOP Wajib):**
    - Di aplikasi/informasi tagihan, sistem memberikan instruksi tegas: "Mohon tuliskan NAMA SANTRI atau NIS pada kolom Berita/Keterangan saat transfer."
    - Admin akan mengandalkan Nama Pengirim atau Berita Transfer di mutasi bank untuk verifikasi.

  - **Konfirmasi Pembayaran (Wajib):**
    - Menyediakan form upload bukti transfer di aplikasi wali santri atau admin via dashboard jika bukti dikirim melaui whatsapp.
    - **Action:** Approve (Sistem menjalankan algoritma alokasi), Reject (Kirim alasan ke WA wali), atau Correction (Edit nominal jika tidak sesuai).

### Integrasi Payment Gateway (Roadmap / Future Phase)
- **Fixed Virtual Account (Open Amount):**
  - Setiap santri memiliki satu nomor VA statis (Bank Prefix + NISY).
  - Wali murid bisa transfer berapapun (Open Amount), sistem yang akan mengalokasikan.

- **Automated Handling:**
  - **Webhook/Callback:** Server wajib menangani callback dari Xendit secara idempotent (mencegah double topup jika Xendit mengirim sinyal 2x).
  - **Real-time Settlement:** Saldo santri terupdate detik itu juga setelah callback sukses diterima. 

- **Fee Management:** Sistem harus dikonfigurasi siapa yang menanggung biaya admin (MDR):
  - **Surcharge:** Dibebankan ke wali murid (Total + Biaya Admin).
  - **Absorb:** Ditanggung lembaga (Dicatat sebagai Expense).

### Alokasi Pembayaran (`payment_allocations`)
Sistem cerdas untuk memecah satu kali transfer menjadi pelunasan berbagai pos tagihan.

#### A. Logika Pembayaran Reguler (SPP/Bulanan) 

Digunakan saat pembayaran dilakukan di luar masa Daftar Ulang/PSB.

- **Priority Logic:** Berdasarkan fee_priorities yang disetting Bendahara.
  - Tunggakan SPP (Urut dari bulan terlama/FIFO).
  - Tunggakan Uang Makan (Katering).
  - Tagihan Insidentil/Lainnya.
- **Sisa Dana:** Otomatis masuk ke Tuition Wallet (Deposit) agar tidak menggantung.

#### B. Logika Pembayaran PSB & Daftar Ulang (Complex Split) 

Mengacu pada dokumen [PRIORITY_ALGORITHM.md](FINANCE/PRIORITY_ALGORITHM.md). Digunakan khusus untuk pembayaran via Panitia PSB.

- **Phase 1 (Madrasah First):** Dana masuk diprioritaskan 100% untuk melunasi tagihan Madrasah/Diniyah.
- **Phase 2 (Split 50:50):** Jika Madrasah lunas, sisa dana dibagi rata:
  - 50% ke Sekolah Formal (SMP/MA/SMK).
  - 50% ke Pondok Pesantren.
- **Overflow Handling:** Jika alokasi Sekolah sudah lunas tapi masih ada sisa uang dari jatah 50%-nya, kelebihannya dilimpahkan ke Pondok.

### Fund Transfer & Settlement (Penting!)
- **Inter-Entity Transfer:** Fitur untuk memindahkan dana digital dari "Rekening Penampung Utama" (Xendit/Yayasan) ke "Rekening Operasional Lembaga" (Misal: Ke Rekening Kepala Sekolah SD). Dan fitur untuk transfer dana dari fincancial account ke financial account lainnya.
- **Journaling:** Setiap transfer antar lembaga wajib mencatat jurnal otomatis (Debit: Kas Sekolah, Kredit: Kas Yayasan/Piutang).

### WhatsApp Billing & Notifikasi
- Reminder tagihan otomatis (Cron Job).
- Notifikasi Real-time saat: Pembayaran masuk, Tagihan baru, Saldo tabungan menipis.

---

## Sub-Modul Manajemen Dompet Santri (`student_wallets`)

Pemisahan tegas fungsi dompet untuk mendukung *Cashless Society* dan kontrol penggunaan dana.

### Tuition Wallet (Deposit Pendidikan)

- **Fungsi:** Dompet *restricted* untuk menyimpan kelebihan bayar atau tabungan biaya pendidikan.
- **Restriksi:**
  - **No Withdrawal:** Tidak bisa ditarik tunai oleh santri.
  - **One-Way Out:** Saldo hanya bisa keluar untuk membayar tagihan sekolah (*Bill Settlement*).
- **Auto-Debet Logic:**
  - Saat tagihan baru terbit (*Generate Bill*), sistem otomatis mengecek saldo *Tuition Wallet*.
  - Jika saldo cukup, tagihan langsung lunas (*Auto-Settled*).
  - Jika kurang, saldo dikuras habis, sisa tagihan menjadi tunggakan (*Partial Paid*).

### Pocket Money Wallet (Uang Saku)

- **Fungsi:** Dompet transaksional untuk kebutuhan harian santri (Jajan, Laundry, Koperasi).
- **Fitur Utama:**
  - **Top-up:** Via VA, Transfer Manual, atau Admin TU.
  - **Daily Limit (Pagu Harian):**
    - Orang tua mensetting batas jajan per hari (Misal: Rp 20.000/hari).
    - **Logic:** *Use it or Lose it* (Sisa jatah hari ini tidak akumulasi ke besok) ATAU *Cumulative* (Tergantung kebijakan pondok).
  - **Emergency Withdrawal:** Santri bisa tarik tunai di kantor TU hanya jika ada surat izin pulang/sakit.
- **Keamanan Transaksi (POS Integration):**
  - **Visual Verification:** Saat Scan Kartu/QR di kantin, layar POS Kasir wajib menampilkan Foto Wajah Santri untuk mencegah penggunaan kartu curian/pinjam.
  - **PIN Protection:** Transaksi di atas nominal tertentu (misal > Rp 50rb) wajib input PIN 6 digit.
  - **Card Freeze:** Orang tua bisa membekukan kartu dari aplikasi jika kartu hilang.

### Transfer Antar Dompet (Inter-Wallet)

- **Pocket to Tuition:** **Allowed.** Orang tua bisa memindahkan saldo uang jajan untuk melunasi tunggakan SPP via aplikasi.
- **Tuition to Pocket:** **Restricted.** Memindahkan dana pendidikan ke uang jajan dilarang, kecuali dilakukan oleh Admin dengan hak akses khusus (*Reversal/Refund*).

---

## Sub-Modul Akuntansi & Laporan (`financial_accounting`)

Fokus: Automasi pencatatan jurnal (*Double Entry*) berbasis *Accrual* dan penyajian laporan *real-time* untuk pengambilan keputusan Yayasan.

### Automasi Jurnal (Automated Journaling)

Sistem bekerja dengan prinsip **"Transaction First, Journal Follows"**. Admin operasional tidak perlu menginput Debit/Kredit manual. Sistem menjurnal otomatis di latar belakang (*Event-Driven*) berdasarkan mapping akun yang sudah dikonfigurasi.

**Logika Jurnal (Accrual Basis):**

- **A. Saat Tagihan Terbit (Revenue Recognition)**
  - Terjadi setiap awal bulan atau saat tagihan dibuat. Pendapatan diakui saat tagihan muncul (hak tagih), bukan saat uang diterima.
  - **(Dr)** Piutang Santri (*Account Receivable*)
  - **(K)** Pendapatan Pendidikan (*Revenue*)

- **B. Saat Pembayaran Diterima (Cash In)**
  - Pelunasan kewajiban santri.
  - **(Dr)** Kas Tunai / Bank / Payment Gateway
  - **(K)** Piutang Santri

- **C. Saat Top-up Tuition Wallet (Deposit/Unearned Revenue)**
  - Uang diterima tapi belum ada tagihan. Diakui sebagai Hutang/Titipan, bukan Pendapatan.
  - **(Dr)** Kas Tunai / Bank
  - **(K)** Hutang Titipan Santri (*Student Deposit Liability*)

- **D. Saat Auto-Debet / Pembayaran via Wallet**
  - Konversi dari titipan menjadi pelunasan.
  - **(Dr)** Hutang Titipan Santri
  - **(K)** Piutang Santri

### Kontrol Pengeluaran & Anggaran (`expenses`)

- **Budget Control (Hard/Soft Limit):**
  - Saat mengajukan pengeluaran, sistem mengecek sisa anggaran (*Budget Remaining*) pos tersebut secara *real-time*.
  - **Workflow:** Input Pengajuan -> Approval Kepala Unit -> Approval Yayasan (jika nominal > Limit Tertentu) -> Pencairan Dana.

- **Cash Advance (Kasbon Kegiatan):**
  - Fitur untuk panitia/guru mengambil uang muka kegiatan.
  - **Status:** Tercatat sebagai Piutang Karyawan sementara sampai ada laporan pertanggungjawaban (LPJ).
  - **Settlement:** Wajib lapor realisasi.
    - **Jika sisa uang:** Dikembalikan ke kas ((D) Kas, (K) Piutang Karyawan).
    - **Jika kurang:** Reimbursement ((D) Beban, (K) Kas).

### Laporan Keuangan Utama

Laporan dapat difilter per Lembaga (Unit) atau Konsolidasi (Yayasan).

- **Laporan Posisi Keuangan (Neraca):**
  - Menampilkan posisi Aset (Kas, Piutang Santri, Aset Tetap) vs Kewajiban (Hutang Titipan Wallet, Hutang Gaji) & Aset Neto.

- **Laporan Realisasi Anggaran (LRA):**
  - Menyandingkan *Budget Plan* vs *Actual Expense*.
  - **Indikator efisiensi:** Menampilkan % serapan anggaran dan sisa pagu.

- **Laporan Arus Kas (Cash Flow):**
  - Mutasi detail uang masuk dan keluar per akun kas (Tunai, Bank, Payment Gateway).

- **Laporan Umur Piutang (Aging Receivables):**
  - Analisa kesehatan keuangan. Mengelompokkan santri penunggak berdasarkan durasi:
    - **0-30 Hari** (Lancar)
    - **31-90 Hari** (Perhatian Khusus)
    - **> 90 Hari** (Macet)
  - Dilengkapi tombol "Follow Up WA" massal per kategori.

- **Laporan Tutup Kas Harian (Daily Closing):**
  - Rekapitulasi total uang fisik dan bank hari ini untuk validasi bendahara sebelum pulang.