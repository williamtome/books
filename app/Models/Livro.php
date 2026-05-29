<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    protected $table = 'livros';
    protected $primaryKey = 'CodL';
    public $timestamps = false;
    protected $fillable = [
        'Titulo',
        'Editora',
        'Edicao',
        'AnoPublicacao'
    ];

    public function autores()
    {
        return $this->belongsToMany(
            Autor::class,
            'livro_autor',
            'Livro_CodL',
            'Autor_CodAu',
            'CodL',
            'CodAu'
        );
    }

    public function assuntos()
    {
        return $this->belongsToMany(
            Assunto::class,
            'livro_assunto',
            'Livro_CodL',
            'Assuno_CodAs',
            'CodL',
            'CodAs'
        );
    }
}
