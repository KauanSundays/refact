<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\ProdutoVenda;

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
            'produtosAdicionados' => 'required|array',
            'produtosAdicionados.*.id' => 'required|exists:produtos,id',
            'produtosAdicionados.*.quantidade' => 'required|integer|min:1',
        ]);

        // Criar a venda principal
        $venda = Venda::create([
            'cliente_id' => $request->cliente_id,
        ]);

        // Adicionar os produtos à tabela produto_venda
        foreach ($request->produtosAdicionados as $produto) {
            ProdutoVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $produto['id'],
                'quantidade' => $produto['quantidade'],
            ]);
        }

        return response()->json(['message' => 'Venda registrada com sucesso!', 'venda' => $venda]);
    }
}
