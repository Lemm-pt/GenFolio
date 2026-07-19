<div class="container mt-4 main-content">

<!-- core/views/admin/publicacoes.php -->
<div class="container mt-4">
    
    <!-- 🔥 LUXOR - MENSAGEM DA SECÇÃO -->
    <?php include('layouts/luxor_message.php'); ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Publicações (máx 7)</h2>
        <a href="?a=admin_publicacao_criar" class="btn btn-gold">+ Nova Publicação</a>
    </div>
    
    <!-- ... resto do conteúdo ... -->
</div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Publicações (máx 7)</h2>
        <a href="?a=admin_publicacao_criar" class="btn btn-gold">+ Nova Publicação</a>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($publicacoes)): ?>
        <p class="text-muted text-center py-5">Nenhuma publicação. Clique em "+ Nova Publicação" para começar!</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($publicacoes as $p): ?>
                    <tr>
                        <td><?= $p->id ?></td>
                        <td>
                            <?php if($p->imagem): ?>
                                <img src="<?= BASE_URL ?>assets/images/blog/<?= $p->imagem ?>" style="height: 40px; width: 40px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">Sem img</span>
                            <?php endif; ?>
                        <td>
                        <td><?= htmlspecialchars($p->titulo) ?></td>
                        <td><?= $p->publicado ? '<span class="text-success">Publicado</span>' : '<span class="text-secondary">Rascunho</span>' ?></td>
                        <td><?= date('d/m/Y', strtotime($p->created_at)) ?></td>
                        <td>
                            <a href="?a=admin_publicacao_editar&id=<?= $p->id ?>" class="btn btn-sm btn-warning">Editar</a>
                            <button class="btn btn-sm btn-danger" onclick="excluir(<?= $p->id ?>, 'publicacao')">Excluir</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
async function excluir(id, tipo) {
    if(!confirm('Tem certeza?')) return;
    const res = await fetch(`?a=admin_${tipo}_deletar`, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
    });
    const data = await res.json();
    if(data.success) location.reload();
    else alert('Erro ao excluir');
}
</script>