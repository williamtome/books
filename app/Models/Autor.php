<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autores';
    protected $primaryKey = 'CodAu';
    public $timestamps = false;
    protected $fillable = [
        'Nome',
    ];

    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_autor',
            'Autor_CodAu',
            'Livro_CodL',
            'CodAu',
            'CodL'
        );
    }
}
