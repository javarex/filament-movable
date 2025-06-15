<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FabricPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.fabric-page';

    // public function getLayout(): string
    // {
    //     return 'layouts.custom-layout';
    // }
}
