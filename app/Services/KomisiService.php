<?php

namespace App\Services;

use App\Models\Pendaftaran;
use App\Models\User;
use Carbon\Carbon;

class KomisiService
{
    // Commission constants - easily configurable
    const KOMISI_PER_PELANGGAN = 200000;
    const BONUS_PER_KELIPATAN = 200000;
    const KELIPATAN_BONUS = 5;

    private $mitra;

    public function __construct(User $mitra)
    {
        $this->mitra = $mitra;
    }

    /**
     * Jumlah pendaftaran milik mitra dengan status 'terpasang'
     */
    public function jumlahTerpasang(): int
    {
        return Pendaftaran::where('mitra_id', $this->mitra->id)
            ->where('status', 'terpasang')
            ->count();
    }

    /**
     * Komisi dasar = jumlah terpasang × Rp200.000
     */
    public function komisiDasar(): int
    {
        return $this->jumlahTerpasang() * self::KOMISI_PER_PELANGGAN;
    }

    /**
     * Total bonus dari semua bulan
     * Untuk tiap bulan: floor(jumlah_bulan / 5) × Rp200.000
     */
    public function totalBonus(): int
    {
        $perBulan = Pendaftaran::where('mitra_id', $this->mitra->id)
            ->where('status', 'terpasang')
            ->whereNotNull('tanggal_terpasang')
            ->get()
            ->groupBy(fn ($p) => $p->tanggal_terpasang->format('Y-m'));

        $totalBonus = 0;
        foreach ($perBulan as $bulan => $items) {
            $totalBonus += floor($items->count() / self::KELIPATAN_BONUS) * self::BONUS_PER_KELIPATAN;
        }

        return $totalBonus;
    }

    /**
     * Total komisi = komisi dasar + total bonus
     */
    public function totalKomisi(): int
    {
        return $this->komisiDasar() + $this->totalBonus();
    }

    /**
     * Total pembayaran yang sudah dicatat
     */
    public function totalDibayar(): int
    {
        return (int) \App\Models\PembayaranKomisi::where('mitra_id', $this->mitra->id)
            ->sum('jumlah');
    }

    /**
     * Sisa komisi yang belum dibayarkan
     */
    public function sisaBelumDibayar(): int
    {
        return max(0, $this->totalKomisi() - $this->totalDibayar());
    }

    /**
     * Progress bonus bulan ini
     * Return: ['jumlah_bulan_ini', 'sisa_menuju_kelipatan', 'deskripsi']
     */
    public function progressBonusBulanIni(): array
    {
        $bulanIni = now()->format('Y-m');
        
        $jumlahBulanIni = Pendaftaran::where('mitra_id', $this->mitra->id)
            ->where('status', 'terpasang')
            ->whereNotNull('tanggal_terpasang')
            ->whereYear('tanggal_terpasang', now()->year)
            ->whereMonth('tanggal_terpasang', now()->month)
            ->count();

        $sisaMenuju = self::KELIPATAN_BONUS - ($jumlahBulanIni % self::KELIPATAN_BONUS);
        $bonusDidapat = floor($jumlahBulanIni / self::KELIPATAN_BONUS);

        if ($jumlahBulanIni >= self::KELIPATAN_BONUS) {
            $deskripsi = "{$jumlahBulanIni} pelanggan terpasang bulan ini — bonus Rp" . number_format($bonusDidapat * self::BONUS_PER_KELIPATAN, 0, ',', '.') . " ({$bonusDidapat}x)";
        } else {
            $deskripsi = "{$jumlahBulanIni} dari " . self::KELIPATAN_BONUS . " pelanggan terpasang bulan ini — {$sisaMenuju} lagi menuju bonus Rp" . number_format(self::BONUS_PER_KELIPATAN, 0, ',', '.');
        }

        return [
            'jumlah_bulan_ini' => $jumlahBulanIni,
            'sisa_menuju_kelipatan' => min($sisaMenuju, self::KELIPATAN_BONUS),
            'deskripsi' => $deskripsi,
            'bonus_didapat' => $bonusDidapat,
        ];
    }

    /**
     * Rincian bonus per bulan
     * Return: array of ['bulan' => 'Juli 2026', 'jumlah' => 12, 'bonus' => 400000]
     */
    public function rincianBonusPerBulan(): array
    {
        $perBulan = Pendaftaran::where('mitra_id', $this->mitra->id)
            ->where('status', 'terpasang')
            ->whereNotNull('tanggal_terpasang')
            ->get()
            ->groupBy(fn ($p) => $p->tanggal_terpasang->format('Y-m'))
            ->sortKeys()
            ->reverse();

        $hasil = [];
        foreach ($perBulan as $bulan => $items) {
            $jumlah = $items->count();
            $bonus = floor($jumlah / self::KELIPATAN_BONUS) * self::BONUS_PER_KELIPATAN;
            
            $hasil[] = [
                'bulan' => Carbon::createFromFormat('Y-m', $bulan)->locale('id')->translatedFormat('F Y'),
                'jumlah' => $jumlah,
                'bonus' => $bonus,
            ];
        }

        return $hasil;
    }
}
