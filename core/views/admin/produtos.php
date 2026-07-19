<!-- core/views/admin/produtos.php -->
<div class="container mt-4 main-content">

 <!-- 🔥 LUXOR - MENSAGEM DA SECÇÃO -->
    <?php include('layouts/luxor_message.php'); ?> 

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">
        <h2 class="mb-0">Gestão de Produtos <small class="text-muted">(máx 7)</small></h2>
        <a href="?a=admin_produto_criar" class="btn btn-gold">
            <i class="fas fa-plus"></i> Novo Produto
        </a>
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
        <!-- 🔥 TABELA RESPONSIVA -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $p): ?>
                    <tr>
                        <td><?= $p->id ?></td>
                        <td>
                            <?php if($p->imagem): ?>
                                <img src="<?= BASE_URL ?>assets/images/produtos/<?= $p->imagem ?>" 
                                     style="height: 45px; width: 45px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <span class="text-muted">Sem img</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p->nome) ?></td>
                        <td>€ <?= number_format($p->preco ?? 0, 2, ',', '.') ?></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="?a=admin_produto_editar&id=<?= $p->id ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger delete-item" data-id="<?= $p->id ?>" data-tipo="produto">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
        if(!confirm('Tem certeza que deseja excluir este produto?')) return;
        const tipo = this.dataset.tipo;
        const id = this.dataset.id;
        try {
            const res = await fetch(`?a=admin_${tipo}_deletar`, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            });
            const data = await res.json();
            if(data.success) location.reload();
            else alert('Erro ao excluir');
        } catch(e) {
            alert('Erro ao excluir');
        }
    });
});
</script>