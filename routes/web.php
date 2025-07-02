<?php

use App\Http\Controllers\CanvasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/save-html', [CanvasController::class, 'saveHtml']);
Route::get('/canvas/{canvasExport}', [CanvasController::class, 'show']);