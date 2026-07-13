# SIMITRA — Spesifikasi Sprint 2: Form Pendaftaran Publik

Dokumen ini adalah instruksi lengkap untuk membangun fitur **Sprint 2** pada proyek SIMITRA. Diberikan sebagai acuan untuk AI coding assistant. Ikuti batasan dan persyaratan di bawah dengan ketat agar hasil sesuai dengan sistem yang sudah ada.

---

## 1. KONTEKS PROYEK

**Nama sistem:** SIMITRA (Sistem Afiliasi & Pendataan Mitra INDIBIZ)

**Apa itu:** Aplikasi web untuk mendata pendaftaran calon pelanggan layanan internet INDIBIZ, sekaligus melacak asal pendaftaran melalui sistem afiliasi mitra/sub-agen. Admin memverifikasi data lalu mendaftarkan pelanggan ke pihak Telkom secara manual.

**Peran pengguna:**
- **Admin** — mengelola semua data, memverifikasi pendaftaran (pakai Filament, sudah ada).
- **Mitra** — merekrut pelanggan, punya kode referral (dashboard menyusul di sprint berikutnya).
- **Pelanggan** — mengisi formulir pendaftaran tanpa login (INI FOKUS SPRINT 2).

**Yang dibangun di Sprint 2:** Halaman **Form Pendaftaran Publik** yang bisa diakses tanpa login, tempat pelanggan (atau mitra yang membantu) mengisi data, mengunggah dokumen, dan menandai titik lokasi. Data tersimpan ke database dengan status awal "menunggu verifikasi".

---

## 2. STACK & LINGKUNGAN (WAJIB DIPATUHI)

- **Framework:** Laravel 10 (PHP 8.1). JANGAN pakai fitur yang butuh PHP 8.2+.
- **Admin panel:** Filament 3 (SUDAH terpasang — jangan diubah/di-reinstall).
- **Database:** MySQL (nama database: `simitra`).
- **Peta:** Leaflet.js + OpenStreetMap (GRATIS, tanpa API key). JANGAN pakai Google Maps.
- **Frontend halaman publik:** Blade + HTML/CSS biasa. Boleh pakai Tailwind CSS jika sudah tersedia, atau CSS biasa. Halaman ini TIDAK memakai Filament (Filament hanya untuk dashboard admin/mitra).
- **Penyimpanan file:** local storage Laravel (`storage/app/public`), diakses lewat `php artisan storage:link`.

**Batasan penting:**
- Halaman form ini **PUBLIK** — tidak perlu login, tidak boleh dilindungi middleware auth.
- **Desain/warna diatur terpisah** oleh pemilik proyek. Buat layout & struktur rapi dan netral; jangan memaksakan skema warna tertentu. Utamakan HTML semantik & kelas yang mudah di-styling nanti.
- **Mobile-first** — mayoritas pengguna akses lewat HP.
- Jangan membuat migration baru untuk tabel `pendaftaran` (SUDAH ADA — lihat bagian 3). Cukup gunakan tabel yang ada.

---

## 3. STRUKTUR DATABASE YANG SUDAH ADA (JANGAN DIUBAH)

Tabel `pendaftaran` sudah dibuat via migration dengan kolom berikut. Form harus menyimpan data ke kolom-kolom ini:

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint (PK) | otomatis |
| nama_pemilik | string | Nama pemilik/penanggung jawab usaha (wajib) |
| nama_usaha | string | Nama usaha (wajib) |
| no_hp | string | Nomor HP/WhatsApp (wajib) |
| jenis_usaha | enum | Nilai: 'sekolah','ruko','hotel','kesehatan','kuliner','ekspedisi','pertambangan','energi','agrikultur','media','lainnya' |
| jenis_usaha_lainnya | string (nullable) | Diisi hanya jika jenis_usaha = 'lainnya' |
| foto_ktp | string | Path file KTP (wajib) |
| foto_izin_usaha | string | Path file izin usaha (wajib) |
| foto_nib_npwp | string | Path file NIB atau NPWP (wajib) |
| foto_lokasi | string | Path file foto lokasi usaha (wajib) |
| latitude | decimal(10,7) nullable | Koordinat lintang |
| longitude | decimal(10,7) nullable | Koordinat bujur |
| mitra_id | bigint (FK ke users.id) | ID mitra pereferal |
| sumber_input | enum('pelanggan','mitra') | Data diisi pelanggan atau mitra. Untuk form publik: 'pelanggan' |
| status | enum('pending','diproses','terpasang','ditolak') | Status awal saat submit: 'pending' |
| catatan_admin | text nullable | Diisi admin nanti, bukan dari form ini |
| created_at, updated_at | timestamp | otomatis |

Tabel `users` (berisi admin & mitra) sudah ada, dengan kolom penting: `id`, `nama`, `email`, `role` ('admin'/'mitra'), `kode_referral` (unik, untuk mitra), `no_hp`, `status_aktif`.

**Relasi:** `pendaftaran.mitra_id` mengacu ke `users.id` (mitra yang kode referralnya dipakai).

---

## 4. YANG HARUS DIBANGUN DI SPRINT 2

### 4.1 Model Eloquent
Buat model `Pendaftaran` (jika belum ada) yang terhubung ke tabel `pendaftaran`. Set `$fillable` untuk semua kolom yang diisi dari form. Tambahkan relasi `mitra()` (belongsTo User). Pastikan nama tabel benar (`pendaftaran`, bukan `pendaftarans`) dengan `protected $table = 'pendaftaran';`.

### 4.2 Route
Buat route publik (di `routes/web.php`), tanpa middleware auth:
- `GET /daftar` → menampilkan form pendaftaran. Menerima query parameter opsional `?ref=KODE_REFERRAL`.
- `POST /daftar` → memproses & menyimpan data form.
- `GET /daftar/berhasil` → menampilkan halaman sukses.

### 4.3 Controller
Buat `PendaftaranController` dengan method:
- `create(Request $request)` — menampilkan form. Baca parameter `ref` dari URL. Jika ada, cari mitra dengan `kode_referral` tersebut; jika ketemu, kirim data mitra ke view agar nama/kode-nya tampil dan kode referral otomatis terpasang. Jika tidak ada `ref`, tampilkan kolom input kode referral manual.
- `store(Request $request)` — validasi input, simpan file unggahan, simpan data ke tabel `pendaftaran`, lalu redirect ke halaman sukses.
- `success()` — menampilkan halaman sukses.

### 4.4 Validasi (di method store)
- `nama_pemilik`: required, string, max 255
- `nama_usaha`: required, string, max 255
- `no_hp`: required, string, max 20
- `jenis_usaha`: required, harus salah satu dari enum
- `jenis_usaha_lainnya`: required_if jenis_usaha == 'lainnya', string, max 255
- `foto_ktp`, `foto_izin_usaha`, `foto_nib_npwp`, `foto_lokasi`: required, file, mimes:jpg,jpeg,png,pdf, max:2048 (2MB)
- `latitude`, `longitude`: required, numeric (pastikan pengguna sudah menandai lokasi)
- `kode_referral` (dari input manual atau hidden dari ref): required. Harus cocok dengan `kode_referral` milik user ber-role 'mitra' yang aktif. Jika tidak cocok, tolak dengan pesan error yang jelas.

Jika validasi gagal: kembali ke form dengan pesan error di dekat field terkait DAN pertahankan input yang sudah diisi (old input). File tidak perlu dipertahankan (wajar diunggah ulang).

### 4.5 Penyimpanan file
- Simpan tiap file ke `storage/app/public/pendaftaran/` dengan nama unik (misal pakai `Str::uuid()` atau `store()`).
- Simpan PATH-nya ke kolom terkait di database.
- Pastikan instruksi `php artisan storage:link` disebutkan agar file bisa diakses publik nanti.

### 4.6 Penyimpanan data
- Cari `mitra_id` dari kode referral yang dipakai (query ke tabel users).
- Set `sumber_input` = 'pelanggan'.
- Set `status` = 'pending'.
- Simpan, lalu redirect ke `/daftar/berhasil`.

---

## 5. SPESIFIKASI TAMPILAN FORM (view: daftar)

Layout mobile-first, dari atas ke bawah. Gunakan struktur HTML yang bersih & mudah di-styling.

1. **Header:** Nama/logo SIMITRA (teks saja tidak apa-apa untuk sekarang), kecil di atas tengah.
2. **Judul:** "Formulir Pendaftaran INDIBIZ" + subteks "Lengkapi data di bawah untuk mendaftar".
3. **Info Referral (kotak kecil):**
   - Jika ada mitra dari `ref` → tampilkan teks "Anda mendaftar melalui: [nama mitra]" dan pasang kode referral sebagai input hidden.
   - Jika tidak ada → tampilkan kolom input teks "Kode Referral" agar diisi manual.
4. **Bagian 1 — Data Diri & Usaha:**
   - Input teks: Nama Pemilik/Penanggung Jawab
   - Input teks: Nama Usaha
   - Input teks: Nomor HP/WhatsApp
   - Dropdown: Jenis Usaha (opsi sesuai enum, tampilkan label ramah: "Sekolah/Pendidikan", "Ruko/Toko", "Hotel/Penginapan", "Kesehatan", "Kuliner", "Ekspedisi/Logistik", "Pertambangan", "Energi", "Agrikultur/Pertanian", "Media & Komunikasi", "Lainnya")
   - Input teks: "Jenis Usaha Lainnya" — disembunyikan secara default; muncul (via JavaScript) HANYA jika dropdown dipilih "Lainnya".
5. **Bagian 2 — Unggah Dokumen** (judul "Dokumen Persyaratan"):
   - 4 field unggah: Foto KTP, Foto Surat Izin Usaha, Foto NIB atau NPWP, Foto Lokasi Usaha (Tampak Depan).
   - Tiap field: input file + teks kecil "Format JPG/PNG/PDF, maksimal 2MB".
   - Sediakan tautan kecil "Lihat contoh foto yang benar" di tiap field. Untuk sekarang cukup buat tautan/tombol placeholder yang membuka modal kosong atau alert (materi gambar contoh disiapkan terpisah). Jangan menghabiskan effort di sini.
   - (Opsional jika mudah) tampilkan nama file setelah dipilih.
6. **Bagian 3 — Titik Lokasi Usaha:**
   - Tombol "Ambil Lokasi Saat Ini" → pakai `navigator.geolocation` browser untuk isi latitude & longitude.
   - Peta Leaflet + OpenStreetMap menampilkan pin di koordinat terpilih. Pin bisa digeser (draggable) atau peta bisa diklik untuk memindahkan pin, dan koordinat ter-update.
   - Simpan latitude & longitude ke dua input hidden agar ikut terkirim saat submit.
   - Tampilkan teks koordinat terpilih (Lat & Long) agar pengguna tahu sudah tertandai.
   - Jika geolokasi ditolak/gagal, pengguna tetap bisa menandai manual dengan mengklik peta.
7. **Tombol Submit:** lebar penuh, teks "Kirim Pendaftaran".
8. Sertakan `@csrf` (token CSRF Laravel) di dalam form. `enctype="multipart/form-data"` wajib ada karena ada unggahan file.

---

## 6. SPESIFIKASI HALAMAN SUKSES (view: berhasil)

Di tengah layar:
1. Ikon centang (boleh SVG sederhana atau karakter ✓).
2. Judul: "Pendaftaran Berhasil!"
3. Teks: "Data Anda telah kami terima dan sedang menunggu verifikasi. Tim kami akan menindaklanjuti."
4. (Opsional) Tampilkan nomor pendaftaran (id) sebagai bukti: "Kode Pendaftaran: #[id]".
5. Tombol "Kembali ke Beranda" (untuk sekarang arahkan ke `/daftar` atau `/`).

---

## 7. LEAFLET — CATATAN TEKNIS

- Muat Leaflet via CDN (CSS + JS) di halaman form.
- Inisialisasi peta dengan tampilan awal wilayah Kalimantan Timur (contoh center: lat -1.24, long 116.85, zoom sekitar 12) agar relevan dengan lokasi pengguna.
- Saat "Ambil Lokasi Saat Ini" ditekan, minta izin geolokasi, pindahkan pin & peta ke lokasi pengguna, update input hidden latitude/longitude.
- Marker draggable; saat digeser atau peta diklik, update koordinat.

---

## 8. HAL YANG TIDAK PERLU DIKERJAKAN DI SPRINT 2 (JANGAN)

Agar fokus, JANGAN kerjakan hal berikut (itu untuk sprint lain):
- Dashboard admin/mitra (sudah/akan pakai Filament terpisah).
- Sistem perhitungan komisi.
- Generate QR code.
- Landing page & halaman benefit mitra.
- Login/registrasi mitra.
- Pembuatan gambar contoh foto benar/salah (cukup placeholder link/modal).
- Integrasi ke sistem Telkom.

---

## 9. KRITERIA SELESAI (DEFINITION OF DONE)

Sprint 2 dianggap selesai jika:
1. Halaman `/daftar` bisa dibuka tanpa login, tampil rapi di HP & desktop.
2. Jika diakses dengan `?ref=KODE`, kode referral otomatis terpasang & nama mitra tampil.
3. Semua field terisi + dokumen terunggah + lokasi tertandai → submit berhasil menyimpan ke tabel `pendaftaran` dengan status 'pending' dan mitra_id yang benar.
4. Validasi bekerja: field kosong / file > 2MB / format salah / kode referral tidak valid → ditolak dengan pesan jelas, input lama dipertahankan.
5. Setelah submit sukses → muncul halaman "Pendaftaran Berhasil".
6. File tersimpan di storage dan path-nya tercatat di database.
7. Data yang masuk bisa dilihat di database (tabel pendaftaran).

---

## 10. CATATAN GAYA KODE

- Ikuti konvensi Laravel standar (controller di app/Http/Controllers, view di resources/views).
- Beri komentar secukupnya pada bagian penting (validasi, penyimpanan file, logika referral).
- Kode bersih & mudah dibaca. Utamakan kejelasan daripada trik pintar.
- Bahasa untuk teks yang tampil ke pengguna: Bahasa Indonesia.
