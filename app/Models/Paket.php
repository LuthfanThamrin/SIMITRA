<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'nama_paket',
        'kategori',
        'kecepatan',
        'harga',
        'keterangan',
        'aktif',
    ];

    protected $appends = ['label'];

    public function getLabelAttribute()
    {
        $harga = number_format($this->harga, 0, ',', '.');
        $satuan = ($this->kategori === 'KDMP') ? 'Thn' : 'Bln';
        return sprintf('%s %s Rp%s/%s', $this->nama_paket, $this->kecepatan, $harga, $satuan);
    }
}
