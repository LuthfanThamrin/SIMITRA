# SIMITRA — Sprint 4: Sistem Komisi

Membangun perhitungan komisi mitra, rekap di admin, pencatatan pembayaran, dan tampilan komisi di panel mitra. JANGAN merusak fungsi yang sudah berjalan (form publik, data pendaftaran, manajemen mitra, panel mitra, papan informasi).

**Konteks:** Laravel 10, Filament 3. Panel admin `/admin`, panel mitra `/mitra`. Tabel `pendaftaran` punya `mitra_id`, `status` (pending/diproses/terpasang/ditolak). Tabel `users` berisi mitra (role='mitra'). Tabel `pembayaran_komisi` sudah ada dari Sprint 1.

---

## ATURAN KOMISI (WAJIB DIIKUTI PERSIS)

**1. Komisi Dasar**
- Rp200.000 per pelanggan yang berstatus **'terpasang'**
- Tidak ada batasan periode — setiap pelanggan terpasang = Rp200.000

**2. Bonus**
- Rp200.000 untuk **setiap kelipatan 5** pelanggan terpasang **dalam bulan yang sama**
- Perhitungan: `floor(jumlah_terpasang_bulan_itu / 5) × Rp200.000`
- Contoh: 12 terpasang dalam 1 bulan → floor(12/5) = 2 → bonus = 2 × 200.000 = Rp400.000
- **Reset tiap bulan** — sisa yang belum genap 5 TIDAK dibawa ke bulan berikutnya
- **Patokan bulan:** berdasarkan **tanggal status diubah menjadi 'terpasang'** (bukan tanggal daftar)

**3. Total Komisi Mitra** = Komisi Dasar (seluruh waktu) + Total Bonus (dijumlah dari semua bulan)

**4. Metode perhitungan:** komisi **DIHITUNG OTOMATIS** dari data tabel `pendaftaran` setiap kali dibutuhkan. **JANGAN** menyimpan hasil perhitungan komisi ke tabel terpisah. Tabel `komisi` yang ada dari migration lama TIDAK dipakai — biarkan saja, jangan dihapus.

---

## BAGIAN 1: MIGRATION — KOLOM `tanggal_terpasang`

Sistem saat ini tidak mencatat kapan status berubah menjadi 'terpasang'. Kolom `updated_at` tidak bisa dipakai karena berubah setiap kali data diedit apa pun.

Buat migration BARU:

```php
Schema::table('pendaftaran', function (Blueprint $table) {
    $table->timestamp('tanggal_terpasang')->nullable()->after('status');
});
```

Tambahkan `tanggal_terpasang` ke `$fillable` model `Pendaftaran`, dan cast ke datetime.

### Pengisian otomatis
Pada model `Pendaftaran`, tambahkan event listener agar `tanggal_terpasang` terisi otomatis:

```php
protected static function booted(): void
{
    static::updating(function ($pendaftaran) {
        // Jika status BARU berubah menjadi 'terpasang' dan belum ada tanggalnya
        if ($pendaftaran->isDirty('status') && $pendaftaran->status === 'terpasang' && empty($pendaftaran->tanggal_terpasang)) {
            $pendaftaran->tanggal_terpasang = now();
        }

        // Jika status diubah dari 'terpasang' ke status lain, kosongkan tanggalnya
        if ($pendaftaran->isDirty('status') && $pendaftaran->getOriginal('status') === 'terpasang' && $pendaftaran->status !== 'terpasang') {
            $pendaftaran->tanggal_terpasang = null;
        }
    });
}
```

### Isi data lama
Untuk data pendaftaran yang **sudah berstatus 'terpasang' sebelumnya** (tanggal_terpasang masih null), isi dengan `updated_at` sebagai perkiraan. Lakukan lewat migration atau perintah sekali jalan:

```php
DB::table('pendaftaran')
    ->where('status', 'terpasang')
    ->whereNull('tanggal_terpasang')
    ->update(['tanggal_terpasang' => DB::raw('updated_at')]);
```

---

## BAGIAN 2: LOGIKA PERHITUNGAN KOMISI

Buat sebuah **service class** atau **method pada model User** untuk menghitung komisi mitra. Contoh: `app/Services/KomisiService.php` atau method di model `User`.

Fungsi yang dibutuhkan (untuk seorang mitra):

1. **`jumlahTerpasang()`** — jumlah pendaftaran milik mitra dengan status 'terpasang'.

2. **`komisiDasar()`** — `jumlahTerpasang() × 200000`

3. **`totalBonus()`** — hitung dengan cara:
   - Ambil semua pendaftaran milik mitra dengan status 'terpasang' dan `tanggal_terpasang` tidak null
   - Kelompokkan berdasarkan **bulan & tahun** dari `tanggal_terpasang`
   - Untuk tiap bulan: `floor(jumlah_bulan_itu / 5) × 200000`
   - Jumlahkan semua bulan

   Contoh implementasi:
   ```php
   $perBulan = Pendaftaran::where('mitra_id', $mitraId)
       ->where('status', 'terpasang')
       ->whereNotNull('tanggal_terpasang')
       ->get()
       ->groupBy(fn ($p) => $p->tanggal_terpasang->format('Y-m'));

   $totalBonus = 0;
   foreach ($perBulan as $bulan => $items) {
       $totalBonus += floor($items->count() / 5) * 200000;
   }
   ```

4. **`totalKomisi()`** — `komisiDasar() + totalBonus()`

5. **`totalDibayar()`** — jumlah seluruh pembayaran ke mitra ini dari tabel `pembayaran_komisi`

6. **`sisaBelumDibayar()`** — `totalKomisi() - totalDibayar()`

7. **`progressBonusBulanIni()`** — untuk ditampilkan ke mitra:
   - Jumlah terpasang bulan berjalan
   - Sisa menuju kelipatan 5 berikutnya: `5 - (jumlah_bulan_ini % 5)`
   - Contoh hasil: "3 dari 5 pelanggan terpasang bulan ini — 2 lagi menuju bonus Rp200.000"

Buat konstanta untuk nilai komisi agar mudah diubah:
```php
const KOMISI_PER_PELANGGAN = 200000;
const BONUS_PER_KELIPATAN = 200000;
const KELIPATAN_BONUS = 5;
```

---

## BAGIAN 3: TABEL `pembayaran_komisi`

Periksa struktur tabel `pembayaran_komisi` yang sudah ada. Kolom yang dibutuhkan:

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | otomatis |
| mitra_id | FK users | mitra yang dibayar |
| jumlah | decimal | nominal yang dibayarkan |
| tanggal_bayar | date | tanggal pembayaran |
| catatan | text nullable | catatan opsional |
| created_at, updated_at | timestamp | otomatis |

Jika ada kolom yang belum sesuai, buat migration BARU untuk menyesuaikan. Pastikan model `PembayaranKomisi` ada dengan `$fillable` dan relasi `mitra()` (belongsTo User).

---

## BAGIAN 4: PANEL ADMIN — REKAP & PEMBAYARAN KOMISI

Buat halaman/resource di panel admin.

### 4.1 Pengaturan
- Navigation label: **"Rekap Komisi"**
- Ikon: heroicon banknotes / currency-dollar
- Letakkan setelah "Manajemen Mitra"

### 4.2 Tabel Rekap Komisi per Mitra
Menampilkan **satu baris per mitra** (query dari tabel users where role='mitra'), dengan kolom:

1. **Nama Mitra** — searchable
2. **Bank & Rekening** — dari kolom `nama_bank` & `no_rekening` (gabung: "BCA - 1234567890"), agar admin mudah transfer
3. **Jumlah Terpasang** — jumlah pelanggan terpasang mitra ini
4. **Komisi Dasar** — format Rupiah
5. **Total Bonus** — format Rupiah
6. **Total Komisi** — format Rupiah, ditonjolkan
7. **Sudah Dibayar** — format Rupiah
8. **Sisa Belum Dibayar** — format Rupiah, beri warna (merah jika > 0, hijau jika 0)

Semua nilai dihitung otomatis dengan logika di Bagian 2. Format Rupiah: `Rp1.400.000` (pemisah ribuan titik).

Fitur: pencarian nama mitra, urut berdasarkan sisa belum dibayar (terbesar di atas).

### 4.3 Aksi "Catat Pembayaran"
Tambahkan action pada tiap baris mitra: **"Catat Pembayaran"** yang membuka modal form:
- **Jumlah** — otomatis terisi dengan nilai `sisaBelumDibayar()` mitra tersebut, tapi **bisa diubah admin** (untuk pembayaran sebagian). Required, numeric, minimal 1.
- **Tanggal Bayar** — default hari ini, bisa diubah. Required.
- **Catatan** — textarea, opsional.

Saat disimpan: buat record baru di `pembayaran_komisi`. Setelah tersimpan, tampilkan notifikasi sukses dan tabel rekap otomatis memperbarui nilai "Sudah Dibayar" & "Sisa".

**Validasi:** jumlah pembayaran tidak boleh melebihi sisa belum dibayar. Jika melebihi, tolak dengan pesan Bahasa Indonesia: "Jumlah pembayaran melebihi sisa komisi yang belum dibayar".

### 4.4 Riwayat Pembayaran
Tambahkan action **"Riwayat Pembayaran"** pada tiap mitra, yang membuka modal berisi daftar pembayaran mitra tersebut: tanggal, jumlah, catatan. Urut terbaru.

Alternatif: buat resource terpisah "Riwayat Pembayaran" yang menampilkan semua pembayaran dengan filter per mitra. Pilih yang paling praktis.

---

## BAGIAN 5: PANEL MITRA — KOMISI SAYA

### 5.1 Perbarui Widget Statistik Dashboard Mitra
Widget statistik mitra saat ini menampilkan "Total Komisi: Rp 0" sebagai placeholder. **Ganti dengan nilai sebenarnya** menggunakan `totalKomisi()`.

### 5.2 Widget Detail Komisi + Progress Bonus (di Dashboard Mitra)
Tambahkan widget di dashboard mitra yang menampilkan:
- **Total Komisi** (besar, menonjol) — format Rupiah
- Rincian: **Komisi Dasar: Rp X** | **Bonus: Rp Y**
- **Sudah Dibayar: Rp Z** | **Sisa Belum Dibayar: Rp W**
- **Progress Bonus Bulan Ini:** tampilkan progress bar atau teks, contoh: "3 dari 5 pelanggan terpasang bulan ini — 2 lagi menuju bonus Rp200.000"
  - Jika bulan ini sudah dapat bonus (≥5), tampilkan juga berapa kali bonus didapat bulan ini.

### 5.3 Menu "Komisi Saya" di Sidebar Mitra
Buat halaman di panel mitra:
- Navigation label: **"Komisi Saya"**
- Ikon: heroicon banknotes

Isi halaman:
1. **Ringkasan** — Total Komisi, Komisi Dasar, Total Bonus, Sudah Dibayar, Sisa Belum Dibayar (format Rupiah)
2. **Rincian Bonus per Bulan** — tabel: Bulan, Jumlah Terpasang, Bonus Didapat. Contoh: "Juli 2026 | 12 pelanggan | Rp400.000"
3. **Riwayat Pembayaran** — tabel: Tanggal Bayar, Jumlah, Catatan. Urut terbaru.
4. **Progress Bonus Bulan Ini** — sama seperti di dashboard.

**Read-only:** mitra hanya bisa melihat, tidak bisa mengubah apa pun. Query WAJIB difilter untuk mitra yang login (`auth()->id()`).

---

## KRITERIA SELESAI

1. Kolom `tanggal_terpasang` ada; terisi otomatis saat admin mengubah status jadi 'terpasang'; menjadi null jika status diubah dari 'terpasang' ke status lain.
2. Data lama yang sudah 'terpasang' punya `tanggal_terpasang` terisi.
3. Perhitungan komisi benar:
   - Dasar = jumlah terpasang × Rp200.000
   - Bonus = untuk tiap bulan: floor(terpasang bulan itu / 5) × Rp200.000, dijumlah semua bulan
   - Reset tiap bulan (sisa tidak dibawa ke bulan berikutnya)
4. Menu "Rekap Komisi" di admin menampilkan per mitra: nama, bank & rekening, jumlah terpasang, komisi dasar, bonus, total, sudah dibayar, sisa.
5. Admin bisa "Catat Pembayaran" (jumlah default = sisa, bisa diubah untuk bayar sebagian); tidak bisa melebihi sisa.
6. Admin bisa melihat riwayat pembayaran per mitra.
7. Dashboard mitra menampilkan total komisi sebenarnya (bukan Rp 0) + rincian dasar/bonus + progress bonus bulan ini.
8. Menu "Komisi Saya" di mitra menampilkan ringkasan, rincian bonus per bulan, dan riwayat pembayaran — read-only, hanya data mitra tersebut.
9. Semua fungsi lama tetap berjalan.

## PENGUJIAN YANG DISARANKAN
- Ubah status 5 pendaftaran milik 1 mitra jadi 'terpasang' di bulan yang sama → cek: dasar Rp1.000.000 + bonus Rp200.000 = total Rp1.200.000.
- Ubah 1 lagi jadi terpasang (jadi 6) → bonus tetap Rp200.000 (belum 10).
- Catat pembayaran sebagian → cek sisa berkurang dengan benar.
- Login sebagai mitra lain → pastikan tidak melihat komisi mitra pertama.

## JANGAN
- Jangan menyimpan hasil perhitungan komisi ke tabel terpisah — hitung otomatis dari data pendaftaran.
- Jangan pakai `updated_at` sebagai patokan bulan bonus — gunakan `tanggal_terpasang`.
- Jangan izinkan mitra mengubah data komisi/pembayaran.
- Jangan izinkan pembayaran melebihi sisa komisi.
- Jangan rusak fitur yang sudah berjalan.
