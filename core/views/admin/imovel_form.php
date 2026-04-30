<?php $imovel = $imovel ?? null; ?>
<h2><?= $imovel ? 'Editar Imóvel' : 'Novo Imóvel' ?></h2>
<form action="" method="POST">
    <input type="text" name="titulo" class="form-control mb-2" placeholder="Título" value="<?= $imovel->titulo ?? '' ?>" required>
    <textarea name="descricao" class="form-control mb-2" placeholder="Descrição" rows="5"><?= $imovel->descricao ?? '' ?></textarea>
    <input type="number" name="preco" class="form-control mb-2" placeholder="Preço" value="<?= $imovel->preco ?? '' ?>" step="0.01">
    <input type="text" name="localizacao" class="form-control mb-2" placeholder="Localização" value="<?= $imovel->localizacao ?? '' ?>">
    <input type="text" name="tipo" class="form-control mb-2" placeholder="Tipo (ex: Moradia T5)" value="<?= $imovel->tipo ?? '' ?>">
    <select name="status" class="form-control mb-2">
        <option value="disponivel" <?= isset($imovel) && $imovel->status=='disponivel' ? 'selected' : '' ?>>Disponível</option>
        <option value="reservado" <?= isset($imovel) && $imovel->status=='reservado' ? 'selected' : '' ?>>Reservado</option>
        <option value="vendido" <?= isset($imovel) && $imovel->status=='vendido' ? 'selected' : '' ?>>Vendido</option>
    </select>
    <div class="mb-2"><input type="checkbox" name="destaque" <?= isset($imovel) && $imovel->destaque ? 'checked' : '' ?>> Destaque na Home</div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="?a=admin" class="btn btn-secondary">Cancelar</a>
</form>