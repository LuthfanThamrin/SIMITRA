<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mitra Berhasil</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">try{
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1D5FAE',
                        'primary-container': '#d6e3ff',
                        secondary: '#b71328',
                        background: '#f9f9fc',
                        surface: '#ffffff',
                        'on-background': '#1a1c1e',
                        'on-surface': '#1a1c1e',
                        'on-surface-variant': '#424751',
                        'outline-variant': '#c2c6d3',
                    },
                    fontFamily: {
                        'headline': ['Comfortaa', 'sans-serif'],
                        'body': ['Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        }
    } catch (_e) {}catch(_e){};</script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-2xl bg-surface border border-outline-variant rounded-3xl p-10 md:p-14 shadow-sm text-center">
            <div class="flex items-center justify-center gap-3 mb-8">
                <img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA Logo" class="h-12 w-auto object-contain">
                <span class="font-headline text-2xl text-primary tracking-tight">SIMITRA</span>
            </div>
            <div class="mb-8">
                <h1 class="font-headline text-4xl text-primary lowercase mb-4">Pendaftaran Berhasil Dikirim</h1>
                <p class="text-base md:text-lg text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                    Terima kasih, pendaftaran mitra Anda sudah kami terima. Akun akan aktif setelah admin menyetujui permohonan. Silakan tunggu notifikasi atau kontak admin untuk informasi selanjutnya.
                </p>
            </div>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center bg-primary text-white font-bold py-3 px-8 rounded-full hover:bg-primary/90 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
