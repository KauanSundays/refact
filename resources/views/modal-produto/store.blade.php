  <div class="modal fade" id="createProdutoModal" tabindex="-1" role="dialog" aria-labelledby="createProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProdutoModalLabel">Criar Novo Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-produto-form">
                    <div class="form-group">
                        <label for="produto-nome">Nome</label>
                        <input type="text" class="form-control" id="produto-nome" required>
                    </div>
                    <div class="form-group">
                        <label for="produto-valor">Valor do Produto</label>
                        <input type="text" class="form-control" id="produto-valor" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>                
            </div>
        </div>
    </div>
</div>