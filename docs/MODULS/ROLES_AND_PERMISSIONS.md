# USER, ROLE, AND PERMISSIONS MANAGEMENT

## USER (PENGGUNA)

Ketentuan relasi pengguna dan lembaga:

1. **Multi-Institution:** Satu akun User dapat terasosiasi dengan beberapa lembaga sekaligus.

2. **Multi-Role:** Satu akun User dapat memiliki lebih dari satu Role (peran) dalam satu lembaga yang sama.

## ROLE (PERAN)

Ketentuan manajemen peran:

1. **Institution-Scoped:** Definisi Role bersifat terikat dan spesifik pada lembaga tertentu.

2. **Role Management:**

- **Admin:** Memiliki akses penuh untuk mengelola (CRUD) seluruh role di semua lembaga.
- **Operator:** Hanya memiliki akses untuk mengelola role di lembaga tempat operator tersebut bertugas.

### Default Roles

Sistem menyediakan role bawaan (built-in) sebagai berikut:

1. Admin (Super User)
2. Operator (Admin Lembaga)
3. Kepala (Pimpinan Lembaga)
4. Bendahara
5. Guru (Ustadz untuk Pondok)
6. Wali (Orang Tua/Wali Santri)

## PERMISSIONS (HAK AKSES)

Pengaturan konfigurasi hak akses modul:

A. **Otoritas Konfigurasi**

- **Admin:** Memiliki otoritas penuh untuk mengonfigurasi permissions pada seluruh modul di semua lembaga.
- **Operator:** Memiliki otoritas terbatas untuk mengonfigurasi permissions hanya pada modul yang aktif di lembaganya sendiri.

B. **Spesifikasi Akses Role "Wali"**

Role Wali memiliki logika akses berbasis relasi siswa (Student-Relationship Based Access):

- Relevansi Lembaga: Wali hanya dapat mengakses modul atau informasi yang relevan dengan lembaga tempat anak terdaftar.
- Akses Terpusat (Unified Access): Jika Wali memiliki lebih dari satu anak di lembaga berbeda (misal: satu di MI, satu di Pondok), Wali dapat memantau seluruh data anak dalam satu akun (single dashboard).
