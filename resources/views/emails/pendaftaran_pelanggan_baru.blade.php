<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Pelanggan Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Pendaftaran Pelanggan Baru</h2>
    <p>Halo Admin,</p>
    <p>Terdapat pendaftaran pelanggan baru dengan rincian sebagai berikut:</p>
    <ul>
        <li><strong>Nama Pemilik:</strong> {{ $pendaftaran->nama_pemilik }}</li>
        <li><strong>Nama Usaha:</strong> {{ $pendaftaran->nama_usaha }}</li>
        <li><strong>No. HP:</strong> {{ $pendaftaran->no_hp }}</li>
        <li><strong>Kota:</strong> {{ $pendaftaran->kota }}</li>
        <li><strong>Paket yang Dipilih:</strong> 
            @if($pendaftaran->konsultasi_paket)
                Konsultasi
            @else
                {{ $pendaftaran->paket ? $pendaftaran->paket->nama_paket . ' ' . $pendaftaran->paket->kecepatan : '-' }}
            @endif
        </li>
        <li><strong>Mitra Referral:</strong> {{ $pendaftaran->mitra ? $pendaftaran->mitra->nama : 'Tidak ada' }}</li>
        <li><strong>Tanggal Daftar:</strong> {{ $pendaftaran->created_at->format('d M Y H:i') }}</li>
    </ul>
    
    <p>Silakan periksa halaman Data Pendaftaran untuk menindaklanjuti pendaftaran ini:</p>
    <p>
        <a href="{{ url('/admin/pendaftarans') }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #1D5FAE; text-decoration: none; border-radius: 5px;">Lihat Data Pendaftaran</a>
    </p>

    <p>Terima kasih,<br>Sistem SIMITRA</p>
</body>
</html>
