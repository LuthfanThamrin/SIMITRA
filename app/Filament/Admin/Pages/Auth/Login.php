<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'SIMITRA';
    }

    public function getSubheading(): ?string
    {
        return 'Panel Admin';
    }
}
