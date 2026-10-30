<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompletedRejectedLoanResource\Pages;
use App\Models\Loan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompletedRejectedLoanResource extends Resource
{
    protected static ?string $model = Loan::class;
    protected static ?string $label = 'History Peminjaman';
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 3;
    public static function canCreate(): bool
    {
       return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => Loan::whereIn('status', ['completed', 'rejected'])) // FIX ERROR
            ->columns([
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Nama Ruangan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_peminjam')
                    ->label('Nama Peminjam')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('acara')
                    ->label('Acara')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i'),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y, H:i'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->color(function (string $state): string {
                        return match($state){
                            'completed' => 'success',
                            'rejected' => 'danger'
                        };
                    })
                    ->formatStateUsing(fn ($state) => $state === 'completed' ? 'Selesai' : 'Ditolak'),
            ])
            ->actions([]) // Tidak ada aksi edit
            ->bulkActions([]); // Tidak ada bulk action
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompletedRejectedLoans::route('/'),
        ];
    }
}
