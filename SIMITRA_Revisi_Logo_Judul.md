# SIMITRA — Revisi Tampilan Logo & Judul Panel

Tiga perbaikan tampilan pada panel Filament. JANGAN mengubah fungsi apa pun — ini murni tampilan.

File logo tersedia di: `public/images/logo-simitra.png`

---

## REVISI 1: Ganti Teks "Sign in" pada Halaman Login Mitra

**Masalah:** Halaman login mitra (`/mitra/login`) masih menampilkan judul default Filament yaitu **"Sign in"**. Diinginkan judul tersebut diganti menjadi **"SIMITRA"**.

**Yang diminta:**
Ubah judul (heading) halaman login pada panel mitra dari "Sign in" menjadi "SIMITRA".

Cara yang disarankan — buat custom login page untuk panel mitra:

1. Buat class login kustom, misal `app/Filament/Mitra/Pages/Auth/Login.php` yang meng-extend `Filament\Pages\Auth\Login`:

```php
<?php

namespace App\Filament\Mitra\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'SIMITRA';
    }

    public function getSubheading(): ?string
    {
        return 'Portal Mitra'; // opsional, boleh dihapus jika tidak diinginkan
    }
}
```

2. Daftarkan pada `MitraPanelProvider`:
```php
->login(\App\Filament\Mitra\Pages\Auth\Login::class)
```

**Alternatif yang lebih sederhana** (jika cara di atas menyulitkan): gunakan file terjemahan/lang untuk mengubah teks "Sign in". Pilih cara yang paling andal.

Terapkan hal yang sama pada **login panel admin** jika di sana juga masih menampilkan "Sign in", agar konsisten.

---

## REVISI 2: Logo di Samping Judul pada Panel Admin

**Masalah:** Pada panel admin, judul sudah menampilkan "SIMITRA", tetapi logo belum tampil di sampingnya.

**Yang diminta:** tampilkan **logo DAN teks "SIMITRA" bersebelahan** di sidebar/topbar panel admin.

Karena Filament secara default hanya menampilkan salah satu (logo ATAU brandName), gunakan salah satu pendekatan berikut:

**Pendekatan A (disarankan):** render brand kustom berisi logo + teks. Pada `AdminPanelProvider`, gunakan render hook untuk brand, atau buat view kustom:

```php
->brandName('SIMITRA')
->brandLogo(view('filament.brand'))
->brandLogoHeight('2.5rem')
```

Lalu buat view `resources/views/filament/brand.blade.php`:
```blade
<div class="flex items-center gap-2">
    <img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA" class="h-8 w-auto">
    <span class="text-lg font-bold">SIMITRA</span>
</div>
```

**Pendekatan B:** jika pendekatan A menyulitkan, cukup pastikan `brandLogo()` mengarah ke gambar logo yang **sudah memuat teks SIMITRA di dalam gambarnya** (jika file logo memang sudah bertuliskan SIMITRA), sehingga logo + teks tampil sekaligus.

Pilih pendekatan yang paling andal dan hasilnya rapi. Yang penting: **logo dan tulisan SIMITRA tampil bersebelahan** di panel admin.

---

## REVISI 3: Judul SIMITRA pada Panel Mitra

**Masalah:** Panel mitra belum menampilkan judul "SIMITRA".

**Yang diminta:** panel mitra harus menampilkan **logo + teks "SIMITRA"** bersebelahan, sama persis seperti panel admin (hasil Revisi 2).

Terapkan konfigurasi yang sama pada `MitraPanelProvider`:
```php
->brandName('SIMITRA')
->brandLogo(view('filament.brand'))   // gunakan view brand yang sama
->brandLogoHeight('2.5rem')
```

Gunakan view brand yang sama agar tampilan konsisten antar panel. Jika ingin membedakan, boleh tambahkan keterangan kecil "Portal Mitra" di bawah/di samping, tapi teks utama tetap "SIMITRA".

---

## KRITERIA SELESAI

1. Halaman login mitra (`/mitra/login`) menampilkan judul **"SIMITRA"**, bukan "Sign in".
2. Halaman login admin juga konsisten (tidak menampilkan "Sign in" default).
3. Panel admin menampilkan **logo + teks "SIMITRA"** bersebelahan di sidebar/topbar.
4. Panel mitra menampilkan **logo + teks "SIMITRA"** bersebelahan, konsisten dengan admin.
5. Logo proporsional, tidak gepeng, rapi di desktop maupun HP.
6. Semua fungsi lama tetap berjalan (login, dashboard, data pendaftaran, manajemen mitra, panel mitra, form publik).

## JANGAN
- Jangan ubah warna (#1D5FAE), font (Plus Jakarta Sans), atau konfigurasi panel lain yang sudah berjalan.
- Jangan ubah logika/fungsi apa pun.
- Jangan hilangkan kemampuan login (halaman login harus tetap berfungsi normal).
