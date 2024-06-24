<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="">
                    <h1 class="mb-4">Crie uma Venda</h1>
                    <div class="mb-3">
                        <label for="cliente-select" class="form-label">Clientes</label>
                        <div class="d-flex align-items-center">
                            <select class="form-select me-2" name="cliente" id="cliente-select"></select>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createClienteModal">
                                Criar novo cliente
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="produto-select" class="form-label">Produtos</label>
                        <div class="d-flex align-items-center">
                            <select class="form-select me-2" name="produto" id="produto-select">
                                <option value="">Selecione um produto</option>
                            </select>
                            <input type="text" class="form-control" id="produto-valor" disabled>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createProdutoModal">
                                Criar novo Produto
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    @include('modal-produto.store')
    @include('modal-cliente.store')


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="resources/js/app.js"></script>
</body>
</html>
