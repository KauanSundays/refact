<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcela;
use App\Models\Venda;

class ParcelaController extends Controller
{
    // Método para listar as parcelas de uma venda
    public function list($venda_id)
    {
        $venda = Venda::findOrFail($venda_id);
        $parcelas = $venda->parcelas;

        return view('parcelas.lista', compact('venda', 'parcelas'));
    }

    // Método para deletar uma parcela
    public function deletarParcela($venda_id, $parcela_id)
    {
        // Encontrar a parcela pelo ID
        $parcela = Parcela::findOrFail($parcela_id);

        // Verificar se a parcela pertence à venda especificada
        if ($parcela->venda_id != $venda_id) {
            abort(404); // Caso a parcela não pertença à venda, retornar erro 404
        }

        // Deletar a parcela
        $parcela->delete();

        // Redirecionar de volta para a lista de parcelas da venda
        return redirect()->route('parcelas.lista', ['venda_id' => $venda_id])
            ->with('success', 'Parcela deletada com sucesso.');
    }

    public function atualizarParcela(Request $request, $venda_id, $parcela_id)
    {
        // Validação dos dados do formulário de atualização
        $request->validate([
            'valor' => 'required|numeric',
            'data_vencimento' => 'required|date',
        ]);

        // Encontrar a parcela pelo ID
        $parcela = Parcela::findOrFail($parcela_id);

        // Atualizar os dados da parcela
        $parcela->valor = $request->valor;
        $parcela->data_vencimento = $request->data_vencimento;
        $parcela->save();

        // Redirecionar de volta à página de parcelas ou retornar uma resposta JSON, se necessário
        return redirect()->back()->with('success', 'Parcela atualizada com sucesso!');
    }

    public function detalhesParcela($venda_id, $parcela_id)
    {
        // Encontrar a parcela pelo ID
        $parcela = Parcela::findOrFail($parcela_id);

        // Verificar se a parcela pertence à venda especificada
        if ($parcela->venda_id != $venda_id) {
            abort(404); // Caso a parcela não pertença à venda, retornar erro 404
        }

        // Retornar a view com os detalhes da parcela
        return view('parcelas.detalhes', compact('parcela'));
    }

    public function atualizarTodasParcelas(Request $request, $venda_id)
{
    $parcelasAtualizadas = $request->input('parcelas');

    foreach ($parcelasAtualizadas as $parcelaId => $dadosParcela) {
        // Encontra a parcela pelo ID
        $parcela = Parcela::find($parcelaId);

        if ($parcela) {
            // Formata o valor para o formato correto (ponto como separador decimal)
            $valorFormatado = str_replace(',', '.', $dadosParcela['valor']);

            // Atualiza o valor da parcela
            $parcela->valor = $valorFormatado;
            
            // Atualiza a data de vencimento da parcela
            $parcela->data_vencimento = $dadosParcela['data_vencimento'];
            
            // Salva as mudanças
            $parcela->save();
        }
    }

    // Redireciona de volta para a página das parcelas da venda
    return redirect()->route('vendas.mostrarParcelas', ['id' => $venda_id])->with('success', 'Parcelas atualizadas com sucesso!');
}

}
