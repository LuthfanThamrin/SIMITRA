# SIMITRA — Revisi Form Pendaftaran (Field Baru, Dokumen, Link Maps)

Lakukan revisi pada fitur Form Pendaftaran publik (`/daftar`) yang SUDAH ADA dan berjalan. Revisi ini menyesuaikan form dengan format data resmi. JANGAN merusak fungsi yang sudah berjalan (upload, peta Leaflet, referral, penyimpanan data, validasi, modal konfirmasi, animasi loading — semua tetap dipertahankan).

Kerjakan dalam 2 tahap berurutan: (1) migration database, (2) update form & controller.

---

## TAHAP 1: MIGRATION DATABASE (tambah kolom baru)

Tabel `pendaftaran` sudah ada. Tambahkan 3 kolom baru lewat migration BARU (jangan ubah migration lama). Buat file migration baru, misalnya `add_fields_to_pendaftaran_table`, dengan isi berikut:

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
            $table->string('cp_alternatif')->nullable()->after('no_hp');
            $table->text('alamat_instalasi')->nullable()->after('cp_alternatif');
            $table->string('link_maps')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['cp_alternatif', 'alamat_instalasi', 'link_maps']);
        });
    }
};
```

Setelah file dibuat, jalankan `php artisan migrate`.

Update juga `$fillable` pada model `Pendaftaran` (`app/Models/Pendaftaran.php`) agar mencakup kolom baru: `cp_alternatif`, `alamat_instalasi`, `link_maps`.

---

## TAHAP 2: UPDATE FORM & CONTROLLER

### 2.1 Field yang DITAMBAHKAN ke form (bagian Data Diri & Usaha)

Tambahkan field berikut pada form, dengan urutan yang rapi:

1. **Alamat Instalasi** (WAJIB)
   - Input berupa `<textarea>` (karena alamat bisa panjang).
   - Label: "Alamat Instalasi".
   - Placeholder contoh: "Jl. ..., Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos".
   - name: `alamat_instalasi`.

2. **CP Alternatif** (OPSIONAL)
   - Input teks/tel.
   - Label: "CP Alternatif (opsional)".
   - Placeholder: "Nomor kontak cadangan".
   - name: `cp_alternatif`.

### 2.2 Perubahan pada Dokumen (dari 4 dokumen jadi 3)

Format resmi hanya butuh 3 dokumen. Sesuaikan bagian Dokumen Persyaratan:

1. **Foto KTP** (WAJIB) — tetap seperti sekarang, name: `foto_ktp`.
2. **Foto NPWP / NIB / Dokumen Usaha** (WAJIB) — ini gabungan. Gunakan kolom/field `foto_nib_npwp` yang sudah ada. Ubah labelnya menjadi "Foto NPWP / NIB / Dokumen Usaha Lainnya". name: `foto_nib_npwp`.
3. **Foto Tampak Depan Usaha (Keseluruhan)** (WAJIB) — gunakan field `foto_lokasi` yang sudah ada. Label: "Foto Tampak Depan Usaha (Keseluruhan)". name: `foto_lokasi`.

**Field "Foto Surat Izin Usaha" (`foto_izin_usaha`) yang lama:**
- HAPUS field ini dari tampilan form (tidak ditampilkan lagi ke pengguna).
- JANGAN hapus kolomnya dari database (biarkan ada untuk data lama).
- Di controller, saat menyimpan, set `foto_izin_usaha` = null (atau tidak diisi) karena sudah tidak dipakai. Pastikan validasi TIDAK mewajibkan `foto_izin_usaha` lagi.

### 2.3 Lokasi — Peta Leaflet TETAP + Auto-generate Link Google Maps

Pertahankan peta Leaflet + fitur "Ambil Lokasi Saat Ini" seperti sekarang (GRATIS, jangan diganti Google Maps API). TAMBAHKAN logika berikut:

- Saat koordinat (latitude, longitude) sudah ditentukan pengguna, secara otomatis buat link Google Maps dengan format:
  `https://www.google.com/maps?q=LATITUDE,LONGITUDE`
  Contoh: jika lat = -1.2379 dan long = 116.8529, maka link = `https://www.google.com/maps?q=-1.2379,116.8529`
- Simpan link ini ke kolom `link_maps` saat form disubmit. Bisa dihasilkan di sisi klien (JavaScript, disimpan ke input hidden `link_maps`) ATAU di sisi server (controller, dibuat dari latitude & longitude yang masuk). Pilih salah satu; membuat di controller lebih andal.
- (Opsional) Tampilkan link maps yang ter-generate di bawah peta sebagai teks yang bisa diklik, agar pengguna bisa verifikasi.

### 2.4 Validasi di Controller (method store)

Sesuaikan aturan validasi:
- `nama_pemilik`: required, string, max 255
- `nama_usaha`: required, string, max 255
- `no_hp`: required, string (validasi format nomor HP seperti sebelumnya)
- `cp_alternatif`: nullable, string, max 20
- `alamat_instalasi`: required, string  ← WAJIB
- `jenis_usaha`: required (enum seperti sebelumnya)
- `jenis_usaha_lainnya`: required_if jenis_usaha == 'lainnya'
- `foto_ktp`: required, file, mimes:jpg,jpeg,png,pdf, max:2048
- `foto_nib_npwp`: required, file, mimes:jpg,jpeg,png,pdf, max:2048  ← WAJIB (gabungan NPWP/NIB/Usaha)
- `foto_lokasi`: required, file, mimes:jpg,jpeg,png,pdf, max:2048  ← WAJIB (Tampak Depan)
- `foto_izin_usaha`: TIDAK divalidasi / tidak wajib (sudah tidak dipakai)
- `latitude`, `longitude`: required, numeric
- `kode_referral`: required, harus cocok dengan mitra aktif (seperti sebelumnya)

Semua pesan error dalam Bahasa Indonesia.

### 2.5 Penyimpanan (method store)

Saat menyimpan ke tabel `pendaftaran`, sertakan kolom baru:
- `cp_alternatif` (dari input, boleh null)
- `alamat_instalasi` (dari input)
- `link_maps` (di-generate dari latitude & longitude: `"https://www.google.com/maps?q={$latitude},{$longitude}"`)
- `foto_izin_usaha` = null

Kolom lain (status = 'pending', sumber_input = 'pelanggan', mitra_id dari referral) tetap seperti sebelumnya.

---

## URUTAN TAMPILAN FORM (setelah revisi)

**Bagian Data Diri & Usaha:**
1. Nama Usaha
2. Nama Pemilik / Penanggung Jawab (PIC)
3. Nomor HP (WhatsApp)
4. CP Alternatif (opsional)
5. Alamat Instalasi (textarea, wajib)
6. Jenis Usaha (dropdown) + Jenis Usaha Lainnya (muncul jika pilih "Lainnya")

**Bagian Dokumen Persyaratan (3 dokumen):**
1. Foto KTP (wajib)
2. Foto NPWP / NIB / Dokumen Usaha Lainnya (wajib)
3. Foto Tampak Depan Usaha Keseluruhan (wajib)

**Bagian Titik Lokasi Usaha:**
- Tombol "Ambil Lokasi Saat Ini" + peta Leaflet (tetap)
- Auto-generate & simpan link Google Maps dari koordinat

---

## KRITERIA SELESAI

1. Migration jalan, kolom `cp_alternatif`, `alamat_instalasi`, `link_maps` ada di tabel pendaftaran.
2. Form menampilkan field baru: Alamat Instalasi (wajib), CP Alternatif (opsional).
3. Dokumen menjadi 3 (KTP, NPWP/NIB/Usaha, Tampak Depan). Field "Izin Usaha" lama tidak tampil lagi.
4. Peta Leaflet tetap berfungsi, dan link Google Maps ter-generate otomatis dari koordinat lalu tersimpan di `link_maps`.
5. Submit berhasil menyimpan semua data termasuk kolom baru ke database.
6. Semua fungsi lama tetap jalan (referral, upload, modal konfirmasi, animasi loading, validasi, halaman sukses).
7. Data bisa diverifikasi masuk di database dengan benar.

## JANGAN

- Jangan pakai Google Maps API berbayar. Peta tetap Leaflet + OpenStreetMap (gratis). Link Google Maps cukup di-generate dari koordinat (tanpa API).
- Jangan hapus kolom `foto_izin_usaha` dari database.
- Jangan rusak fitur yang sudah berjalan.
