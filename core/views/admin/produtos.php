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
        
        <!-- 🔥 PRODUTOS EM DESTAQUE -->
        <?php 
        $produtosDestaque = array_filter($produtos, function($p) { return $p->destaque == 1; });
        $produtosNormais = array_filter($produtos, function($p) { return $p->destaque == 0 || $p->destaque === null; });
        ?>
        
        <?php if(!empty($produtosDestaque)): ?>
        <div class="mb-4">
            <h5 class="text-gold mb-3"><i class="fas fa-star"></i> Produtos em Destaque / Promoção</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço Normal</th>
                            <th>Preço Promo</th>
                            <th>Ordem</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtosDestaque as $p): ?>
                        <tr class="table-gold-light">
                            <td><?= $p->id ?></td>
                            <td>
                                <?php if($p->imagem): ?>
                                    <img src="<?= BASE_URL ?>assets/images/produtos/<?= $p->imagem ?>" 
                                         style="height: 45px; width: 45px; object-fit: cover; border-radius: 6px;">
                                <?php else: ?>
                                    <span class="text-muted">Sem img</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($p->nome) ?></strong> <span class="badge bg-gold text-dark ms-2"><i class="fas fa-star"></i> Destaque</span></td>
                            <td>€ <?= number_format($p->preco ?? 0, 2, ',', '.') ?></td>
                            <td>
                                <?php if($p->preco_promocional): ?>
                                    <span class="text-success fw-bold">€ <?= number_format($p->preco_promocional, 2, ',', '.') ?></span>
                                    <span class="badge bg-danger ms-1">PROMO</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $p->ordem ?? 0 ?></td>
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
        </div>
        <?php endif; ?>
        
        <!-- 🔥 PRODUTOS NORMAIS -->
        <?php if(!empty($produtosNormais)): ?>
        <div>
            <h5 class="text-muted mb-3"><i class="fas fa-list"></i> Todos os Produtos</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Ordem</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtosNormais as $p): ?>
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
                            <td><?= $p->ordem ?? 0 ?></td>
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
        </div>
        <?php endif; ?>
        
    <?php endif; ?>
</div>

<style>
    .text-gold { color: #C6A43F; }
    .bg-gold { background-color: #C6A43F; }
    .table-gold-light { background: rgba(198, 164, 63, 0.05) !important; }
    .table-gold-light:hover { background: rgba(198, 164, 63, 0.12) !important; }
    .btn-gold { background: #C6A43F; color: #0a0a1a; border: none; }
    .btn-gold:hover { background: #d4b96a; color: #0a0a1a; }
    .badge.bg-gold { background: #C6A43F !important; color: #0a0a1a !important; }
    
    .table-modern thead th {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.8rem 1rem;
        border: none;
    }
    
    .table-modern thead th:first-child { border-radius: 8px 0 0 8px; }
    .table-modern thead th:last-child { border-radius: 0 8px 8px 0; }
</style>

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