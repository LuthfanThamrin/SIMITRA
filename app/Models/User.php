<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'kode_referral',
        'no_hp',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    // Biar Filament dapat nama user dari kolom "nama"
    public function getFilamentName(): string
    {
        return $this->nama ?? '';
    }

    // Biar kapan pun ada yang nyari "name", otomatis ambil dari "nama"
    public function getNameAttribute(): string
    {
        return $this->nama ?? '';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role == 'admin' ;
    }
}