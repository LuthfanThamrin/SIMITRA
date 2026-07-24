<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapKomisiResource\Pages;
use App\Models\User;
use App\Models\PembayaranKomisi;
use App\Services\KomisiService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RekapKomisiResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Rekap Komisi';
    protected static ?string $modelLabel = 'Rekap Komisi';
    protected static ?string $pluralModelLabel = 'Rekap Komisi';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'mitra');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form tidak digunakan untuk resource ini (read-only recap)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank_rekening')
                    ->label('Bank & Rekening')
                    ->getStateUsing(function (Model $record) {
                        if ($record->nama_bank && $record->no_rekening) {
                            return "{$record->nama_bank} - {$record->no_rekening}";
                        }
                        return '-';
                    })
                    ->copyable(),

                TextColumn::make('terpasang')
                    ->label('Jumlah Terpasang')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return (string) $komisi->jumlahTerpasang();
                    }),

                TextColumn::make('komisi_dasar')
                    ->label('Komisi Dasar')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return 'Rp' . number_format($komisi->komisiDasar(), 0, ',', '.');
                    }),

                TextColumn::make('total_bonus')
                    ->label('Total Bonus')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return 'Rp' . number_format($komisi->totalBonus(), 0, ',', '.');
                    }),

                TextColumn::make('total_komisi')
                    ->label('Total Komisi')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return 'Rp' . number_format($komisi->totalKomisi(), 0, ',', '.');
                    })
                    ->weight('bold'),

                TextColumn::make('sudah_dibayar')
                    ->label('Sudah Dibayar')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return 'Rp' . number_format($komisi->totalDibayar(), 0, ',', '.');
                    }),

                TextColumn::make('sisa')
                    ->label('Sisa Belum Dibayar')
                    ->getStateUsing(function (Model $record) {
                        $komisi = new KomisiService($record);
                        $sisa = $komisi->sisaBelumDibayar();
                        return 'Rp' . number_format($sisa, 0, ',', '.');
                    })
                    ->color(function (Model $record) {
                        $komisi = new KomisiService($record);
                        return $komisi->sisaBelumDibayar() > 0 ? 'danger' : 'success';
                    }),
            ])
            ->defaultSort('nama')
            ->filters([
                // Filter placeholder
            ])
            ->actions([
                Action::make('catatPembayaran')
                    ->label('Catat Pembayaran')
                    ->icon('heroicon-m-currency-dollar')
                    ->form(function (Model $record) {
                        $komisi = new KomisiService($record);
                        $sisa = $komisi->sisaBelumDibayar();

                        return [
                            Forms\Components\Section::make('Catat Pembayaran Komisi')
                                ->schema([
                                    Forms\Components\TextInput::make('mitra_nama')
                                        ->label('Mitra')
                                        ->default($record->nama)
                                        ->disabled(),

                                    Forms\Components\TextInput::make('jumlah')
                                        ->label('Jumlah')
                                        ->required()
                                        ->numeric()
                                        ->default($sisa)
                                        ->minValue(1)
                                        ->prefix('Rp')
                                        ->validationMessages([
                                            'required' => 'Jumlah wajib diisi',
                                            'numeric' => 'Jumlah harus berupa angka',
                                            'min' => 'Jumlah minimal 1',
                                        ]),

                                    Forms\Components\DatePicker::make('tanggal_bayar')
                                        ->label('Tanggal Bayar')
                                        ->required()
                                        ->default(now()),

                                    Forms\Components\Textarea::make('catatan')
                                        ->label('Catatan')
                                        ->placeholder('Opsional')
                                        ->maxLength(255),
                                ]),
                        ];
                    })
                    ->action(function (Model $record, array $data): void {
                        $komisi = new KomisiService($record);
                        $sisa = $komisi->sisaBelumDibayar();
                        $jumlah = (int) $data['jumlah'];

                        if ($jumlah > $sisa) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Gagal')
                                ->body('Jumlah pembayaran melebihi sisa komisi yang belum dibayar')
                                ->send();
                            return;
                        }

                        PembayaranKomisi::create([
                            'mitra_id' => $record->id,
                            'jumlah' => $jumlah,
                            'tanggal_bayar' => $data['tanggal_bayar'],
                            'catatan' => $data['catatan'] ?? null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Berhasil')
                            ->body('Pembayaran komisi berhasil dicatat')
                            ->send();
                    })
                    ->modalHeading('Catat Pembayaran Komisi'),

                Action::make('riwayatPembayaran')
                    ->label('Riwayat Pembayaran')
                    ->icon('heroicon-m-clock')
                    ->modalContent(function (Model $record) {
                        $pembayaran = PembayaranKomisi::where('mitra_id', $record->id)
                            ->orderBy('tanggal_bayar', 'desc')
                            ->get();

                        return view('komisi.riwayat-pembayaran', ['pembayaran' => $pembayaran]);
                    })
                    ->modalHeading(fn ($record) => "Riwayat Pembayaran - {$record->nama}")
                    ->modalWidth('lg'),
            ])
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapKomisi::route('/'),
        ];
    }
}
