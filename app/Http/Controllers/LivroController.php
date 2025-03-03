<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use App\Services\RequestService;
use App\Services\FirebaseService;
use Illuminate\Support\Str;

class LivroController extends Controller
{
    protected $requestService;
    protected $firebaseService;

    public function __construct(RequestService $requestService, FirebaseService $firebaseService)
    {
        $this->requestService = $requestService;
        $this->firebaseService = $firebaseService;
    }
    // request de listagem de livros
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
        ]);

        $perPage = $request->query('limit', 10); 
        $page = $request->query('page', 1);
        $livros = $this->requestService->getBooks($perPage, $page);
        return response()->json($livros);
    }

    // request de criacao de livro
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
            'imagem' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $filename = 'images/' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $imageUrl = $this->firebaseService->uploadImage($file, $filename);
            $request['imagem'] = $imageUrl;
        }
        dd($request->all());
        $livro = $this->requestService->createBook($request->all());
        return response()->json($livro, 201);
    }

    // request de criacao de varios livros
    public function createMultiple(Request $request)
    {
        $validated = $request->validate([
            'livros' => 'required|array|min:1', 
            'livros.*.titulo' => 'required|string|max:255', 
            'livros.*.autor' => 'required|string|max:255', 
            'livros.*.ano_publicacao' => 'required|integer', 
            'livros.*.editora' => 'sometimes|string|max:255', 
            'livros.*.isbn' => 'sometimes|string|max:255', 
            'livros.*.genero' => 'sometimes|string|max:255', 
            'livros.*.descricao' => 'sometimes|string', 
            'livros.*.imagem' => 'sometimes|string',
        ]);

        // Criação dos livros em massa
        $livrosData = $validated['livros'];

        foreach ($livrosData as &$livro) {
            // se tiver imagem, envia para o Firebase
            if (isset($livro['imagem'])) {
                $file = $livro['imagem']; 
                $filename = 'images/' . Str::random(10) . '.jpg'; 
                $livro['imagem'] = $this->firebaseService->uploadImage($file, $filename);
            }
        }

        $livros = $this->requestService->createMultipleBooks($livrosData);
        return response()->json($livros, 201);
    }
    // request de leitura de livro
    public function read($id)
    {
        $livro = $this->requestService->getBookById($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        return response()->json($livro);
    }
    // request de atualizar livro
    public function update(Request $request, $id)
    {
        $livro = $this->requestService->getBookById($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'autor' => 'sometimes|string|max:255',
            'ano_publicacao' => 'sometimes|integer',
        ]);

        $this->requestService->updateBook($livro, $request->all());

        return response()->json($livro);
    }
    // request de deletar livro
    public function delete($id)
    {
        $livro = $this->requestService->getBookById($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro não encontrado'], 404);
        }

        $this->requestService->deleteBook($livro);

        return response()->json(['message' => 'Livro deletado com sucesso']);
    }
}
