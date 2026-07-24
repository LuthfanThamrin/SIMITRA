<x-mail::message>
# Halo {{ $mitra->nama }},

Selamat! Pendaftaran Anda sebagai mitra SIMITRA telah **disetujui** dan akun Anda kini sudah aktif.

Berikut adalah kode referral Anda yang dapat Anda sebarkan:
**{{ $mitra->kode_referral }}**

Tautan referral lengkap Anda:
[{{ url('/daftar?ref=' . $mitra->kode_referral) }}]({{ url('/daftar?ref=' . $mitra->kode_referral) }})

Silakan login menggunakan alamat email dan kata sandi yang telah Anda daftarkan.

<x-mail::button :url="url('/mitra/login')">
Login ke Panel Mitra
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
