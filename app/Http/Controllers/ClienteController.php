<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:clientes,cpf',
        ]);

        $cliente = Cliente::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
        ]);

        return response()->json(['message' => 'Cliente criado com sucesso!', 'cliente' => $cliente]);
    }
}
