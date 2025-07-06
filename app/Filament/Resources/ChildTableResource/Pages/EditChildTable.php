<?php

namespace App\Filament\Resources\ChildTableResource\Pages;

use App\Filament\Resources\ChildTableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChildTable extends EditRecord
{
    protected static string $resource = ChildTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
