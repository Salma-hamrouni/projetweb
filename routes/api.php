<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ObjectifApiController;
use App\Http\Controllers\API\DataApiController;
use App\Http\Controllers\API\JournalApiController;

// Routes pour les objectifs
Route::get('objectifs', [ObjectifApiController::class, 'index']);
Route::post('objectifs', [ObjectifApiController::class, 'store']);
Route::get('objectifs/{id}', [ObjectifApiController::class, 'show']);
Route::put('objectifs/{id}', [ObjectifApiController::class, 'update']);
Route::delete('objectifs/{id}', [ObjectifApiController::class, 'destroy']);

// Routes pour les données
Route::get('data', [DataApiController::class, 'index']);
Route::post('data', [DataApiController::class, 'store']);
Route::delete('data/{id}', [DataApiController::class, 'destroy']);

// Routes pour les journaux
Route::get('journals', [JournalApiController::class, 'index']);
Route::get('journals/{id}', [JournalApiController::class, 'show']);

// Dans routes/api.php



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
