# SIMITRA — Sprint 3 Tahap 1: Manajemen Mitra (Dashboard Admin)

Membangun fitur pengelolaan mitra (sub-agen) di dashboard admin, termasuk pembuatan kode referral otomatis. Ini fondasi sebelum panel mitra dibangun. JANGAN merusak fungsi yang sudah berjalan (form publik, data pendaftaran, dashboard, ubah status).

**Konteks:** Laravel 10, Filament 3. Tabel `users` sudah ada dengan kolom: `id`, `nama`, `email`, `password`, `role` (enum 'admin'/'mitra'), `kode_referral`, `no_hp`, `status_aktif`. Model `User` sudah punya `getFilamentName()` dan `canAccessPanel()` (hanya admin yang boleh akses panel admin).

---

## BAGIAN 1: FILAMENT RESOURCE UNTUK MITRA

Buat Filament Resource untuk mengelola mitra. Karena mitra tersimpan di tabel `users` (dibedakan lewat kolom `role`), resource ini harus **hanya menampilkan user dengan role = 'mitra'** (jangan tampilkan admin).

Perintah: `php artisan make:filament-resource User --generate` — LALU sesuaikan, ATAU buat resource baru khusus (misal `MitraResource`) yang model-nya `User` dengan query difilter.

**Wajib:** query resource ini harus difilter agar hanya menampilkan `role = 'mitra'`. Gunakan override:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('role', 'mitra');
}
```

### Pengaturan Resource
- Navigation label: **"Manajemen Mitra"**
- Model label: "Mitra", plural: "Mitra"
- Ikon: heroicon yang sesuai (misal `heroicon-o-user-group`)
- Letakkan di sidebar setelah "Data Pendaftaran"

---

## BAGIAN 2: TABEL DAFTAR MITRA

Kolom yang ditampilkan:
1. **Nama** (`nama`) — searchable
2. **Email** (`email`) — searchable
3. **No HP** (`no_hp`)
4. **Kode Referral** (`kode_referral`) — tampilkan sebagai badge/teks yang menonjol, **bisa disalin** (gunakan `copyable()` jika tersedia)
5. **Jumlah Pelanggan** — hitung jumlah pendaftaran yang mitra_id-nya = id mitra ini (gunakan `counts()` atau withCount pada relasi)
6. **Status Aktif** (`status_aktif`) — tampilkan sebagai badge/toggle: Aktif (hijau) / Nonaktif (abu)
7. **Tanggal Dibuat** (`created_at`) — format tanggal Indonesia

**Fitur tabel:**
- Pencarian untuk nama, email, kode referral
- Filter berdasarkan status aktif (Aktif/Nonaktif)
- Urut default: terbaru

---

## BAGIAN 3: FORM TAMBAH / EDIT MITRA

Field pada form:
1. **Nama** (`nama`) — required
2. **Email** (`email`) — required, harus unik (validasi `unique:users,email`), tipe email
3. **No HP** (`no_hp`) — required
4. **Password** — required saat CREATE, opsional saat EDIT (jika dikosongkan saat edit, password lama dipertahankan). Gunakan input password + hashing otomatis (`->dehydrateStateUsing(fn ($state) => Hash::make($state))` dan `->dehydrated(fn ($state) => filled($state))` serta `->required(fn (string $context) => $context === 'create')`).
5. **Kode Referral** (`kode_referral`) — **dibuat OTOMATIS oleh sistem saat mitra baru dibuat**, ditampilkan sebagai field read-only/disabled. Jangan biarkan admin mengetik manual.
6. **Status Aktif** (`status_aktif`) — toggle, default aktif (true)

**Penting:** kolom `role` harus otomatis diisi `'mitra'` saat menyimpan (jangan tampilkan sebagai pilihan ke admin, karena resource ini khusus mitra).

---

## BAGIAN 4: PEMBUATAN KODE REFERRAL OTOMATIS

Saat mitra baru dibuat, sistem harus otomatis menghasilkan `kode_referral` yang **unik**.

**Format yang diinginkan:** `MITRA-XXXXX` di mana XXXXX adalah kombinasi huruf/angka acak (misal 5 karakter, huruf besar). Contoh: `MITRA-A3K9P`.

**Implementasi yang disarankan:** gunakan model event `creating` pada model `User`, yang mengisi `kode_referral` jika role = 'mitra' dan kode_referral masih kosong:

```php
protected static function booted(): void
{
    static::creating(function ($user) {
        if ($user->role === 'mitra' && empty($user->kode_referral)) {
            do {
                $kode = 'MITRA-' . strtoupper(Str::random(5));
            } while (self::where('kode_referral', $kode)->exists());
            $user->kode_referral = $kode;
        }
    });
}
```

Pastikan kode yang dihasilkan **dicek keunikannya** (tidak boleh sama dengan mitra lain).

Jangan ubah kode referral mitra yang sudah ada (kode tidak berubah saat edit).

---

## BAGIAN 5: AKSI TAMBAHAN PADA TABEL MITRA

Tambahkan action pada tiap baris mitra:
1. **Edit** — buka form edit
2. **Aktifkan/Nonaktifkan** — toggle `status_aktif` tanpa harus buka form edit (action cepat). Beri konfirmasi sebelum menonaktifkan.
3. **Salin Link Referral** — action yang menampilkan/menyalin link referral lengkap mitra tersebut, formatnya: `{URL_APLIKASI}/daftar?ref={kode_referral}`. Gunakan `url('/daftar?ref=' . $record->kode_referral)`. Bisa berupa action yang membuka modal berisi link + tombol salin, atau kolom copyable.

**Catatan:** Delete mitra sebaiknya dinonaktifkan atau diberi konfirmasi kuat, karena mitra yang punya data pendaftaran tidak boleh dihapus sembarangan (bisa merusak relasi). Sarankan gunakan nonaktifkan daripada hapus.

---

## BAGIAN 6: HAL PENTING TERKAIT MITRA NONAKTIF

Pada form pendaftaran publik, validasi kode referral saat ini mengecek mitra yang **aktif**. Pastikan mitra yang `status_aktif` = false tidak bisa lagi dipakai kode referralnya untuk pendaftaran baru (kode referral ditolak dengan pesan jelas). Jika logika ini sudah ada, biarkan; jika belum, pastikan diterapkan.

---

## KRITERIA SELESAI

1. Menu "Manajemen Mitra" muncul di sidebar admin dengan ikon.
2. Tabel hanya menampilkan user dengan role 'mitra' (admin tidak muncul).
3. Tabel menampilkan: nama, email, no HP, kode referral, jumlah pelanggan, status aktif, tanggal.
4. Admin bisa menambah mitra baru: isi nama, email, no HP, password → **kode referral otomatis dibuat** (format MITRA-XXXXX, unik).
5. Mitra baru otomatis punya `role` = 'mitra'.
6. Admin bisa edit mitra (password opsional saat edit).
7. Admin bisa mengaktifkan/menonaktifkan mitra.
8. Admin bisa melihat/menyalin link referral mitra (`/daftar?ref=KODE`).
9. Mitra yang dibuat bisa dipakai: buka `/daftar?ref=KODE_BARU` → nama mitra muncul di form.
10. Semua fungsi lama tetap berjalan.

## JANGAN
- Jangan tampilkan user admin di resource ini.
- Jangan biarkan admin mengetik kode referral manual (harus otomatis).
- Jangan hapus/ubah kode referral mitra yang sudah ada.
- Jangan rusak form pendaftaran publik atau data pendaftaran.
