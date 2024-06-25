<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ProdutoVenda;
use App\Models\Parcela;
use Carbon\Carbon;

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
            'metodoPagamento' => 'required|in:parcelamento',
            'parcelas' => 'required|integer|min:1|max:12',
            'produtosAdicionados' => 'required|array',
            'produtosAdicionados.*.id' => 'required|exists:produtos,id',
            'produtosAdicionados.*.quantidade' => 'required|integer|min:1',
            'valoresParcelas' => 'required|array|min:' . $request->parcelas,
        ]);

        $venda = Venda::create([
            'cliente_id' => $request->cliente_id,
        ]);

        foreach ($request->produtosAdicionados as $produto) {
            ProdutoVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $produto['id'],
                'quantidade' => $produto['quantidade'],
            ]);
        }

        if ($request->metodoPagamento === 'parcelamento') {
            $dataVencimento = Carbon::now()->addDays(30);

            for ($i = 0; $i < $request->parcelas; $i++) {
                $valorParcela = $request->valoresParcelas[$i];

                Parcela::create([
                    'venda_id' => $venda->id,
                    'cliente_id' => $request->cliente_id,
                    'valor' => $valorParcela,
                    'data_vencimento' => $dataVencimento,
                ]);

                $dataVencimento->addMonths(1);
            }
        }

        return response()->json(['message' => 'Venda registrada com sucesso!', 'venda' => $venda]);
    }

    public function list()
    {
        $vendas = Venda::with('parcelas', 'cliente')->get();

        foreach ($vendas as $venda) {
            $venda->valor_total_parcelas = $venda->parcelas->sum('valor');
        }

        return view('vendas.lista', compact('vendas'));
    }

    public function edit($id)
    {
        $venda = Venda::findOrFail($id);
        return view('vendas.editar', compact('venda'));
    }

    public function parcelas($id)
    {
        $venda = Venda::findOrFail($id);
        $parcelas = $venda->parcelas;

        return view('vendas.parcelas', compact('venda', 'parcelas'));
    }

    public function mostrarParcelas($id)
    {
        $venda = Venda::findOrFail($id);
        $parcelas = $venda->parcelas;

        return view('vendas.mostrar_parcelas', compact('venda', 'parcelas'));
    }
}
