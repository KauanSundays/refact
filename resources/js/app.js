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
    let parcelas = 1; 
    
    function formatarValor(valor) {
        return parseFloat(valor).toFixed(2);
    }

    document.getElementById('create-produto-form').addEventListener('submit', async function (event) {
        event.preventDefault();
        const nome = document.getElementById('produto-nome').value;
        const valor = document.getElementById('produto-valor-creater').value;

        try {
            const response = await axios.post('/produtos', { nome, valor });
            document.getElementById('produto-nome').value = '';
            document.getElementById('produto-valor-creater').value = '';

            $('#createProdutoModal').modal('hide');
            alert(response.data.message);
            loadProdutos();
        } catch (error) {
            console.error('Erro ao criar produto:', error);
        }
    });
    document.getElementById('create-cliente-form').addEventListener('submit', async function (event) {
        event.preventDefault();

        const cpfInput = document.getElementById('cliente-cpf');
        const cpfValue = cpfInput.value.replace(/\D/g, '');

        if (cpfValue.length !== 11) {
            alert('CPF inválido! O CPF deve conter exatamente 11 números.');
            return;
        }

        const nome = document.getElementById('cliente-nome').value;

        try {
            const cpfCheckResponse = await axios.get(`/api/clientes/check-cpf/${cpfValue}`);
            if (cpfCheckResponse.data.exists) {
                alert('CPF já cadastrado! Insira um CPF diferente.');
                return;
            }

            const createClienteResponse = await axios.post('/clientes', {
                nome: nome,
                cpf: cpfValue,
            });

            console.log('Cliente criado com sucesso:', createClienteResponse.data.cliente);

            document.getElementById('cliente-nome').value = '';
            cpfInput.value = '';
            $('#createClienteModal').modal('hide');

            alert(createClienteResponse.data.message);
            loadClientes();
        } catch (error) {
            console.error('Erro ao criar cliente:', error);
        }
    });

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
        let valoresParcelas = [];
        let valorMedioParcelas = formatarValor(valorParcela);
        let valorTotalParcelas = 0;
    
        for (let i = 1; i <= parcelas; i++) {
            const divParcela = document.createElement('div');
            divParcela.classList.add('mb-3');
    
            let valorInicialParcela = (i === 1) ? valorMedioParcelas : valorParcela;
            let inputParcela = `<strong>Parcela ${i}:</strong> <input type="text" id="valor-parcela-${i}" class="form-control mb-2 valor-parcela" style="width: 100px;" value="${formatarValor(valorInicialParcela)}">`;
            divParcela.innerHTML = inputParcela;
            divParcelas.appendChild(divParcela);
    
            valoresParcelas.push({
                index: i,
                inputId: `valor-parcela-${i}`,
                valor: valorInicialParcela
            });
    
            valorTotalParcelas += parseFloat(valorInicialParcela);
        }
    
        infoParcelas.innerText = `Você está parcelando R$ ${formatarValor(valorTotalVenda)} em ${parcelas} parcelas`;
    
        const parcelasInvalidas = valoresParcelas.filter(parcela => parcela.valor < 0.01);
        if (parcelasInvalidas.length > 0) {
            alert(`Uma ou mais parcelas têm valor inferior a 1 centavo. Ajuste o número de parcelas para garantir que todas tenham pelo menos 1 centavo.`);
            valorMedioParcelas = formatarValor(valorTotalParcelas / parcelas);
            valoresParcelas.forEach(parcela => {
                document.getElementById(parcela.inputId).value = formatarValor(valorMedioParcelas);
            });
        }
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

    // Voltar para a venda
    document.getElementById('voltar-venda').addEventListener('click', function () {
        abaPagamento.style.display = 'none';
        abaVenda.style.display = 'block';
    });
});
