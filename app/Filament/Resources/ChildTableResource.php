<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChildTableResource\Pages;
use App\Filament\Resources\ChildTableResource\RelationManagers;
use App\Models\ChildTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChildTableResource extends Resource
{
    protected static ?string $model = ChildTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('canvas_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('text1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('text2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('text3')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('canvas_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('text1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('text2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListChildTables::route('/'),
            'create' => Pages\CreateChildTable::route('/create'),
            'edit' => Pages\EditChildTable::route('/{record}/edit'),
        ];
    }
}
