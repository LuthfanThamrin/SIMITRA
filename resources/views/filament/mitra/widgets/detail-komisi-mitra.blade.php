<div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow ring-1 ring-gray-200 dark:ring-white/10">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detail Komisi</h3>

        <!-- Total Komisi - Large Display -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg mb-6">
            <p class="text-sm font-medium text-blue-100 mb-1">Total Komisi</p>
            <p class="text-3xl font-bold">Rp{{ number_format($totalKomisi, 0, ',', '.') }}</p>
        </div>

        <!-- Breakdown -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Komisi Dasar</p>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">Rp{{ number_format($komisiDasar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Total Bonus</p>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">Rp{{ number_format($totalBonus, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-green-50 dark:bg-green-900/10 p-4 rounded">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Sudah Dibayar</p>
                <p class="text-xl font-semibold text-green-700 dark:text-green-300">Rp{{ number_format($sudahDibayar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Sisa Belum Dibayar</p>
                <p class="text-xl font-semibold {{ $sisaBelumDibayar > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-300' }}">
                    Rp{{ number_format($sisaBelumDibayar, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Progress Bonus Bulan Ini -->
        <div class="bg-yellow-50 dark:bg-yellow-900/10 p-4 rounded border-l-4 border-yellow-400 dark:border-yellow-600">
            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Progress Bonus Bulan Ini</p>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ $progressData['deskripsi'] }}</p>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                <div
                    class="bg-yellow-400 dark:bg-yellow-500 h-3 rounded-full transition-all duration-300"
                    style="width: {{ min(100, ($progressData['jumlah_bulan_ini'] / 5) * 100) }}%"
                ></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-600 dark:text-gray-300">
                <span>{{ $progressData['jumlah_bulan_ini'] }}/5</span>
                <span>{{ $progressData['sisa_menuju_kelipatan'] }} lagi</span>
            </div>
        </div>
    </div>
</div>
