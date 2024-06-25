<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Produto;
use App\Models\Cliente;

class VendaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
        ]);

        $venda = Venda::create([
            'cliente_id' => $request->cliente_id,
        ]);

        return response()->json(['message' => 'Venda registrada com sucesso!', 'venda' => $venda]);
    }
}
