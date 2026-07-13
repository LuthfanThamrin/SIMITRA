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
        'jenis_usaha',
        'jenis_usaha_lainnya',
        'foto_ktp',
        'foto_izin_usaha',
        'foto_nib_npwp',
        'foto_lokasi',
        'latitude',
        'longitude',
        'mitra_id',
        'sumber_input',
        'status',
        'catatan_admin',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }
}
