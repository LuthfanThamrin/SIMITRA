# SIMITRA — Sprint 3 Bagian 1 & 2: Dashboard Data Pendaftaran (Admin)

Merapikan dan melengkapi Filament Resource untuk data pendaftaran di dashboard admin. Resource `PendaftaranResource` sudah di-generate sebelumnya (`app/Filament/Resources/PendaftaranResource.php`) tapi masih mentah. Tugasnya: buat tabel rapi + halaman detail + kemampuan ubah status. JANGAN merusak form pendaftaran publik atau fungsi lain yang sudah berjalan.

Konteks: Filament 3, Laravel 10. Tabel `pendaftaran` sudah berisi data dari form publik. Model `Pendaftaran` punya relasi `mitra()` (belongsTo User) dan `paket()` (belongsTo Paket).

---

## BAGIAN A: TABEL DAFTAR PENDAFTARAN (Table)

Atur tabel pada PendaftaranResource agar menampilkan kolom yang relevan dan rapi:

**Kolom yang ditampilkan (berurutan):**
1. **No** — nomor urut atau id (format rapi, misal #000123).
2. **Nama Pemilik** (`nama_pemilik`) — searchable.
3. **Nama Usaha** (`nama_usaha`) — searchable.
4. **No HP** (`no_hp`).
5. **Jenis Usaha** (`jenis_usaha`) — tampilkan label ramah (bukan value mentah). Jika "lainnya", boleh tampilkan `jenis_usaha_lainnya`.
6. **Paket** — tampilkan nama paket dari relasi (`paket.nama_paket` + kecepatan), atau "Konsultasi" jika `konsultasi_paket` = true.
7. **Mitra** — tampilkan nama mitra dari relasi (`mitra.nama`), atau "-" jika tidak ada.
8. **Kota** (`kota`).
9. **Tanggal Daftar** (`created_at`) — format tanggal Indonesia (misal "14 Jul 2026"), sortable.
10. **Status** (`status`) — tampilkan sebagai **badge berwarna**:
    - `pending` → warna abu/kuning, label "Menunggu Verifikasi"
    - `diproses` → warna biru, label "Diproses"
    - `terpasang` → warna hijau, label "Terpasang"
    - `ditolak` → warna merah, label "Ditolak"

**Fitur tabel:**
- **Pencarian** aktif untuk nama pemilik, nama usaha, no hp.
- **Filter berdasarkan Status** (dropdown filter: semua/pending/diproses/terpasang/ditolak).
- **Filter berdasarkan Mitra** (dropdown filter berdasarkan relasi mitra).
- **Urutkan default** berdasarkan tanggal terbaru (created_at desc).
- Kolom yang terlalu panjang boleh di-toggle/hidden secara default agar rapi.

---

## BAGIAN B: HALAMAN DETAIL / EDIT PENDAFTARAN

Saat admin membuka satu pendaftaran (View/Edit), tampilkan seluruh informasi dengan rapi, dikelompokkan dalam beberapa section:

**Section 1 — Data Pelanggan & Usaha (read-only untuk data dari pelanggan):**
- Nama Pemilik, Nama Usaha, No HP, CP Alternatif, Jenis Usaha, Paket yang dipilih (atau "Konsultasi"), Alamat Instalasi, Kota.

**Section 2 — Dokumen (tampilkan sebagai gambar/preview, bukan hanya teks path):**
- Foto KTP, Foto NPWP/NIB/Dokumen Usaha, Foto Tampak Depan Usaha.
- Tampilkan sebagai **gambar yang bisa diklik untuk diperbesar** (gunakan komponen image Filament, ambil dari storage: `Storage::url($path)` atau `ImageColumn`/`ImageEntry`).
- Jika file berupa PDF, tampilkan link untuk membuka.

**Section 3 — Lokasi:**
- Tampilkan latitude & longitude.
- Tampilkan **link Google Maps** (`link_maps`) sebagai tautan yang bisa diklik ("Lihat di Google Maps") — buka di tab baru.

**Section 4 — Verifikasi & Status (bagian yang bisa DIUBAH admin):**
- **Status** — dropdown/select yang bisa diubah admin: pending / diproses / terpasang / ditolak.
- **Catatan Admin** (`catatan_admin`) — textarea yang bisa diisi admin (misal alasan ditolak, atau catatan untuk mitra).
- Tombol simpan perubahan.

**Section 5 — Info Referral (read-only):**
- Nama mitra pereferal (dari relasi), tanggal daftar, sumber input (pelanggan/mitra).

---

## BAGIAN C: PENGATURAN UMUM RESOURCE

- **Label menu**: ubah dari "Pendaftarans" menjadi **"Data Pendaftaran"** (perbaiki pluralisasi yang jelek). Set `protected static ?string $navigationLabel = 'Data Pendaftaran';` dan `protected static ?string $modelLabel = 'Pendaftaran';` serta `$pluralModelLabel = 'Data Pendaftaran';`.
- **Ikon menu**: beri ikon yang sesuai (misal heroicon clipboard-document-list).
- Admin TIDAK perlu bisa "Create" pendaftaran baru dari dashboard (pendaftaran datang dari form publik). Boleh sembunyikan tombol Create, ATAU biarkan (opsional). Yang penting: View, Edit (ubah status), dan Delete tersedia.
- Field yang tidak relevan untuk diedit admin (seperti path foto mentah) sebaiknya ditampilkan sebagai preview, bukan input teks.

---

## KRITERIA SELESAI

1. Menu di sidebar bernama "Data Pendaftaran" (bukan "Pendaftarans"), dengan ikon.
2. Tabel menampilkan kolom rapi: nama, usaha, HP, jenis usaha, paket, mitra, kota, tanggal, status (badge berwarna).
3. Pencarian & filter (status, mitra) berfungsi.
4. Halaman detail menampilkan semua data + preview dokumen (gambar bisa diklik) + link Google Maps + peta/koordinat.
5. Admin bisa mengubah status pendaftaran & menulis catatan admin, lalu menyimpannya.
6. Perubahan status tersimpan ke database.
7. Form pendaftaran publik & fungsi lain tetap berjalan normal.

## JANGAN
- Jangan ubah form pendaftaran publik (`/daftar`) atau logikanya.
- Jangan hapus data atau kolom yang sudah ada.
- Jangan rusak relasi mitra/paket.
