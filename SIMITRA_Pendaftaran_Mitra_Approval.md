# SIMITRA — Pendaftaran Mitra Self-Service + Approval Admin

Membangun form pendaftaran mitra publik (calon mitra daftar sendiri) + sistem approve/tolak di dashboard admin. Calon mitra daftar → status menunggu (pending) → admin approve → jadi mitra aktif + kode referral otomatis + bisa login. JANGAN merusak fungsi yang sudah berjalan (form pendaftaran pelanggan, data pendaftaran, manajemen mitra, panel mitra, komisi, papan informasi).

**Konteks:** Laravel 10, Filament 3. Tabel `users` sudah ada dengan kolom: nama, email, password, role (admin/mitra), kode_referral, no_hp, nama_bank, no_rekening, alamat, status_aktif. Kode referral mitra auto-generate format `MITRA-XXXXX` via model event `creating` (sudah ada). Desain form mengikuti file HTML Stitch yang diberikan user (warna primary #1D5FAE, font Comfortaa untuk judul + Plus Jakarta Sans untuk body).

---

## BAGIAN 0: TAMBAH KOLOM STATUS PENDAFTARAN MITRA

Saat ini `status_aktif` (boolean) menentukan mitra aktif/nonaktif. Untuk membedakan mitra yang **menunggu approval** vs **ditolak** vs **aktif**, tambahkan kolom status pendaftaran.

Buat migration BARU:

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('status_pendaftaran', ['pending', 'disetujui', 'ditolak'])
          ->default('disetujui')
          ->after('status_aktif');
});
```

Ketentuan:
- Default `'disetujui'` agar admin & mitra lama yang sudah ada tidak terpengaruh (dianggap sudah disetujui).
- Tambahkan `status_pendaftaran` ke `$fillable` model `User`.
- Mitra yang daftar lewat form publik akan berstatus `'pending'`.

---

## BAGIAN 1: FORM PENDAFTARAN MITRA PUBLIK (Blade, bukan Filament)

Buat halaman publik untuk pendaftaran mitra, mengikuti desain HTML Stitch yang diberikan user.

### 1.1 Route & Controller
- Route GET `/daftar-mitra` → tampilkan form (`MitraPendaftaranController@create`)
- Route POST `/daftar-mitra` → proses simpan (`MitraPendaftaranController@store`)
- Route GET `/daftar-mitra/berhasil` → halaman sukses

### 1.2 View Form (`resources/views/mitra-pendaftaran/create.blade.php`)
Gunakan desain dari file HTML Stitch yang diberikan user (header dengan logo SIMITRA, judul "pendaftaran mitra baru", kartu form dengan warna & font sesuai). Field pada form:

1. **Nama Lengkap** (`nama`) — text, required, placeholder "Masukkan nama sesuai KTP"
2. **Alamat Email** (`email`) — email, required, harus unik di tabel users
3. **No HP** (`no_hp`) — text/tel, required. (CATATAN: desain Stitch belum ada field No HP — TAMBAHKAN field ini karena dibutuhkan, letakkan setelah email atau dekat data kontak)
4. **Alamat Lengkap** (`alamat`) — textarea, required
5. **Nama Bank** (`nama_bank`) — text, required, placeholder "Contoh: Bank Mandiri"
6. **Nomor Rekening** (`no_rekening`) — text (angka), required
7. **Kata Sandi** (`password`) — password, required, minimal 8 karakter, ada tombol show/hide (ikon visibility)
8. **Konfirmasi Kata Sandi** (`password_confirmation`) — password, required, harus SAMA dengan password
9. **Checkbox Syarat & Ketentuan** — required (tidak bisa submit jika belum dicentang)

Sertakan `@csrf` di dalam `<form>`. Method POST ke `/daftar-mitra`.

### 1.3 Validasi (di controller `store`)
- `nama`: required, string, max 255
- `email`: required, email, unique:users,email — pesan jika sudah terpakai: "Email sudah terdaftar"
- `no_hp`: required, string
- `alamat`: required, string
- `nama_bank`: required, string
- `no_rekening`: required, string (angka)
- `password`: required, min 8, confirmed (otomatis cek `password_confirmation` sama)
- `terms`: accepted (checkbox harus dicentang)

Semua pesan validasi dalam Bahasa Indonesia. Jika validasi gagal, kembalikan ke form dengan input lama (`old()`) dan pesan error, KECUALI field password (jangan kembalikan password lama demi keamanan).

### 1.4 Proses Simpan
Saat valid, buat user baru:
```php
User::create([
    'nama' => $request->nama,
    'email' => $request->email,
    'no_hp' => $request->no_hp,
    'alamat' => $request->alamat,
    'nama_bank' => $request->nama_bank,
    'no_rekening' => $request->no_rekening,
    'password' => Hash::make($request->password),
    'role' => 'mitra',
    'status_aktif' => false,            // belum aktif sampai di-approve
    'status_pendaftaran' => 'pending',  // menunggu persetujuan
    // kode_referral JANGAN diisi sekarang — digenerate saat approve
]);
```

**PENTING soal kode referral:** model event `creating` yang ada saat ini otomatis generate kode_referral untuk semua mitra baru. Untuk pendaftaran self-service, kode referral sebaiknya BARU dibuat saat di-APPROVE (bukan saat daftar), agar mitra yang ditolak tidak memakai jatah kode. 

Sesuaikan: boleh tetap generate kode saat create (lebih sederhana), ATAU generate saat approve. Pilih yang lebih mudah & konsisten — jika tetap generate saat create, pastikan itu tidak masalah. Yang penting: mitra pending TIDAK bisa login sampai disetujui.

### 1.5 Halaman Sukses (`/daftar-mitra/berhasil`)
Tampilkan pesan bahwa pendaftaran berhasil dan sedang menunggu persetujuan admin. Contoh: "Pendaftaran Anda berhasil dikirim dan sedang menunggu persetujuan admin. Anda akan dapat login setelah akun disetujui." Sertakan logo SIMITRA, gaya konsisten dengan halaman lain.

---

## BAGIAN 2: MITRA PENDING TIDAK BISA LOGIN

Pastikan mitra dengan `status_pendaftaran` = 'pending' atau 'ditolak' TIDAK bisa masuk panel mitra.

Perbarui method `canAccessPanel()` pada model `User`:

```php
public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->role === 'admin';
    }

    if ($panel->getId() === 'mitra') {
        return $this->role === 'mitra'
            && $this->status_aktif
            && $this->status_pendaftaran === 'disetujui';
    }

    return false;
}
```

Sehingga hanya mitra yang sudah disetujui DAN aktif yang bisa login.

---

## BAGIAN 3: APPROVAL DI MANAJEMEN MITRA (ADMIN)

Perbarui resource Manajemen Mitra di panel admin.

### 3.1 Tambah Kolom Status Pendaftaran
Pada tabel daftar mitra, tambahkan kolom **Status Pendaftaran** sebagai badge berwarna:
- `pending` → kuning, label "Menunggu Persetujuan"
- `disetujui` → hijau, label "Disetujui"
- `ditolak` → merah, label "Ditolak"

### 3.2 Filter
Tambahkan filter berdasarkan `status_pendaftaran` (Menunggu/Disetujui/Ditolak), agar admin mudah melihat siapa yang perlu di-approve. Default bisa menampilkan semua, tapi mudahkan admin memfilter yang "pending".

### 3.3 Aksi Approve & Tolak
Tambahkan 2 action pada tiap baris mitra yang berstatus `pending`:

**a. Approve (Setujui):**
- Tombol/action "Setujui", ikon check, warna hijau.
- Beri konfirmasi ("Setujui pendaftaran mitra ini?").
- Saat diklik:
  - Ubah `status_pendaftaran` = 'disetujui'
  - Ubah `status_aktif` = true
  - Jika `kode_referral` masih kosong, generate kode referral (format MITRA-XXXXX, unik) — jika belum tergenerate saat create.
  - Tampilkan notifikasi sukses: "Mitra berhasil disetujui".

**b. Tolak:**
- Tombol/action "Tolak", ikon x, warna merah.
- Beri konfirmasi ("Tolak pendaftaran mitra ini?").
- Saat diklik:
  - Ubah `status_pendaftaran` = 'ditolak'
  - Ubah `status_aktif` = false
  - Tampilkan notifikasi: "Pendaftaran mitra ditolak".

Kedua action ini sebaiknya HANYA muncul saat status masih 'pending' (gunakan `->visible(fn ($record) => $record->status_pendaftaran === 'pending')`). Untuk mitra yang sudah disetujui, action approve/tolak tidak perlu muncul (tetap ada aktif/nonaktif seperti sebelumnya).

### 3.4 Admin Tetap Bisa Tambah Mitra Manual
Fitur tambah mitra manual oleh admin (yang sudah ada) tetap dipertahankan. Mitra yang dibuat manual oleh admin langsung berstatus `status_pendaftaran` = 'disetujui' dan `status_aktif` = true (tidak perlu approve lagi).

---

## BAGIAN 4: TAUTAN KE FORM PENDAFTARAN MITRA

Agar form bisa diakses calon mitra, pastikan ada tautan `/daftar-mitra`. Untuk sekarang cukup route-nya bisa diakses langsung. (Nanti bisa ditautkan dari landing page.)

---

## KRITERIA SELESAI

1. Halaman `/daftar-mitra` menampilkan form pendaftaran mitra sesuai desain (nama, email, no HP, alamat, nama bank, no rekening, password + konfirmasi, checkbox S&K).
2. Validasi jalan: email unik, password minimal 8 & harus sama dengan konfirmasi, checkbox wajib dicentang. Pesan Bahasa Indonesia.
3. Submit valid → user baru dibuat dengan role 'mitra', status_pendaftaran 'pending', status_aktif false → muncul halaman sukses "menunggu persetujuan".
4. Mitra pending / ditolak TIDAK bisa login ke `/mitra`.
5. Di admin Manajemen Mitra: kolom Status Pendaftaran (badge) muncul + filter status.
6. Admin bisa "Setujui" mitra pending → status jadi disetujui + aktif + kode referral tergenerate → mitra bisa login.
7. Admin bisa "Tolak" mitra pending → status jadi ditolak → tetap tidak bisa login.
8. Action Setujui/Tolak hanya muncul untuk mitra berstatus pending.
9. Admin masih bisa tambah mitra manual (langsung disetujui & aktif).
10. Mitra yang sudah disetujui bisa login dan kode referralnya berfungsi di form pendaftaran pelanggan (`/daftar?ref=KODE`).
11. Semua fungsi lama tetap berjalan.

## PENGUJIAN YANG DISARANKAN
- Daftar mitra baru lewat `/daftar-mitra` → cek muncul di admin sebagai "Menunggu Persetujuan".
- Coba login mitra itu sebelum disetujui → ditolak.
- Admin klik Setujui → coba login lagi → berhasil masuk.
- Cek kode referral mitra baru berfungsi di `/daftar?ref=KODE`.
- Daftar mitra lain, admin klik Tolak → mitra tidak bisa login.
- Password dan konfirmasi berbeda → ditolak dengan pesan.

## JANGAN
- Jangan izinkan mitra pending/ditolak login.
- Jangan simpan password sebagai teks biasa — selalu Hash::make.
- Jangan kembalikan password lama ke form saat validasi gagal.
- Jangan rusak fitur manajemen mitra manual, panel mitra, komisi, atau form pendaftaran pelanggan yang sudah berjalan.
