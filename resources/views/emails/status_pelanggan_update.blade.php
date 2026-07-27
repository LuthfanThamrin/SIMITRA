<!DOCTYPE html>
<html>
<head>
    <title>Status Pendaftaran Pelanggan Diperbarui</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo Mitra {{ $pendaftaran->mitra->nama }},</h2>
    
    <p>Status pendaftaran untuk pelanggan Anda telah diperbarui.</p>
    
    <ul>
        <li><strong>Nama Usaha:</strong> {{ $pendaftaran->nama_usaha }}</li>
        <li><strong>Nama Pemilik:</strong> {{ $pendaftaran->nama_pemilik }}</li>
        <li><strong>Status Saat Ini:</strong> 
            @if($pendaftaran->status === 'terpasang')
                <span style="color: green; font-weight: bold;">Terpasang</span>
            @elseif($pendaftaran->status === 'ditolak')
                <span style="color: red; font-weight: bold;">Ditolak</span>
            @else
                {{ ucfirst($pendaftaran->status) }}
            @endif
        </li>
    </ul>

    @if($pendaftaran->status === 'terpasang')
        <p>Selamat! Layanan untuk pelanggan <strong>{{ $pendaftaran->nama_usaha }}</strong> telah terpasang. Komisi Anda atas pendaftaran ini akan segera dihitung dan ditambahkan ke akun Anda.</p>
    @elseif($pendaftaran->status === 'ditolak')
        <p>Mohon maaf, pendaftaran untuk pelanggan <strong>{{ $pendaftaran->nama_usaha }}</strong> telah ditolak.</p>
        @if(!empty($pendaftaran->catatan_admin))
            <p><strong>Catatan dari Admin:</strong><br/>
            <span style="background-color: #f8d7da; padding: 10px; display: inline-block; border-radius: 5px; color: #721c24;">
                {{ $pendaftaran->catatan_admin }}
            </span>
            </p>
        @endif
        <p>Anda dapat mendaftarkan pelanggan lain atau memperbaiki data jika memungkinkan.</p>
    @endif

    <p>Silakan periksa halaman Pelanggan Saya untuk menindaklanjuti atau melihat detail lebih lanjut:</p>
    <p>
        <a href="{{ url('/mitra/pendaftarans') }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #1D5FAE; text-decoration: none; border-radius: 5px;">Lihat Data Pelanggan</a>
    </p>

    <p>Terima kasih,<br>Sistem SIMITRA</p>
</body>
</html>
