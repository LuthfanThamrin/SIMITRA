<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendaftaranTerbaru extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Pendaftaran::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemilik')
                    ->label('Nama Pemilik'),
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->label('Nama Usaha'),
                Tables\Columns\TextColumn::make('mitra.nama')
                    ->label('Mitra')
                    ->default('-'),
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
                fn (\App\Models\Pendaftaran $record): string => \App\Filament\Resources\PendaftaranResource::getUrl('edit', ['record' => $record]),
            );
    }
}
