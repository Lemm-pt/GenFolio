<!-- core/views/admin/produto_form.php -->
<div class="container mt-4">
    <h2><?= isset($produto) ? 'Editar Produto' : 'Novo Produto' ?></h2>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Nome do Produto *</label>
            <input type="text" name="nome" class="form-control" value="<?= $produto->nome ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4"><?= $produto->descricao ?? '' ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Preço Normal (€)</label>
                <input type="number" name="preco" class="form-control" step="0.01" value="<?= $produto->preco ?? '' ?>" placeholder="0.00">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Preço Promocional (€)</label>
                <input type="number" name="preco_promocional" class="form-control" step="0.01" value="<?= $produto->preco_promocional ?? '' ?>" placeholder="0.00">
                <small class="text-muted">Deixe vazio se não houver promoção</small>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem do Produto</label>
            <?php if(isset($produto) && $produto->imagem): ?>
               <div class="mb-2">
                   <img src="<?= BASE_URL ?>assets/images/produtos/<?= $produto->imagem ?>" style="height: 100px; object-fit: cover; border-radius: 8px;">
                   <p class="small text-muted">Imagem atual</p>
               </div>
           <?php endif; ?>
            <input type="file" name="imagem" class="form-control" accept="image/*">
            <small>Deixe em branco para manter a imagem atual. Formatos: JPG, PNG, WEBP</small>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ordem de exibição</label>
                <input type="number" name="ordem" class="form-control" value="<?= $produto->ordem ?? 0 ?>">
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-check form-switch mt-4">
                    <input class="form-check-input" type="checkbox" name="destaque" value="1" id="destaqueSwitch" <?= (isset($produto) && $produto->destaque == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="destaqueSwitch">
                        <i class="fas fa-star text-gold"></i> Produto em Destaque / Promoção
                    </label>
                </div>
                <small class="text-muted">Produtos em destaque aparecem na vitrina principal (máx 7)</small>
            </div>
        </div>
        <button type="submit" class="btn btn-gold">Salvar</button>
        <a href="?a=admin_produtos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<style>
    .text-gold { color: #C6A43F; }
    .btn-gold { background: #C6A43F; color: #0a0a1a; border: none; padding: 10px 30px; border-radius: 50px; font-weight: 600; transition: all 0.3s; }
    .btn-gold:hover { background: #d4b96a; color: #0a0a1a; transform: translateY(-2px); }
    .form-switch .form-check-input:checked {
        background-color: #C6A43F;
        border-color: #C6A43F;
    }
</style>