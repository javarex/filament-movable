<?php

namespace App\Filament\Pages;

use App\Models\Canvas;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class FabricPage extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.fabric-page';

    public function getHeading(): string|Htmlable
    {
        return 'Documents';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_doc')
                ->label('Create Document')
                // ->url()
        ];
    }

    public function table(Table $table): Table
    {
        return $table
                ->query(Canvas::query())
                ->columns([

                ]);
    }
    // public function getLayout(): string
    // {
    //     return 'layouts.custom-layout';
    // }
}
