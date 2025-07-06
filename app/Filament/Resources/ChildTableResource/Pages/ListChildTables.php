<?php

namespace App\Filament\Resources\ChildTableResource\Pages;

use App\Filament\Resources\ChildTableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChildTables extends ListRecords
{
    protected static string $resource = ChildTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
