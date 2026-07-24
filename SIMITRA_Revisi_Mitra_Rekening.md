# SIMITRA — Revisi Manajemen Mitra: Field Rekening, Bank, Alamat & Redirect

Revisi pada fitur Manajemen Mitra yang sudah dibangun. JANGAN merusak fungsi yang sudah berjalan (kode referral otomatis, filter role mitra, aktif/nonaktif, salin link referral, dll).

---

## BAGIAN 1: MIGRATION — TAMBAH KOLOM BARU DI TABEL `users`

Buat migration BARU untuk menambah 3 kolom pada tabel `users`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_bank')->nullable()->after('no_hp');
            $table->string('no_rekening')->nullable()->after('nama_bank');
            $table->text('alamat')->nullable()->after('no_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'no_rekening', 'alamat']);
        });
    }
};
```

**Catatan:** ketiga kolom dibuat `nullable` di database (karena user admin tidak perlu mengisinya), tetapi pada form mitra, `nama_bank` dan `no_rekening` diwajibkan lewat validasi form.

Tambahkan `nama_bank`, `no_rekening`, `alamat` ke `$fillable` pada model `User`.

Jalankan `php artisan migrate`.

---

## BAGIAN 2: TAMBAH FIELD PADA FORM MITRA

Tambahkan field berikut pada form tambah/edit mitra (di resource Manajemen Mitra):

1. **Nama Bank** (`nama_bank`)
   - Input teks bebas (bukan dropdown) — mitra/admin mengetik sendiri nama banknya.
   - Label: "Nama Bank"
   - Placeholder: "Contoh: BCA, BRI, Mandiri"
   - **WAJIB diisi** (required), pesan validasi Bahasa Indonesia: "Nama bank wajib diisi".

2. **Nomor Rekening** (`no_rekening`)
   - Input teks (hanya angka).
   - Label: "Nomor Rekening"
   - Placeholder: "Contoh: 1234567890"
   - **WAJIB diisi** (required), pesan: "Nomor rekening wajib diisi".
   - Validasi: hanya angka, panjang wajar (misal 5–20 digit).

3. **Alamat** (`alamat`)
   - Input textarea (alamat bisa panjang).
   - Label: "Alamat (opsional)"
   - **OPSIONAL** (nullable) — boleh dikosongkan.

**Penempatan:** letakkan setelah field "No HP", sebelum "Kode Referral". Jika form dikelompokkan dalam section, boleh dibuat section terpisah misal "Informasi Pembayaran" berisi Nama Bank + Nomor Rekening, dan Alamat masuk ke section data diri.

---

## BAGIAN 3: TAMBAH KOLOM PADA TABEL DAFTAR MITRA

Pada tabel daftar mitra, tambahkan kolom:
- **Bank & Rekening** — bisa digabung jadi satu kolom, contoh tampilan: "BCA - 1234567890". Atau dibuat 2 kolom terpisah (Nama Bank, No Rekening).
- Kolom ini boleh disembunyikan secara default (`toggleable`) agar tabel tidak terlalu penuh, tapi tetap bisa ditampilkan admin.

Alamat tidak perlu ditampilkan di tabel (cukup di form detail/edit).

---

## BAGIAN 4: REDIRECT SETELAH SIMPAN

Sama seperti pada resource Data Pendaftaran: setelah admin menekan tombol simpan (Create maupun Edit) pada Manajemen Mitra, halaman harus **kembali ke halaman daftar mitra**, bukan tetap di halaman form.

Tambahkan pada class halaman Edit dan Create di resource mitra:

```php
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

Terapkan pada:
- `EditMitra` / `EditUser` (halaman edit resource mitra)
- `CreateMitra` / `CreateUser` (halaman create resource mitra)

Notifikasi sukses tetap muncul setelah tersimpan.

---

## KRITERIA SELESAI

1. Migration jalan: kolom `nama_bank`, `no_rekening`, `alamat` ada di tabel `users`.
2. Form tambah/edit mitra menampilkan field: Nama Bank (wajib), Nomor Rekening (wajib), Alamat (opsional).
3. Validasi jalan: tidak bisa simpan mitra tanpa nama bank & nomor rekening; alamat boleh kosong.
4. Data tersimpan ke database dengan benar.
5. Tabel daftar mitra menampilkan info bank & rekening (boleh toggleable).
6. Setelah menekan simpan (create/edit), halaman kembali ke daftar mitra.
7. Semua fungsi lama tetap berjalan: kode referral otomatis, filter hanya role mitra, aktif/nonaktif, salin link referral.

## JANGAN
- Jangan wajibkan alamat (harus opsional).
- Jangan buat nama bank sebagai dropdown — harus input teks bebas.
- Jangan rusak fitur kode referral otomatis atau fungsi lain yang sudah berjalan.
- Jangan wajibkan kolom baru ini untuk user admin (admin tidak perlu punya rekening).
