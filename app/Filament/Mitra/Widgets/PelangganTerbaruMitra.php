<?php

namespace App\Filament\Mitra\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PelangganTerbaruMitra extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Pelanggan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Pendaftaran::query()
                    ->where('mitra_id', auth()->id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemilik')
                    ->label('Nama Pelanggan'),
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->label('Nama Usaha'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Verifikasi',
                        'diproses' => 'Diproses',
                        'terpasang' => 'Terpasang',
                        'ditolak' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'terpasang' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false)
            ->recordUrl(
                fn (\App\Models\Pendaftaran $record): string => \App\Filament\Mitra\Resources\PendaftaranResource::getUrl('view', ['record' => $record]),
            );
    }
}
