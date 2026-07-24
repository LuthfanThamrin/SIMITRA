# SPESIFIKASI FITUR LANDING PAGE SIMITRA
## Dokumen Implementasi — Sprint 4

> Dokumen ini menjadi acuan implementasi Landing Page SIMITRA. Seluruh implementasi harus mengikuti spesifikasi ini dan tidak mengubah fitur yang telah selesai pada sprint sebelumnya.

---

# 1. TUJUAN FITUR

Landing Page merupakan halaman publik utama SIMITRA yang akan menjadi pintu masuk bagi calon mitra maupun calon pelanggan.

Tujuan landing page adalah:

- memperkenalkan SIMITRA
- meningkatkan kepercayaan pengunjung
- menjelaskan manfaat menjadi mitra
- menjelaskan alur kerja
- mengarahkan pengunjung menuju halaman pendaftaran

Landing page harus terlihat modern, profesional, ringan, dan konsisten dengan branding SIMITRA.

---

# 2. DESAIN ACUAN

Desain mengikuti file Stitch yang telah disediakan.

Jangan mengubah layout utama.

Gunakan:

- warna
- spacing
- typography
- icon
- animasi
- proporsi layout

sesuai desain Stitch.

---

# 3. STACK

- Laravel 10
- Blade
- TailwindCSS
- Vite
- Asset Laravel

Landing page bukan bagian dari Filament.

---

# 4. STRUKTUR HALAMAN

Urutan section:

1. Header
2. Hero
3. Benefit
4. Cara Kerja
5. Footer

Jangan mengubah urutan section.

---

# 5. DETAIL TIAP SECTION

## Header

Header bersifat sticky.

Isi:

- Logo SIMITRA
- Nama SIMITRA
- Menu Beranda
- Menu Benefit
- Menu Cara Kerja
- Tombol Daftar Sekarang

Saat discroll:

- tinggi mengecil
- background lebih solid
- shadow halus

Menu menggunakan smooth scrolling.

Tombol menuju:

/daftar

---

## Hero

Layout 2 kolom desktop.

Kolom kiri:

- Badge Official SIMITRA Partner
- Judul besar
- Deskripsi
- Tombol Daftar Sekarang

Kolom kanan:

- Hero Image
- Floating card statistik

CTA menuju:

/daftar

---

## Benefit

Judul:

Keuntungan Bergabung

Berisi dua card.

Card 1

Komisi Kompetitif

Card 2

Kemudahan Operasional

Hover:

- shadow bertambah
- border primary
- icon berubah biru

---

## Cara Kerja

Background surface.

Terdiri dari tiga langkah.

1 Registrasi

2 Instalasi

3 Terima Komisi

Desktop memakai garis penghubung.

Mobile tidak.

---

## Footer

Berisi:

- Logo
- Deskripsi
- Layanan
- Kontak
- Copyright

---

# 6. RESPONSIVE

Desktop:
>=1024px

Tablet:
768–1023px

Mobile:
<768px

Semua section wajib responsive.

---

# 7. BRANDING

Primary

#1D5FAE

Secondary

#B71328

Background

#FFFFFF

Surface

#F9F9FC

Font:

Comfortaa

---

# 8. ANIMASI

- Smooth scrolling
- Header shrink
- Hover button
- Hover card
- Transition sekitar 300ms

---

# 9. INTEGRASI

Tombol "Daftar Sekarang" menuju:

/daftar

Landing page tidak boleh mengubah sistem pendaftaran yang sudah ada.

---

# 10. HAL YANG TIDAK BOLEH DIUBAH

- Database
- Migration
- Model
- Authentication
- Filament
- Dashboard Admin
- Dashboard Mitra
- Sistem Referral
- Sistem Komisi

---

# 11. ACCEPTANCE CRITERIA

- Tampilan sesuai desain Stitch.
- Responsive.
- Header sticky.
- Smooth scrolling.
- CTA menuju /daftar.
- Tidak ada console error.
- Tidak merusak fitur yang sudah ada.

---

# 12. TESTING

Lakukan pengujian:

- Desktop
- Tablet
- Mobile

Pastikan:

- seluruh menu berfungsi
- CTA berfungsi
- animasi berjalan
- layout tidak rusak
- tidak ada error browser
