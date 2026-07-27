<x-filament-widgets::widget>
    <x-filament::section class="p-2 sm:p-4">
        <div class="flex flex-col md:flex-row gap-8 md:gap-12 items-center justify-between">
            <!-- Kiri: Info Komisi Utama -->
            <div class="flex-1 w-full space-y-6 md:space-y-8">
                <div class="space-y-2">
                    <h3 class="text-sm md:text-base font-medium text-gray-500 dark:text-gray-400">Total Komisi</h3>
                    <p class="text-4xl md:text-5xl font-extrabold text-primary-600 dark:text-primary-400 tracking-tight">Rp{{ number_format($totalKomisi, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Dasar: Rp{{ number_format($komisiDasar, 0, ',', '.') }} &bull; Bonus: Rp{{ number_format($totalBonus, 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800/50 p-3 md:p-4 rounded-xl border border-gray-100 dark:border-white/5 inline-flex">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Dibayar:</span>
                    <span class="text-lg font-bold {{ $sisaBelumDibayar > 0 ? 'text-danger-600 dark:text-danger-400' : 'text-success-600 dark:text-success-400' }}">
                        Rp{{ number_format($sisaBelumDibayar, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Kanan: Progres Bonus -->
            <div class="flex-1 w-full md:max-w-md border-t md:border-t-0 md:border-l border-gray-200 dark:border-white/10 pt-6 md:pt-0 md:pl-10 relative">
                @php
                    $jumlah = $progressData['jumlah_bulan_ini'] ?? 0;
                    $scale = min($jumlah, 5);
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
                .flame-outer { 
                    width: 32px; height: 32px; bottom: 4px;
                    background: linear-gradient(135deg, #dc2626, #f97316);
                    animation: fire-outer-anim 1.2s infinite alternate ease-in-out; 
                }
                .flame-middle { 
                    width: 24px; height: 24px; bottom: 6px;
                    background: linear-gradient(135deg, #f97316, #facc15);
                    animation: fire-middle-anim 0.9s infinite alternate ease-in-out; 
                }
                .flame-inner { 
                    width: 12px; height: 12px; bottom: 12px;
                    background: linear-gradient(135deg, #fde047, #ffffff);
                    animation: fire-inner-anim 0.7s infinite alternate ease-in-out; 
                }
                
                .fire-scale-0 { transform: scale(0.4); filter: grayscale(100%) opacity(0.3); }
                .fire-scale-1 { transform: scale(0.6); }
                .fire-scale-2 { transform: scale(0.75); }
                .fire-scale-3 { transform: scale(0.9); }
                .fire-scale-4 { transform: scale(1.05); }
                .fire-scale-5 { transform: scale(1.25); filter: drop-shadow(0 0 10px rgba(249, 115, 22, 0.6)); }
                .fire-scale-0 .flame-base { animation: none !important; }
                </style>

                <p class="text-sm md:text-base font-medium text-gray-950 dark:text-white mb-4">Progres Bonus Bulan Ini</p>
                
                <div class="flex items-center gap-4 mb-5">
                    <div class="relative w-12 h-12 flex items-end justify-center fire-scale-{{ $scale }} transition-transform duration-500 shrink-0">
                        <div class="flame-base flame-outer"></div>
                        <div class="flame-base flame-middle"></div>
                        <div class="flame-base flame-inner"></div>
                        <span class="absolute z-10 text-xs font-extrabold text-white" style="bottom: 10px; text-shadow: 0 1px 3px rgba(0,0,0,0.9);">{{ $jumlah }}</span>
                    </div>
                    
                    <div class="flex-1 space-y-1">
                        @if($jumlah >= 5)
                            <div>
                                <x-filament::badge color="success" class="animate-pulse px-3 py-1 text-xs">
                                    Bonus Tercapai!
                                </x-filament::badge>
                            </div>
                        @endif
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            @if($jumlah >= 5)
                                Selamat! 5 pelanggan terpasang.
                            @else
                                {{ $progressData['deskripsi'] }}
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium text-gray-950 dark:text-white">{{ $progressData['jumlah_bulan_ini'] }}/5 Pelanggan</span>
                        <span>{{ $progressData['sisa_menuju_kelipatan'] }} lagi</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden shadow-inner">
                        <div class="bg-primary-500 dark:bg-primary-400 h-2.5 rounded-full transition-all duration-500 ease-out"
                             style="width: {{ min(100, ($progressData['jumlah_bulan_ini'] / 5) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
