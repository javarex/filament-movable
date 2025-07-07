<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CanvasResource\Pages;
use App\Filament\Resources\CanvasResource\RelationManagers;
use App\Models\Canvas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CanvasResource extends Resource
{
    protected static ?string $model = Canvas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('html')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('width')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('height')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('background')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('background')
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCanvases::route('/'),
            'create' => Pages\CreateCanvas::route('/create'),
            'edit' => Pages\EditCanvas::route('/{record}/edit'),
            'view' => Pages\ViewCanvas::route('/{record}'),
        ];
    }
}
