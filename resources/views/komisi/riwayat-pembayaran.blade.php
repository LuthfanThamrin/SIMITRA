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
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $p->tanggal_bayar->locale('id')->translatedFormat('d F Y') }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->catatan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
