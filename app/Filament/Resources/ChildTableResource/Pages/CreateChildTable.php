<?php

namespace App\Filament\Resources\ChildTableResource\Pages;

use App\Filament\Resources\ChildTableResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChildTable extends CreateRecord
{
    protected static string $resource = ChildTableResource::class;
}
