<div class="fi-wi-papan-informasi">
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-header flex items-center gap-x-3 px-6 py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    📢 Papan Informasi
                </h3>
            </div>
            <a href="{{ route('filament.mitra.pages.papan-informasi') }}"
               class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">
                Lihat semua →
            </a>
        </div>

        <div class="px-6 pb-6">
            @php $items = $this->getPengumuman(); @endphp

            @if ($items->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">
                    Belum ada pengumuman aktif.
                </p>
            @else
                <div class="space-y-3">
                    @foreach ($items as $item)
                        <div class="flex gap-4 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            @if ($item->gambar)
                                <img src="{{ Storage::url($item->gambar) }}"
                                     alt="{{ $item->judul }}"
                                     class="h-16 w-16 flex-shrink-0 rounded-lg object-contain object-center bg-gray-50 dark:bg-gray-800">
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' => $item->tipe === 'info',
                                        'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' => $item->tipe === 'promo',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' => $item->tipe === 'pengumuman',
                                    ])>
                                        {{ ucfirst($item->tipe) }}
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $item->created_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <p class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                    {{ $item->judul }}
                                </p>
                                @if ($item->isi)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ $item->isi }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
