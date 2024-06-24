document.addEventListener('DOMContentLoaded', function () {
    const clienteSelect = document.getElementById('cliente-select');
    const produtoSelect = document.getElementById('produto-select');
    const produtoValorInput = document.getElementById('produto-valor');

    document.getElementById('create-cliente-form').addEventListener('submit', function (event) {
        const cpfInput = document.getElementById('cliente-cpf');
        const cpfValue = cpfInput.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

        if (cpfValue.length !== 11) {
            alert('CPF inválido! Por favor, insira todos os números do CPF.');
            event.preventDefault(); // Impede o envio do formulário se o CPF não estiver completo
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
        $('#produto-valor').mask('0000000000.00', { reverse: true });
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

    document.getElementById('create-cliente-form').addEventListener('submit', async function (event) {
        event.preventDefault();
        
        const nome = document.getElementById('cliente-nome').value;
        const cpf = document.getElementById('cliente-cpf').value;

        try {
            const response = await axios.post('/clientes', { nome, cpf });
            $('#createClienteModal').modal('hide');
            alert(response.data.message);
            loadClientes();
        } catch (error) {
            console.error('Erro ao criar cliente:', error);
        }
    });

    function formatarValor(valor) {
        const valorFormatado = parseFloat(valor).toFixed(2);
        return `R$ ${valorFormatado}`;
    }
});
