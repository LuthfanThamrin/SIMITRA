# SIMITRA — Sprint 4: Papan Informasi

Membangun fitur Papan Informasi: admin membuat pengumuman/promo, mitra melihatnya. JANGAN merusak fungsi yang sudah berjalan (form publik, data pendaftaran, manajemen mitra, panel mitra, dll).

**Konteks:** Laravel 10, Filament 3. Tabel `pengumuman` SUDAH ADA dari migration Sprint 1. Panel admin di `/admin`, panel mitra di `/mitra`.

---

## BAGIAN 0: PERIKSA & SESUAIKAN TABEL `pengumuman`

Tabel `pengumuman` sudah dibuat sebelumnya. Periksa struktur kolomnya. Kolom yang **dibutuhkan**:

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | otomatis |
| judul | string | judul pengumuman (wajib) |
| isi | text nullable | isi teks (boleh kosong jika hanya gambar) |
| gambar | string nullable | path file gambar/poster (boleh kosong jika hanya teks) |
| tipe | enum/string | 'info', 'promo', 'pengumuman' |
| aktif | boolean default true | untuk menyembunyikan tanpa menghapus |
| created_at, updated_at | timestamp | otomatis |

**Jika ada kolom yang belum ada** (misal `aktif`), buat migration BARU untuk menambahkannya. Jangan ubah migration lama.

Pastikan model `Pengumuman` (`app/Models/Pengumuman.php`) ada dengan:
- `protected $table = 'pengumuman';` (nama tabel tunggal, bukan `pengumumans`)
- `$fillable` mencakup semua kolom di atas.

---

## BAGIAN 1: PANEL ADMIN — KELOLA PAPAN INFORMASI

Buat Filament Resource untuk model `Pengumuman` di panel admin.

### 1.1 Pengaturan Resource
- Navigation label: **"Papan Informasi"**
- Model label: "Pengumuman", plural: "Papan Informasi"
- Ikon: heroicon megaphone / speaker-wave
- Letakkan di sidebar setelah "Manajemen Mitra"

### 1.2 Tabel Daftar Pengumuman
Kolom:
1. **Gambar** — preview thumbnail kecil (ImageColumn) jika ada gambar
2. **Judul** (`judul`) — searchable
3. **Tipe** (`tipe`) — badge berwarna: Info (biru), Promo (hijau), Pengumuman (kuning)
4. **Status** (`aktif`) — badge: Aktif (hijau) / Nonaktif (abu)
5. **Tanggal Dibuat** (`created_at`) — format Indonesia, sortable

Fitur:
- Pencarian: judul
- Filter: berdasarkan tipe, berdasarkan status aktif
- Urut default: terbaru

### 1.3 Form Tambah/Edit
Field:
1. **Judul** (`judul`) — required, max 255
2. **Tipe** (`tipe`) — select, pilihan: Info / Promo / Pengumuman — required, default 'info'
3. **Isi** (`isi`) — textarea atau rich editor, **opsional** (boleh kosong)
4. **Gambar** (`gambar`) — FileUpload, **opsional**. Simpan ke `storage/app/public/pengumuman`. Terima jpg/jpeg/png, maksimal 2MB. Tampilkan preview.
5. **Aktif** (`aktif`) — toggle, default true

**Aturan validasi khusus:** minimal salah satu dari `isi` ATAU `gambar` harus diisi (tidak boleh dua-duanya kosong). Tampilkan pesan Bahasa Indonesia jika keduanya kosong: "Isi teks atau gambar minimal salah satu harus diisi".

### 1.4 Redirect setelah simpan
Sama seperti resource lain: setelah Create/Edit disimpan, kembali ke halaman daftar:
```php
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

---

## BAGIAN 2: PANEL MITRA — LIHAT PAPAN INFORMASI

Mitra **hanya bisa MELIHAT** pengumuman (read-only). Tidak bisa membuat/mengubah/menghapus.

### 2.1 Widget di Dashboard Mitra
Buat widget yang menampilkan **3 pengumuman terbaru yang aktif** di dashboard mitra:
- Judul widget: "Papan Informasi"
- Tampilkan: tipe (badge berwarna), judul, tanggal, dan potongan isi (jika ada). Jika ada gambar, tampilkan thumbnail kecil.
- Hanya tampilkan pengumuman dengan `aktif` = true, urut terbaru, batasi 3.
- Letakkan di bawah widget yang sudah ada (statistik & pelanggan terbaru).
- Jika ingin, beri tautan "Lihat semua" yang mengarah ke halaman Papan Informasi (Bagian 2.2).

### 2.2 Menu "Papan Informasi" di Sidebar Mitra
Buat halaman/resource read-only di panel mitra:
- Navigation label: **"Papan Informasi"**
- Ikon: heroicon megaphone
- Menampilkan **semua pengumuman aktif** (`aktif` = true), urut terbaru.
- Tampilan berupa daftar/kartu, tiap item menampilkan: badge tipe, judul, tanggal, isi lengkap, dan **gambar penuh** (jika ada).
- Jika ada gambar, sediakan **tombol/tautan untuk mengunduh gambar** (agar mitra bisa menyimpan poster promo untuk disebarkan).
- **Read-only:** tidak ada tombol Create/Edit/Delete.

**Query WAJIB difilter:** hanya tampilkan `aktif` = true. Pengumuman nonaktif tidak boleh terlihat mitra.

---

## KRITERIA SELESAI

1. Menu "Papan Informasi" muncul di sidebar admin; admin bisa tambah/edit/hapus/nonaktifkan pengumuman.
2. Admin bisa membuat pengumuman dengan teks saja, gambar saja, atau keduanya. Tidak bisa menyimpan jika keduanya kosong.
3. Gambar tersimpan ke storage dan tampil sebagai preview di tabel admin.
4. Setelah simpan, admin kembali ke halaman daftar.
5. Dashboard mitra menampilkan widget "Papan Informasi" berisi 3 pengumuman aktif terbaru.
6. Menu "Papan Informasi" muncul di sidebar mitra, menampilkan semua pengumuman aktif dengan gambar penuh + tombol unduh gambar.
7. Mitra tidak bisa membuat/mengubah/menghapus pengumuman (read-only).
8. Pengumuman nonaktif tidak terlihat oleh mitra.
9. Semua fungsi lama tetap berjalan.

## JANGAN
- Jangan izinkan mitra mengubah pengumuman.
- Jangan tampilkan pengumuman nonaktif ke mitra.
- Jangan buat migration baru untuk tabel `pengumuman` jika kolomnya sudah lengkap — periksa dulu.
- Jangan rusak fitur yang sudah berjalan (form publik, data pendaftaran, manajemen mitra, panel mitra, dashboard).
