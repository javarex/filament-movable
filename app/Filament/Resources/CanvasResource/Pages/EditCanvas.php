<?php

namespace App\Filament\Resources\CanvasResource\Pages;

use App\Filament\Resources\CanvasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCanvas extends EditRecord
{
    protected static string $resource = CanvasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
