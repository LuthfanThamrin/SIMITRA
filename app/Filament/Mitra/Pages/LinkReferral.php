<?php

namespace App\Filament\Mitra\Pages;

use Filament\Pages\Page;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkReferral extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'filament.mitra.pages.link-referral';
    protected static ?string $navigationLabel = 'Link & QR Referral';
    protected static ?string $title = 'Link & QR Referral';
    protected static ?int $navigationSort = 3;

    public function getKodeReferral(): string
    {
        return auth()->user()->kode_referral;
    }

    public function getLinkReferral(): string
    {
        return url('/daftar?ref=' . $this->getKodeReferral());
    }

    public function getQrCode()
    {
        return QrCode::size(250)->generate($this->getLinkReferral());
    }
}
