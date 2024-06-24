document.addEventListener('DOMContentLoaded', function () {
    const clienteSelect = document.getElementById('cliente-select');
    const produtoSelect = document.getElementById('produto-select');
    const produtoValorInput = document.getElementById('produto-valor');

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
    document.getElementById('create-cliente-form').addEventListener('submit', async function(event) {
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

    $(document).ready(function () {
        $('#cliente-cpf').mask('000.000.000-00');
        $('#produto-valor-creater').mask('0000000000.00', { reverse: true });
        $('#qtd').mask('00');
    });

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


    function formatarValor(valor) {
        const valorFormatado = parseFloat(valor).toFixed(2);
        return `R$ ${valorFormatado}`;
    };

    document.getElementById('produto-quantidade').addEventListener('input', function (e) {
        if (this.value.length > 2) {
            this.value = this.value.slice(0, 2);
        }
    });
});
