# SIMITRA — Pasang Logo di Seluruh Halaman

File logo sudah tersedia di: `public/images/logo-simitra.png`

Pasang logo ini di **seluruh halaman sistem**: panel admin (login + dashboard), panel mitra (login + dashboard), dan halaman publik (form pendaftaran + halaman sukses). JANGAN mengubah fungsi apa pun — ini murni penambahan tampilan.

**Catatan:** jika file logo berekstensi selain `.png`, sesuaikan nama file di seluruh kode.

---

## BAGIAN 1: PANEL FILAMENT (Admin & Mitra)

Filament otomatis menampilkan brand logo di **halaman login DAN di dalam panel (sidebar/topbar)**, cukup dengan satu konfigurasi.

### 1.1 Panel Admin
Pada `app/Providers/Filament/AdminPanelProvider.php`, di dalam method `panel()`, tambahkan:

```php
->brandLogo(asset('images/logo-simitra.png'))
->brandLogoHeight('2.5rem')
```

### 1.2 Panel Mitra
Pada `app/Providers/Filament/MitraPanelProvider.php`, tambahkan konfigurasi yang **sama persis**:

```php
->brandLogo(asset('images/logo-simitra.png'))
->brandLogoHeight('2.5rem')
```

### 1.3 Ketentuan
- **JANGAN hapus** `->brandName('SIMITRA')` yang sudah ada — biarkan sebagai fallback jika logo gagal dimuat.
- Sesuaikan `brandLogoHeight` agar proporsional. Mulai dari `'2.5rem'`; jika terlalu besar/kecil, sesuaikan (misal `'2rem'` atau `'3rem'`).
- Jika logo kurang terlihat pada mode gelap, tambahkan `->darkModeBrandLogo(asset('images/logo-simitra.png'))`.
- Logo akan otomatis muncul di **halaman login** dan **di dalam panel** — tidak perlu konfigurasi terpisah.

### 1.4 Favicon (opsional, jika mudah)
Tambahkan juga favicon agar tab browser menampilkan logo:
```php
->favicon(asset('images/logo-simitra.png'))
```

---

## BAGIAN 2: HALAMAN PUBLIK (Form Pendaftaran & Halaman Sukses)

Halaman publik dibuat manual dengan Blade, jadi logo dipasang langsung sebagai elemen `<img>`.

### 2.1 Form Pendaftaran (`resources/views/pendaftaran/create.blade.php`)
Saat ini bagian header hanya menampilkan **teks "SIMITRA"**. Ganti/lengkapi dengan logo:

- Ganti teks brand di header dengan gambar logo:
```blade
<img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA" class="h-10 w-auto">
```
- Letakkan di posisi header yang sama dengan teks SIMITRA saat ini (bagian atas halaman).
- Boleh tetap menampilkan teks "SIMITRA" di samping logo jika terlihat lebih baik — sesuaikan agar rapi.
- Pastikan ukuran logo proporsional dan responsif (tidak gepeng, tidak terlalu besar di HP). Gunakan `h-10 w-auto` atau sesuaikan.

### 2.2 Halaman Sukses (`resources/views/pendaftaran/success.blade.php`)
Tambahkan logo di bagian atas halaman, dengan cara yang sama:
```blade
<img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA" class="h-10 w-auto mx-auto">
```

### 2.3 Favicon halaman publik (opsional)
Pada bagian `<head>` kedua view publik, tambahkan:
```blade
<link rel="icon" type="image/png" href="{{ asset('images/logo-simitra.png') }}">
```

---

## KRITERIA SELESAI

1. Logo tampil di **halaman login admin** (`/admin/login`).
2. Logo tampil di **dalam panel admin** (sidebar/topbar).
3. Logo tampil di **halaman login mitra** (`/mitra/login`).
4. Logo tampil di **dalam panel mitra** (sidebar/topbar).
5. Logo tampil di **form pendaftaran publik** (`/daftar`).
6. Logo tampil di **halaman pendaftaran berhasil**.
7. Logo proporsional & rapi di desktop maupun HP (tidak gepeng/kebesaran).
8. Semua fungsi lama tetap berjalan (form submit, upload, peta, referral, panel admin/mitra, dll).

## JANGAN
- Jangan hapus `brandName('SIMITRA')` pada panel Filament.
- Jangan ubah warna, font, atau konfigurasi panel lain yang sudah berjalan.
- Jangan ubah logika/fungsi apa pun — ini hanya penambahan tampilan logo.
