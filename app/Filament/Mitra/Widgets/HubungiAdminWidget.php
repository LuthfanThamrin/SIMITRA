<?php

namespace App\Filament\Mitra\Widgets;

use Filament\Widgets\Widget;

class HubungiAdminWidget extends Widget
{
    protected static string $view = 'filament.mitra.widgets.hubungi-admin-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    // Position this widget at the top or bottom as appropriate
    protected static ?int $sort = -1;
}
