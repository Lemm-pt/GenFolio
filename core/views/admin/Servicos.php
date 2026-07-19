<!-- core/views/admin/servicos.php -->
<div class="container mt-4 main-content">


 <!-- 🔥 LUXOR - MENSAGEM DA SECÇÃO -->
    <?php include('layouts/luxor_message.php'); ?>


    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">
        <h2 class="mb-0">Gestão de Serviços</h2>
        <a href="?a=admin_servico_criar" class="btn btn-gold">
            <i class="fas fa-plus"></i> Novo Serviço
        </a>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($servicos)): ?>
        <p class="text-muted text-center py-5">Nenhum serviço cadastrado.</p>
    <?php else: ?>
        <!-- 🔥 TABELA RESPONSIVA -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Ícone</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($servicos as $s): ?>
                    <tr>
                        <td><?= $s->id ?></td>
                        <td><?= htmlspecialchars($s->titulo) ?></td>
                        <td><?= htmlspecialchars(substr($s->descricao, 0, 50)) ?>...</td>
                        <td><i class="fas <?= $s->icone ?>"></i></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="?a=admin_servico_editar&id=<?= $s->id ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger delete-item" data-id="<?= $s->id ?>" data-tipo="servico">
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
        if(!confirm('Tem certeza que deseja excluir este serviço?')) return;
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