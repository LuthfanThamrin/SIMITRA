<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'nama_pemilik',
        'nama_usaha',
        'no_hp',
        'cp_alternatif',
        'alamat_instalasi',
        'kota',
        'jenis_usaha',
        'jenis_usaha_lainnya',
        'foto_ktp',
        'foto_izin_usaha',
        'foto_nib_npwp',
        'foto_lokasi',
        'latitude',
        'longitude',
        'link_maps',
        'mitra_id',
        'sumber_input',
        'status',
        'catatan_admin',
        'paket_id',
        'konsultasi_paket',
        'tanggal_terpasang',
    ];

    protected $casts = [
        'tanggal_terpasang' => 'datetime',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($pendaftaran) {
            // Jika status BARU berubah menjadi 'terpasang' dan belum ada tanggalnya
            if ($pendaftaran->isDirty('status') && $pendaftaran->status === 'terpasang' && empty($pendaftaran->tanggal_terpasang)) {
                $pendaftaran->tanggal_terpasang = now();
            }

            // Jika status diubah dari 'terpasang' ke status lain, kosongkan tanggalnya
            if ($pendaftaran->isDirty('status') && $pendaftaran->getOriginal('status') === 'terpasang' && $pendaftaran->status !== 'terpasang') {
                $pendaftaran->tanggal_terpasang = null;
            }
        });
    }
}
