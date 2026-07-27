<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMITRA | Mitra</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">try{tailwind.config={darkMode:'class',theme:{extend:{colors:{primary:'#1D5FAE','primary-container':'#d6e3ff',secondary:'#b71328',background:'#ffffff',surface:'#f9f9fc','on-background':'#1a1c1e','on-surface':'#1a1c1e','on-surface-variant':'#424751','outline-variant':'#e2e2e5'},fontFamily:{headline:['Comfortaa','sans-serif'],body:['Comfortaa','sans-serif']},spacing:{'container-max-width':'1280px'}}}}}catch(_e){};</script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        .brand-simitra {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            color: #1D5FAE !important;
        }
        .header-font {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        body { font-family: 'Comfortaa', sans-serif; }
        .nav-link-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background-color: currentColor;
            transition: width 0.3s ease;
        }
        .nav-link-underline:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-background text-on-background overflow-x-hidden">
    <header class="header-font bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-outline-variant w-full px-6 md:px-20 h-20 flex justify-between items-center transition-all duration-300">
        <div class="flex items-center gap-3">
            <img alt="SIMITRA Logo" class="h-10 w-auto" src="{{ asset('images/logo-simitra.png') }}">
            <span class="text-xl tracking-tight brand-simitra">SIMITRA</span>
        </div>
        <nav class="hidden md:flex items-center gap-10 font-medium">
            <a class="text-on-background hover:text-primary transition-colors relative nav-link-underline" href="#beranda">Beranda</a>
            <a class="text-on-background hover:text-primary transition-colors relative nav-link-underline" href="#benefit">Benefit</a>
            <a class="text-on-background hover:text-primary transition-colors relative nav-link-underline" href="#cara-kerja">Cara Kerja</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ url('/mitra/login') }}" class="bg-primary-container text-primary font-bold px-5 py-2.5 rounded-full transition-all hover:brightness-90 active:scale-95">
                Login
            </a>
            <a class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-full font-bold transition-all active:scale-95 shadow-lg shadow-primary/20" href="{{ url('/daftar-mitra') }}">
                Daftar Sekarang
            </a>
        </div>
    </header>

    <main>
        <section class="relative min-h-[85vh] flex items-center pt-10 pb-20 px-6 md:px-20" id="beranda">
            <div class="max-w-container-max-width mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-bold">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                        Official indibiz Partner
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold text-on-background leading-[1.1] lowercase" style="font-weight: 700;">
                        solusi cerdas untuk bisnis anda
                    </h1>
                    <p class="text-xl text-on-surface-variant max-w-xl leading-relaxed">
                       SIMITRA memudahkan pendaftaran layanan internet bisnis INDIBIZ sekaligus menjadi wadah kemitraan bagi agen yang ingin berkembang bersama.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a class="flex items-center justify-center bg-primary hover:bg-primary/90 text-white px-10 py-5 rounded-full font-bold text-lg shadow-xl shadow-primary/25 transition-all hover:-translate-y-1" href="{{ url('/daftar-mitra') }}">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <img alt="SIMITRA Business Analytics" class="w-full h-auto rounded-[3rem] shadow-2xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhNne3I6qFYrDywJ2OFlFftF4fY6fp6CgEAWpA6t8Cb2eS_dcQLNJm-aBO0Ujsd_nPY_aFzH069Qp7zXcZZomq3egy4qJ3LNnr5nRQpduECId8B-IpBnQBxXPKshylYdxAkT0bbrmLmbcCRNU8ftCvfhBz19-FNdEVZQFTiA9784XsA7iU87tDxXW7FpV1KPS5KmSV27BFfFYdCF4tQriKCw7XsiyV0OVVcyyXKoOOfk3D-yaGjk4N" />
                </div>
            </div>
        </section>

        <section class="py-24 px-6 md:px-20" id="benefit">
            <div class="max-w-container-max-width mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-primary mb-4">keuntungan bergabung</h2>
                    <p class="text-lg text-on-surface-variant max-w-2xl mx-auto">Sistem kemitraan yang dirancang khusus untuk memaksimalkan potensi pendapatan Anda.</p>
                </div>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 max-w-4xl mx-auto">
                    <div class="p-10 rounded-[2rem] border border-outline-variant hover:border-primary hover:shadow-xl transition-all duration-300 group">
                        <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-3xl">payments</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-on-background">Komisi Kompetitif</h3>
                        <p class="text-on-surface-variant leading-relaxed">Nikmati bagi hasil transparan dan pembayaran tepat waktu untuk setiap layanan yang terpasang.</p>
                    </div>
                    <div class="p-10 rounded-[2rem] border border-outline-variant hover:border-primary hover:shadow-xl transition-all duration-300 group">
                        <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-3xl">bolt</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-on-background">Kemudahan Operasional</h3>
                        <p class="text-on-surface-variant leading-relaxed">Dashboard manajemen mitra yang intuitif untuk memantau performa dan prospek Anda secara real-time.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-surface px-6 md:px-20" id="cara-kerja">
            <div class="max-w-container-max-width mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-primary">langkah mudah menjadi mitra</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                    <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-primary/10 -translate-y-1/2 z-0"></div>
                    <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                        <div class="w-20 h-20 bg-white border-4 border-primary rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-primary">1</span>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-outline-variant w-full">
                            <span class="material-symbols-outlined text-4xl text-primary mb-4">app_registration</span>
                            <h4 class="text-xl font-bold mb-2">Registrasi</h4>
                            <p class="text-on-surface-variant text-sm">Isi formulir pendaftaran mitra melalui portal resmi SIMITRA.</p>
                        </div>
                    </div>
                    <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                        <div class="w-20 h-20 bg-white border-4 border-primary rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-primary">2</span>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-outline-variant w-full">
                            <span class="material-symbols-outlined text-4xl text-primary mb-4">router</span>
                            <h4 class="text-xl font-bold mb-2">Instalasi</h4>
                            <p class="text-on-surface-variant text-sm">Proses aktivasi layanan pelanggan oleh tim teknis lapangan.</p>
                        </div>
                    </div>
                    <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                        <div class="w-20 h-20 bg-white border-4 border-primary rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-primary">3</span>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-outline-variant w-full">
                            <span class="material-symbols-outlined text-4xl text-primary mb-4">savings</span>
                            <h4 class="text-xl font-bold mb-2">Terima Komisi</h4>
                            <p class="text-on-surface-variant text-sm">Dapatkan komisi untuk setiap layanan yang berhasil terpasang.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-on-background text-white pt-20 pb-10 px-6 md:px-20">
        <div class="max-w-container-max-width mx-auto grid grid-cols-1 md:grid-cols-4 gap-16 border-b border-white/10 pb-16">
            <div class="col-span-1 md:col-span-2 space-y-8">
                <div class="flex items-center gap-3">
                    <img alt="SIMITRA Footer Logo" class="h-10 w-auto" src="{{ asset('images/logo-simitra.png') }}">
                    <span class="font-bold text-2xl tracking-tight">SIMITRA</span>
                </div>
                <p class="text-white/60 max-w-sm leading-relaxed">
                    Penyedia solusi bisnis internet terpadu di Indonesia. Memberdayakan UMKM dan korporasi melalui ekosistem digital yang transparan dan handal.
                </p>
            </div>
            
            <div class="space-y-6">
                <h4 class="text-lg font-bold">Hubungi Kami</h4>
                <ul class="space-y-4 text-white/60">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        <span>simitra003@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <span>Balikpapan, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <a class="flex items-center gap-3 hover:text-white transition-colors" href="https://wa.me/your-number">
                            <span class="material-symbols-outlined text-primary">chat</span>
                            <span>WhatsApp Support</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-container-max-width mx-auto flex flex-col md:flex-row justify-between items-center pt-10 gap-4 text-sm text-white/40">
            <p>© 2026 SIMITRA. All rights reserved.</p>
            <p>Built with precision in Balikpapan</p>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('h-16', 'bg-white/95', 'shadow-sm');
                header.classList.remove('h-20');
            } else {
                header.classList.remove('h-16', 'bg-white/95', 'shadow-sm');
                header.classList.add('h-20');
            }
        });
    </script>
</body>
</html>
