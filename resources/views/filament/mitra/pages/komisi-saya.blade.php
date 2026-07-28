<x-filament::page>
    <!-- Header -->
    <div class="mb-6 pt-2">
        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">Komisi Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Ringkasan komisi dan riwayat pembayaran Anda</p>
    </div>

    <!-- Ringkasan (Summary) -->
    <x-filament::section>
        <!-- Baris Atas: Total Komisi -->
        <div class="mb-6 text-center">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Komisi</h3>
            <p class="mt-2 text-4xl font-bold text-primary-600 dark:text-primary-400">
                Rp{{ number_format($totalKomisi, 0, ',', '.') }}
            </p>
        </div>

        <!-- Baris Kedua: Komisi Dasar & Total Bonus -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="text-gray-400 dark:text-gray-500">
                        <x-heroicon-o-currency-dollar class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Komisi Dasar</p>
                        <p class="text-xl font-bold text-gray-950 dark:text-white">Rp{{ number_format($komisiDasar, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $komisi->jumlahTerpasang() }} pelanggan × Rp200.000</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="text-yellow-500 dark:text-yellow-400">
                        <x-heroicon-o-sparkles class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bonus</p>
                        <p class="text-xl font-bold text-gray-950 dark:text-white">Rp{{ number_format($totalBonus, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dari {{ count($rincianBonus) }} bulan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Ketiga: Sudah Dibayar & Belum Dibayar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="text-success-500 dark:text-success-400">
                        <x-heroicon-o-check-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Dibayar</p>
                        <p class="text-xl font-bold text-gray-950 dark:text-white">Rp{{ number_format($sudahDibayar, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ count($riwayatPembayaran) }} kali pembayaran</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="text-danger-500 dark:text-danger-400">
                        <x-heroicon-o-clock class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Belum Dibayar</p>
                        <p class="text-xl font-bold {{ $sisaBelumDibayar > 0 ? 'text-danger-600 dark:text-danger-400' : 'text-success-600 dark:text-success-400' }}">Rp{{ number_format($sisaBelumDibayar, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $sisaBelumDibayar > 0 ? 'Menunggu pembayaran' : 'Lunas' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progres Pembayaran Kecil -->
        <div class="flex flex-col gap-2 pt-4 border-t border-gray-200 dark:border-white/10">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Progres Pembayaran</span>
                <span class="font-medium text-gray-950 dark:text-white">
                    {{ $totalKomisi > 0 ? round(($sudahDibayar / $totalKomisi) * 100, 1) : 0 }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-success-500 dark:bg-success-400 h-1.5 rounded-full"
                     style="width: {{ $totalKomisi > 0 ? ($sudahDibayar / $totalKomisi) * 100 : 0 }}%"></div>
            </div>
        </div>
    </x-filament::section>

    <!-- Progress Bonus Bulan Ini -->
    <x-filament::section class="mt-6" heading="Progress Bonus Bulan Ini" icon="heroicon-o-chart-bar">
        @php
            $jumlahBulanIni = $progressBulanIni['jumlah_bulan_ini'] ?? 0;
            $scaleBulanIni = min($jumlahBulanIni, 5);
        @endphp
        <style>
        @keyframes fire-outer-anim {
            0% { transform: rotate(-45deg) scale(1) skewX(0deg); opacity: 0.9; }
            50% { transform: rotate(-43deg) scale(1.05) skewX(3deg); opacity: 1; }
            100% { transform: rotate(-47deg) scale(0.95) skewX(-3deg); opacity: 0.9; }
        }
        @keyframes fire-middle-anim {
            0% { transform: rotate(-45deg) scale(1) skewY(0deg); opacity: 0.8; }
            50% { transform: rotate(-48deg) scale(0.95) skewY(-2deg); opacity: 1; }
            100% { transform: rotate(-42deg) scale(1.1) skewY(2deg); opacity: 0.8; }
        }
        @keyframes fire-inner-anim {
            0% { transform: rotate(-45deg) scale(1); opacity: 0.9; }
            50% { transform: rotate(-44deg) scale(1.1); opacity: 1; }
            100% { transform: rotate(-46deg) scale(0.9); opacity: 0.9; }
        }
        .flame-base {
            border-radius: 50% 0 50% 50%;
            transform: rotate(-45deg);
            transform-origin: center center;
            position: absolute;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        }
        .flame-outer-lg { 
            width: 32px; height: 32px; bottom: 4px;
            background: linear-gradient(135deg, #dc2626, #f97316);
            animation: fire-outer-anim 1.2s infinite alternate ease-in-out; 
        }
        .flame-middle-lg { 
            width: 20px; height: 20px; bottom: 6px;
            background: linear-gradient(135deg, #f97316, #facc15);
            animation: fire-middle-anim 0.9s infinite alternate ease-in-out; 
        }
        .flame-inner-lg { 
            width: 10px; height: 10px; bottom: 10px;
            background: linear-gradient(135deg, #fde047, #ffffff);
            animation: fire-inner-anim 0.7s infinite alternate ease-in-out; 
        }
        
        .fire-scale-0 { transform: scale(0.4); filter: grayscale(100%) opacity(0.3); }
        .fire-scale-1 { transform: scale(0.6); }
        .fire-scale-2 { transform: scale(0.75); }
        .fire-scale-3 { transform: scale(0.9); }
        .fire-scale-4 { transform: scale(1.05); }
        .fire-scale-5 { transform: scale(1.2); filter: drop-shadow(0 0 8px rgba(249, 115, 22, 0.6)); }
        .fire-scale-0 .flame-base { animation: none !important; }
        </style>

        <div class="flex items-center gap-4 mb-5">
            <div class="relative w-12 h-12 flex items-end justify-center fire-scale-{{ $scaleBulanIni }} transition-transform duration-500 shrink-0">
                <div class="flame-base flame-outer-lg"></div>
                <div class="flame-base flame-middle-lg"></div>
                <div class="flame-base flame-inner-lg"></div>
                <span class="absolute z-10 text-xs font-bold text-white" style="bottom: 8px; text-shadow: 0 1px 2px rgba(0,0,0,0.9);">{{ $jumlahBulanIni }}</span>
            </div>
            
            <div class="flex-1">
                @if($jumlahBulanIni >= 5)
                    <div class="mb-2">
                        <x-filament::badge color="success" class="animate-pulse">
                            Bonus Tercapai!
                        </x-filament::badge>
                    </div>
                @endif
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    @if($jumlahBulanIni >= 5)
                        Selamat! Anda telah mencapai {{ $jumlahBulanIni }} pelanggan bulan ini dan berhak mendapatkan bonus.
                    @else
                        {{ $progressBulanIni['deskripsi'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                <span class="font-medium text-gray-950 dark:text-white">{{ $progressBulanIni['jumlah_bulan_ini'] }}/5 Pelanggan</span>
                <span>{{ $progressBulanIni['sisa_menuju_kelipatan'] }} lagi</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                <div class="bg-primary-500 dark:bg-primary-400 h-2 rounded-full transition-all duration-500"
                     style="width: {{ min(100, ($progressBulanIni['jumlah_bulan_ini'] / 5) * 100) }}%"></div>
            </div>
        </div>
    </x-filament::section>

    <!-- Rincian Bonus per Bulan -->
    @if(count($rincianBonus) > 0)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Rincian Bonus per Bulan</h2>
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow ring-1 ring-gray-200 dark:ring-white/10">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-200">Bulan</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-900 dark:text-gray-200">Jumlah Terpasang</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-gray-200">Bonus Didapat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rincianBonus as $bonus)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $bonus['bulan'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-100 dark:bg-blue-900/70 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $bonus['jumlah'] }} pelanggan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-700 dark:text-green-300">
                                    Rp{{ number_format($bonus['bonus'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-700 p-6 rounded-lg mb-6 text-center">
            <p class="text-gray-600 dark:text-gray-300">Belum ada data bonus. Dapatkan 5 pelanggan terpasang dalam sebulan untuk mendapatkan bonus pertama Anda.</p>
        </div>
    @endif

    <!-- Riwayat Pembayaran -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Riwayat Pembayaran</h2>

        @if(count($riwayatPembayaran) > 0)
            <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow ring-1 ring-gray-200 dark:ring-white/10">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700">
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-200">Tanggal Bayar</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-gray-200">Jumlah</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-200">Catatan</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-900 dark:text-gray-200">Bukti Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatPembayaran as $pembayaran)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4 align-middle">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $pembayaran->tanggal_bayar->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right align-middle">
                                    <span class="font-semibold text-green-700 dark:text-green-300">
                                        Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 align-middle">
                                    {{ $pembayaran->catatan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center align-middle">
                                    @if($pembayaran->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="inline-block group" title="Klik untuk memperbesar">
                                            <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                                 alt="Bukti Transfer" 
                                                 class="h-12 w-16 object-cover rounded border border-gray-200 dark:border-gray-700 shadow-sm group-hover:scale-105 group-hover:shadow transition duration-150 mx-auto">
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-700 p-6 rounded-lg text-center">
                <p class="text-gray-600 dark:text-gray-300">Belum ada riwayat pembayaran. Admin akan mencatat pembayaran komisi Anda di sini.</p>
            </div>
        @endif
    </div>
</x-filament::page>
