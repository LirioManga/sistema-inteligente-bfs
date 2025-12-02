<?php

use App\Http\Controllers\GeminiController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TriagemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::post('/gemini/interpretar', [GeminiController::class, 'interpretar']);
Route::post('/triagem/automatica', [TriagemController::class, 'triagemAutomatica']);
Route::put('/triagem/{id}/departamento', [TriagemController::class, 'atualizarDepartamento']);
Route::get('/triagem/listar', [TriagemController::class, 'listar']);
Route::post('/pacientes/registar', [PacienteController::class, 'store']);


require __DIR__.'/auth.php';
