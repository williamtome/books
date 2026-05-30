<?php

use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::controller(LivroController::class)->prefix('livros')->group(function () {
    Route::get('/', 'index')->name('livros.index');
    Route::get('/cadastro', 'create')->name('livros.cadastro');
    Route::post('/cadastrar', 'store')->name('livros.cadastrar');
    Route::get('/editar/{id}', 'edit')->name('livros.editar');
    Route::put('/atualizar/{id}', 'update')->name('livros.atualizar');
    Route::delete('/excluir/{id}', 'destroy')->name('livros.excluir');
});
