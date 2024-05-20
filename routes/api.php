<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;

// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas pelo Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/restaurantes', [RestauranteController::class, 'index']);
    Route::post('/restaurantes', [RestauranteController::class, 'store']);
    Route::get('/restaurantes/{restaurante}', [RestauranteController::class, 'show']);
    Route::put('/restaurantes/{restaurante}', [RestauranteController::class, 'update']);
    Route::delete('/restaurantes/{restaurante}', [RestauranteController::class, 'destroy']);
    Route::get('/restaurantes/{restaurante}/avaliacoes', [RestauranteController::class, 'avaliacoes']);
    Route::post('/restaurantes/{restaurante}/avaliacoes', [AvaliacaoController::class, 'store']);

});
