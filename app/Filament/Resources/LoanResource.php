<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;
    public static ?string $label = 'Antrian Peminjam Ruangan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function canCreate(): bool
    {
       return false;
    }

    // Form untuk edit dan create peminjaman
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_organisasi')
                    ->label('Nama Organisasi')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nama_peminjam')
                    ->label('Nama Peminjam')
                    ->required()
                    ->maxLength(255),

                Select::make('room_id')
                    ->label('Ruangan')
                    ->relationship('room', 'name')
                    ->required(),

                TextInput::make('no_hp')
                    ->label('No. HP')
                    ->required()
                    ->maxLength(15),

                TextInput::make('acara')
                    ->label('Acara')
                    ->required(),

                DateTimePicker::make('tanggal_pinjam')
                    ->label('Waktu Mulai')
                    ->required(),

                DateTimePicker::make('tanggal_kembali')
                    ->label('Waktu Selesai')
                    ->required(),

                FileUpload::make('surat_pinjam')
                    ->label('Surat Pinjam')
                    ->directory('uploads/surat_pinjam')
                    ->downloadable()
                    ->openable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                    ])
                    ->default('pending'),
            ]);
    }

    // Tampilan tabel daftar peminjam
    public static function table(Table $table): Table
    {
        return $table
        ->query(fn () => Loan::whereIn('status', ['approved', 'pending']))
            ->columns([
                Tables\Columns\TextColumn::make('#')
                ->rowIndex()
                ->label('Urutan'),

                Tables\Columns\TextColumn::make('nama_organisasi')
                    ->label('Organisasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_peminjam')
                    ->label('Nama Peminjam')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.name')
                    ->label('Ruangan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i'),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y, H:i'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary',
                        'warning' => static fn ($state): bool => $state === 'pending',
                        'info' => static fn ($state): bool => $state === 'approved',
                        'success' => static fn ($state): bool => $state === 'completed',
                        'danger' => static fn ($state): bool => $state === 'rejected',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Diterima, Sedang Berlangsung',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => 'Tidak Diketahui',
                    }),   
            ])
            ->actions([
                EditAction::make(),

                Tables\Actions\Action::make('no_hp')
                ->label('Chat')
                ->icon('heroicon-o-inbox')
                ->color('success')
                ->url(fn ($record) => 'https://wa.me/' . preg_replace('/^0/', '62', $record->no_hp) . 
                    '?text=' . rawurlencode(
                        "Halo, peminjaman ruangan telah diterima!\n\n" .
                        "Nama Peminjam: {$record->nama_peminjam}\n" .
                        "Organisasi: {$record->nama_organisasi}\n" .
                        "Ruangan: {$record->room->name}\n" .
                        "Tanggal: {$record->tanggal_pinjam} - {$record->tanggal_kembali}\n\n" .
                        "Silakan hubungi admin jika ada pertanyaan."
                    )
                )
                ->openUrlInNewTab(), // Membuka link di tab baru
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
