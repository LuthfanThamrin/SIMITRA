# SIMITRA — Dashboard Admin (Widget Statistik) & Perbaikan Redirect

Dua pekerjaan: (1) mengisi halaman Dashboard admin yang saat ini masih kosong, (2) memperbaiki perilaku redirect setelah menyimpan perubahan. JANGAN merusak fungsi yang sudah berjalan (form publik, data pendaftaran, ubah status, dll).

---

## BAGIAN 1: ISI DASHBOARD ADMIN DENGAN WIDGET

Saat ini halaman Dashboard admin (`/admin`) hanya menampilkan kartu bawaan Filament ("Welcome" dan info versi Filament), sehingga terlihat kosong. Isi dengan widget yang berguna.

### 1.1 Widget Kartu Statistik (Stats Overview)

Buat widget Filament bertipe **Stats Overview** yang menampilkan ringkasan data pendaftaran:

| Kartu | Isi | Warna |
|---|---|---|
| Total Pendaftaran | jumlah seluruh baris di tabel `pendaftaran` | primary/abu |
| Menunggu Verifikasi | jumlah `status` = 'pending' | warning (kuning) |
| Diproses | jumlah `status` = 'diproses' | info (biru) |
| Terpasang | jumlah `status` = 'terpasang' | success (hijau) |
| Ditolak | jumlah `status` = 'ditolak' | danger (merah) |

Ketentuan:
- Tiap kartu diberi ikon yang sesuai (heroicon).
- Tiap kartu diberi deskripsi singkat, contoh pada kartu "Menunggu Verifikasi": "Perlu ditindaklanjuti".
- Widget ini ditampilkan di bagian atas Dashboard.

Perintah pembuatan: `php artisan make:filament-widget StatistikPendaftaran --stats-overview`

### 1.2 Widget Tabel Pendaftaran Terbaru

Buat widget Filament bertipe **Table Widget** yang menampilkan **10 pendaftaran terbaru**:
- Kolom: Nama Pemilik, Nama Usaha, Mitra (dari relasi), Tanggal Daftar, Status (badge berwarna seperti di halaman Data Pendaftaran).
- Diurutkan dari yang terbaru (`created_at` desc), dibatasi 10 data.
- Beri judul widget: "Pendaftaran Terbaru".
- Jika memungkinkan, baris bisa diklik untuk membuka detail pendaftaran tersebut.
- Widget ini ditampilkan di bawah kartu statistik.

Perintah pembuatan: `php artisan make:filament-widget PendaftaranTerbaru --table`

### 1.3 Pengaturan urutan widget
Atur agar urutan tampil di Dashboard: Kartu Statistik (atas) → Tabel Pendaftaran Terbaru (bawah). Gunakan properti `$sort` pada widget.

Kartu bawaan Filament ("Welcome"/info versi) boleh disembunyikan agar dashboard lebih fokus dan profesional (hapus dari `AdminPanelProvider` bagian `widgets()`), atau biarkan jika lebih mudah.

---

## BAGIAN 2: PERBAIKAN REDIRECT SETELAH SIMPAN

**Masalah saat ini:** Setelah admin menekan "Save changes" pada halaman Edit Pendaftaran (misal setelah mengubah status), halaman tetap berada di halaman edit tersebut. Diharapkan setelah data tersimpan, admin otomatis kembali ke halaman **daftar (index) Data Pendaftaran**.

**Perbaikan yang diminta:**
Pada class `EditPendaftaran` (di `app/Filament/Resources/PendaftaranResource/Pages/EditPendaftaran.php`), tambahkan method berikut agar setelah simpan diarahkan kembali ke halaman daftar:

```php
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

Ketentuan:
- Setelah menekan "Save changes", data tersimpan lalu halaman berpindah ke daftar Data Pendaftaran.
- Notifikasi sukses tetap muncul ("Saved" / pesan berhasil).
- Terapkan hal yang sama pada halaman Create jika ada (opsional).

---

## KRITERIA SELESAI

1. Dashboard admin menampilkan kartu statistik (Total, Menunggu Verifikasi, Diproses, Terpasang, Ditolak) dengan angka yang benar sesuai data di database.
2. Dashboard menampilkan tabel 10 pendaftaran terbaru dengan status badge berwarna.
3. Dashboard tidak lagi terlihat kosong.
4. Setelah menekan "Save changes" pada Edit Pendaftaran, halaman kembali ke daftar Data Pendaftaran dan perubahan tersimpan.
5. Semua fungsi lama tetap berjalan.

## JANGAN
- Jangan ubah form pendaftaran publik.
- Jangan rusak fitur ubah status & filter yang sudah berjalan.
