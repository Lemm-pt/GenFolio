<div class="container mt-4">
    <h2><?= isset($produto) ? 'Editar Produto' : 'Novo Produto' ?></h2>
    
    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Nome do Produto *</label>
            <input type="text" name="nome" class="form-control" value="<?= $produto->nome ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4"><?= $produto->descricao ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Preço (€)</label>
            <input type="number" name="preco" class="form-control" step="0.01" value="<?= $produto->preco ?? '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem do Produto</label>
            <?php if(isset($produto) && $produto->imagem): ?>
               <div class="mb-2">
                   <img src="<?= BASE_URL ?>assets/images/produtos/<?= $produto->imagem ?>" style="height: 100px; object-fit: cover;">
                   <p class="small text-muted">Imagem atual</p>
               </div>
           <?php endif; ?>
            <input type="file" name="imagem" class="form-control" accept="image/*">
            <small>Deixe em branco para manter a imagem atual. Formatos: JPG, PNG</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Ordem de exibição</label>
            <input type="number" name="ordem" class="form-control" value="<?= $produto->ordem ?? 0 ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="?a=admin_produtos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>