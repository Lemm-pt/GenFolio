<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Produtos (máx 7)</h2>
        <a href="?a=admin_produto_criar" class="btn btn-gold">+ Novo Produto</a>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($produtos)): ?>
        <p class="text-muted text-center py-5">Nenhum produto cadastrado. Clique em "+ Novo Produto" para adicionar.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $p): ?>
                    <tr>
                        <td><?= $p->id ?></td>
                       <td>
                           <?php if($p->imagem): ?>
                               <img src="<?= BASE_URL ?>assets/images/produtos/<?= $p->imagem ?>" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;">
                           <?php else: ?>
                               <span class="text-muted">Sem imagem</span>
                           <?php endif; ?>
                       </td>
                        <td><?= htmlspecialchars($p->nome) ?></td>
                        <td>€ <?= number_format($p->preco ?? 0, 2, ',', '.') ?></td>
                        <td>
                            <a href="?a=admin_produto_editar&id=<?= $p->id ?>" class="btn btn-sm btn-warning">Editar</a>
                            <button class="btn btn-sm btn-danger delete-item" data-id="<?= $p->id ?>" data-tipo="produto">Excluir</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.delete-item').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que deseja excluir?')) return;
        const tipo = this.dataset.tipo;
        const id = this.dataset.id;
        try {
            const res = await fetch(`?a=admin_${tipo}_deletar`, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            });
            const data = await res.json();
            if(data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir');
            }
        } catch(e) {
            alert('Erro ao excluir');
        }
    });
});
</script>