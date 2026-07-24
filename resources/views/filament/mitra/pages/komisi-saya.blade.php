<x-filament::page>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Komisi Saya</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Ringkasan komisi dan riwayat pembayaran Anda</p>
    </div>

    <!-- Ringkasan (Summary) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Komisi - Highlighted -->
        <div class="lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100 mb-1">Total Komisi</p>
                    <p class="text-3xl font-bold">Rp{{ number_format($totalKomisi, 0, ',', '.') }}</p>
                </div>
                <div class="text-5xl opacity-20">💰</div>
            </div>
        </div>

        <!-- Komisi Dasar -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-6 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Komisi Dasar</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($komisiDasar, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $komisi->jumlahTerpasang() }} pelanggan × Rp200.000</p>
        </div>

        <!-- Total Bonus -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-6 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Total Bonus</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">Rp{{ number_format($totalBonus, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Dari {{ count($rincianBonus) }} bulan</p>
        </div>

        <!-- Sudah Dibayar -->
        <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-700 p-6 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-green-700 dark:text-green-300">Rp{{ number_format($sudahDibayar, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ count($riwayatPembayaran) }} kali pembayaran</p>
        </div>

        <!-- Sisa Belum Dibayar -->
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-700 p-6 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Sisa Belum Dibayar</p>
            <p class="text-2xl font-bold {{ $sisaBelumDibayar > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-300' }}">
                Rp{{ number_format($sisaBelumDibayar, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ $sisaBelumDibayar > 0 ? 'Menunggu pembayaran' : 'Lunas' }}
            </p>
        </div>

        <!-- Persentase Pembayaran -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-6 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Progres Pembayaran</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $totalKomisi > 0 ? round(($sudahDibayar / $totalKomisi) * 100, 1) : 0 }}%
            </p>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-3">
                <div
                    class="bg-green-500 dark:bg-green-400 h-2 rounded-full transition-all"
                    style="width: {{ $totalKomisi > 0 ? ($sudahDibayar / $totalKomisi) * 100 : 0 }}%"
                ></div>
            </div>
        </div>
    </div>

    <!-- Progress Bonus Bulan Ini -->
    <div class="bg-yellow-50 dark:bg-yellow-900/10 border-2 border-yellow-300 dark:border-yellow-600 p-6 rounded-lg mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">📊 Progress Bonus Bulan Ini</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $progressBulanIni['deskripsi'] }}</p>

        <!-- Progress Bar -->
        <div class="space-y-2">
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2">
                <span class="font-medium">{{ $progressBulanIni['jumlah_bulan_ini'] }}/5 Pelanggan</span>
                <span>{{ $progressBulanIni['sisa_menuju_kelipatan'] }} lagi</span>
            </div>
            <div class="w-full bg-yellow-200 dark:bg-yellow-800 rounded-full h-4 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-yellow-400 to-yellow-600 dark:from-yellow-400 dark:to-yellow-500 h-4 rounded-full transition-all duration-500"
                    style="width: {{ min(100, ($progressBulanIni['jumlah_bulan_ini'] / 5) * 100) }}%"
                ></div>
            </div>
        </div>
    </div>

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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatPembayaran as $pembayaran)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $pembayaran->tanggal_bayar->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-semibold text-green-700 dark:text-green-300">
                                        Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $pembayaran->catatan ?? '-' }}
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
