<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Vendas</title>
    <!-- Adicione o link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Lista de Vendas</h2>
        
        @if ($vendas->isEmpty())
            <p class="alert alert-warning">Nenhuma venda encontrada.</p>
        @else
            <ul class="list-group">
                @foreach ($vendas as $venda)
                    <li class="list-group-item">
                        Venda #{{ $venda->id }} - Cliente: {{ $venda->cliente->nome }} - Total Parcelas: R$ {{ number_format($venda->valor_total_parcelas, 2, ',', '.') }}
                        <br>
                        Criado em: {{ $venda->created_at->format('d/m/Y H:i:s') }}
                        <a href="{{ route('vendas.mostrarParcelas', $venda->id) }}" class="btn btn-primary btn-sm float-right">Editar essa venda</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Adicione o link para o jQuery (necessário para o Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Adicione o link para o Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
