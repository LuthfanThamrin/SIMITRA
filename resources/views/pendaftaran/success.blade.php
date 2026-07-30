<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Pendaftaran Berhasil</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-simitra.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&amp;family=Plus+Jakarta+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
              "surface": "#f9f9fc",
              "surface-dim": "#dadadc",
              "surface-bright": "#f9f9fc",
              "surface-container-lowest": "#ffffff",
              "surface-container-low": "#f3f3f6",
              "surface-container": "#eeeef0",
              "surface-container-high": "#e8e8ea",
              "surface-container-highest": "#e2e2e5",
              "on-surface": "#1a1c1e",
              "on-surface-variant": "#424751",
              "inverse-surface": "#2f3133",
              "inverse-on-surface": "#f0f0f3",
              "outline": "#727782",
              "outline-variant": "#c2c6d3",
              "surface-tint": "#1b5ead",
              "primary": "#00478c",
              "on-primary": "#ffffff",
              "primary-container": "#1d5fae",
              "on-primary-container": "#c8dbff",
              "inverse-primary": "#a8c8ff",
              "secondary": "#b71328",
              "on-secondary": "#ffffff",
              "secondary-container": "#da323d",
              "on-secondary-container": "#fffbff",
              "tertiary": "#44484b",
              "on-tertiary": "#ffffff",
              "tertiary-container": "#5c6063",
              "on-tertiary-container": "#d7dbde",
              "error": "#ba1a1a",
              "on-error": "#ffffff",
              "error-container": "#ffdad6",
              "on-error-container": "#93000a",
              "primary-fixed": "#d6e3ff",
              "primary-fixed-dim": "#a8c8ff",
              "on-primary-fixed": "#001b3d",
              "on-primary-fixed-variant": "#00468a",
              "secondary-fixed": "#ffdad8",
              "secondary-fixed-dim": "#ffb3b0",
              "on-secondary-fixed": "#410006",
              "on-secondary-fixed-variant": "#93001a",
              "tertiary-fixed": "#e0e3e6",
              "tertiary-fixed-dim": "#c3c7ca",
              "on-tertiary-fixed": "#181c1e",
              "on-tertiary-fixed-variant": "#43474a",
              "background": "#f9f9fc",
              "on-background": "#1a1c1e",
              "surface-variant": "#e2e2e5"
            },
            "fontFamily": {
              "headline-xl": ["Comfortaa"],
              "headline-lg": ["Comfortaa"],
              "headline-md": ["Comfortaa"],
              "headline-lg-mobile": ["Comfortaa"],
              "body-lg": ["Plus Jakarta Sans"],
              "body-md": ["Plus Jakarta Sans"],
              "label-md": ["Plus Jakarta Sans"]
            },
            "borderRadius": {
              "sm": "0.25rem",
              "DEFAULT": "0.5rem",
              "md": "0.75rem",
              "lg": "1rem",
              "xl": "1.5rem",
              "full": "9999px"
            },
            "spacing": {
              "header-height": "72px",
              "footer-padding-y": "64px",
              "gutter": "24px",
              "margin-desktop": "80px",
              "margin-mobile": "20px",
              "container-max-width": "1280px"
            }
          }
        }
      }
    </script>
    <style>
        .shadow-level-1 { box-shadow: 0 10px 25px rgba(15,23,42,0.08); }
    </style>
</head>
<body class="bg-[#f9f9fc] font-body-md text-body-md text-on-surface antialiased flex flex-col items-center justify-center min-h-screen px-4">
    
    <div class="w-full max-w-[390px] bg-surface rounded-xl shadow-level-1 p-8 text-center space-y-6">
        <a href="{{ url('/') }}" class="inline-block hover:opacity-90 transition-opacity">
            <img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA" class="h-10 w-auto mx-auto">
        </a>
        <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-green-600 text-[48px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        
        <h1 class="font-headline-lg text-[28px] font-bold text-on-surface">Pendaftaran Berhasil!</h1>
        
        <p class="text-on-surface-variant">
            Data Anda telah kami terima dan sedang menunggu verifikasi. Tim kami akan segera menindaklanjuti.
        </p>
        
        @if(session('success_id'))
            <div class="bg-surface-container-low border border-outline-variant rounded-lg p-3 inline-block">
                <span class="text-sm text-on-surface-variant">Kode Pendaftaran:</span>
                <span class="font-bold text-primary block text-lg">#{{ str_pad(session('success_id'), 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        @endif
        
        <div class="pt-4">
            <a href="{{ route('daftar.create') }}" class="inline-block w-full bg-primary text-on-primary text-center hover:opacity-90 transition-all duration-200 py-[12px] px-4 rounded-lg font-label-md shadow-sm active:scale-[0.98]">
                Kembali ke Form
            </a>
        </div>
    </div>
    
</body>
</html>
