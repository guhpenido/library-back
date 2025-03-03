<?php

namespace App\Services;

use App\Models\Livro;

class RequestService
{
    public function getBooks($perPage, $page)
    {
        return Livro::paginate($perPage, ['*'], 'page', $page);
    }

    // metodo para criar um livro
    public function createBook($data)
    {
        return Livro::create($data);
    }

    // metodo para criar varios livros (usado pra popular o bd)
    public function createMultipleBooks($livrosData)
    {
        return Livro::insert($livrosData);
    }

    // metodo para buscar um livro específico
    public function getBookById($id)
    {
        return Livro::find($id);
    }

    // metodo para atualizar um livro específico
    public function updateBook(Livro $livro, $data)
    {
        return $livro->update($data);
    }

    // metodo para deletar um livro específico
    public function deleteBook(Livro $livro)
    {
        return $livro->delete();
    }
}
