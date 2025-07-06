<?php

namespace App\Filament\Resources\CanvasResource\Pages;

use App\Filament\Resources\CanvasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCanvas extends EditRecord
{
    protected static string $resource = CanvasResource::class;
    protected static string $view = 'filament.pages.fabric-page1';
    public $canvas_id;
    public $name_of_document;
    public $data1 = 'testing';

    public function mount(int|string $record): void
    {
        $this->canvas_id = $record;
        $this->record = $this->resolveRecord($record);
        $this->name_of_document = $this->record->name;
    }

}
