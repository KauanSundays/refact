<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parcelas da Venda #{{ $venda->id }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Parcelas da Venda #{{ $venda->id }}</h2>
        <a href="{{ route('vendas.lista') }}" class="ms-3">
            <x-primary-button>
                {{ __('ir para vendas') }}
            </x-primary-button>
        </a>
        @if ($parcelas->isEmpty())
            <p class="alert alert-warning">Nenhuma parcela encontrada para esta venda.</p>
        @else
            <form id="formAtualizarParcelas" action="{{ route('parcelas.atualizarTodos', ['venda_id' => $venda->id]) }}" method="POST">
                @csrf
                <ul class="list-group">
                    @foreach ($parcelas as $parcela)
                        <li class="list-group-item">
                            Parcela #{{ $parcela->id }} - Cliente: {{ $parcela->cliente->nome }} - 
                            <input type="text" class="form-control valor-parcela" data-id="{{ $parcela->id }}" name="parcelas[{{ $parcela->id }}][valor]" value="{{ $parcela->valor }}" onchange="atualizarValorParcela(this)">
                            <br>
                            <input type="date" class="form-control data-vencimento" data-id="{{ $parcela->id }}" name="parcelas[{{ $parcela->id }}][data_vencimento]" value="{{ $parcela->data_vencimento }}" onchange="atualizarDataVencimento(this)">
                        </li>
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-primary mt-3">Atualizar Parcelas</button>
            </form>
            <!-- Div para exibir mensagem de sucesso -->
            <div id="mensagem" class="mt-3 alert alert-success" style="display: none;"></div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputsValorParcela = document.querySelectorAll('.valor-parcela');
            const inputsDataVencimento = document.querySelectorAll('.data-vencimento');
            const valorTotalVenda = calcularValorTotalVenda();
            const parcelas = inputsValorParcela.length; 
            
            inputsValorParcela.forEach(input => {
                input.addEventListener('input', function(event) {
                    const target = event.target;
                    const idParcela = target.getAttribute('data-id');
                    const novoValor = parseFloat(target.value.replace(',', '.'));
                    
                    if (!isNaN(novoValor) && novoValor >= 0) {
                        const index = Array.from(inputsValorParcela).indexOf(target);
                        const somaOutrasParcelas = Array.from(inputsValorParcela)
                            .filter((input, i) => i !== index)
                            .reduce((acc, input) => acc + parseFloat(input.value.replace(',', '.')), 0);
                        const restante = valorTotalVenda - novoValor;
                        const valorParcelaRestante = restante / (parcelas - 1);

                        inputsValorParcela.forEach((input, i) => {
                            if (i !== index) {
                                input.value = formatarValor(valorParcelaRestante);
                            }
                        });

                    }
                });
            });

            inputsDataVencimento.forEach(input => {
                input.addEventListener('input', function(event) {
                    const target = event.target;
                    const idParcela = target.getAttribute('data-id');
                    const novaData = target.value;
                });
            });
        });

        function formatarValor(valor) {
            return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function calcularValorTotalVenda() {
            const inputsParcelas = document.querySelectorAll('.valor-parcela');
            let total = 0;
            inputsParcelas.forEach(input => {
                total += parseFloat(input.value.replace(',', '.'));
            });
            return total;
        }

        function atualizarValorParcela(element) {
            const idParcela = element.getAttribute('data-id');
            const novoValor = element.value.replace(',', '.'); 

            const formData = new FormData();
            formData.append('parcelas[' + idParcela + '][valor]', novoValor);

            fetch('/vendas/{{ $venda->id }}/parcelas/' + idParcela + '/atualizar-valor', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ocorreu um erro ao atualizar o valor da parcela.');
                }
                return response.json();
            })
            
        }

        function atualizarDataVencimento(element) {
            const idParcela = element.getAttribute('data-id');
            const novaData = element.value;

            const formData = new FormData();
            formData.append('parcelas[' + idParcela + '][data_vencimento]', novaData);

            fetch('/vendas/{{ $venda->id }}/parcelas/' + idParcela + '/atualizar-data-vencimento', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ocorreu um erro ao atualizar a data de vencimento da parcela.');
                }
                return response.json();
            })
            .then(data => {
            })
            .catch(error => {
            });
        }

        function exibirMensagem(mensagem) {
            const divMensagem = document.getElementById('mensagem');
            divMensagem.textContent = mensagem;
            divMensagem.style.display = 'block';
            setTimeout(() => {
                divMensagem.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
