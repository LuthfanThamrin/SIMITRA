<?php

namespace App\Filament\Mitra\Resources;

use App\Filament\Mitra\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pelanggan Saya';
    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan Saya';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('mitra_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelanggan & Usaha')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pemilik')
                            ->label('Nama Pelanggan')
                            ->disabled(),
                        Forms\Components\TextInput::make('nama_usaha')
                            ->disabled(),
                        Forms\Components\TextInput::make('no_hp')
                            ->disabled(),
                        Forms\Components\TextInput::make('jenis_usaha')
                            ->formatStateUsing(fn ($record) => $record->jenis_usaha === 'lainnya' ? $record->jenis_usaha_lainnya : ucfirst($record->jenis_usaha))
                            ->disabled(),
                        Forms\Components\TextInput::make('paket_id')
                            ->label('Paket')
                            ->formatStateUsing(fn ($record) => $record->konsultasi_paket ? 'Konsultasi Dulu' : ($record->paket ? $record->paket->nama_paket . ' ' . $record->paket->kecepatan : '-'))
                            ->disabled(),
                        Forms\Components\Textarea::make('alamat_instalasi')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\TextInput::make('kota')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Catatan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'diproses' => 'Diproses',
                                'terpasang' => 'Terpasang',
                                'ditolak' => 'Ditolak',
                            ])
                            ->disabled(),
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan dari Admin')
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pemilik')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paket.nama_paket')
                    ->label('Paket')
                    ->formatStateUsing(fn ($record) => $record->konsultasi_paket ? 'Konsultasi' : ($record->paket ? $record->paket->nama_paket . ' ' . $record->paket->kecepatan : '-')),
                Tables\Columns\TextColumn::make('kota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y')
                    ->sortable(),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'diproses' => 'Diproses',
                        'terpasang' => 'Terpasang',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftarans::route('/'),
            'view' => Pages\ViewPendaftaran::route('/{record}'),
        ];
    }
}
