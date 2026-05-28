<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $table = 'assuntos';
    protected $primaryKey = 'CodAs';
    public $timestamps = false;
    protected $fillable = [
        'Descricao',
    ];

    public function livros()
    {
        return $this->belongsToMany(
            Livro::class,
            'livro_assunto',
            'Assunto_CodAs',
            'Livro_CodL'
        );
    }
}
