@if($pembayaran->isEmpty())
    <div class="text-center py-6">
        <p class="text-gray-500">Belum ada riwayat pembayaran</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-semibold">Tanggal Bayar</th>
                    <th class="px-4 py-2 text-right font-semibold">Jumlah</th>
                    <th class="px-4 py-2 text-left font-semibold">Catatan</th>
                    <th class="px-4 py-2 text-center font-semibold">Bukti Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 align-middle">{{ $p->tanggal_bayar->locale('id')->translatedFormat('d F Y') }}</td>
                        <td class="px-4 py-3 text-right font-medium align-middle">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-600 align-middle">{{ $p->catatan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center align-middle">
                            @if($p->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $p->bukti_pembayaran) }}" target="_blank" class="inline-block group" title="Klik untuk memperbesar">
                                    <img src="{{ asset('storage/' . $p->bukti_pembayaran) }}" 
                                         alt="Bukti Transfer" 
                                         class="h-12 w-16 object-cover rounded border border-gray-200 shadow-sm group-hover:scale-105 group-hover:shadow transition duration-150 mx-auto">
                                </a>
                            @else
                                <span class="text-xs text-gray-400 font-normal">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
