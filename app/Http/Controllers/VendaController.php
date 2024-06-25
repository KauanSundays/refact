<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\ProdutoVenda;
use App\Models\Parcela;

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
        dd($request);
        // Validação dos dados do formulário
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodoPagamento' => 'required|in:parcelamento',
            'parcelas' => 'required|integer|min:1|max:12',
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

        // Verificar se o método de pagamento é parcelamento
        if ($request->metodoPagamento === 'parcelamento') {
            $valorTotalVenda = $this->calcularValorTotal($request->produtosAdicionados);

            // Data inicial de vencimento
            $dataVencimento = now()->addDays(30);

            // Criar as parcelas
            for ($i = 1; $i <= $request->parcelas; $i++) {
                $campoValorParcela = 'valor-parcela-' . $i;

                // Verificar se o campo está presente no request
                if (!$request->has($campoValorParcela)) {
                    return response()->json(['error' => 'Valor da parcela ' . $i . ' não encontrado.'], 400);
                }

                $valorParcelaAtual = $request->input($campoValorParcela);

                // Verificar se o valor da parcela é válido
                if ($valorParcelaAtual <= 0) {
                    return response()->json(['error' => 'Valor da parcela ' . $i . ' inválido.'], 400);
                }

                Parcela::create([
                    'venda_id' => $venda->id,
                    'cliente_id' => $request->cliente_id,
                    'valor' => $valorParcelaAtual,
                    'data_vencimento' => $dataVencimento,
                ]);

                $dataVencimento->addMonths(1); // Adiciona 1 mês para a próxima parcela
            }
        }

        return response()->json(['message' => 'Venda registrada com sucesso!', 'venda' => $venda]);
    }


    /**
     * Função para calcular o valor total dos produtos adicionados à venda.
     *
     * @param array $produtos
     * @return float
     */
    private function calcularValorTotal(array $produtos)
    {
        $total = 0.0;

        foreach ($produtos as $produto) {
            $total += $produto['valor'] * $produto['quantidade'];
        }

        return $total;
    }
}
