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
        <!-- 🔥 TABELA RESPONSIVA - DESIGN MODERNO -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-modern">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-title">Título</th>
                        <th class="col-desc d-none d-md-table-cell">Descrição</th>
                        <th class="col-icon text-center">Ícone</th>
                        <th class="col-actions text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($servicos as $s): ?>
                    <tr>
                        <td class="col-id align-middle">
                            <span class="badge bg-dark-subtle text-dark fw-normal">#<?= $s->id ?></span>
                        </td>
                        <td class="col-title align-middle">
                            <strong><?= htmlspecialchars($s->titulo) ?></strong>
                            <!-- Descrição em mobile (visível apenas em telas pequenas) -->
                            <div class="d-block d-md-none text-muted small mt-1">
                                <?= htmlspecialchars(substr($s->descricao, 0, 60)) ?>...
                            </div>
                        </td>
                        <td class="col-desc d-none d-md-table-cell align-middle">
                            <?= htmlspecialchars(substr($s->descricao, 0, 60)) ?>...
                        </td>
                        <td class="col-icon text-center align-middle">
                            <i class="fas <?= $s->icone ?> fa-lg text-gold" style="font-size: 1.3rem;"></i>
                        </td>
                        <td class="col-actions text-end align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="?a=admin_servico_editar&id=<?= $s->id ?>" 
                                   class="btn btn-outline-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger delete-item" 
                                        data-id="<?= $s->id ?>" 
                                        data-tipo="servico"
                                        title="Excluir">
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

<!-- 🔥 ESTILOS ADICIONAIS -->
<style>
    /* Cores Luxor */
    .text-gold {
        color: #c9a84c;
    }
    .bg-gold {
        background-color: #c9a84c;
    }
    .btn-outline-warning {
        color: #c9a84c;
        border-color: #c9a84c;
    }
    .btn-outline-warning:hover {
        background-color: #c9a84c;
        color: #fff;
        border-color: #c9a84c;
    }
    
    /* Tabela moderna */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 0.3rem;
    }
    
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
    
    .table-modern thead th:first-child {
        border-radius: 8px 0 0 8px;
    }
    
    .table-modern thead th:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .table-modern tbody tr {
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.15);
        transform: translateY(-2px);
    }
    
    .table-modern tbody td {
        padding: 0.8rem 1rem;
        border: none;
        vertical-align: middle;
        background: transparent;
    }
    
    .table-modern tbody td:first-child {
        border-radius: 8px 0 0 8px;
    }
    
    .table-modern tbody td:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .table-modern .badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.7rem;
        background: #f0e8d8 !important;
        color: #6b5b3a !important;
    }
    
    .table-modern .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
        border-width: 1px;
    }
    
    .table-modern .btn-group .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .table-modern .btn-group .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    
    /* Estatísticas */
    .display-6 {
        font-size: 2rem;
        line-height: 1.2;
    }
    
    /* Ajustes mobile */
    @media (max-width: 767.98px) {
        .table-modern thead th {
            font-size: 0.7rem;
            padding: 0.5rem 0.6rem;
        }
        .table-modern tbody td {
            padding: 0.6rem 0.6rem;
            font-size: 0.85rem;
        }
        .col-id {
            width: 50px !important;
        }
        .col-title {
            min-width: 120px !important;
        }
        .col-icon {
            width: 50px !important;
        }
        .col-actions {
            width: 90px !important;
        }
        .display-6 {
            font-size: 1.5rem;
        }
        .fa-lg {
            font-size: 1rem !important;
        }
    }
    
    @media (max-width: 575.98px) {
        .table-modern tbody td {
            padding: 0.5rem 0.4rem;
            font-size: 0.8rem;
        }
        .col-actions .btn-group .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
        }
    }
</style>

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