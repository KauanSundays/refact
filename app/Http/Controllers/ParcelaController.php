<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcela;
use App\Models\Venda;

class ParcelaController extends Controller
{
    public function list($venda_id)
    {
        $venda = Venda::findOrFail($venda_id);
        $parcelas = $venda->parcelas;

        return view('parcelas.lista', compact('venda', 'parcelas'));
    }

    public function deletarParcela($venda_id, $parcela_id)
    {
        $parcela = Parcela::findOrFail($parcela_id);

        if ($parcela->venda_id != $venda_id) {
            abort(404);
        }

        $parcela->delete();

        return redirect()->route('parcelas.lista', ['venda_id' => $venda_id])
            ->with('success', 'Parcela deletada com sucesso.');
    }

    public function atualizarParcela(Request $request, $venda_id, $parcela_id)
    {
        $request->validate([
            'valor' => 'required|numeric',
            'data_vencimento' => 'required|date',
        ]);

        $parcela = Parcela::findOrFail($parcela_id);

        $parcela->valor = $request->valor;
        $parcela->data_vencimento = $request->data_vencimento;
        $parcela->save();

        return redirect()->back()->with('success', 'Parcela atualizada com sucesso!');
    }

    public function detalhesParcela($venda_id, $parcela_id)
    {
        $parcela = Parcela::findOrFail($parcela_id);

        if ($parcela->venda_id != $venda_id) {
            abort(404);
        }

        return view('parcelas.detalhes', compact('parcela'));
    }

    public function atualizarTodasParcelas(Request $request, $venda_id)
    {
        $parcelasAtualizadas = $request->input('parcelas');

        foreach ($parcelasAtualizadas as $parcelaId => $dadosParcela) {
            $parcela = Parcela::find($parcelaId);

            if ($parcela) {
                $valorFormatado = str_replace(',', '.', $dadosParcela['valor']);

                $parcela->valor = $valorFormatado;
                $parcela->data_vencimento = $dadosParcela['data_vencimento'];
                $parcela->save();
            }
        }

        session()->flash('success', 'Parcelas atualizadas com sucesso!');

        return redirect()->route('vendas.mostrarParcelas', ['id' => $venda_id])->with('success', 'Parcelas atualizadas com sucesso!');
    }
}
