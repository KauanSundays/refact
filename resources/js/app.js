import axios from 'axios';

document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('cliente-select');

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

    loadClientes();

    $(document).ready(function() {
        $('#cliente-cpf').mask('000.000.000-00');
    });

    document.getElementById('create-cliente-form').addEventListener('submit', async function(event) {
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
});
