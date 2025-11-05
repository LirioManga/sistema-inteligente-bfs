<?php

use App\Http\Controllers\GeminiController;
use App\Http\Controllers\TriagemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::post('/gemini/interpretar', [GeminiController::class, 'interpretar']);
Route::post('/triagem/automatica', [TriagemController::class, 'triagemAutomatica']);