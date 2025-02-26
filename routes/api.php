<?php

use Illuminate\Http\Request;
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', function () {
    return response()->json(['message' => 'API funcionando!', 'status' => true]);
});


// API's para o CRUD dos livros

Route::get('/livros', [LivroController::class, 'index']);

Route::post('/createmultiple', [LivroController::class, 'createMultiple']);

Route::post('/create', [LivroController::class, 'create']);

Route::get('/livro/{id}', [LivroController::class, 'show']);

Route::put('/update/{id}', [LivroController::class, 'update']);

Route::delete('/delete/{id}', [LivroController::class, 'delete']);