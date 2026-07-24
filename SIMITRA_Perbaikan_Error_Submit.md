# SIMITRA — Perbaikan Error Submit Form Pendaftaran

Saat submit form pendaftaran (`/daftar`) muncul error. Perbaiki masalah berikut. JANGAN merusak fungsi yang sudah berjalan (upload, peta, referral, paket, panduan foto, penyimpanan data).

---

## MASALAH 1 (UTAMA): Column 'foto_izin_usaha' cannot be null

**Error:** `SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'foto_izin_usaha' cannot be null`

**Penyebab:** Field "Foto Izin Usaha" sudah dihapus dari form pendaftaran (digabung ke NPWP/NIB pada revisi sebelumnya), tetapi kolom `foto_izin_usaha` di tabel `pendaftaran` masih bersifat NOT NULL. Karena tidak ada input untuk kolom itu lagi, database menolak saat menyimpan.

**Perbaikan:**

1. Buat migration BARU untuk mengubah kolom `foto_izin_usaha` menjadi nullable:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('foto_izin_usaha')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('foto_izin_usaha')->nullable(false)->change();
        });
    }
};
```

2. Menggunakan `->change()` membutuhkan package `doctrine/dbal`. Jika belum terpasang, jalankan lebih dulu:
   `composer require doctrine/dbal`
   lalu jalankan `php artisan migrate`.

3. Di controller `PendaftaranController@store`, pastikan `foto_izin_usaha` tidak menyebabkan error: set nilainya `null` secara eksplisit (atau jangan sertakan di array penyimpanan). Jangan wajibkan `foto_izin_usaha` di validasi.

4. Setelah migration jalan, submit form harus berhasil dan data tersimpan dengan `foto_izin_usaha` = null.

---

## MASALAH 2 (ANTISIPASI): Error 419 Page Expired saat submit via modal konfirmasi

Sebelumnya sempat muncul error "419 Page Expired" saat submit lewat modal konfirmasi custom. Ini indikasi token CSRF tidak ikut terkirim. Pastikan hal berikut agar tidak terjadi lagi:

1. Tag `<form>` pendaftaran memiliki `@csrf` (token CSRF Laravel) di dalamnya.
2. Jika submit dilakukan via JavaScript setelah pengguna menekan "Ya, Kirim" pada modal konfirmasi, JavaScript harus men-submit **form asli yang berisi token CSRF** (contoh: panggil `document.getElementById('formPendaftaran').submit()` pada elemen form yang benar), BUKAN membuat request/fetch baru tanpa token.
3. Modal konfirmasi tidak boleh memindahkan atau menghapus input token CSRF dari dalam form. Jika modal berada di luar `<form>`, pastikan tombol "Ya, Kirim" tetap mensubmit form yang benar (yang berisi @csrf).
4. Tambahkan meta tag CSRF di `<head>` jika belum ada: `<meta name="csrf-token" content="{{ csrf_token() }}">`.
5. Setelah perbaikan, alur: isi form → klik Kirim → modal konfirmasi → "Ya, Kirim" → data terkirim tanpa error 419.

---

## KRITERIA SELESAI

1. Migration jalan, kolom `foto_izin_usaha` menjadi nullable.
2. Submit form pendaftaran BERHASIL tanpa error (baik error null maupun 419).
3. Data tersimpan ke database dengan benar (foto_izin_usaha = null, kolom lain terisi).
4. Halaman sukses muncul setelah submit.
5. Semua fungsi lama tetap berjalan (upload, peta, referral, paket, panduan foto, modal konfirmasi, animasi loading).

## JANGAN
- Jangan hapus kolom `foto_izin_usaha` (cukup jadikan nullable).
- Jangan wajibkan `foto_izin_usaha` di form/validasi.
- Jangan rusak fitur yang sudah berjalan.
