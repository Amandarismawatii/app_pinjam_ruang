<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    public static ?string $label = 'Daftar Ruangan';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Ruangan') // Nama Label Saat Create
                ->required() // Form Harus Di Isi
                ->maxLength(255), // Maksimal Panjang text

            Forms\Components\TextInput::make('capacity')
                ->label('Kapasitas') 
                ->required()
                ->numeric(), // Isi form harus numeric atau INT

                Forms\Components\FileUpload::make('image')
                ->label('Gambar Ruangan') 
                ->image() // Form Harus Berisi Gambar
                ->directory('img') 
                ->maxSize(5120) // Maksimal 5MB
                ->required()
                ->helperText('Ukuran maksimal gambar adalah 5MB.') // Pesan bantu
                ->validationMessages([
                    'maxSize' => 'Gambar tidak boleh lebih dari 5MB!',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Ruangan')->searchable(),
                Tables\Columns\TextColumn::make('capacity')->label('Kapasitas')->sortable(),
                Tables\Columns\ImageColumn::make('image')->label('Gambar')->size(100),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat Pada')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
