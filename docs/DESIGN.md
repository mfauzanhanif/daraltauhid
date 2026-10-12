# Panduan Desain & Antarmuka (Neobrutalism) — Dar Al Tauhid EMS

**Dokumen Terkait:** [PRD.md](file:///c:/laragon/www/daraltauhid/docs/PRD.md) · [TECHSTACK.md](file:///c:/laragon/www/daraltauhid/docs/TECHSTACK.md) · [MODULES.md](file:///c:/laragon/www/daraltauhid/docs/MODULES.md) · [DATABASE.md](file:///c:/laragon/www/daraltauhid/docs/DATABASE.md) · [ROADMAP.md](file:///c:/laragon/www/daraltauhid/docs/ROADMAP.md)

---

## 1. Filosofi Desain: Neobrutalism

Sistem Informasi Manajemen Dar Al Tauhid menerapkan bahasa desain **Neobrutalism**. Berbeda dari tren korporat biasa yang serba abu-abu redup (soft minimalism), Neobrutalism menghadirkan antarmuka yang berkarakter kuat, berani, jujur, dan mudah dibaca oleh berbagai kalangan (guru senior, staf tata usaha, musyrif, hingga wali santri di ponsel).

### Karakteristik Visual Utama:
1. **Garis Batas Tegas (Thick Solid Borders):** Setiap komponen utama dibingkai oleh garis tegas `border-2 border-black` atau `border-[3px] border-black`.
2. **Hard Drop Shadows (Tanpa Blur):** Menggunakan bayangan solid offset retro tanpa Gaussian blur, memberikan ilusi kedalaman fisik (`shadow-[4px_4px_0_0_#000]`).
3. **Kontras Warna Tinggi:** Latar belakang terang dipadukan dengan aksen warna cerah yang kaya (kuning kenari, sian cerah, hijau limau, koral) dan teks hitam pekat.
4. **Mikro-Animasi Responsif:** Efek interaktif tombol yang menekan ke dalam saat diklik (`translate-x-[2px] translate-y-[2px]` dengan reduksi shadow).
5. **Tipografi Hibrida:** Kombinasi **Inter** untuk keterbacaan teks antarmuka dan **JetBrains Mono** untuk angka, kode NIK/NISN, status badge, dan nominal keuangan.

---

## 2. Palet Warna & Token Desain

```
+-------------------------------------------------------------------------------+
|  CANVAS / BASE     |  INK / BORDER     |  PRIMARY ACCENT   |  SECONDARY CYAN  |
|  #FFFDF9           |  #000000          |  #FFE600          |  #38BDF8         |
|  (Warm Paper)      |  (Deep Black)     |  (Canary Yellow)  |  (Sky Blue)      |
+-------------------------------------------------------------------------------+
|  SUCCESS LIME      |  WARNING ORANGE   |  DANGER CORAL     |  PURPLE ACCENT   |
|  #4ADE80           |  #FB923C          |  #F87171          |  #C084FC         |
|  (Green Mint)      |  (Amber Pop)      |  (Pastel Red)     |  (Lilac)         |
+-------------------------------------------------------------------------------+
```

### Definisi Variabel Tailwind:
- **Background Canvas:** `bg-[#FFFDF9]` (hangat seperti kertas buku) atau `bg-slate-50`.
- **Card Surface:** `bg-white`.
- **Text Ink:** `text-black` / `text-slate-900`.
- **Primary Brand (Pondok & Yayasan):** `bg-[#FFE600]` (Kuning Kenari Ikonik).
- **Secondary (SMP Plus):** `bg-[#38BDF8]` (Biru Muda Segar).
- **Secondary (MA Nusantara):** `bg-[#4ADE80]` (Hijau Daun Segar).
- **Secondary (MI Dar Al Tauhid):** `bg-[#FB923C]` (Oranye Semangat).
- **Border Default:** `border-2 border-black`.
- **Shadow Default:** `shadow-[4px_4px_0_0_#000]`.
- **Shadow Hover:** `hover:shadow-[2px_2px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px]`.
- **Shadow Active:** `active:shadow-none active:translate-x-[4px] active:translate-y-[4px]`.

---

## 3. Tipografi

Sistem menggunakan dua keluarga font Google Fonts:

1. **Font UI Utama (`font-sans`):** `Inter, -apple-system, BlinkMacSystemFont, sans-serif`
   - Digunakan untuk: Judul halaman, label navigasi, form input, isi tabel, tombol, dan teks deskripsi.
   - Karakter: Bersih, humanis, sangat mudah dibaca pada layar monitor maupun layar HP.
2. **Font Teknis & Numerik (`font-mono`):** `JetBrains Mono, monospace`
   - Digunakan untuk: NIK, NISN, nomor registrasi PSB, jam pelajaran, angka skor nilai, nominal uang (Rupiah), tanggal kalender, dan badge kode singkatan.
   - Karakter: Modern, presisi, proporsi angka seragam (tabular figures).

---

## 4. Spesifikasi Komponen Inti

### 4.1 Tombol (Buttons)

#### Tombol Utama (Primary Action)
```tsx
<button className="inline-flex items-center justify-center font-bold px-5 py-2.5 bg-[#FFE600] text-black border-2 border-black shadow-[4px_4px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_0_#000] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all rounded-lg">
  Simpan Data
</button>
```

#### Tombol Sekunder / Netral
```tsx
<button className="inline-flex items-center justify-center font-bold px-5 py-2.5 bg-white text-black border-2 border-black shadow-[4px_4px_0_0_#000] hover:bg-slate-100 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_0_#000] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all rounded-lg">
  Batal
</button>
```

#### Tombol Bahaya (Destructive / Delete)
```tsx
<button className="inline-flex items-center justify-center font-bold px-4 py-2 bg-[#F87171] text-black border-2 border-black shadow-[4px_4px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_0_#000] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none transition-all rounded-lg">
  Hapus
</button>
```

---

### 4.2 Kartu Data & Kontainer (Cards & Panels)

```tsx
<div className="bg-white border-2 border-black rounded-xl p-6 shadow-[4px_4px_0_0_#000]">
  <div className="flex items-center justify-between pb-4 border-b-2 border-black mb-4">
    <h3 className="text-lg font-black tracking-tight text-black">Ringkasan Santri Aktif</h3>
    <span className="font-mono text-xs font-bold px-2 py-1 bg-[#86EFAC] border border-black rounded shadow-[2px_2px_0_0_#000]">
      TERVALIDASI
    </span>
  </div>
  <p className="text-sm font-medium text-slate-700">
    Total santri aktif di unit Pondok Pesantren tahun ajaran 2025/2026.
  </p>
</div>
```

---

### 4.3 Workspace Switcher (Pemilih Lembaga di Header)

Komponen vital yang berada di navbar atas dashboard. Menampilkan lembaga yang sedang aktif dan memungkinkan pengguna berpindah lembaga:

```tsx
<div className="relative inline-block">
  <button className="flex items-center gap-2 px-3 py-1.5 bg-[#FFE600] border-2 border-black rounded-lg shadow-[3px_3px_0_0_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0_0_#000] transition-all font-bold text-sm">
    <Building2 className="w-4 h-4 stroke-[2.5]" />
    <span>SMP Plus Dar Al Tauhid</span>
    <ChevronDown className="w-4 h-4 stroke-[2.5]" />
  </button>
</div>
```

---

### 4.4 Badge Status & Label Tag

Digunakan untuk status santri, tahapan PSB, kehadiran, dan kelulusan:

- **Aktif / Lolos:**
  ```tsx
  <span className="font-mono text-xs font-bold px-2.5 py-0.5 bg-[#4ADE80] text-black border border-black rounded-md shadow-[2px_2px_0_0_#000]">
    AKTIF
  </span>
  ```
- **Menunggu / Verifikasi:**
  ```tsx
  <span className="font-mono text-xs font-bold px-2.5 py-0.5 bg-[#FDE047] text-black border border-black rounded-md shadow-[2px_2px_0_0_#000]">
    VERIFIKASI
  </span>
  ```
- **Ditolak / Alpa / Tidak Lulus:**
  ```tsx
  <span className="font-mono text-xs font-bold px-2.5 py-0.5 bg-[#F87171] text-black border border-black rounded-md shadow-[2px_2px_0_0_#000]">
    TIDAK LOLOS
  </span>
  ```
- **Santri Lintas Lembaga (Pondok + Sekolah):**
  ```tsx
  <span className="font-mono text-xs font-bold px-2.5 py-0.5 bg-[#C084FC] text-black border border-black rounded-md shadow-[2px_2px_0_0_#000]">
    GANDA: PONDOK + SMP
  </span>
  ```

---

### 4.5 Formulir & Input Data

Semua kolom formulir memiliki border hitam solid dan focus ring dengan gaya Neobrutalism yang kontras:

```tsx
<div className="space-y-1.5">
  <label className="block text-sm font-bold text-black">
    Nomor Induk Kependudukan (NIK) <span className="text-red-500">*</span>
  </label>
  <input
    type="text"
    placeholder="3209..."
    className="w-full px-3.5 py-2.5 bg-white border-2 border-black rounded-lg font-mono text-sm shadow-[3px_3px_0_0_#000] focus:outline-none focus:ring-2 focus:ring-[#FFE600] focus:border-black placeholder:text-slate-400"
  />
  <p className="text-xs font-medium text-slate-600">Wajib 16 digit sesuai Kartu Keluarga.</p>
</div>
```

---

### 4.6 Indikator Kelengkapan Data Santri

Fitur *Pemeriksaan Data Belum Lengkap* (3.1 & 3.2 PRD) menggunakan progress bar visual Neobrutalism:

```tsx
<div className="space-y-1">
  <div className="flex justify-between text-xs font-mono font-bold">
    <span>Kelengkapan Berkas</span>
    <span>65%</span>
  </div>
  <div className="w-full h-3 bg-white border-2 border-black rounded-full overflow-hidden shadow-[2px_2px_0_0_#000]">
    <div className="h-full bg-[#FFE600] border-r-2 border-black" style={{ width: '65%' }}></div>
  </div>
</div>
```

---

## 5. Perbedaan Karakteristik Antar Domain

| Aspek | Aplikasi Inti (`app.*`) | Portal PSB (`psb.*`) | Landing Page (`daraltauhid.com` & `mis.*`) |
|---|---|---|---|
| **Teknologi Render** | Inertia.js + React 19 (SPA) | Inertia.js + React 19 (SPA) | Inertia.js + React 19 (SPA + Inertia `<Head>`) |
| **Audiens Target** | Admin, Guru, Musyrif, Wali Siswa | Calon Santri & Calon Wali | Masyarakat umum, Alumni, Simpatisan |
| **Kepadatan Data (Density)** | Tinggi (Tabel data, metrik, shortcut) | Sedang (Form langkah demi langkah) | Rendah (Visual hero, narasi, foto galeri) |
| **Prioritas Utama** | Efisiensi & Kecepatan Input Data | Kemudahan ponsel & upload berkas | Keindahan branding & skor SEO 100% |
| **Header Navigasi** | Workspace Switcher, Profil, Notif | Step Pendaftaran, Cek Kelulusan | Menu Profil, Unit Pendidikan, Berita |
