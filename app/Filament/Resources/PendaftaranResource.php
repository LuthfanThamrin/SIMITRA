<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Data Pendaftaran';
    protected static ?string $modelLabel = 'Pendaftaran';
    protected static ?string $pluralModelLabel = 'Data Pendaftaran';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelanggan & Usaha')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pemilik')
                            ->disabled(),
                        Forms\Components\TextInput::make('nama_usaha')
                            ->disabled(),
                        Forms\Components\TextInput::make('no_hp')
                            ->disabled(),
                        Forms\Components\TextInput::make('cp_alternatif')
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

                Forms\Components\Section::make('Dokumen')
                    ->schema([
                        Forms\Components\Placeholder::make('foto_ktp_preview')
                            ->label('Foto KTP')
                            ->content(fn ($record) => self::getFilePreview($record->foto_ktp)),
                        Forms\Components\Placeholder::make('foto_nib_npwp_preview')
                            ->label('Foto NPWP / NIB / Dokumen Usaha')
                            ->content(fn ($record) => self::getFilePreview($record->foto_nib_npwp)),
                        Forms\Components\Placeholder::make('foto_lokasi_preview')
                            ->label('Foto Tampak Depan Usaha')
                            ->content(fn ($record) => self::getFilePreview($record->foto_lokasi)),
                    ])->columns(3),

                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->disabled(),
                        Forms\Components\TextInput::make('longitude')
                            ->disabled(),
                        Forms\Components\Placeholder::make('link_maps')
                            ->label('Google Maps')
                            ->content(fn ($record) => $record->link_maps ? new HtmlString("<a href='{$record->link_maps}' target='_blank' class='text-primary-600 underline'>Lihat di Google Maps</a>") : '-'),
                    ])->columns(3),

                Forms\Components\Section::make('Verifikasi & Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'diproses' => 'Diproses',
                                'terpasang' => 'Terpasang',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('catatan_admin')
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Info Referral')
                    ->schema([
                        Forms\Components\TextInput::make('mitra')
                            ->label('Mitra Referral')
                            ->formatStateUsing(fn ($record) => $record->mitra ? $record->mitra->nama : '-')
                            ->disabled(),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal Daftar')
                            ->disabled(),
                        Forms\Components\TextInput::make('sumber_input')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    private static function getFilePreview(?string $path)
    {
        if (!$path) return '-';
        $url = \Illuminate\Support\Facades\Storage::url($path);
        
        if (str_ends_with(strtolower($path), '.pdf')) {
            return new HtmlString("<a href='{$url}' target='_blank' class='text-primary-600 underline'>Buka PDF</a>");
        }
        
        return new HtmlString("<a href='{$url}' target='_blank'><img src='{$url}' style='max-width: 100%; height: auto; max-height: 200px; border-radius: 8px;' /></a>");
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_usaha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_usaha')
                    ->formatStateUsing(fn ($record) => $record->jenis_usaha === 'lainnya' ? $record->jenis_usaha_lainnya : ucfirst($record->jenis_usaha)),
                Tables\Columns\TextColumn::make('paket.nama_paket')
                    ->label('Paket')
                    ->formatStateUsing(fn ($record) => $record->konsultasi_paket ? 'Konsultasi' : ($record->paket ? $record->paket->nama_paket . ' ' . $record->paket->kecepatan : '-')),
                Tables\Columns\TextColumn::make('mitra.nama')
                    ->label('Mitra')
                    ->default('-'),
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
                Tables\Filters\SelectFilter::make('mitra_id')
                    ->label('Mitra')
                    ->relationship('mitra', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            // 'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
