<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parcelas da Venda #{{ $venda->id }}</title>
    <!-- Adicione o link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Parcelas da Venda #{{ $venda->id }}</h2>
        
        @if ($parcelas->isEmpty())
            <p class="alert alert-warning">Nenhuma parcela encontrada para esta venda.</p>
        @else
            <ul class="list-group">
                @foreach ($parcelas as $parcela)
                    <li class="list-group-item">
                        Parcela #{{ $parcela->id }} - Cliente: {{ $parcela->cliente->nome }} - Valor: R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                        <br>
                        Data de Vencimento: {{ $parcela->data_vencimento->format('d/m/Y') }}
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
