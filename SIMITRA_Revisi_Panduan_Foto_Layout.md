# SIMITRA — Revisi Gabungan: Panduan Foto (Lihat Contoh), Field Wajib, & Perbaikan Layout Upload

Tiga perbaikan pada form pendaftaran (`/daftar`) yang sudah berjalan. JANGAN merusak fungsi yang sudah ada (upload, peta, referral, paket, penyimpanan data, validasi, modal konfirmasi, animasi loading).

---

## BAGIAN 1: ISI MODAL "LIHAT CONTOH" DENGAN GAMBAR PANDUAN + KRITERIA

Saat ini tautan "Lihat contoh" pada tiap field unggah membuka modal kosong (placeholder). Sekarang isi modal tersebut dengan **gambar contoh valid/tidak valid + teks kriteria** sesuai jenis dokumennya.

### Persiapan file gambar
Gambar panduan akan disediakan (di-upload manual ke project). Simpan semua gambar di folder: `public/images/panduan/`.
Nama file gambar yang akan dipakai (akan disediakan oleh pemilik project):
- `ktp-valid.jpg` — contoh KTP yang benar
- `ktp-invalid.jpg` — contoh KTP yang salah
- `npwp-valid.jpg` — contoh NPWP/NIB/dokumen usaha yang benar
- `npwp-invalid.jpg` — contoh NPWP/NIB/dokumen usaha yang salah
- `bangunan-valid.jpg` — contoh foto bangunan/tampak depan usaha yang benar
- `bangunan-invalid.jpg` — contoh foto bangunan yang salah

(Jika nama file berbeda, sesuaikan di kode. Gunakan path `asset('images/panduan/NAMA.jpg')`.)

### Struktur modal panduan (untuk SETIAP jenis dokumen)
Tiap modal "Lihat contoh" menampilkan 2 kolom bersebelahan (atau bertumpuk di mobile):
- **Kolom kiri — CONTOH BENAR:** gambar valid + label hijau "✓ VALID" + daftar kriteria benar.
- **Kolom kanan — CONTOH SALAH:** gambar invalid + label merah "✗ TIDAK VALID" + daftar kriteria salah.

Modal harus bisa ditutup (tombol X atau klik luar area). Responsif (2 kolom di desktop, menumpuk di HP).

### Isi tiap modal:

**MODAL 1 — Foto KTP** (gambar: ktp-valid.jpg & ktp-invalid.jpg)
- ✓ VALID:
  - Data terbaca jelas, tidak blur
  - Seluruh bagian KTP terlihat (tidak terpotong)
  - Masa berlaku aktif (kecuali E-KTP seumur hidup)
  - Pencahayaan cukup, tidak silau
- ✗ TIDAK VALID:
  - Identitas rusak / buram
  - Masa berlaku sudah habis
  - Sebagian data atau foto tertutup
  - Blur, gelap, atau terpotong

**MODAL 2 — Foto NPWP / NIB / Dokumen Usaha** (gambar: npwp-valid.jpg & npwp-invalid.jpg)
- ✓ VALID:
  - Data tampak jelas dan terbaca
  - Alamat pada dokumen sesuai dengan alamat instalasi
  - Data sesuai saat dicek di OSS
- ✗ TIDAK VALID:
  - Dokumen salah (bukan NPWP/NIB/izin usaha, misal malah PBB)
  - Buram / blur / tidak terbaca
  - Masa berlaku habis (expired)

**MODAL 3 — Foto Tampak Depan Usaha** (gambar: bangunan-valid.jpg & bangunan-invalid.jpg)
- ✓ VALID:
  - Bangunan tampak menyeluruh dari depan
  - Ada papan nama / logo usaha yang terlihat jelas
  - Foto asli hasil ambil langsung (bukan editing, bukan dari Google Street View)
  - Gambar jelas dan terang
- ✗ TIDAK VALID:
  - Diambil dari Google Street View / hasil editing
  - Bangunan tidak terlihat jelas / dari terlalu jauh
  - Gelap / backlight / hanya sebagian bangunan
  - Tidak menampilkan bangunan yang akan dipasang internet

### Catatan teknis
- Tautan "Lihat contoh" pada tiap field membuka modal yang SESUAI dengan dokumen itu (KTP → modal KTP, dst).
- Pastikan bug sebelumnya tidak muncul lagi: klik "Lihat contoh" TIDAK memicu dialog upload file (gunakan `event.preventDefault()` + `event.stopPropagation()`, dan pastikan tautan tidak berada di dalam `<label>` input file).
- Gambar ditampilkan dengan ukuran wajar (tidak terlalu besar), bisa proporsional.

---

## BAGIAN 2: FIELD WAJIB & OPSIONAL

Pastikan aturan wajib/opsional berikut diterapkan (validasi sisi klien DAN server), dengan pesan Bahasa Indonesia:

**WAJIB diisi (jika kosong, form tidak bisa dikirim):**
- Nama Pemilik / Penanggung Jawab (PIC)
- Nama Usaha
- Nomor HP (WhatsApp)
- Alamat Instalasi
- Kota
- Jenis Usaha (jika "Lainnya", maka Jenis Usaha Lainnya wajib diisi)
- Paket (pilih paket atau opsi "Konsultasi Dulu")
- Foto KTP
- Foto NPWP / NIB / Dokumen Usaha
- Foto Tampak Depan Usaha
- Titik Lokasi (latitude & longitude harus terisi)

**OPSIONAL (boleh kosong):**
- CP Alternatif

Pastikan `cp_alternatif` bersifat `nullable` di validasi server, dan tidak menghalangi submit jika kosong. Semua field lain `required`.

---

## BAGIAN 3: PERBAIKAN LAYOUT KOTAK UPLOAD (masih belum sejajar)

**Masalah:** Kotak upload "Foto NPWP / NIB / Dokumen Usaha" masih belum sejajar / rata dengan kotak "Foto KTP" di sebelahnya. Kotak yang labelnya lebih panjang (2 baris) posisinya turun, sehingga kedua kotak tidak rata atas/bawah.

**Perbaikan yang diminta (harus benar-benar diterapkan):**
- Buat setiap pasangan kotak upload dalam satu baris memiliki **tinggi sama & sejajar rata atas-bawah**, meskipun panjang labelnya berbeda.
- Solusi yang disarankan (pilih yang paling tepat):
  1. Gunakan CSS Grid atau Flexbox dengan `items-stretch` pada container baris, sehingga tiap kolom mengisi tinggi yang sama.
  2. ATAU beri **tinggi minimum tetap** pada area label (misal `min-height` yang cukup untuk 2 baris teks), sehingga label 1 baris dan 2 baris tetap menyisakan ruang sama, dan kotak upload mulai pada posisi vertikal yang sama.
  3. ATAU pastikan area label dan area kotak upload dipisah menjadi 2 baris grid yang selaras antar kolom (label sejajar dengan label, kotak sejajar dengan kotak).
- Hasil akhir WAJIB: kotak KTP dan kotak NPWP/NIB (dan pasangan lainnya) **rata & sejajar**, rapi di desktop maupun mobile. Uji dengan label pendek vs panjang — keduanya harus tetap sejajar.

---

## KRITERIA SELESAI

1. Klik "Lihat contoh" pada tiap dokumen membuka modal berisi gambar contoh valid & tidak valid + kriteria teks, sesuai dokumennya.
2. Klik "Lihat contoh" tidak memicu dialog upload.
3. Field wajib benar-benar wajib; CP Alternatif tetap opsional (boleh kosong).
4. Kotak-kotak upload dokumen sejajar rata (tidak ada yang turun/condong), termasuk pasangan KTP & NPWP/NIB.
5. Semua fungsi lama tetap berjalan (upload, peta, referral, paket, penyimpanan, modal konfirmasi, animasi loading).

## JANGAN
- Jangan rusak fitur yang sudah berjalan.
- Jangan wajibkan CP Alternatif.
- Jangan biarkan kotak upload tidak sejajar — perbaikan layout ini wajib benar-benar terlihat hasilnya.
