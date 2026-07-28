<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Formulir Pendaftaran INDIBIZ</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-simitra.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        .brand-simitra {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            color: #1D5FAE !important;
        }
        .header-font {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .shadow-level-1 { box-shadow: 0 10px 25px rgba(15,23,42,0.08); }
        /* Hide file input text */
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body class="bg-background font-body-md text-body-md text-on-surface antialiased flex flex-col items-center justify-start min-h-screen px-4 py-6 md:py-12">
<div class="w-full max-w-full md:max-w-[600px] lg:max-w-[700px] bg-surface rounded-xl shadow-level-1 overflow-hidden flex flex-col">
    <!-- Header Minimalis -->
    <header class="header-font flex items-center gap-2 px-6 h-header-height border-b border-outline-variant bg-surface sticky top-0 z-10">
        <img src="{{ asset('images/logo-simitra.png') }}" alt="SIMITRA" class="h-10 w-10 object-contain">
        <span class="tracking-tight text-[24px] brand-simitra">SIMITRA</span>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-6 pb-12 space-y-8">
        <!-- Header Section -->
        <div class="space-y-2">
            <h1 class="font-headline-lg text-[28px] font-bold text-on-background lowercase leading-tight" style="font-weight: 700;">formulir pendaftaran indibiz</h1>
            <p class="font-body-md text-on-surface-variant">Lengkapi data berikut untuk mendaftar</p>
            
            @if($mitra)
            <div class="bg-surface-container-low border border-primary-container rounded-lg p-4 mt-4">
                <p class="font-body-md text-primary-container text-sm">
                    Anda mendaftar melalui mitra:<br>
                    <strong class="font-label-md text-primary mt-1 block">{{ $mitra->nama }} ({{ $mitra->kode_referral }})</strong>
                </p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-error-container text-on-error-container p-4 mt-4 rounded-lg border border-error text-sm">
                {{ session('error') }}
            </div>
            @endif
        </div>

        <form id="pendaftaranForm" action="{{ route('daftar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if(!$mitra)
            <div class="space-y-1">
                <label class="font-label-md text-on-surface-variant block">Kode Referral Mitra <span class="text-outline font-normal">(opsional)</span></label>
                <input name="kode_referral" value="{{ old('kode_referral', request()->query('ref', session('referral_code'))) }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Kosongkan jika tidak ada kode" type="text">
                @error('kode_referral')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
                <input type="hidden" name="kode_referral" value="{{ $mitra->kode_referral }}">
            @endif

            <!-- Form Data Diri & Usaha -->
            <section class="space-y-4">
                <h2 class="font-label-md text-primary uppercase tracking-wider mb-2 font-semibold">Data Diri &amp; Usaha</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">Nama Usaha</label>
                        <input name="nama_usaha" value="{{ old('nama_usaha') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Contoh: Warung Sari Rasa" type="text">
                        @error('nama_usaha') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">Nama Pemilik / Penanggung Jawab (PIC)</label>
                        <input name="nama_pemilik" value="{{ old('nama_pemilik') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Contoh: Ahmad Fauzi" type="text">
                        @error('nama_pemilik') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">Nomor HP (WhatsApp)</label>
                        <input name="no_hp" value="{{ old('no_hp') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="08xx-xxxx-xxxx" type="tel" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('no_hp') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">CP Alternatif <span class="text-outline font-normal">(opsional)</span></label>
                        <input name="cp_alternatif" value="{{ old('cp_alternatif') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Nomor kontak cadangan" type="tel" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('cp_alternatif') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1 lg:col-span-2">
                        <label class="font-label-md text-on-surface-variant block">Alamat Instalasi</label>
                        <textarea name="alamat_instalasi" rows="3" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2 resize-none" placeholder="Jl. ..., Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos">{{ old('alamat_instalasi') }}</textarea>
                        @error('alamat_instalasi') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">Kota</label>
                        <input name="kota" value="{{ old('kota') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Contoh: Bontang" type="text">
                        @error('kota') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="font-label-md text-on-surface-variant block">Jenis Usaha</label>
                        <select id="jenis_usaha" name="jenis_usaha" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2 bg-surface">
                            <option value="">Pilih jenis usaha</option>
                            <option value="sekolah" {{ old('jenis_usaha') == 'sekolah' ? 'selected' : '' }}>Sekolah/Pendidikan</option>
                            <option value="ruko" {{ old('jenis_usaha') == 'ruko' ? 'selected' : '' }}>Ruko/Toko</option>
                            <option value="hotel" {{ old('jenis_usaha') == 'hotel' ? 'selected' : '' }}>Hotel/Penginapan</option>
                            <option value="kesehatan" {{ old('jenis_usaha') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="kuliner" {{ old('jenis_usaha') == 'kuliner' ? 'selected' : '' }}>Kuliner</option>
                            <option value="ekspedisi" {{ old('jenis_usaha') == 'ekspedisi' ? 'selected' : '' }}>Ekspedisi/Logistik</option>
                            <option value="pertambangan" {{ old('jenis_usaha') == 'pertambangan' ? 'selected' : '' }}>Pertambangan</option>
                            <option value="energi" {{ old('jenis_usaha') == 'energi' ? 'selected' : '' }}>Energi</option>
                            <option value="agrikultur" {{ old('jenis_usaha') == 'agrikultur' ? 'selected' : '' }}>Agrikultur/Pertanian</option>
                            <option value="media" {{ old('jenis_usaha') == 'media' ? 'selected' : '' }}>Media & Komunikasi</option>
                            <option value="lainnya" {{ old('jenis_usaha') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_usaha') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1 lg:col-span-2" id="lainnya_container" style="display: {{ old('jenis_usaha') == 'lainnya' ? 'block' : 'none' }};">
                        <label class="font-label-md text-on-surface-variant block">Jenis Usaha Lainnya</label>
                        <input name="jenis_usaha_lainnya" value="{{ old('jenis_usaha_lainnya') }}" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2" placeholder="Sebutkan jenis usaha" type="text">
                        @error('jenis_usaha_lainnya') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1 lg:col-span-2">
                        <label class="font-label-md text-on-surface-variant block">Paket yang Dipilih</label>
                        <select id="paket_id" name="paket_id" class="w-full border-outline rounded text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary px-4 py-2 bg-surface">
                            <option value="">-- Pilih Paket --</option>
                            <option value="konsultasi" {{ old('paket_id') == 'konsultasi' ? 'selected' : '' }}>-- Konsultasi Dulu (belum menentukan paket) --</option>
                            @if(isset($paketGrouped) && $paketGrouped->count())
                                @php
                                    $order = ['HSI Bisnis','HSI Basic','WMS Lite','WMS Reguler'];
                                @endphp
                                @foreach($order as $grp)
                                    @if(isset($paketGrouped[$grp]))
                                        <optgroup label="{{ $grp }}">
                                            @foreach($paketGrouped[$grp] as $p)
                                                <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                                @if(isset($paketGrouped['KDMP']))
                                    <optgroup label="KDMP (Paket Lengkap)">
                                        @foreach($paketGrouped['KDMP'] as $p)
                                            <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @foreach($paketGrouped as $grpName => $items)
                                    @if(!in_array($grpName, ['HSI Bisnis','HSI Basic','WMS Lite','WMS Reguler','KDMP']))
                                        <optgroup label="{{ $grpName }}">
                                            @foreach($items as $p)
                                                <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('paket_id') <p class="text-error text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Dokumen Persyaratan -->
            <section class="space-y-4">
                <h2 class="font-label-md text-primary uppercase tracking-wider mb-2 font-semibold">Dokumen Persyaratan</h2>
                {{-- Grid 2 kolom; setiap kolom terdiri dari 2 baris: [label] dan [kotak-upload].
                     Dengan grid-rows dan row-span, semua label sejajar dan semua kotak sejajar. --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-0" id="upload-grid">

                    {{-- Baris 1, Kolom 1: Label KTP --}}
                    <div class="pb-1 pt-0 flex items-end" style="min-height:2.75rem;">
                        <span class="font-label-md text-on-surface-variant">Foto KTP</span>
                    </div>
                    {{-- Baris 1, Kolom 2: Label NPWP --}}
                    <div class="pb-1 pt-0 flex items-end" style="min-height:2.75rem;">
                        <span class="font-label-md text-on-surface-variant">Foto NPWP / NIB / Dokumen Usaha Lainnya</span>
                    </div>

                    {{-- Baris 2, Kolom 1: Kotak Upload KTP --}}
                    <div class="flex flex-col mb-6">
                        <div class="border-2 border-dashed border-outline rounded-lg bg-surface-container-low p-6 flex flex-col items-center justify-center gap-2 transition-colors hover:border-primary cursor-pointer group" onclick="document.getElementById('foto_ktp').click()">
                            <span class="material-symbols-outlined text-outline text-[28px] group-hover:text-primary transition-colors">upload_file</span>
                            <span class="font-label-md text-primary" id="foto_ktp_text">Ketuk untuk unggah</span>
                        </div>
                        <input type="file" id="foto_ktp" name="foto_ktp" accept="image/jpeg,image/png,application/pdf" onchange="updateFileName(this, 'foto_ktp_text')">
                        <div class="flex justify-between items-center px-1 pt-1">
                            <span class="font-body-md text-[12px] text-outline">Format JPG/PNG/PDF, maks 2MB</span>
                            <button type="button" class="font-body-md text-[12px] text-primary font-semibold hover:underline" onclick="event.preventDefault(); event.stopPropagation(); openModalType('ktp')">Lihat contoh</button>
                        </div>
                        @error('foto_ktp') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Baris 2, Kolom 2: Kotak Upload NPWP --}}
                    <div class="flex flex-col mb-6">
                        <div class="border-2 border-dashed border-outline rounded-lg bg-surface-container-low p-6 flex flex-col items-center justify-center gap-2 transition-colors hover:border-primary cursor-pointer group" onclick="document.getElementById('foto_nib_npwp').click()">
                            <span class="material-symbols-outlined text-outline text-[28px] group-hover:text-primary transition-colors">upload_file</span>
                            <span class="font-label-md text-primary" id="foto_nib_npwp_text">Ketuk untuk unggah</span>
                        </div>
                        <input type="file" id="foto_nib_npwp" name="foto_nib_npwp" accept="image/jpeg,image/png,application/pdf" onchange="updateFileName(this, 'foto_nib_npwp_text')">
                        <div class="flex justify-between items-center px-1 pt-1">
                            <span class="font-body-md text-[12px] text-outline">Format JPG/PNG/PDF, maks 2MB</span>
                            <button type="button" class="font-body-md text-[12px] text-primary font-semibold hover:underline" onclick="event.preventDefault(); event.stopPropagation(); openModalType('npwp')">Lihat contoh</button>
                        </div>
                        @error('foto_nib_npwp') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Baris 3, Kolom 1: Label Tampak Depan --}}
                    <div class="pb-1 pt-0 flex items-end" style="min-height:2.75rem;">
                        <span class="font-label-md text-on-surface-variant">Foto Tampak Depan Usaha (Keseluruhan)</span>
                    </div>
                    {{-- Baris 3, Kolom 2: (kolom kosong agar label kolom 1 sejajar) --}}
                    <div class="hidden lg:block" style="min-height:2.75rem;"></div>

                    {{-- Baris 4, Kolom 1: Kotak Upload Tampak Depan --}}
                    <div class="flex flex-col mb-6">
                        <div class="border-2 border-dashed border-outline rounded-lg bg-surface-container-low p-6 flex flex-col items-center justify-center gap-2 transition-colors hover:border-primary cursor-pointer group" onclick="document.getElementById('foto_lokasi').click()">
                            <span class="material-symbols-outlined text-outline text-[28px] group-hover:text-primary transition-colors">add_a_photo</span>
                            <span class="font-label-md text-primary" id="foto_lokasi_text">Ketuk untuk unggah</span>
                        </div>
                        <input type="file" id="foto_lokasi" name="foto_lokasi" accept="image/jpeg,image/png,application/pdf" onchange="updateFileName(this, 'foto_lokasi_text')">
                        <div class="flex justify-between items-center px-1 pt-1">
                            <span class="font-body-md text-[12px] text-outline">Format JPG/PNG/PDF, maks 2MB</span>
                            <button type="button" class="font-body-md text-[12px] text-primary font-semibold hover:underline" onclick="event.preventDefault(); event.stopPropagation(); openModalType('bangunan')">Lihat contoh</button>
                        </div>
                        @error('foto_lokasi') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </section>

            <!-- Titik Lokasi -->
            <section class="space-y-4">
                <h2 class="font-label-md text-primary uppercase tracking-wider mb-2 font-semibold">Titik Lokasi Usaha</h2>
                
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                <input type="hidden" id="link_maps" name="link_maps" value="{{ old('link_maps') }}">
                
                <button type="button" id="btn-lokasi" class="w-full bg-surface border border-primary text-primary hover:bg-surface-container-low focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 py-[12px] px-4 rounded-lg flex items-center justify-center gap-2 font-label-md">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">my_location</span>
                    Ambil Lokasi Saat Ini
                </button>
                
                <div id="map" class="w-full h-[200px] rounded-lg border border-outline-variant relative overflow-hidden bg-background mt-2 z-0"></div>
                
                <div class="flex justify-center mt-1">
                    <span id="coords-display" class="font-body-md text-[12px] text-on-surface-variant bg-surface/90 px-2 py-1 rounded shadow-sm backdrop-blur-sm">
                        {{ old('latitude') ? 'Lat: '.old('latitude').', Long: '.old('longitude') : 'Titik belum ditentukan' }}
                    </span>
                </div>

                <div id="maps-link-container" class="text-center" style="display: {{ old('latitude') ? 'block' : 'none' }};">
                    <a id="maps-link-display" href="{{ old('latitude') ? 'https://www.google.com/maps?q='.old('latitude').','.old('longitude') : '#' }}" target="_blank" class="font-body-md text-[12px] text-primary hover:underline flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:14px;">open_in_new</span>
                        Lihat di Google Maps
                    </a>
                </div>
                
                @error('latitude') <p class="text-error text-xs text-center">{{ $message }}</p> @enderror
            </section>

            <!-- Submit Action -->
            <div class="pt-6">
                <button type="submit" id="btn-submit" class="w-full bg-primary text-on-primary hover:bg-primary-container focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 py-[12px] px-4 rounded-lg font-label-md shadow-sm active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                    <span id="btn-submit-text">Kirim Pendaftaran</span>
                    <span id="btn-submit-spinner" class="hidden material-symbols-outlined animate-spin" style="font-size: 18px;">progress_activity</span>
                </button>
            </div>
        </form>
    </main>
    
    <!-- Modal Panduan Foto -->
    <div id="modal-panduan" class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center p-4" onclick="handleModalBackdropClick(event)">
        <div class="bg-surface rounded-xl shadow-level-1 w-full max-w-xl overflow-hidden flex flex-col max-h-[92vh]" onclick="event.stopPropagation()">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface sticky top-0">
                <h3 class="font-headline-md text-lg font-bold text-on-surface" id="modal-panduan-title">Panduan Foto</h3>
                <button type="button" onclick="closeModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-5 overflow-y-auto" id="modal-panduan-body">
                <!-- Diisi dinamis via JS -->
            </div>
            <div class="px-6 py-4 border-t border-outline-variant bg-surface mt-auto">
                <button type="button" onclick="closeModal()" class="w-full bg-surface border border-outline text-on-surface hover:bg-surface-container-low focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 py-[10px] px-4 rounded-lg font-label-md">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="modal-konfirmasi" class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center p-4 transition-opacity">
        <div class="bg-surface rounded-xl shadow-level-1 w-full max-w-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-outline-variant bg-surface">
                <h3 class="font-headline-md text-xl font-bold text-on-surface">Konfirmasi Pengiriman</h3>
            </div>
            <div class="p-6">
                <p class="font-body-md text-on-surface-variant">Pastikan semua data sudah benar. Kirim pendaftaran sekarang?</p>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant bg-surface flex gap-3 justify-end">
                <button type="button" onclick="closeConfirmModal()" class="bg-surface border border-outline text-on-surface hover:bg-surface-container-low focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all py-2 px-4 rounded-lg font-label-md">Batal</button>
                <button type="button" onclick="submitFormConfirmed()" class="bg-primary text-on-primary hover:bg-primary-container focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all py-2 px-4 rounded-lg font-label-md">Ya, Kirim</button>
            </div>
        </div>
    </div>

    <!-- Overlay Loading -->
    <div id="overlay-loading" class="fixed inset-0 z-[100] hidden bg-black/70 backdrop-blur-sm flex flex-col items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-4 border-surface-container-low border-t-primary mb-4"></div>
        <p class="text-surface-container-lowest font-headline-md text-lg font-bold animate-pulse">Mengirim data Anda...</p>
    </div>
    
    <!-- Footer Minimalis -->
    <footer class="bg-surface-container-low border-t border-outline-variant py-footer-padding-y text-center px-6 mt-auto">
        <p class="font-body-md text-[12px] text-outline">© 2026 SIMITRA. All rights reserved.</p>
    </footer>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
 integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
 crossorigin=""></script>
 
<script>
    // JS for dropdown
    document.getElementById('jenis_usaha').addEventListener('change', function() {
        if (this.value === 'lainnya') {
            document.getElementById('lainnya_container').style.display = 'block';
        } else {
            document.getElementById('lainnya_container').style.display = 'none';
        }
    });

    // JS for file name & validation
    function updateFileName(input, textId) {
        var errorDisplayId = textId + '_error';
        var errorDisplay = document.getElementById(errorDisplayId);
        
        // Remove existing custom error if any
        if (errorDisplay) {
            errorDisplay.remove();
        }

        if (input.files && input.files[0]) {
            var file = input.files[0];
            var maxSize = 2 * 1024 * 1024; // 2MB
            var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            
            var errorMessage = "";
            if (file.size > maxSize) {
                errorMessage = "File terlalu besar, maksimal 2MB";
            } else if (!validTypes.includes(file.type)) {
                errorMessage = "Format harus JPG, PNG, atau PDF";
            }
            
            if (errorMessage !== "") {
                input.value = ""; // Clear the input
                document.getElementById(textId).textContent = 'Ketuk untuk unggah';
                document.getElementById(textId).classList.add('text-primary');
                document.getElementById(textId).classList.remove('text-on-surface');
                
                // Show error under the field
                var errorP = document.createElement('p');
                errorP.id = errorDisplayId;
                errorP.className = 'text-error text-xs mt-1 client-error';
                errorP.innerText = errorMessage;
                input.parentElement.appendChild(errorP);
                return;
            }

            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(textId).innerHTML = '<img src="' + e.target.result + '" class="h-[100px] w-auto object-contain mx-auto mb-2 rounded border border-outline-variant shadow-sm"><span class="block text-on-surface font-semibold text-sm break-all text-center">' + file.name + '</span>';
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById(textId).innerHTML = '<span class="material-symbols-outlined block mx-auto text-primary mb-1 text-center" style="font-size: 32px;">picture_as_pdf</span><span class="block text-on-surface font-semibold text-sm break-all text-center">' + file.name + '</span>';
            }
            
            document.getElementById(textId).classList.remove('text-primary');
            document.getElementById(textId).classList.add('text-on-surface');
        } else {
            document.getElementById(textId).textContent = 'Ketuk untuk unggah';
            document.getElementById(textId).classList.add('text-primary');
            document.getElementById(textId).classList.remove('text-on-surface');
        }
    }

    // Leaflet map initialization
    var map = L.map('map').setView([-1.24, 116.85], 12); // Kaltim default
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([-1.24, 116.85], {
        draggable: true
    }).addTo(map);

    // Initial check for old inputs
    var oldLat = document.getElementById('latitude').value;
    var oldLng = document.getElementById('longitude').value;
    if (oldLat && oldLng) {
        var latlng = new L.LatLng(oldLat, oldLng);
        marker.setLatLng(latlng);
        map.setView(latlng, 15);
    }

    // Update input fields and display text when marker moves
    function updateLocation(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('coords-display').innerText = 'Lat: ' + lat.toFixed(5) + ', Long: ' + lng.toFixed(5);
        var mapsUrl = 'https://www.google.com/maps?q=' + lat + ',' + lng;
        document.getElementById('link_maps').value = mapsUrl;
        var mapsLink = document.getElementById('maps-link-display');
        mapsLink.href = mapsUrl;
        document.getElementById('maps-link-container').style.display = 'block';
    }

    marker.on('dragend', function (e) {
        var latlng = marker.getLatLng();
        updateLocation(latlng.lat, latlng.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLocation(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('btn-lokasi').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var latlng = new L.LatLng(lat, lng);
                marker.setLatLng(latlng);
                map.setView(latlng, 15);
                updateLocation(lat, lng);
            }, function(error) {
                alert("Gagal mengambil lokasi: " + error.message);
            });
        } else {
            alert("Browser tidak mendukung geolokasi.");
        }
    });

    // Client-side validation before submit
    document.getElementById('pendaftaranForm').addEventListener('submit', function(event) {
        // Clear previous client errors
        document.querySelectorAll('.client-error').forEach(function(el) {
            el.remove();
        });

        var isValid = true;
        var firstErrorField = null;

        function showError(elementId, message) {
            var el = document.getElementById(elementId);
            if (!el) {
                el = document.getElementsByName(elementId)[0];
            }
            if (el) {
                var errorP = document.createElement('p');
                errorP.className = 'text-error text-xs mt-1 client-error';
                errorP.innerText = message;
                el.parentElement.appendChild(errorP);
                if (!firstErrorField) firstErrorField = el;
                isValid = false;
            }
        }

        // Validate text fields
        var requiredFields = ['kode_referral', 'nama_pemilik', 'nama_usaha', 'jenis_usaha', 'paket_id'];
        requiredFields.forEach(function(field) {
            var el = document.getElementsByName(field)[0];
            if (el && !el.value.trim()) {
                showError(field, 'Field ini wajib diisi sebelum submit.');
            }
        });

        // Kota validation
        var kotaEl = document.getElementsByName('kota')[0];
        if (kotaEl && !kotaEl.value.trim()) {
            showError('kota', 'Kota wajib diisi sebelum submit.');
        }

        // Alamat instalasi validation
        var alamatEl = document.getElementsByName('alamat_instalasi')[0];
        if (alamatEl && !alamatEl.value.trim()) {
            showError('alamat_instalasi', 'Alamat instalasi wajib diisi sebelum submit.');
        }

        // Phone validation
        var noHpEl = document.getElementsByName('no_hp')[0];
        if (noHpEl) {
            var noHpVal = noHpEl.value.trim();
            if (!noHpVal) {
                showError('no_hp', 'Field ini wajib diisi sebelum submit.');
            } else if (!/^0[0-9]{9,14}$/.test(noHpVal)) {
                showError('no_hp', 'Nomor HP tidak valid (contoh: 081234567890).');
                // Also trigger validation message on blur if needed
            }
        }

        if (document.getElementById('jenis_usaha').value === 'lainnya') {
            var lainnyaEl = document.getElementsByName('jenis_usaha_lainnya')[0];
            if (lainnyaEl && !lainnyaEl.value.trim()) {
                showError('jenis_usaha_lainnya', 'Jenis usaha lainnya wajib diisi.');
            }
        }

        // Validate file fields
        var fileFields = ['foto_ktp', 'foto_nib_npwp', 'foto_lokasi'];
        fileFields.forEach(function(field) {
            var el = document.getElementById(field);
            // Ignore if it has a value (valid)
            if (el && (!el.files || el.files.length === 0)) {
                var container = el.parentElement;
                var errorP = document.createElement('p');
                errorP.className = 'text-error text-xs mt-1 client-error';
                errorP.innerText = 'File ini wajib diunggah sebelum submit.';
                container.appendChild(errorP);
                if (!firstErrorField) firstErrorField = container;
                isValid = false;
            }
        });

        // Validate latitude
        if (!document.getElementById('latitude').value) {
            showError('btn-lokasi', 'Titik lokasi belum ditentukan. Harap ketuk tombol di atas.');
        }

        if (!isValid) {
            event.preventDefault(); // Stop form submission
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            event.preventDefault(); // Prevent direct submission to show modal
            var modal = document.getElementById('modal-konfirmasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });

    function closeConfirmModal() {
        var modal = document.getElementById('modal-konfirmasi');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function submitFormConfirmed() {
        closeConfirmModal();
        
        // Show loading overlay
        var overlay = document.getElementById('overlay-loading');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
        // Disable main submit button
        var btnSubmit = document.getElementById('btn-submit');
        if (btnSubmit) btnSubmit.disabled = true;
        
        // Actually submit the form
        document.getElementById('pendaftaranForm').submit();
    }

    // ============================================================
    // Modal Panduan Foto — data per jenis dokumen
    // ============================================================
    var modalData = {
        ktp: {
            title: 'Panduan Foto KTP',
            validImg: '{{ asset("images/panduan/ktp-valid.png") }}',
            invalidImg: '{{ asset("images/panduan/ktp-invalid.png") }}',
            validCriteria: [
                'Data terbaca jelas, tidak blur',
                'Seluruh bagian KTP terlihat (tidak terpotong)',
                'Masa berlaku aktif (kecuali E-KTP seumur hidup)',
                'Pencahayaan cukup, tidak silau'
            ],
            invalidCriteria: [
                'Identitas rusak / buram',
                'Masa berlaku sudah habis',
                'Sebagian data atau foto tertutup',
                'Blur, gelap, atau terpotong'
            ]
        },
        npwp: {
            title: 'Panduan Foto NPWP / NIB / Dokumen Usaha',
            validImg: '{{ asset("images/panduan/npwp-valid.png") }}',
            invalidImg: '{{ asset("images/panduan/npwp-invalid.png") }}',
            validCriteria: [
                'Data tampak jelas dan terbaca',
                'Alamat pada dokumen sesuai dengan alamat instalasi',
                'Data sesuai saat dicek di OSS'
            ],
            invalidCriteria: [
                'Dokumen salah (bukan NPWP/NIB/izin usaha, misal malah PBB)',
                'Buram / blur / tidak terbaca',
                'Masa berlaku habis (expired)'
            ]
        },
        bangunan: {
            title: 'Panduan Foto Tampak Depan Usaha',
            validImg: '{{ asset("images/panduan/bangunan-valid.png") }}',
            invalidImg: '{{ asset("images/panduan/bangunan-invalid.png") }}',
            validCriteria: [
                'Bangunan tampak menyeluruh dari depan',
                'Ada papan nama / logo usaha yang terlihat jelas',
                'Foto asli hasil ambil langsung (bukan editing/Google Street View)',
                'Gambar jelas dan terang'
            ],
            invalidCriteria: [
                'Diambil dari Google Street View / hasil editing',
                'Bangunan tidak terlihat jelas / dari terlalu jauh',
                'Gelap / backlight / hanya sebagian bangunan',
                'Tidak menampilkan bangunan yang akan dipasang internet'
            ]
        }
    };

    function openModalType(type) {
        var data = modalData[type];
        if (!data) return;

        document.getElementById('modal-panduan-title').innerText = data.title;

        var validList = data.validCriteria.map(function(c) {
            return '<li class="flex items-start gap-1.5"><span class="text-[#16a34a] font-bold mt-0.5">&#10003;</span><span>' + c + '</span></li>';
        }).join('');
        var invalidList = data.invalidCriteria.map(function(c) {
            return '<li class="flex items-start gap-1.5"><span class="text-[#ba1a1a] font-bold mt-0.5">&#10007;</span><span>' + c + '</span></li>';
        }).join('');

        document.getElementById('modal-panduan-body').innerHTML =
            '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">'
            + '<div>'
            + '<div class="inline-flex items-center gap-1.5 bg-[#dcfce7] text-[#15803d] text-xs font-bold px-2.5 py-1 rounded-full mb-2">'
            + '<span class="material-symbols-outlined" style="font-size:14px;">check_circle</span> VALID'
            + '</div>'
            + '<div class="rounded-lg overflow-hidden border border-[#bbf7d0] mb-3" style="background:#f0fdf4;">'
            + '<img src="' + data.validImg + '" alt="Contoh valid" class="w-full object-cover" style="max-height:180px;object-fit:cover;" onerror="this.style.display=\'none\'">'
            + '</div>'
            + '<ul class="space-y-1 text-sm text-on-surface font-body-md">' + validList + '</ul>'
            + '</div>'
            + '<div>'
            + '<div class="inline-flex items-center gap-1.5 bg-[#fee2e2] text-[#ba1a1a] text-xs font-bold px-2.5 py-1 rounded-full mb-2">'
            + '<span class="material-symbols-outlined" style="font-size:14px;">cancel</span> TIDAK VALID'
            + '</div>'
            + '<div class="rounded-lg overflow-hidden border border-[#fecaca] mb-3" style="background:#fef2f2;">'
            + '<img src="' + data.invalidImg + '" alt="Contoh tidak valid" class="w-full object-cover" style="max-height:180px;object-fit:cover;" onerror="this.style.display=\'none\'">'
            + '</div>'
            + '<ul class="space-y-1 text-sm text-on-surface font-body-md">' + invalidList + '</ul>'
            + '</div>'
            + '</div>';

        var modal = document.getElementById('modal-panduan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function handleModalBackdropClick(event) {
        // Menutup modal jika klik di luar area modal (pada backdrop)
        if (event.target === document.getElementById('modal-panduan')) {
            closeModal();
        }
    }

    function closeModal() {
        var modal = document.getElementById('modal-panduan');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>

</body>
</html>
