# SIMITRA — Revisi Form Pendaftaran (Field Kota, Paket KDMP, Layout Upload)

Tiga revisi pada form pendaftaran (`/daftar`) yang sudah berjalan. JANGAN merusak fungsi yang sudah ada (upload, peta, referral, paket, penyimpanan data, validasi, modal konfirmasi, animasi loading).

---

## REVISI 1: Tambah Field "Kota"

Tambahkan field **Kota** pada bagian Data Diri & Usaha di form pendaftaran.

**Tahap database:**
- Buat migration baru untuk menambah kolom `kota` (string, nullable di database) di tabel `pendaftaran`, diletakkan setelah kolom `alamat_instalasi`.

```php
Schema::table('pendaftaran', function (Blueprint $table) {
    $table->string('kota')->nullable()->after('alamat_instalasi');
});
```

- Tambahkan `kota` ke `$fillable` model `Pendaftaran`.

**Tahap form:**
- Tambah field input teks "Kota" pada form, diletakkan dekat/setelah Alamat Instalasi. Label: "Kota". Placeholder contoh: "Contoh: Bontang".
- name: `kota`.
- WAJIB diisi (validasi di controller: `kota` => required, string, max 100, dengan pesan Bahasa Indonesia "Kota wajib diisi").
- Simpan nilai `kota` ke database saat submit.

---

## REVISI 2: Tambah Paket KDMP Lengkap (A-D) ke Tabel Paket

Tambahkan 4 paket bundling KDMP ke tabel `paket` yang sudah ada. Semua berkategori "KDMP", harga 7464000, satuan per tahun.

Buat/ tambahkan ke seeder (atau tambahkan data via seeder baru `PaketKdmpSeeder`) data berikut:

| nama_paket | kategori | kecepatan | harga | keterangan |
|---|---|---|---|---|
| Paket A Lengkap | KDMP | 50 Mbps | 7464000 | Digikop, IP Cam Indoor, Cloud Recording, People Counting (AI Video Analytic) |
| Paket B Lengkap | KDMP | 50 Mbps | 7464000 | Digikop, Duolink Cam Indoor, Cloud Recording, People Counting (AI Video Analytic) |
| Paket C Lengkap | KDMP | 50 Mbps | 7464000 | Digikop, IP Cam Indoor, Cloud Recording, Smoke & Fire Detection (AI Video Analytic) |
| Paket D Lengkap | KDMP | 50 Mbps | 7464000 | Padi Kasir (POS), Duolink Cam Indoor, Cloud Recording, Smoke & Fire Detection (AI Video Analytic) |

**Catatan penting soal kolom keterangan:**
- Tabel `paket` saat ini mungkin belum punya kolom untuk menyimpan rincian fitur bundling. Tambahkan kolom baru `keterangan` (text, nullable) ke tabel `paket` lewat migration, untuk menyimpan rincian fitur paket lengkap (Digikop, IP Cam, dll).

```php
Schema::table('paket', function (Blueprint $table) {
    $table->text('keterangan')->nullable()->after('harga');
});
```

- Tambahkan `keterangan` ke `$fillable` model `Paket`.
- Untuk paket biasa (HSI/WMS), kolom `keterangan` boleh null.

**Tampilan di dropdown:**
- Paket KDMP muncul sebagai grup tersendiri `<optgroup label="KDMP (Paket Lengkap)">`.
- Label option: "Paket A Lengkap 50 Mbps Rp7.464.000/Thn" (perhatikan satuan per Tahun untuk KDMP, sedangkan paket lain per Bulan). Jika memungkinkan, tampilkan satuan yang benar (/Thn untuk KDMP, /Bln untuk lainnya). Jika sulit membedakan satuan, cukup tampilkan nominal harga + label paket; satuan bisa diabaikan untuk sekarang.

Paket HSI/WMS yang sudah ada JANGAN diubah.

---

## REVISI 3: Perbaiki Layout Kotak Upload Dokumen (rata & sejajar)

**Masalah:** Pada tampilan form, kotak upload "Foto NPWP / NIB / Dokumen Usaha Lainnya" posisinya turun/condong ke bawah dan tidak sejajar dengan kotak "Foto KTP" di sebelahnya. Ini karena label "Foto NPWP / NIB / Dokumen Usaha Lainnya" lebih panjang (2 baris) sehingga mendorong kotaknya ke bawah, membuat kedua kotak tidak rata.

**Perbaikan yang diminta:**
- Buat kotak-kotak upload dokumen memiliki **tinggi yang sama dan sejajar rata** (rata atas), meskipun panjang labelnya berbeda.
- Gunakan pendekatan CSS yang tepat, misalnya:
  - Bungkus label + kotak upload dalam container flex/grid dengan `align-items: stretch` atau `items-stretch` (Tailwind), sehingga tiap kolom punya tinggi sama.
  - Atau beri tinggi minimum tetap pada area label sehingga label 1 baris dan 2 baris tetap menyisakan ruang yang sama, membuat kotak upload mulai di posisi vertikal yang sama.
- Hasil akhir: sepasang kotak upload dalam satu baris HARUS rata atas & bawah (sejajar), rapi di desktop maupun mobile.
- Terapkan perbaikan ini untuk SEMUA pasangan kotak upload dokumen agar konsisten.

Jangan mengubah fungsi upload, hanya perbaiki tata letak/perataannya.

---

## KRITERIA SELESAI

1. Migration jalan: kolom `kota` ada di `pendaftaran`, kolom `keterangan` ada di `paket`.
2. Form menampilkan field Kota (wajib), tersimpan saat submit.
3. Tabel paket bertambah 4 paket KDMP Lengkap (A-D), muncul di dropdown sebagai grup "KDMP".
4. Kotak upload dokumen sejajar rata kanan-kiri (tidak ada yang turun/condong).
5. Semua fungsi lama tetap berjalan.

## JANGAN

- Jangan masukkan paket KDMP yang biasa (Paket A/B/C non-lengkap) — hanya yang "Lengkap" (A-D).
- Jangan ubah paket HSI/WMS yang sudah ada.
- Jangan pakai harga promo — tetap harga normal.
- Jangan rusak fitur yang sudah berjalan.
