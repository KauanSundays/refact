<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|string|unique:produtos,valor',
        ]);

        $produto = Produto::create([
            'nome' => $request->nome,
            'valor' => $request->valor,
        ]);

        return response()->json(['message' => 'Produto criado com sucesso!', 'produto' => $produto]);
    }

    public function show($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            return response()->json($produto);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }
    }
}
