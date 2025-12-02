<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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


Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->middleware('auth:sanctum');


require __DIR__.'/auth.php';
