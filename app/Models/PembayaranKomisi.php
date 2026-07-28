<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranKomisi extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_komisi';

    protected $fillable = [
        'mitra_id',
        'jumlah',
        'tanggal_bayar',
        'catatan',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }
}
