<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Mail\MitraDisetujui;
use App\Mail\MitraDitolak;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Manajemen Mitra';
    protected static ?string $modelLabel = 'Mitra';
    protected static ?string $pluralModelLabel = 'Mitra';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('role', 'mitra')
            ->where('status_pendaftaran', 'pending')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'mitra');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Diri & Akun')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_hp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context) => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat (opsional)')
                            ->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->label('Nama Bank')
                            ->placeholder('Contoh: BCA, BRI, Mandiri')
                            ->required()
                            ->validationMessages([
                                'required' => 'Nama bank wajib diisi',
                            ])
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_rekening')
                            ->label('Nomor Rekening')
                            ->placeholder('Contoh: 1234567890')
                            ->required()
                            ->numeric()
                            ->validationMessages([
                                'required' => 'Nomor rekening wajib diisi',
                            ]),
                    ])->columns(2),
                Forms\Components\Section::make('Sistem')
                    ->schema([
                        Forms\Components\Hidden::make('role')
                            ->default('mitra'),
                        Forms\Components\TextInput::make('kode_referral')
                            ->disabled()
                            ->dehydrated(false)
                            ->maxLength(255)
                            ->helperText('Dibuat otomatis oleh sistem saat mitra baru dibuat.'),
                        Forms\Components\Toggle::make('status_aktif')
                            ->default(true)
                            ->required(),
                        Forms\Components\Select::make('status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->options([
                                'pending'   => 'Menunggu Persetujuan',
                                'disetujui' => 'Disetujui',
                                'ditolak'   => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_bank')
                    ->label('Bank & Rekening')
                    ->formatStateUsing(fn ($record) => $record->nama_bank . ' - ' . $record->no_rekening)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kode_referral')
                    ->searchable()
                    ->badge()
                    ->copyable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('pendaftarans_count')
                    ->counts('pendaftarans')
                    ->label('Jumlah Pelanggan'),
                Tables\Columns\IconColumn::make('status_aktif')
                    ->boolean()
                    ->label('Status Aktif'),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status Pendaftaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu Persetujuan',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        default     => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'disetujui' => 'success',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status_aktif')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Status Pendaftaran')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->placeholder('Semua'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pendaftaran Mitra')
                    ->modalDescription('Setujui pendaftaran mitra ini?')
                    ->visible(fn ($record) => $record->status_pendaftaran === 'pending')
                    ->action(function ($record) {
                        Log::info('Action Setujui ditekan untuk mitra: ' . $record->id);
                        
                        $data = [
                            'status_pendaftaran' => 'disetujui',
                            'status_aktif' => true,
                        ];

                        if (empty($record->kode_referral)) {
                            do {
                                $kode = 'MITRA-' . strtoupper(\Illuminate\Support\Str::random(5));
                            } while (User::where('kode_referral', $kode)->exists());

                            $data['kode_referral'] = $kode;
                        }

                        $record->update($data);

                        try {
                            Mail::to($record->email)->send(new MitraDisetujui($record));
                            Notification::make()
                                ->title('Mitra berhasil disetujui dan email pemberitahuan telah dikirim.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Log::error('Gagal mengirim email MitraDisetujui: ' . $e->getMessage());
                            Notification::make()
                                ->title('Mitra berhasil disetujui, tetapi email gagal dikirim.')
                                ->warning()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran Mitra')
                    ->modalDescription('Tolak pendaftaran mitra ini?')
                    ->visible(fn ($record) => $record->status_pendaftaran === 'pending')
                    ->action(function ($record) {
                        Log::info('Action Tolak ditekan untuk mitra: ' . $record->id);
                        
                        $record->update([
                            'status_pendaftaran' => 'ditolak',
                            'status_aktif' => false,
                        ]);

                        try {
                            Mail::to($record->email)->send(new MitraDitolak($record));
                            Notification::make()
                                ->title('Pendaftaran mitra ditolak dan email pemberitahuan telah dikirim.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Log::error('Gagal mengirim email MitraDitolak: ' . $e->getMessage());
                            Notification::make()
                                ->title('Pendaftaran ditolak, tetapi email pemberitahuan gagal dikirim.')
                                ->warning()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->status_aktif ? 'Nonaktifkan' : 'Aktifkan')
                    ->color(fn ($record) => $record->status_aktif ? 'danger' : 'success')
                    ->icon(fn ($record) => $record->status_aktif ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->requiresConfirmation(fn ($record) => $record->status_aktif)
                    ->modalHeading('Konfirmasi Nonaktifkan Mitra')
                    ->modalDescription('Apakah Anda yakin ingin menonaktifkan mitra ini?')
                    ->action(function ($record) {
                        $record->update(['status_aktif' => !$record->status_aktif]);
                    }),
                Tables\Actions\Action::make('salin_link')
                    ->label('Salin Link')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->url('#')
                    ->extraAttributes(fn ($record) => [
                        'x-on:click.prevent' => "
                            let link = '" . url('/daftar?ref=' . $record->kode_referral) . "';
                            let showCopyNotification = () => {
                                new FilamentNotification().title('Link berhasil disalin!').success().duration(3000).send();
                            };
                            let copyFallback = () => {
                                const textArea = document.createElement('textarea');
                                textArea.value = link;
                                textArea.setAttribute('readonly', '');
                                textArea.style.position = 'fixed';
                                textArea.style.top = '-9999px';
                                textArea.style.left = '-9999px';
                                textArea.style.opacity = '0';
                                document.body.appendChild(textArea);
                                textArea.focus();
                                textArea.select();
                                textArea.setSelectionRange(0, textArea.value.length);
                                try {
                                    const copied = document.execCommand('copy');
                                    if (copied) {
                                        showCopyNotification();
                                    }
                                } catch (error) {
                                    showCopyNotification();
                                } finally {
                                    document.body.removeChild(textArea);
                                }
                            };
                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(link)
                                    .then(showCopyNotification)
                                    .catch(copyFallback);
                            } else {
                                copyFallback();
                            }
                        ",
                    ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
