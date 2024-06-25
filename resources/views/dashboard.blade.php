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
            <div class="col-md-10">
                <!-- Aba 1: Venda -->
                <div id="aba-venda">
                    <form id="form-venda">
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
                        <div class="mb-1">
                            <label for="">Quer criar um produto novo?</label>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createProdutoModal">
                                Criar novo Produto
                            </button>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="me-3 mb-2">
                                    <label for="produto-select" class="form-label">Produto</label>
                                    <select class="form-select" name="produto" id="produto-select">
                                        <option value="">Selecione um produto</option>
                                    </select>
                                </div>
                                <div class="me-3 mb-2">
                                    <label for="produto-valor" class="form-label">Valor</label>
                                    <input type="text" class="form-control" id="produto-valor" disabled>
                                </div>
                                <div class="me-3 mb-2">
                                    <label for="produto-quantidade" class="form-label">Quantidade</label>
                                    <input type="number" class="form-control" id="produto-quantidade" min="1" max="99" placeholder="Qtd" value="1" required>
                                </div>                            
                                <div class="me-3 mb-1">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="adicionarProduto()">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="produtos-adicionados" style="display: none;">
                            <h1 class="mb-4">Produtos Adicionados</h1>
                            <p>Valor total da compra: R$ <span id="valor-total"></span></p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Valor</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody id="tabela-produtos-adicionados">
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success" id="btnEnviar">Enviar</button>
                        </div>
                    </form>
                </div>
                
                <div id="aba-pagamento" style="display: none;">
                    <button type="button" class="btn btn-primary mt-3" onclick="voltarParaVenda()">Voltar para Venda</button>
                    <input type="text"style="display: none;" id="valorTotals">
                    <h1 class="mb-4">Formulário de Pagamento</h1>
                    <form id="form-pagamento" class="d-flex flex-column align-items-start">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="metodo-pagamento" class="form-label me-3">Método de Pagamento</label>
                            <select class="form-select mb-3 me-3" id="metodo-pagamento" name="metodoPagamento">
                                <option value="opcao">Selecione uma opção</option>
                                <option value="parcelamento">PARCELAMENTO</option>
                            </select>
                
                            <div id="parcelamento-container" style="display: none;">
                                <div class="mb-4 d-flex align-items-center" id="parcelas-container">
                                    <label for="parcelas" class="form-label me-2">Número de Parcelas</label>
                                    <input type="number" class="form-control mb-8 me-8" id="parcelas" min="1" max="12" value="1" placeholder="Número de parcelas">
                                    <button type="button" class="btn btn-success mb-2" id="btn-enviar-parcelas">Enviar Parcelas</button>
                                </div>
                            </div>
                        </div>
                
                        <div>
                            <div id="info-parcelas"></div>
                        </div>
                    </form>
                
                    <!-- Div para conter as parcelas, abaixo do formulário de pagamento -->
                    <div id="div-parcelas" class="mt-4"></div>
                </div>
                
                
                
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

    <script>
        let primeiroProdutoAdicionado = false;
        let valorTotalVenda = 0;
    
        function adicionarProduto() {
            let valorAdicionado = 0;
    
            const produtoSelect = document.getElementById('produto-select');
            const produtoValorInput = document.getElementById('produto-valor');
            const produtoQuantidadeInput = document.getElementById('produto-quantidade');
            const tabelaProdutosAdicionados = document.getElementById('tabela-produtos-adicionados');
            const divProdutosAdicionados = document.getElementById('produtos-adicionados');
    
            const selectedProductId = produtoSelect.value;
            const selectedProductName = produtoSelect.options[produtoSelect.selectedIndex].text;
            const selectedProductValue = produtoValorInput.value;
            const selectedProductQuantity = parseInt(produtoQuantidadeInput.value) || 1;
    
            let selectedProductValue2 = produtoValorInput.value.replace('R$', '').trim();
            selectedProductValue2 = parseFloat(selectedProductValue2);
            let qtd = parseInt(selectedProductQuantity);
            valorAdicionado = selectedProductValue2 * qtd;
    
            valorTotalVenda += valorAdicionado;
            $('#valor-total').text(valorTotalVenda.toFixed(2)); 
            $('#valorTotals').text(valorTotalVenda.toFixed(2)); 

            if (selectedProductId && tabelaProdutosAdicionados) {
                let produtoExistente = false;
                const rows = tabelaProdutosAdicionados.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    const cellProductName = cells[0].innerText;

                    if (cellProductName === selectedProductName) {
                        const cellProductQuantity = cells[2];
                        const currentQuantity = parseInt(cellProductQuantity.innerText);
                        cellProductQuantity.innerText = currentQuantity + selectedProductQuantity;
                        produtoExistente = true;
                        break;
                    }
                }

                if (!produtoExistente) {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${selectedProductName}</td>
                        <td>${selectedProductValue}</td>
                        <td>${selectedProductQuantity}</td>
                    `;
                    tabelaProdutosAdicionados.appendChild(newRow);
                }

                if (!primeiroProdutoAdicionado) {
                    divProdutosAdicionados.style.display = 'block';
                    primeiroProdutoAdicionado = true;
                }

                produtoValorInput.value = '';
                produtoSelect.value = '';
                produtoQuantidadeInput.value = 1;

                alert(`Produto adicionado com ID: ${selectedProductId}`);
            } else {
                alert('Por favor, selecione um produto antes de adicionar.');
            } 
        }

        function voltarParaVenda() {
            document.getElementById('aba-venda').style.display = 'block';
            document.getElementById('aba-pagamento').style.display = 'none';
        }
    </script>
<script src="resources/js/app.js"></script>
</body>
</html>
