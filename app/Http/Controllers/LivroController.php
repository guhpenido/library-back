<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    //como esse é um projeto simples, realizei as operações aqui mesmo no Controller, mas o ideal é que essas operações sejam feitas em um Service

    /*
    * Como isso é apenas para um projeto, não estou utilizando validação profunda de Request, filtros, padronização de respostas, etc.
    */

    //esse método retorna os livros por paginacao
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
        ]);

        $perPage = $request->query('limit', 10); 
        $page = $request->query('page', 1);
        $livros = Livro::paginate($perPage, ['*'], 'page', $page);
        return response()->json($livros);
    }

    //esse método cria um novo livro
    public function create(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'ano_publicacao' => 'required|integer',
            'editora' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:255',
            'genero' => 'sometimes|string|max:255',
            'descricao' => 'sometimes|string',
        ]);

        $livro = Livro::create($request->all());

        return response()->json($livro, 201);
    }
    
    //esse método cria multiplos livros
    public function createMultiple(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'livros' => 'required|array|min:1', 
            'livros.*.titulo' => 'required|string|max:255', 
            'livros.*.autor' => 'required|string|max:255', 
            'livros.*.ano_publicacao' => 'required|integer', 
            'livros.*.editora' => 'sometimes|string|max:255', 
            'livros.*.isbn' => 'sometimes|string|max:255', 
            'livros.*.genero' => 'sometimes|string|max:255', 
            'livros.*.descricao' => 'sometimes|string', 
        ]);

        // Cria os livros em massa
        $livrosData = $validated['livros'];
        $livros = Livro::insert($livrosData);  


        // Retorna os livros criados como resposta
        return response()->json($livros, 201);
    }

    //esse método retorna um livro específico
    public function read($id)
    {
        $livro = Livro::find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        return response()->json($livro);
    }

    //esse método atualiza um livro específico
    public function update(Request $request, $id)
    {
        $livro = Livro::find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'autor' => 'sometimes|string|max:255',
            'ano_publicacao' => 'sometimes|integer',
        ]);

        $livro->update($request->all());

        return response()->json($livro);
    }

    //esse método deleta um livro específico
    public function delete($id)
    {
        $livro = Livro::find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $livro->delete();

        return response()->json(['message' => 'Livro deletado com sucesso']);
    }
}
