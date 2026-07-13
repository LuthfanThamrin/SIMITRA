# SIMITRA — Instruksi Perbaikan (Improve) Form Pendaftaran

Form pendaftaran publik (`/daftar`) sudah berfungsi (data tersimpan ke database, upload jalan, peta Leaflet jalan, referral jalan). Sekarang lakukan sejumlah **peningkatan kualitas (improve)** berikut. Semua perbaikan bersifat ringan (HTML/CSS/JavaScript sisi klien) dan TIDAK boleh mengubah fungsi inti yang sudah berjalan (penyimpanan data, upload, peta, referral, validasi server).

Kerjakan semua poin di bawah ini.

---

## 1. Loading State saat Submit (cegah dobel-submit)

Saat pengguna menekan tombol "Kirim Pendaftaran":
- Ubah teks tombol menjadi "Mengirim..." dan tampilkan indikator loading (spinner sederhana atau teks).
- Nonaktifkan (disable) tombol submit selama proses berlangsung, agar pengguna tidak bisa menekan berkali-kali (mencegah data terkirim dobel).
- Aktifkan kembali tombol jika terjadi error validasi sisi klien (agar pengguna bisa memperbaiki dan submit ulang).

Implementasikan via JavaScript pada event submit form.

---

## 2. Validasi & Format Nomor HP

Untuk field Nomor HP (WhatsApp):
- Batasi input hanya angka (boleh diawali 0). Karakter selain angka tidak diterima.
- Validasi panjang: minimal 10 digit, maksimal 15 digit.
- Jika tidak valid saat pengguna selesai mengisi (on blur) atau saat submit, tampilkan pesan di bawah field: "Nomor HP tidak valid (contoh: 081234567890)".
- Tambahkan juga validasi ini di sisi server (controller) sebagai lapis kedua: `no_hp` harus `regex:/^0[0-9]{9,14}$/` dengan pesan Bahasa Indonesia.

---

## 3. Preview Foto Setelah Dipilih

Untuk setiap input file (KTP, izin usaha, NIB/NPWP, foto lokasi):
- Setelah pengguna memilih file, tampilkan **preview thumbnail kecil** dari gambar tersebut di bawah/di dalam area unggah (untuk file gambar JPG/PNG). Untuk file PDF, cukup tampilkan ikon PDF + nama file.
- Tampilkan juga nama file yang dipilih.
- Jika pengguna mengganti file, preview ikut berubah.

Ini membantu pengguna memastikan file yang benar sudah dipilih.

---

## 4. Konfirmasi Sebelum Kirim

Sebelum form benar-benar dikirim:
- Tampilkan dialog konfirmasi (boleh `confirm()` bawaan browser, atau modal sederhana) berisi pesan: "Pastikan semua data sudah benar. Kirim pendaftaran sekarang?".
- Jika pengguna menekan "Ya/OK" → lanjutkan submit.
- Jika "Batal" → batalkan submit, pengguna kembali ke form tanpa kehilangan data.

Pastikan konfirmasi ini muncul SETELAH validasi sisi klien lolos (jangan konfirmasi dulu baru ketahuan ada field kosong).

---

## 5. Nomor Pendaftaran di Halaman Sukses

Pada halaman "Pendaftaran Berhasil" (`success`):
- Tampilkan nomor/kode pendaftaran berdasarkan `id` data yang baru tersimpan, dengan format rapi, contoh: "Kode Pendaftaran: #000123" (id di-pad dengan nol di depan hingga 6 digit).
- Kirim id ini dari controller `store` ke halaman sukses (misal via session flash atau parameter), lalu tampilkan di view success.

---

## 6. Placeholder Panduan Foto (SEDIAKAN TEMPATNYA, JANGAN ISI)

Tautan "Lihat contoh" pada tiap field unggah sudah ada. Untuk sekarang:
- **Biarkan tautan/tombol "Lihat contoh" tetap ada** di tiap field.
- Saat diklik, tampilkan **modal kosong** dengan struktur siap-pakai: ada judul "Panduan Foto [nama dokumen]", dan dua area kosong bertuliskan "Contoh Benar" (dengan tempat gambar) dan "Contoh Salah" (dengan tempat gambar). Beri komentar di kode `<!-- TODO: isi gambar contoh benar/salah di sini nanti -->`.
- **JANGAN hapus fitur ini.** Materi gambar contoh belum tersedia dan akan diisi nanti. Yang penting strukturnya sudah siap sehingga tinggal menambahkan gambar.

---

## HAL YANG TIDAK PERLU DIKERJAKAN

- JANGAN buat progress bar / indikator langkah.
- JANGAN ubah logika penyimpanan data, upload ke storage, peta Leaflet, atau sistem referral yang sudah berjalan.
- JANGAN buat migration baru.

---

## KRITERIA SELESAI

1. Tombol submit berubah jadi "Mengirim..." & disable saat proses (tidak bisa dobel-klik).
2. Nomor HP hanya menerima angka, tervalidasi (klien + server), pesan Bahasa Indonesia.
3. Preview thumbnail muncul setelah memilih file (gambar), nama file tampil (PDF).
4. Muncul konfirmasi "Pastikan data benar..." sebelum submit final.
5. Halaman sukses menampilkan "Kode Pendaftaran: #000xxx".
6. Tombol "Lihat contoh" membuka modal kosong dengan struktur siap-isi (ada TODO comment).
7. Semua fungsi lama tetap berjalan normal (data tersimpan, upload, peta, referral).
