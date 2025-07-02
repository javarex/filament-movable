<?php

namespace App\Filament\Resources\CanvasResource\Pages;

use App\Filament\Resources\CanvasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateCanvas extends CreateRecord
{
    protected static string $resource = CanvasResource::class;
    protected static string $view = 'filament.pages.fabric-page1';

    public $canvas_id;
    public function getHeading(): string|Htmlable
    {
        return '';
    }
    
}
