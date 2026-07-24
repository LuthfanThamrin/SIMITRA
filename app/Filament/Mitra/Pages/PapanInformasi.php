<?php

namespace App\Filament\Mitra\Pages;

use App\Models\Pengumuman;
use Filament\Pages\Page;

class PapanInformasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static string $view = 'filament.mitra.pages.papan-informasi';
    protected static ?string $navigationLabel = 'Papan Informasi';
    protected static ?string $title = 'Papan Informasi';
    protected static ?int $navigationSort = 4;

    public function getPengumuman()
    {
        return Pengumuman::where('aktif', true)
            ->latest()
            ->get();
    }
}
