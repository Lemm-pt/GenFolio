<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Serviços</h2>
        <a href="?a=admin_servico_criar" class="btn btn-gold">+ Novo Serviço</a>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($servicos)): ?>
        <p class="text-muted">Nenhum serviço cadastrado.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Título</th><th>Descrição</th><th>Ícone</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php foreach($servicos as $s): ?>
                <tr>
                    <td><?= $s->id ?></td>
                    <td><?= htmlspecialchars($s->titulo) ?></td>
                    <td><?= htmlspecialchars(substr($s->descricao, 0, 50)) ?>...</td>
                    <td><i class="fas <?= $s->icone ?>"></i></td>
                    <td>
                        <a href="?a=admin_servico_editar&id=<?= $s->id ?>" class="btn btn-sm btn-warning">Editar</a>
                        <button class="btn btn-sm btn-danger delete-item" data-id="<?= $s->id ?>" data-tipo="servico">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.delete-item').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza?')) return;
        const tipo = this.dataset.tipo;
        const id = this.dataset.id;
        const res = await fetch(`?a=admin_${tipo}_deletar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}`
        });
        const data = await res.json();
        if(data.success) location.reload();
        else alert('Erro ao excluir');
    });
});
</script>