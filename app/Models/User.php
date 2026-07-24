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
        'nama_bank',
        'no_rekening',
        'alamat',
        'status_aktif',
        'status_pendaftaran',
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
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'mitra') {
            return $this->role === 'mitra'
                && $this->status_aktif
                && $this->status_pendaftaran === 'disetujui';
        }

        return false;
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'mitra_id');
    }

    public function pembayaranKomisis()
    {
        return $this->hasMany(PembayaranKomisi::class, 'mitra_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if ($user->role === 'mitra' && empty($user->kode_referral)) {
                do {
                    $kode = 'MITRA-' . strtoupper(\Illuminate\Support\Str::random(5));
                } while (self::where('kode_referral', $kode)->exists());
                $user->kode_referral = $kode;
            }
        });
    }
}