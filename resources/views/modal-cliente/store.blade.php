    <div class="modal fade" id="createClienteModal" tabindex="-1" role="dialog" aria-labelledby="createClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClienteModalLabel">Criar Novo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-cliente-form">
                        <div class="form-group">
                            <label for="cliente-nome">Nome</label>
                            <input type="text" class="form-control" id="cliente-nome" required>
                        </div>
                        <div class="form-group">
                            <label for="cliente-cpf">CPF</label>
                            <input type="text" class="form-control" id="cliente-cpf" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>                
                </div>
            </div>
        </div>
    </div>
