# SIMITRA — Revisi Form Pendaftaran: Konfirmasi & Loading

Dua revisi pada form pendaftaran (`/daftar`). Keduanya soal tampilan/UX, jangan ubah fungsi inti (penyimpanan data, upload, peta, referral, validasi server tetap berjalan).

---

## REVISI 1: Ganti Konfirmasi Bawaan Browser dengan Modal Custom

**Masalah saat ini:** Konfirmasi sebelum submit memakai `confirm()` bawaan browser (kotak dialog polos yang muncul terpisah dari halaman, tampilannya tidak menyatu dengan desain web).

**Yang diminta:**
- Ganti `confirm()` bawaan browser dengan **modal/popup custom yang muncul DI DALAM halaman** (overlay + kotak dialog di tengah), sehingga menyatu dengan tampilan web dan tidak keluar sebagai popup browser terpisah.
- Isi modal konfirmasi:
  - Judul: "Konfirmasi Pengiriman"
  - Pesan: "Pastikan semua data sudah benar. Kirim pendaftaran sekarang?"
  - Dua tombol: **"Batal"** (menutup modal, kembali ke form tanpa mengirim) dan **"Ya, Kirim"** (melanjutkan submit form).
- Modal muncul di atas halaman dengan latar belakang gelap semi-transparan (overlay). Tampilan tetap berada di halaman utama (tidak pindah halaman).
- Modal ini muncul HANYA setelah validasi sisi klien lolos (jangan tampilkan konfirmasi jika masih ada field wajib yang kosong/tidak valid).
- Gunakan HTML + CSS + JavaScript biasa (vanilla JS), konsisten dengan pendekatan yang sudah dipakai di form ini.

---

## REVISI 2: Animasi Loading Saat Mengirim (Overlay, bukan hanya teks di tombol)

**Masalah saat ini:** Saat submit, hanya teks tombol berubah jadi "Mengirim..." (kurang terlihat/kurang jelas ke pengguna).

**Yang diminta:**
- Setelah pengguna menekan "Ya, Kirim" pada modal konfirmasi (Revisi 1), tampilkan **overlay loading yang menutupi layar** dengan **animasi yang jelas terlihat**, berisi:
  - Animasi loading (spinner berputar, atau titik-titik bergerak, atau animasi sejenis — bebas, yang penting terlihat bergerak/hidup).
  - Teks "Mengirim..." atau "Mengirim data Anda...".
- Overlay ini menutupi seluruh layar dengan latar semi-transparan, muncul TEPAT sebelum form benar-benar dikirim ke server, sehingga pengguna tahu proses sedang berjalan (penting karena upload 4 file bisa memakan beberapa detik).
- Selama overlay tampil, form tetap terkirim seperti biasa; setelah server merespons (redirect ke halaman sukses), overlay otomatis hilang karena halaman berpindah.
- Buat animasi murni dengan CSS (misal `@keyframes` untuk spinner) — jangan pakai library berat, cukup CSS/JS biasa.

**Catatan:** Boleh tetap mempertahankan tombol jadi disable saat proses (agar tidak dobel-submit), tapi indikator utama sekarang adalah overlay animasi ini, bukan sekadar teks di tombol.

---

## URUTAN ALUR YANG DIINGINKAN (setelah revisi)

1. Pengguna isi form → klik "Kirim Pendaftaran".
2. Validasi sisi klien jalan. Jika ada yang salah → tampilkan error, berhenti (tidak lanjut).
3. Jika lolos → muncul **modal konfirmasi custom** (Revisi 1).
4. Pengguna klik "Batal" → modal tutup, kembali ke form.
5. Pengguna klik "Ya, Kirim" → modal tutup, muncul **overlay loading beranimasi** (Revisi 2), form dikirim ke server.
6. Server memproses & redirect ke halaman sukses.

---

## KRITERIA SELESAI

1. Konfirmasi TIDAK lagi memakai popup browser (`confirm()`), melainkan modal custom di dalam halaman dengan tombol "Batal" & "Ya, Kirim".
2. Setelah klik "Ya, Kirim", muncul overlay loading dengan animasi yang jelas terlihat + teks "Mengirim...".
3. Semua fungsi lama tetap berjalan (data tersimpan, upload, peta, referral, halaman sukses).
4. Tidak ada popup browser polos yang muncul lagi dalam alur ini.

## JANGAN

- Jangan ubah logika penyimpanan data, upload, peta Leaflet, atau referral.
- Jangan pakai library JavaScript berat; cukup vanilla JS + CSS.
