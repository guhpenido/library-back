<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';
    
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'autor',
        'editora',
        'ano_publicacao',
        'isbn',
        'genero',
        'descricao',
        'imagem'
    ];
}