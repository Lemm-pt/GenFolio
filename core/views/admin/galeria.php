<div class="container mt-4 main-content">

 <!-- 🔥 LUXOR - MENSAGEM DA SECÇÃO -->
    <?php include('layouts/luxor_message.php'); ?>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Galeria de Fotos (máx 7)</h2>
        <button type="button" class="btn btn-gold" onclick="abrirModalGaleria()">
            <i class="fas fa-plus"></i> Adicionar Foto
        </button>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>
    
    <?php if(empty($galeria)): ?>
        <p class="text-muted text-center py-5">Nenhuma foto na galeria. Clique em "Adicionar Foto" para começar!</p>
    <?php else: ?>
        <div class="row">
            <?php foreach($galeria as $foto): ?>
            <div class="col-md-3 col-sm-4 mb-4">
                <div class="card h-100">
                    <img src="<?= BASE_URL ?>assets/images/galeria/<?= $foto->imagem ?>" class="card-img-top" style="height: 180px; width: 100%; object-fit: cover;">
                    <div class="card-body p-2">
                        <p class="small text-muted"><?= htmlspecialchars($foto->legenda ?? 'Sem legenda') ?></p>
                        <button class="btn btn-sm btn-danger w-100" onclick="excluirFoto(<?= $foto->id ?>, 'galeria')">Excluir</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para adicionar foto -->
<div id="modalGaleria" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 500px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <h3>Adicionar Foto</h3>
            <button onclick="fecharModalGaleria()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form action="?a=admin_galeria_criar" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Imagem *</label>
                <input type="file" name="imagem" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Legenda (opcional)</label>
                <input type="text" name="legenda" class="form-control" placeholder="Descrição da imagem">
            </div>
            <button type="submit" class="btn btn-gold" style="width: 100%;">Adicionar</button>
        </form>
    </div>
</div>

<script>
function abrirModalGaleria() {
    document.getElementById('modalGaleria').style.display = 'block';
}
function fecharModalGaleria() {
    document.getElementById('modalGaleria').style.display = 'none';
}
async function excluirFoto(id, tipo) {
    if(!confirm('Tem certeza que deseja excluir esta foto?')) return;
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