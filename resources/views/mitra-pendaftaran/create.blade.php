<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mitra SIMITRA</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">try {
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
    } catch (_e) {} catch (_e) {};</script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
        .input-error { border-color: #dc2626; }
        .text-error { color: #dc2626; }
        /* Header solid — tidak tembus saat scroll */
        header {
            background-color: #ffffff !important;
            -webkit-backdrop-filter: none;
            backdrop-filter: none;
        }
        /* Font headline Comfortaa */
        .font-headline, h1.font-headline {
            font-family: 'Comfortaa', sans-serif !important;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen font-body">
    <header class="flex justify-between items-center w-full px-8 md:px-16 h-16 sticky top-0 z-50 border-b border-outline-variant shadow-sm" style="background-color: #ffffff;">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA Logo" class="h-10 w-10 object-contain">
            <span class="font-headline text-primary tracking-tight text-xl">SIMITRA</span>
        </div>

        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="font-semibold text-secondary hover:text-primary transition-colors">Beranda</a>
            <a href="#form" class="font-semibold text-primary border-b-2 border-primary pb-1">Daftar Mitra</a>
        </nav>

        <div class="flex items-center gap-4">
            <a href="{{ url('/daftar-mitra') }}" class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:brightness-90 transition-all active:scale-95 inline-block" style="background-color: #1D5FAE; color: #ffffff;">
                Daftar Sekarang
            </a>
            <a href="{{ url('/mitra/login') }}" class="bg-primary-container text-primary font-bold py-2 px-6 rounded-lg hover:brightness-90 transition-all active:scale-95 inline-block" style="background-color: #d6e3ff; color: #1D5FAE;">
                Login
            </a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-16 px-4">
        <div class="w-full max-w-4xl">
            <div class="text-center mb-10">
                <h1 class="font-headline text-4xl md:text-5xl font-bold text-primary lowercase mb-2" style="font-family: 'Comfortaa', sans-serif;">pendaftaran mitra baru</h1>
                <p class="text-base md:text-lg text-on-surface-variant max-w-2xl mx-auto">
                    Bergabunglah bersama ekosistem SIMITRA INDIBIZ dan kembangkan potensi bisnis Anda melalui kolaborasi profesional.
                </p>
            </div>

            <div class="bg-surface border border-outline-variant rounded-3xl p-8 md:p-12 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <strong>Periksa kembali form</strong> dan perbaiki data yang perlu diisi.
                    </div>
                @endif

                <form id="form" action="{{ route('daftar-mitra.store') }}" method="POST" class="space-y-7 relative z-10">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nama" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">person</span>
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Masukkan nama sesuai KTP" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('nama') ? 'input-error' : '' }}">
                            @error('nama')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="email" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">mail</span>
                                Alamat Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="contoh@domain.com" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('email') ? 'input-error' : '' }}">
                            @error('email')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="alamat" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                Alamat Lengkap
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap domisili" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('alamat') ? 'input-error' : '' }}">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="no_hp" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">phone</span>
                                No HP
                            </label>
                            <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('no_hp') ? 'input-error' : '' }}">
                            @error('no_hp')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nama_bank" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">account_balance</span>
                                Nama Bank
                            </label>
                            <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank') }}" placeholder="Contoh: Bank Mandiri" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('nama_bank') ? 'input-error' : '' }}">
                            @error('nama_bank')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="no_rekening" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                Nomor Rekening
                            </label>
                            <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}" placeholder="Contoh: 1234567890" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 {{ $errors->has('no_rekening') ? 'input-error' : '' }}">
                            @error('no_rekening')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="password" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">lock</span>
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="••••••••" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 pr-12 {{ $errors->has('password') ? 'input-error' : '' }}">
                                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="password_confirmation" class="font-semibold text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">verified_user</span>
                                Konfirmasi Kata Sandi
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface text-on-background outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/10 pr-12">
                                <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 py-2">
                        <input type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required class="mt-1 h-4 w-4 text-primary border-outline-variant rounded focus:ring-primary">
                        <label for="terms" class="text-sm text-on-surface-variant leading-tight">
                            Saya menyetujui <a href="#" class="text-primary font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-primary font-semibold hover:underline">Kebijakan Privasi</a> yang berlaku di SIMITRA.
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-error text-sm -mt-4">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full bg-primary-container text-white font-bold py-3 px-6 rounded-lg hover:brightness-90 transition-all active:scale-95" style="background-color: #1D5FAE; color: #ffffff;">
                        Daftar Sekarang
                    </button>

                    <p class="text-center text-sm text-on-surface-variant mt-4">
                        Sudah punya akun?
                        <a href="{{ url('/mitra/login') }}" class="text-primary font-bold hover:underline">Masuk di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-surface border-t border-outline-variant py-6 text-center text-sm text-on-surface-variant">
        <p>© {{ date('Y') }} SIMITRA INDIBIZ. Semua hak dilindungi.</p>
    </footer>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const input = document.getElementById('password_confirmation');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
