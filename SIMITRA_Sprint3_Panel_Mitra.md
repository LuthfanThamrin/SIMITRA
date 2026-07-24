# SIMITRA — Sprint 3 Tahap 2: Panel Mitra

Membangun **panel terpisah untuk Mitra** menggunakan Filament, di mana mitra bisa login dan melihat data miliknya sendiri. JANGAN merusak panel admin atau fungsi yang sudah berjalan (form publik, data pendaftaran, manajemen mitra, dll).

**Konteks:** Laravel 10, Filament 3. Panel admin sudah ada di `/admin` (`AdminPanelProvider`). Tabel `users` punya kolom `role` ('admin'/'mitra'), `kode_referral`, `status_aktif`. Tabel `pendaftaran` punya `mitra_id` (FK ke users) dan `status` (pending/diproses/terpasang/ditolak).

---

## BAGIAN 1: BUAT PANEL MITRA BARU

Buat panel Filament kedua khusus mitra:

```bash
php artisan make:filament-panel mitra
```

Ini menghasilkan `app/Providers/Filament/MitraPanelProvider.php`. Konfigurasikan:

- **Path:** `/mitra` (jadi mitra login di `http://localhost:8000/mitra`)
- **ID:** `mitra`
- **Login:** aktifkan `->login()`
- **Branding (sama seperti panel admin):**
  ```php
  ->brandName('SIMITRA')
  ->colors(['primary' => Color::hex('#1D5FAE')])
  ->font('Plus Jakarta Sans')
  ```
  Jika logo sudah dipasang di panel admin, pasang juga di sini.
- Pastikan panel admin (`AdminPanelProvider`) tetap `->default()` dan tidak terganggu.

---

## BAGIAN 2: HAK AKSES — SANGAT PENTING

**Aturan keamanan yang WAJIB dipenuhi:**

1. **Hanya user dengan `role = 'mitra'` DAN `status_aktif = true` yang boleh masuk panel `/mitra`.** Admin tidak boleh masuk panel mitra; mitra tidak boleh masuk panel admin (aturan admin sudah ada).

2. Model `User` saat ini punya method `canAccessPanel(Panel $panel)` yang mengembalikan `$this->role === 'admin'`. **Ubah** method ini agar mendukung dua panel:

```php
public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->role === 'admin';
    }

    if ($panel->getId() === 'mitra') {
        return $this->role === 'mitra' && $this->status_aktif;
    }

    return false;
}
```

3. **Mitra HANYA boleh melihat data miliknya sendiri.** Mitra A tidak boleh melihat pelanggan mitra B. Ini WAJIB diterapkan di setiap query pada panel mitra, dengan filter `where('mitra_id', auth()->id())`.

---

## BAGIAN 3: HALAMAN DASHBOARD MITRA (Ringkasan)

Halaman utama panel mitra. Berisi widget-widget berikut:

### 3.1 Widget Kartu Statistik (Stats Overview)
Tiga kartu, semuanya **hanya menghitung data milik mitra yang sedang login**:

| Kartu | Isi | Ikon |
|---|---|---|
| Total Pelanggan Masuk | jumlah pendaftaran dengan `mitra_id` = id mitra login | group/users |
| Pelanggan Terpasang | jumlah pendaftaran milik mitra dengan `status` = 'terpasang' | check-circle |
| Total Komisi | **SEMENTARA tampilkan "Rp 0" atau "Belum tersedia"** — perhitungan komisi belum dibangun (akan dikerjakan pada sprint berikutnya). Siapkan kartunya, tapi jangan buat perhitungan komisi sekarang. | wallet |

Buat dengan: `php artisan make:filament-widget StatistikMitra --stats-overview` (letakkan di panel mitra).

### 3.2 Widget Tabel Pelanggan Terbaru
Tabel 5 pendaftaran terbaru milik mitra yang login:
- Kolom: Nama Pelanggan (`nama_pemilik`), Nama Usaha, Tanggal Daftar, Status (badge berwarna: pending=kuning "Menunggu Verifikasi", diproses=biru, terpasang=hijau, ditolak=merah)
- Query WAJIB difilter: `where('mitra_id', auth()->id())`
- Urut terbaru, batasi 5 data
- Judul widget: "Pelanggan Terbaru"

**CATATAN:** Bagian "Progress Bonus" dan detail komisi (komisi dasar/bonus) pada rancangan desain BELUM dibuat sekarang — perhitungan komisi adalah pekerjaan sprint berikutnya. Jangan membuat perhitungan komisi apa pun.

---

## BAGIAN 4: HALAMAN "PELANGGAN SAYA"

Buat Filament Resource pada panel mitra untuk menampilkan pendaftaran milik mitra tersebut.

- Model: `Pendaftaran`
- Navigation label: **"Pelanggan Saya"**
- Ikon: heroicon users/user-group

**Query WAJIB difilter** agar mitra hanya melihat pelanggannya sendiri:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('mitra_id', auth()->id());
}
```

**Kolom tabel:**
1. No (id, format #000123)
2. Nama Pelanggan (`nama_pemilik`) — searchable
3. Nama Usaha (`nama_usaha`) — searchable
4. Paket (dari relasi paket, atau "Konsultasi" jika `konsultasi_paket` = true)
5. Kota
6. Tanggal Daftar (`created_at`) — format Indonesia, sortable
7. Status — badge berwarna (sama seperti di admin)

**Fitur:**
- Pencarian: nama pelanggan, nama usaha
- Filter: berdasarkan status
- Urut default: terbaru

**Hak akses — PENTING:**
- Mitra **TIDAK BOLEH** membuat, mengedit, atau menghapus data pendaftaran. Hanya **melihat** (read-only).
- Matikan Create, Edit, Delete: `canCreate()` return false, dan jangan sediakan halaman edit/delete. Sediakan halaman **View** (detail) saja.

**Halaman Detail (View):**
- Tampilkan data pelanggan (nama, usaha, HP, alamat, kota, jenis usaha, paket)
- Tampilkan status saat ini
- Tampilkan **catatan admin** (`catatan_admin`) jika ada — ini penting agar mitra tahu jika ada permintaan perbaikan dari admin
- Mitra tidak bisa mengubah apa pun di halaman ini (read-only)

---

## BAGIAN 5: HALAMAN "LINK & QR REFERRAL"

Buat halaman kustom Filament (bukan resource) pada panel mitra:

```bash
php artisan make:filament-page LinkReferral --panel=mitra
```

- Navigation label: **"Link & QR Referral"**
- Ikon: heroicon qr-code

**Isi halaman:**

1. **Kode Referral** — tampilkan kode referral mitra yang login (`auth()->user()->kode_referral`) dengan teks besar/menonjol.

2. **Link Referral** — tampilkan link lengkap: `url('/daftar?ref=' . auth()->user()->kode_referral)`
   - Sediakan **tombol "Salin Link"** yang menyalin link ke clipboard (gunakan JavaScript sederhana), dengan notifikasi "Link berhasil disalin".

3. **QR Code** — tampilkan QR code dari link referral tersebut.
   - Gunakan package `simplesoftwareio/simple-qrcode`. Install dengan: `composer require simplesoftwareio/simple-qrcode`
   - Generate QR dari link referral, tampilkan sebagai gambar (SVG atau PNG).
   - Sediakan **tombol "Unduh QR"** agar mitra bisa mengunduh gambar QR untuk dicetak/disebar.

4. **Petunjuk singkat** — teks pendek yang menjelaskan cara pakai, misal: "Sebarkan link atau QR code ini kepada calon pelanggan. Pendaftaran yang masuk melalui link/QR ini akan tercatat atas nama Anda."

---

## BAGIAN 6: STRUKTUR MENU PANEL MITRA

Menu di sidebar panel mitra (berurutan):
1. Dashboard (ringkasan)
2. Pelanggan Saya
3. Link & QR Referral

**Catatan:** Menu "Komisi Saya" dan "Papan Informasi" (yang ada pada rancangan desain) BELUM dibuat sekarang — keduanya adalah pekerjaan sprint berikutnya. Jangan membuat menu tersebut sekarang agar tidak ada menu kosong.

---

## KRITERIA SELESAI

1. Panel mitra bisa diakses di `/mitra` dengan halaman login.
2. **Keamanan:** mitra aktif bisa login ke `/mitra`; admin TIDAK bisa masuk `/mitra`; mitra TIDAK bisa masuk `/admin`; mitra nonaktif (`status_aktif` = false) tidak bisa login.
3. Dashboard mitra menampilkan kartu statistik (Total Pelanggan Masuk, Pelanggan Terpasang, Total Komisi placeholder) dengan angka yang benar — **hanya data milik mitra tersebut**.
4. Dashboard menampilkan tabel 5 pelanggan terbaru milik mitra tersebut.
5. Menu "Pelanggan Saya" menampilkan HANYA pendaftaran milik mitra yang login (mitra lain tidak terlihat). Read-only, ada halaman detail yang menampilkan catatan admin.
6. Menu "Link & QR Referral" menampilkan kode, link (bisa disalin), dan QR code (bisa diunduh).
7. Branding panel mitra sama dengan admin (SIMITRA, biru #1D5FAE, Plus Jakarta Sans).
8. Panel admin & form pendaftaran publik tetap berjalan normal.

## PENGUJIAN YANG DISARANKAN
- Login sebagai mitra A → hanya lihat pelanggan mitra A.
- Login sebagai mitra B → hanya lihat pelanggan mitra B (tidak lihat punya A).
- Coba login admin di `/mitra` → ditolak.
- Coba login mitra di `/admin` → ditolak.

## JANGAN
- Jangan biarkan mitra melihat data mitra lain (WAJIB filter `mitra_id`).
- Jangan izinkan mitra membuat/mengubah/menghapus data pendaftaran.
- Jangan membuat perhitungan komisi atau menu Komisi/Papan Informasi sekarang (sprint berikutnya).
- Jangan rusak panel admin atau form publik.
