<?php

namespace App\Filament\Mitra\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Services\KomisiService;

class StatistikMitra extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $userId = auth()->id();
        $user = \App\Models\User::find($userId);
        $komisi = new KomisiService($user);

        return [
            Stat::make('Total Pelanggan Masuk', \App\Models\Pendaftaran::where('mitra_id', $userId)->count())
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Pelanggan Terpasang', \App\Models\Pendaftaran::where('mitra_id', $userId)->where('status', 'terpasang')->count())
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Total Komisi', 'Rp' . number_format($komisi->totalKomisi(), 0, ',', '.'))
                ->description('Dasar: Rp' . number_format($komisi->komisiDasar(), 0, ',', '.') . ' | Bonus: Rp' . number_format($komisi->totalBonus(), 0, ',', '.'))
                ->descriptionIcon('heroicon-m-wallet')
                ->color('info'),
        ];
    }
}
