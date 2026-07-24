<x-filament-panels::page>
    @php $items = $this->getPengumuman(); @endphp

    @if ($items->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <x-heroicon-o-megaphone class="h-12 w-12 text-gray-400 mb-4" />
            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada pengumuman aktif.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Pantau terus halaman ini untuk informasi terbaru.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($items as $item)
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">

                    {{-- Gambar penuh --}}
                        @if ($item->gambar)
                        <div class="relative">
                            <img src="{{ Storage::url($item->gambar) }}"
                                 alt="{{ $item->judul }}"
                                 class="block w-full h-auto max-h-[600px] object-contain object-center bg-gray-50 dark:bg-gray-800">
                            {{-- Tombol unduh gambar --}}
                            <a href="{{ Storage::url($item->gambar) }}"
                               download
                               target="_blank"
                               class="absolute top-3 right-3 inline-flex items-center gap-1.5 rounded-lg bg-white/90 dark:bg-gray-900/90 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 shadow hover:bg-white dark:hover:bg-gray-800 transition-colors ring-1 ring-gray-950/10 dark:ring-white/10">
                                <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                Unduh Gambar
                            </a>
                        </div>
                    @endif

                    <div class="p-6">
                        {{-- Badge tipe & tanggal --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' => $item->tipe === 'info',
                                'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' => $item->tipe === 'promo',
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' => $item->tipe === 'pengumuman',
                            ])>
                                {{ ucfirst($item->tipe) }}
                            </span>
                            <span class="text-sm text-gray-400 dark:text-gray-500">
                                {{ $item->created_at->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        {{-- Judul --}}
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                            {{ $item->judul }}
                        </h2>

                        {{-- Isi teks --}}
                        @if ($item->isi)
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                {{ $item->isi }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
