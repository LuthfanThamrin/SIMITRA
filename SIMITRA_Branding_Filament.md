# SIMITRA — Branding Panel Filament (Warna, Font, Logo, Judul)

Menyesuaikan tampilan panel Filament agar sesuai identitas SIMITRA (warna, font, logo, judul), mengikuti desain yang sudah dibuat. Ini hanya perubahan tampilan/branding — JANGAN mengubah fungsi apa pun yang sudah berjalan (form publik, data pendaftaran, ubah status, dashboard widget, dll).

Berlaku untuk panel admin yang sudah ada (`AdminPanelProvider`), dan nantinya juga panel mitra saat dibuat.

---

## 1. WARNA (Brand Color)

Warna utama SIMITRA: **`#1D5FAE`** (biru korporat).

Terapkan sebagai warna primary panel Filament. Di `app/Providers/Filament/AdminPanelProvider.php`, pada method `panel()`:

```php
use Filament\Support\Colors\Color;

->colors([
    'primary' => Color::hex('#1D5FAE'),
])
```

Warna pendukung dari desain (untuk referensi jika dibutuhkan pada kustomisasi lanjutan):
- Background: `#f8f9ff`
- Surface variant: `#d3e4fe`
- Primary fixed: `#d6e3ff`
- Surface alt: `#F8FAFC`

---

## 2. FONT

Font yang dipakai pada desain:
- **Judul/Headline:** `Comfortaa` (weight 700)
- **Body/Teks:** `Plus Jakarta Sans` (weight 400, 500, 600)

Terapkan di panel Filament:

```php
->font('Plus Jakarta Sans')
```

Filament secara default memuat font dari Google Fonts, sehingga `Plus Jakarta Sans` bisa langsung dipakai sebagai font utama panel.

Untuk font judul `Comfortaa` (opsional, jika ingin lebih sesuai desain): tambahkan lewat custom theme/CSS. Jika terlalu kompleks, cukup gunakan `Plus Jakarta Sans` sebagai font utama panel — ini sudah cukup mendekati desain. **Jangan mempersulit; prioritaskan font body yang benar.**

---

## 3. JUDUL / BRAND NAME

Saat ini panel masih menampilkan judul default **"Laravel"** di kiri atas dan pada judul tab browser. Ubah menjadi **SIMITRA**.

Pada `AdminPanelProvider`:

```php
->brandName('SIMITRA')
```

Ini akan mengubah teks brand pada sidebar/topbar dan judul halaman.

---

## 4. LOGO

Ganti teks brand dengan logo SIMITRA (jika file logo tersedia).

**Persiapan:** file logo akan disimpan di `public/images/logo-simitra.png` (disediakan oleh pemilik project).

Pada `AdminPanelProvider`:

```php
->brandLogo(asset('images/logo-simitra.png'))
->brandLogoHeight('2rem')
```

Ketentuan:
- Jika file logo belum tersedia, **cukup gunakan `brandName('SIMITRA')` saja** (teks). Jangan memaksa memasang logo yang belum ada — nanti tinggal ditambahkan.
- Jika logo dipasang, pastikan tetap terlihat baik pada mode terang maupun gelap. Jika perlu, gunakan `->darkModeBrandLogo()` untuk versi gelap.

---

## 5. FAVICON (opsional)

Jika tersedia file favicon, tambahkan:

```php
->favicon(asset('images/favicon.png'))
```

Jika belum ada, lewati bagian ini.

---

## 6. CONTOH HASIL AKHIR PADA AdminPanelProvider

Struktur akhir kira-kira seperti ini (sesuaikan dengan kode yang sudah ada, JANGAN hapus konfigurasi yang sudah berjalan seperti `->login()`, `->discoverResources()`, `->widgets()`, dll):

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->brandName('SIMITRA')
        ->brandLogo(asset('images/logo-simitra.png'))   // hanya jika file logo ada
        ->brandLogoHeight('2rem')
        ->colors([
            'primary' => Color::hex('#1D5FAE'),
        ])
        ->font('Plus Jakarta Sans')
        // ... konfigurasi lain yang SUDAH ADA tetap dipertahankan
        ;
}
```

---

## KRITERIA SELESAI

1. Warna utama panel berubah menjadi biru `#1D5FAE` (tombol, highlight menu aktif, dsb).
2. Font panel menggunakan `Plus Jakarta Sans`.
3. Brand name berubah dari "Laravel" menjadi **SIMITRA** (terlihat di panel dan judul tab browser).
4. Logo tampil jika file logo tersedia; jika belum ada, cukup teks "SIMITRA".
5. Semua fungsi lama tetap berjalan normal (menu Data Pendaftaran, dashboard, ubah status, form publik).

## JANGAN
- Jangan hapus konfigurasi panel yang sudah ada (login, resources, widgets, dll).
- Jangan memasang logo jika file-nya belum tersedia — gunakan brandName saja.
- Jangan ubah fungsi/logika apa pun; ini murni perubahan tampilan.
