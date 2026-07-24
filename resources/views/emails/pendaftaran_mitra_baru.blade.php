<x-mail::message>
# Pendaftaran Mitra Baru

Halo Admin,

Telah mendaftar calon mitra baru dengan rincian sebagai berikut:

- **Nama Lengkap**: {{ $mitra->nama }}
- **Email**: {{ $mitra->email }}
- **No. HP**: {{ $mitra->no_hp }}
- **Nama Bank**: {{ $mitra->nama_bank }}
- **No. Rekening**: {{ $mitra->no_rekening }}
- **Alamat Lengkap**: {{ $mitra->alamat }}
- **Tanggal Daftar**: {{ $mitra->created_at->translatedFormat('d F Y, H:i') }}

Silakan cek halaman Admin untuk melakukan review dan persetujuan (approval) terhadap mitra ini.

<x-mail::button :url="url('/admin')">
Ke Dashboard Admin
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
