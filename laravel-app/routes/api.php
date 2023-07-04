<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para determinar si una secuencia es mutante
Route::post('/mutant', 'App\Http\Controllers\MutantController@isMutant');

// Ruta para obtener estadísticas de cadenas analizadas
Route::get('/stats', 'App\Http\Controllers\MutantController@getStats');
