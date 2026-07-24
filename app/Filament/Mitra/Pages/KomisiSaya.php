<?php

namespace App\Filament\Mitra\Pages;

use Filament\Pages\Page;
use App\Services\KomisiService;
use App\Models\PembayaranKomisi;

class KomisiSaya extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Komisi Saya';
    protected static string $view = 'filament.mitra.pages.komisi-saya';
    protected static ?int $navigationSort = 2;

    public function mount(): void
    {
        // Ensure only logged-in mitra can access
        if (!auth()->check() || auth()->user()->role !== 'mitra') {
            abort(403);
        }
    }

    public function getViewData(): array
    {
        $user = auth()->user();
        $komisi = new KomisiService($user);

        $pembayaran = PembayaranKomisi::where('mitra_id', $user->id)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        return [
            'komisi' => $komisi,
            'totalKomisi' => $komisi->totalKomisi(),
            'komisiDasar' => $komisi->komisiDasar(),
            'totalBonus' => $komisi->totalBonus(),
            'sudahDibayar' => $komisi->totalDibayar(),
            'sisaBelumDibayar' => $komisi->sisaBelumDibayar(),
            'rincianBonus' => $komisi->rincianBonusPerBulan(),
            'progressBulanIni' => $komisi->progressBonusBulanIni(),
            'riwayatPembayaran' => $pembayaran,
        ];
    }
}
