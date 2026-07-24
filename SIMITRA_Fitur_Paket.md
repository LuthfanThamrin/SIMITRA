# SIMITRA — Tambah Fitur Pilihan Paket Internet pada Form Pendaftaran

Tambahkan fitur **pemilihan paket internet** pada form pendaftaran (`/daftar`) yang sudah ada. Paket disimpan dalam tabel terpisah (`paket`) agar bisa dikelola admin. Dropdown paket dikelompokkan per kategori. JANGAN merusak fungsi form yang sudah berjalan.

Kerjakan dalam 4 tahap: (1) migration tabel paket, (2) migration kolom paket_id di pendaftaran, (3) seeder data paket, (4) update form/controller/model.

---

## TAHAP 1: MIGRATION TABEL `paket`

Buat migration baru untuk tabel `paket`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');       // contoh: "HSI Bisnis 1:1"
            $table->string('kategori');         // contoh: "HSI Bisnis", "WMS Lite"
            $table->string('kecepatan');        // contoh: "50 Mbps"
            $table->decimal('harga', 12, 2);    // contoh: 320000
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
```

Buat model `Paket` (`app/Models/Paket.php`) dengan `protected $table = 'paket';` dan `$fillable = ['nama_paket','kategori','kecepatan','harga','aktif']`.
Tambahkan accessor untuk label lengkap, misal method `getLabelAttribute()` yang mengembalikan: `"{nama_paket} {kecepatan} Rp{harga_terformat}/Bln"` (harga diformat dengan pemisah ribuan titik). Contoh hasil: "HSI Bisnis 1:1 50 Mbps Rp320.000/Bln".

---

## TAHAP 2: MIGRATION KOLOM `paket_id` DI TABEL `pendaftaran`

Buat migration baru untuk menambah kolom relasi paket di tabel `pendaftaran`:

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
            $table->foreignId('paket_id')->nullable()->after('jenis_usaha_lainnya')->constrained('paket')->nullOnDelete();
            $table->boolean('konsultasi_paket')->default(false)->after('paket_id'); // true jika pelanggan pilih "Konsultasi dulu"
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn(['paket_id', 'konsultasi_paket']);
        });
    }
};
```

`paket_id` dibuat nullable karena jika pelanggan memilih "Konsultasi dulu", paket_id kosong dan `konsultasi_paket` = true.

Tambahkan `paket_id` dan `konsultasi_paket` ke `$fillable` model `Pendaftaran`, dan tambahkan relasi:
```php
public function paket() { return $this->belongsTo(\App\Models\Paket::class); }
```

---

## TAHAP 3: SEEDER DATA PAKET

Buat seeder `PaketSeeder` (`database/seeders/PaketSeeder.php`) yang mengisi tabel paket dengan data berikut. Jalankan dengan `php artisan db:seed --class=PaketSeeder`.

Data paket (nama_paket, kategori, kecepatan, harga):

**Kategori "HSI Bisnis":**
- HSI Bisnis 1:1 | HSI Bisnis | 50 Mbps | 320000
- HSI Bisnis 1:1 | HSI Bisnis | 75 Mbps | 365000
- HSI Bisnis 1:1 | HSI Bisnis | 100 Mbps | 440000
- HSI Bisnis 1:1 | HSI Bisnis | 150 Mbps | 540000
- HSI Bisnis 1:1 | HSI Bisnis | 300 Mbps | 950000

**Kategori "HSI Basic":**
- HSI Basic 1:2 | HSI Basic | 50 Mbps | 355000
- HSI Basic 1:2 | HSI Basic | 75 Mbps | 415000
- HSI Basic 1:2 | HSI Basic | 100 Mbps | 535000
- HSI Basic 1:2 | HSI Basic | 200 Mbps | 790000
- HSI Basic 1:2 | HSI Basic | 300 Mbps | 1130000

**Kategori "WMS Lite":**
- WMS Lite | WMS Lite | 30 Mbps | 375000
- WMS Lite | WMS Lite | 40 Mbps | 475000
- WMS Lite | WMS Lite | 50 Mbps | 575000
- WMS Lite | WMS Lite | 100 Mbps | 1000000

**Kategori "WMS Reguler":**
- WMS Reguler Silver | WMS Reguler | 20 Mbps | 435000
- WMS Reguler Gold | WMS Reguler | 50 Mbps | 950000
- WMS Reguler Platinum | WMS Reguler | 50 Mbps | 1500000
- WMS Reguler Diamond | WMS Reguler | 200 Mbps | 4500000
- WMS Reguler Crown | WMS Reguler | 300 Mbps | 3050000

Set semua `aktif` = true.

---

## TAHAP 4: UPDATE FORM, CONTROLLER

### 4.1 Form (view create.blade.php)

Tambahkan field **"Paket yang Dipilih"** pada bagian Data Diri & Usaha (letakkan setelah Jenis Usaha, sebelum bagian Dokumen).

- Berupa dropdown (`<select name="paket_id">`).
- Opsi pertama (paling atas): **"-- Konsultasi Dulu (belum menentukan paket) --"** dengan value khusus, misal `value="konsultasi"`.
- Sisanya: daftar paket dari database, DIKELOMPOKKAN per kategori menggunakan `<optgroup label="...">`. Urutan grup: HSI Bisnis, HSI Basic, WMS Lite, WMS Reguler.
- Tiap `<option>` menampilkan label lengkap: "HSI Bisnis 1:1 50 Mbps Rp320.000/Bln", dengan value = id paket.
- Controller `create` harus mengirim data paket (dikelompokkan per kategori) ke view. Contoh: `Paket::where('aktif', true)->get()->groupBy('kategori')`.

Contoh struktur dropdown yang diinginkan:
```
-- Pilih Paket --
[Konsultasi Dulu (belum menentukan paket)]
── HSI Bisnis ──
   HSI Bisnis 1:1 50 Mbps Rp320.000/Bln
   HSI Bisnis 1:1 75 Mbps Rp365.000/Bln
   ...
── HSI Basic ──
   ...
── WMS Lite ──
   ...
── WMS Reguler ──
   ...
```

Field ini WAJIB dipilih (tidak boleh kosong — pengguna harus pilih paket ATAU "Konsultasi Dulu").

### 4.2 Controller (method store)

- Validasi: `paket_id` required. Nilai boleh berupa id paket yang valid ATAU string "konsultasi".
- Logika penyimpanan:
  - Jika `paket_id` == "konsultasi" → simpan `konsultasi_paket` = true, `paket_id` = null.
  - Jika `paket_id` berupa id angka → validasi bahwa id itu ada di tabel paket & aktif, simpan `paket_id` = id tersebut, `konsultasi_paket` = false.
- Pesan validasi Bahasa Indonesia, contoh: "Silakan pilih paket atau opsi konsultasi".

---

## TAHAP 5: (Filament) Kelola Paket dari Dashboard Admin

Buat Filament Resource untuk model Paket agar admin bisa kelola paket:
- Jalankan: `php artisan make:filament-resource Paket --generate`
- Pastikan resource menampilkan kolom: nama_paket, kategori, kecepatan, harga, aktif.
- Admin bisa tambah/edit/hapus/nonaktifkan paket.

Ini membuat daftar paket bisa diperbarui admin tanpa ubah kode.

---

## KRITERIA SELESAI

1. Tabel `paket` terbuat & terisi 19 paket via seeder.
2. Tabel `pendaftaran` punya kolom `paket_id` (nullable, FK ke paket) & `konsultasi_paket`.
3. Form pendaftaran menampilkan dropdown Paket, dikelompokkan per kategori (optgroup), dengan opsi "Konsultasi Dulu" di atas.
4. Submit: jika pilih paket → paket_id tersimpan; jika pilih konsultasi → konsultasi_paket=true, paket_id null.
5. Data tersimpan benar di database.
6. Admin bisa kelola paket via Filament (menu Paket muncul di dashboard).
7. Semua fungsi form lama tetap berjalan (field lain, upload, peta, referral, dll).

## JANGAN

- Jangan hapus/ubah kolom yang sudah ada di pendaftaran.
- Jangan rusak fitur form yang sudah jalan.
- Harga disimpan sebagai angka (decimal), diformat dengan titik ribuan hanya saat ditampilkan.
