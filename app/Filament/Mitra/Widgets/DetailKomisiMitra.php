<?php

namespace App\Filament\Mitra\Widgets;

use Filament\Widgets\Widget;
use App\Services\KomisiService;

class DetailKomisiMitra extends Widget
{
    protected static string $view = 'filament.mitra.widgets.detail-komisi-mitra';
    protected static ?int $sort = 2;
    protected static bool $isLazy = true;

    public function getViewData(): array
    {
        $userId = auth()->id();
        $user = \App\Models\User::find($userId);
        $komisi = new KomisiService($user);

        $progress = $komisi->progressBonusBulanIni();

        return [
            'totalKomisi' => $komisi->totalKomisi(),
            'komisiDasar' => $komisi->komisiDasar(),
            'totalBonus' => $komisi->totalBonus(),
            'sudahDibayar' => $komisi->totalDibayar(),
            'sisaBelumDibayar' => $komisi->sisaBelumDibayar(),
            'progressData' => $progress,
        ];
    }
}
