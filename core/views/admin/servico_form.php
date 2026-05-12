<div class="container mt-4">
    <h2><?= isset($servico) ? 'Editar Serviço' : 'Novo Serviço' ?></h2>
    
    <form action="" method="POST" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= $servico->titulo ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" required><?= $servico->descricao ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Ícone (Font Awesome)</label>
            <input type="text" name="icone" class="form-control" value="<?= $servico->icone ?? 'fa-star' ?>" placeholder="Ex: fa-building, fa-chart-line">
            <small><a href="https://fontawesome.com/icons" target="_blank">Ver ícones disponíveis</a></small>
        </div>
        <div class="mb-3">
            <label class="form-label">Ordem</label>
            <input type="number" name="ordem" class="form-control" value="<?= $servico->ordem ?? 0 ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="?a=admin_servicos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>