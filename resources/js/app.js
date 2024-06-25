document.addEventListener('DOMContentLoaded', function () {
    const clienteSelect = document.getElementById('cliente-select');
    const produtoSelect = document.getElementById('produto-select');
    const produtoValorInput = document.getElementById('produto-valor');
    const valorTotalSpan = document.querySelector('#valor-total');
    const valorTotalsInput = document.getElementById('valorTotals');
    const abaVenda = document.getElementById('aba-venda');
    const abaPagamento = document.getElementById('aba-pagamento');
    const metodoPagamentoSelect = document.getElementById('metodo-pagamento');
    const parcelamentoContainer = document.getElementById('parcelamento-container');
    const parcelasContainer = document.getElementById('parcelas-container');
    const infoParcelas = document.getElementById('info-parcelas');
    const btnEnviarParcelas = document.getElementById('btn-enviar-parcelas');
    const divParcelas = document.getElementById('div-parcelas');

    let valorTotalVenda = 0;
    let parcelas = 1; // Variável global para armazenar o número de parcelas

    // Formatação de valores
    function formatarValor(valor) {
        return parseFloat(valor).toFixed(2); // Formata o valor para duas casas decimais com ponto
    }

    // Carregar clientes e produtos
    const loadClientes = async () => {
        try {
            const response = await axios.get('/api/clientes');
            const clientes = response.data;
            clienteSelect.innerHTML = '';
            clientes.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.id;
                option.textContent = cliente.nome;
                clienteSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erro ao carregar clientes:', error);
        }
    };

    const loadProdutos = async () => {
        try {
            const response = await axios.get('/api/produtos');
            const produtos = response.data;
            produtoSelect.innerHTML = '<option value="">Selecione um produto</option>';
            produtos.forEach(produto => {
                const option = document.createElement('option');
                option.value = produto.id;
                option.textContent = produto.nome;
                produtoSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erro ao carregar produtos:', error);
        }
    };

    loadClientes();
    loadProdutos();

    // Máscaras de input
    $(document).ready(function () {
        $('#cliente-cpf').mask('000.000.000-00');
        $('#produto-valor-creater').mask('0000000000.00', { reverse: true });
        $('#qtd').mask('00');
    });

    // Atualizar valor do produto selecionado
    produtoSelect.addEventListener('change', async function () {
        const selectedProdutoId = produtoSelect.value;
        try {
            const response = await axios.get(`/api/produtos/${selectedProdutoId}`);
            const produto = response.data;
            if (produto) {
                produtoValorInput.value = formatarValor(produto.valor);
            } else {
                produtoValorInput.value = '';
            }
        } catch (error) {
            console.error('Erro ao carregar valor do produto:', error);
            produtoValorInput.value = '';
        }
    });

    // Formulário de venda
    document.getElementById('form-venda').addEventListener('submit', function (event) {
        event.preventDefault();
        abaVenda.style.display = 'none';
        abaPagamento.style.display = 'block';
        valorTotalVenda = parseFloat(valorTotalSpan.innerText.replace('R$', '').trim());
    });

    // Método de pagamento
    metodoPagamentoSelect.addEventListener('change', function () {
        const metodoSelecionado = metodoPagamentoSelect.value;
        if (metodoSelecionado === 'parcelamento') {
            parcelamentoContainer.style.display = 'block';
        } else {
            parcelamentoContainer.style.display = 'none';
        }
    });

    // Enviar parcelas
    btnEnviarParcelas.addEventListener('click', function () {
        parcelas = parseInt(document.getElementById('parcelas').value);
        if (isNaN(parcelas) || parcelas < 1 || parcelas > 12) {
            alert('Número de parcelas inválido. Escolha um valor entre 1 e 12.');
            return;
        }
        valorTotalVenda = parseFloat(valorTotalsInput.innerText.replace('R$', '').trim());
        const valorParcela = valorTotalVenda / parcelas;
        divParcelas.innerHTML = '';
        for (let i = 1; i <= parcelas; i++) {
            const divParcela = document.createElement('div');
            divParcela.classList.add('mb-3');
            divParcela.innerHTML = `<strong>Parcela ${i}:</strong> <input type="text" id="valor-parcela-${i}" class="form-control mb-2 valor-parcela" style="width: 100px;" value="${formatarValor(valorParcela)}">`;
            divParcelas.appendChild(divParcela);
        }
        infoParcelas.innerText = `Você está parcelando R$ ${formatarValor(valorTotalVenda)} em ${parcelas} parcelas`;
    });

    // Atualizar parcelas automaticamente
    divParcelas.addEventListener('input', function (event) {
        const target = event.target;
        if (target.classList.contains('valor-parcela')) {
            const novoValor = parseFloat(target.value);
            if (!isNaN(novoValor) && novoValor > 0) {
                const inputsParcelas = Array.from(document.querySelectorAll('.valor-parcela'));
                const index = inputsParcelas.indexOf(target);
                const somaOutrasParcelas = inputsParcelas
                    .filter((input, i) => i !== index)
                    .reduce((acc, input) => acc + parseFloat(input.value), 0);
                const restante = valorTotalVenda - novoValor;
                const valorParcelaRestante = restante / (parcelas - 1);

                inputsParcelas.forEach((input, i) => {
                    if (i !== index) {
                        input.value = formatarValor(valorParcelaRestante);
                    }
                });
            }
        }
    });

    // Formatar valores das parcelas ao digitar
    divParcelas.addEventListener('focusout', function (event) {
        const target = event.target;
        if (target.classList.contains('valor-parcela')) {
            const valor = parseFloat(target.value.replace(',', '.'));
            if (!isNaN(valor)) {
                target.value = formatarValor(valor);
            }
        }
    });

    // Evento de submit para criar novo cliente
    document.getElementById('create-cliente-form').addEventListener('submit', async function (event) {
        event.preventDefault();
        
        const nome = document.getElementById('cliente-nome').value;
        const cpf = document.getElementById('cliente-cpf').value;

        try {
            const response = await axios.post('/api/clientes', {
                nome: nome,
                cpf: cpf
            });

            console.log('Novo cliente criado:', response.data);
            alert('Novo cliente criado com sucesso!');
            
            loadClientes();
            
            $('#createClienteModal').modal('hide');
        } catch (error) {
            console.error('Erro ao criar novo cliente:', error);
            alert('Erro ao criar novo cliente. Verifique o console para mais detalhes.');
        }
    });

    // Evento de submit para criar novo produto
    document.getElementById('create-produto-form').addEventListener('submit', async function (event) {
        event.preventDefault();
        
        const nomeProduto = document.getElementById('produto-nome').value;
        const valorProduto = document.getElementById('produto-valor-creater').value;

        try {
            const response = await axios.post('/api/produtos', {
                nome: nomeProduto,
                valor: parseFloat(valorProduto)
            });

            console.log('Novo produto criado:', response.data);
            alert('Novo produto criado com sucesso!');
            
            loadProdutos();
            
            $('#createProdutoModal').modal('hide');
        } catch (error) {
            console.error('Erro ao criar novo produto:', error);
            alert('Erro ao criar novo produto. Verifique o console para mais detalhes.');
        }
    });

    // Voltar para a venda
    document.getElementById('voltar-venda').addEventListener('click', function () {
        abaPagamento.style.display = 'none';
        abaVenda.style.display = 'block';
    });
});
