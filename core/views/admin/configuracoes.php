<div class="container mt-4">
    <h2>Configurações do Site</h2>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="?a=admin_salvar_config" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logotipo - Parte 1 (cor normal)</label>
                <input type="text" name="logo_parte1" class="form-control" value="<?= htmlspecialchars($config->get('logo_parte1', 'Seven')) ?>">
                <small class="text-muted">Ex: "Vitrine" – aparecerá em branco</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logotipo - Parte 2 (cor dourada)</label>
                <input type="text" name="logo_parte2" class="form-control" value="<?= htmlspecialchars($config->get('logo_parte2', 'Lux')) ?>">
                <small class="text-muted">Ex: ".lemm" – aparecerá dourado</small>
            </div>
        </div>

        <div class="mb-3">
              <label class="form-label fw-bold">Logotipo - Imagem (opcional)</label>
              <input type="file" name="logo_imagem" class="form-control" accept="image/*">
              <?php if($config->get('logo_imagem')): ?>
                  <div class="mt-2">
                      <img src="<?= BASE_URL ?>assets/images/logos/<?= $config->get('logo_imagem') ?>" style="height: 60px;">
                      <small class="text-muted">Imagem atual. Envie uma nova para substituir.</small>
                  </div>
              <?php endif; ?>
              <small class="text-muted">Se enviar uma imagem, substitui o logotipo textual.</small>
          </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Slogan</label>
            <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas para web ')) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Texto Descritivo (opcional)</label>
            <textarea name="texto_descritivo" class="form-control" rows="3" placeholder="Um texto descritivo que aparece por baixo do slogan..."><?= htmlspecialchars($config->get('texto_descritivo', '')) ?></textarea>
            <small class="text-muted">Este texto aparece na página inicial, logo abaixo do slogan.</small>
       </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($config->get('meta_description', 'SevenLux - Soluções digitais para o seu negócio')) ?></textarea>
            <small class="text-muted">Descrição que aparece nos resultados de busca (max 160 caracteres)</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Keywords (max 7, separadas por vírgula)</label>
            <input type="text" name="meta_keywords" class="form-control" value="<?= htmlspecialchars($config->get('meta_keywords', 'Apps,lemm,digital')) ?>">
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email (recebe contactos)</label>
                <input type="email" name="email_contacto" class="form-control" value="<?= htmlspecialchars($config->get('email_contacto', $_SESSION['usuario'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($config->get('telefone', '')) ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Endereço (para o mapa)</label>
            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($config->get('endereco', '')) ?>">
        </div>


        <!-- No core/views/admin/configuracoes.php, substitua a parte do horário por: -->

<!-- ============================================ -->
<!-- HORÁRIO DE ATENDIMENTO -->
<!-- ============================================ -->
<?php 
$horarioModel = new \core\models\Horario($_SESSION['cliente_id']);
$horarios = $horarioModel->getAll();
?>
<div class="mt-4 pt-3 border-top">
    <h4 class="mb-3"><i class="fas fa-clock"></i> Horário de Atendimento</h4>
    <p class="text-muted small">Defina o horário de funcionamento para cada dia da semana. Use "fechado" para dias sem atendimento.</p>
    
    <div class="row">
        <?php foreach ($horarios as $dia => $horario): ?>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold"><?= $horario['label'] ?></label>
                <div class="row g-2">
                    <div class="col-5">
                        <input type="text" name="horario_<?= $dia ?>" class="form-control" 
                               value="<?= htmlspecialchars($horario['abertura']) ?>" 
                               placeholder="09:00 ou fechado">
                    </div>
                    <div class="col-1 text-center pt-2">—</div>
                    <div class="col-5">
                        <input type="text" name="horario_<?= $dia ?>_fim" class="form-control" 
                               value="<?= htmlspecialchars($horario['fechamento'] ?? '') ?>" 
                               placeholder="18:00">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-2">
        <div class="form-check">
            <input type="hidden" name="mostrar_horario" value="0">
            <input type="checkbox" name="mostrar_horario" class="form-check-input" id="mostrarHorario" value="1" 
                   <?= $config->get('mostrar_horario', '1') == '1' ? 'checked' : '' ?>>
            <label class="form-check-label" for="mostrarHorario">
                <i class="fas fa-eye"></i> Mostrar horário no site
            </label>
        </div>
    </div>
</div>




        
        <div class="mt-4">
            <button type="submit" class="btn btn-gold px-4">💾 Guardar Configurações</button>
            <a href="?a=admin" class="btn btn-secondary px-4">↩️ Voltar</a>
        </div>
    </form>
    
    <div class="mt-5 p-4 bg-light rounded">
        <h4>Pré-visualização do Logotipo</h4>
        <div class="text-center p-4" style="background: #1a1a2e; border-radius: 10px;">
            <h2 class="mb-0">
                <span style="color: white;"><?= htmlspecialchars($config->get('logo_parte1', 'Seven')) ?></span>
                <span style="color: #C6A43F;"><?= htmlspecialchars($config->get('logo_parte2', 'Lux')) ?></span>
            </h2>
            <p class="text-white-50 mt-2"><?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>
        </div>
    </div>
</div>