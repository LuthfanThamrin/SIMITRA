<?php

namespace App\Filament\Mitra\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'SIMITRA';
    }

    public function getSubheading(): ?string
    {
        return 'Portal Mitra';
    }
}
