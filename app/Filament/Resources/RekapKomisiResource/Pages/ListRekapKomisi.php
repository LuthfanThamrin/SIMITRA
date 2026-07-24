<?php

namespace App\Filament\Resources\RekapKomisiResource\Pages;

use App\Filament\Resources\RekapKomisiResource;
use Filament\Resources\Pages\ListRecords;

class ListRekapKomisi extends ListRecords
{
    protected static string $resource = RekapKomisiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
