<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Modules\ProgramaController;
use App\Http\Controllers\Modules\ProgramadetalleController;
use App\Http\Controllers\Modules\CursoController;
use App\Http\Controllers\Modules\CursodetalleController;
use App\Http\Controllers\Modules\CompetenciaController;
use App\Http\Controllers\Modules\ObjetivoController;
use App\Http\Controllers\Modules\GraficaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'getIndex']);

Route::get('/objetivo', [ObjetivoController::class, 'getIndex']);
Route::get('/programa', [ProgramaController::class, 'getIndex']);
Route::get('/curso', [CursoController::class, 'getIndex']);
Route::get('/grafica', [GraficaController::class, 'getIndex']);
Route::get('/cursoDetalle/{id}', [CursodetalleController::class, 'getIndex']);
Route::get('/cursoDetalleShow/{id}', [CursodetalleController::class, 'show']);
Route::get('/competencia', [CompetenciaController::class, 'getIndex']);
Route::get('/programaDetalle/{id}', [ProgramadetalleController::class, 'getIndex']);
