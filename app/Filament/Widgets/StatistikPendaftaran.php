<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikPendaftaran extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftaran', \App\Models\Pendaftaran::count())
                ->description('Seluruh pendaftaran')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Menunggu Verifikasi', \App\Models\Pendaftaran::where('status', 'pending')->count())
                ->description('Perlu ditindaklanjuti')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Diproses', \App\Models\Pendaftaran::where('status', 'diproses')->count())
                ->description('Sedang ditangani')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            Stat::make('Terpasang', \App\Models\Pendaftaran::where('status', 'terpasang')->count())
                ->description('Selesai dipasang')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Ditolak', \App\Models\Pendaftaran::where('status', 'ditolak')->count())
                ->description('Pendaftaran dibatalkan')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
