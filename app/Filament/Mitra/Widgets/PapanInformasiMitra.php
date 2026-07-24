<?php

namespace App\Filament\Mitra\Widgets;

use App\Models\Pengumuman;
use Filament\Widgets\Widget;

class PapanInformasiMitra extends Widget
{
    protected static string $view = 'filament.mitra.widgets.papan-informasi-mitra';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getPengumuman()
    {
        return Pengumuman::where('aktif', true)
            ->latest()
            ->limit(3)
            ->get();
    }
}
