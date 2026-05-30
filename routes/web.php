<?php

use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\AutorController;
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

Route::controller(AutorController::class)->prefix('autores')->group(function () {
    Route::get('/', 'index')->name('autores.index');
    Route::get('/cadastro', 'create')->name('autores.cadastro');
    Route::post('/cadastrar', 'store')->name('autores.cadastrar');
    Route::get('/editar/{id}', 'edit')->name('autores.editar');
    Route::put('/atualizar/{id}', 'update')->name('autores.atualizar');
    Route::delete('/excluir/{id}', 'destroy')->name('autores.excluir');
});

Route::controller(AssuntoController::class)->prefix('assuntos')->group(function () {
    Route::get('/', 'index')->name('assuntos.index');
    Route::get('/cadastro', 'create')->name('assuntos.cadastro');
    Route::post('/cadastrar', 'store')->name('assuntos.cadastrar');
    Route::get('/editar/{id}', 'edit')->name('assuntos.editar');
    Route::put('/atualizar/{id}', 'update')->name('assuntos.atualizar');
    Route::delete('/excluir/{id}', 'destroy')->name('assuntos.excluir');
});
