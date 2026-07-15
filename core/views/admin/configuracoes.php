<div class="container-fluid config-container">
    <div class="config-header mb-4">
        <h2><i class="fas fa-cog text-gold"></i> Configurações do Site</h2>
        <p class="text-muted small">Gerir logotipo, conteúdo e informações de contacto</p>
    </div>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="?a=admin_salvar_config" enctype="multipart/form-data">
        
        <!-- ============================================ -->
        <!-- SECÇÃO: LOGOTIPO -->
        <!-- ============================================ -->
        <div class="config-section">
            <h5 class="section-title"><i class="fas fa-font text-gold"></i> Logotipo</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Parte 1 (cor normal)</label>
                    <input type="text" name="logo_parte1" class="form-control" 
                           value="<?= htmlspecialchars($config->get('logo_parte1', 'Seven')) ?>"
                           placeholder="Ex: Vitrine">
                    <small class="text-muted">Aparecerá em branco</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Parte 2 (cor dourada)</label>
                    <input type="text" name="logo_parte2" class="form-control" 
                           value="<?= htmlspecialchars($config->get('logo_parte2', 'Lux')) ?>"
                           placeholder="Ex: .lemm">
                    <small class="text-muted">Aparecerá em dourado</small>
                </div>
            </div>
            
            <div class="mt-3">
                <label class="form-label">Imagem do Logotipo <small class="text-muted">(opcional)</small></label>
                <input type="file" name="logo_imagem" class="form-control" accept="image/*">
                
                <?php if($config->get('logo_imagem')): ?>
                    <div class="mt-2 d-flex align-items-center gap-3">
                        <img src="<?= BASE_URL ?>assets/images/logos/<?= $config->get('logo_imagem') ?>" 
                             alt="Logo atual" style="height: 40px; width: auto; border-radius: 6px; border: 1px solid #ddd; padding: 4px;">
                        <small class="text-muted">Imagem atual. Envie uma nova para substituir.</small>
                    </div>
                <?php endif; ?>
                <small class="text-muted">Se enviar uma imagem, substitui o logotipo textual.</small>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECÇÃO: CONTEÚDO -->
        <!-- ============================================ -->
        <div class="config-section">
            <h5 class="section-title"><i class="fas fa-pen-fancy text-gold"></i> Conteúdo</h5>
            
            <div class="mb-3">
                <label class="form-label">Slogan</label>
                <input type="text" name="slogan" class="form-control" 
                       value="<?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas para web')) ?>"
                       placeholder="O slogan do seu negócio">
            </div>

            <div class="mb-3">
                <label class="form-label">Texto Descritivo <small class="text-muted">(opcional)</small></label>
                <textarea name="texto_descritivo" class="form-control" rows="3" 
                          placeholder="Um texto descritivo que aparece por baixo do slogan..."><?= htmlspecialchars($config->get('texto_descritivo', '')) ?></textarea>
                <small class="text-muted">Este texto aparece na página inicial, logo abaixo do slogan.</small>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECÇÃO: SEO -->
        <!-- ============================================ -->
        <div class="config-section">
            <h5 class="section-title"><i class="fas fa-search text-gold"></i> SEO</h5>
            
            <div class="mb-3">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2" 
                          placeholder="Descrição que aparece nos resultados de busca (max 160 caracteres)"><?= htmlspecialchars($config->get('meta_description', 'SevenLux - Soluções digitais para o seu negócio')) ?></textarea>
                <small class="text-muted">Descrição que aparece nos resultados de busca (max 160 caracteres)</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Keywords <small class="text-muted">(max 7, separadas por vírgula)</small></label>
                <input type="text" name="meta_keywords" class="form-control" 
                       value="<?= htmlspecialchars($config->get('meta_keywords', 'Apps,lemm,digital')) ?>"
                       placeholder="Ex: web, design, marketing">
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECÇÃO: CONTACTO -->
        <!-- ============================================ -->
        <div class="config-section">
            <h5 class="section-title"><i class="fas fa-address-book text-gold"></i> Contacto</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email <small class="text-muted">(recebe contactos)</small></label>
                    <input type="email" name="email_contacto" class="form-control" 
                           value="<?= htmlspecialchars($config->get('email_contacto', $_SESSION['usuario'] ?? '')) ?>"
                           placeholder="exemplo@email.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" 
                           value="<?= htmlspecialchars($config->get('telefone', '')) ?>"
                           placeholder="+351 912 345 678">
                </div>
            </div>
            
            <div class="mt-3">
                <label class="form-label">Endereço <small class="text-muted">(para o mapa)</small></label>
                <input type="text" name="endereco" class="form-control" 
                       value="<?= htmlspecialchars($config->get('endereco', '')) ?>"
                       placeholder="Ex: Lisboa, Portugal">
            </div>
        </div>

       <!-- ============================================ -->
<!-- SECÇÃO: HORÁRIO -->
<!-- ============================================ -->
<?php 
$horarioModel = new \core\models\Horario($_SESSION['cliente_id']);
$horarios = $horarioModel->getAll();
$horarioAtivo = $horarioModel->isAtivo();
?>
<div class="config-section">
    <h5 class="section-title"><i class="fas fa-clock text-gold"></i> Horário de Atendimento</h5>
    <p class="text-muted small">Defina o horário de funcionamento para cada dia da semana. Use "fechado" para dias sem atendimento.</p>
    
    <!-- 🔥 Checkbox para ativar/desativar horário -->
    <div class="mb-3 p-3" style="background: rgba(198, 164, 63, 0.05); border-radius: 8px; border: 1px solid rgba(198, 164, 63, 0.1);">
        <div class="form-check form-switch">
            <input type="hidden" name="horario_ativo" value="0">
            <input type="checkbox" name="horario_ativo" class="form-check-input" id="horarioAtivo" value="1" 
                   style="width: 40px; height: 20px; cursor: pointer;"
                   <?= $horarioAtivo ? 'checked' : '' ?>>
            <label class="form-check-label fw-bold" for="horarioAtivo">
                <i class="fas <?= $horarioAtivo ? 'fa-eye text-success' : 'fa-eye-slash text-danger' ?>"></i>
                <?= $horarioAtivo ? 'Horário visível no site' : 'Horário oculto no site' ?>
            </label>
        </div>
        <small class="text-muted">Desative para ocultar completamente a secção de horário no site.</small>
    </div>
    
    <div class="row g-2" id="horariosContainer" <?= !$horarioAtivo ? 'style="opacity: 0.5; pointer-events: none;"' : '' ?>>
        <?php foreach ($horarios as $dia => $horario): ?>
            <div class="col-md-6 col-lg-4">
                <label class="form-label small fw-bold"><?= $horario['label'] ?></label>
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" name="horario_<?= $dia ?>" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($horario['abertura']) ?>" 
                           placeholder="09:00"
                           <?= !$horarioAtivo ? 'disabled' : '' ?>>
                    <span class="text-muted small">—</span>
                    <input type="text" name="horario_<?= $dia ?>_fim" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($horario['fechamento'] ?? '') ?>" 
                           placeholder="18:00"
                           <?= !$horarioAtivo ? 'disabled' : '' ?>>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

        <!-- ============================================ -->
        <!-- BOTÕES DE AÇÃO -->
        <!-- ============================================ -->
        <div class="config-actions mt-4">
            <button type="submit" class="btn btn-gold px-4">
                <i class="fas fa-save"></i> Guardar Configurações
            </button>
            <a href="?a=admin" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
        
    </form>
</div>

<style>
/* ============================================
   CONFIGURAÇÕES - ESTILOS
   ============================================ */

.config-container {
    padding: 0.5rem;
}

.config-header h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 2px;
}

.config-header h2 i {
    margin-right: 8px;
}

/* Secções */
.config-section {
    background: white;
    border-radius: 12px;
    border: 1px solid #e8e8e8;
    padding: 18px 20px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid rgba(198, 164, 63, 0.2);
}

.section-title i {
    margin-right: 8px;
}

/* Formulário */
.form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 4px;
}

.form-label small {
    font-weight: 400;
    color: #888;
}

.form-control {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    color: #1a1a2e;
    font-size: 0.85rem;
    padding: 8px 12px;
}

.form-control:focus {
    background: #fff;
    border-color: #C6A43F;
    box-shadow: 0 0 0 0.2rem rgba(198, 164, 63, 0.15);
}

.form-control-sm {
    font-size: 0.75rem;
    padding: 4px 8px;
    height: 32px;
}

.form-control::placeholder {
    color: #aaa;
    font-size: 0.8rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 60px;
}

/* Checkbox */
.form-check-input:checked {
    background-color: #C6A43F;
    border-color: #C6A43F;
}

.form-check-label {
    font-size: 0.85rem;
    color: #1a1a2e;
}

/* Botões */
.config-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.config-actions .btn {
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 24px;
}

.btn-gold {
    background: #C6A43F;
    border: none;
    color: #1a1a2e;
}

.btn-gold:hover {
    background: #b8922e;
    color: #1a1a2e;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
}

/* Alertas */
.alert-success {
    background: rgba(40, 167, 69, 0.08);
    border: 1px solid rgba(40, 167, 69, 0.2);
    color: #155724;
    border-radius: 10px;
    padding: 10px 16px;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.08);
    border: 1px solid rgba(220, 53, 69, 0.2);
    color: #721c24;
    border-radius: 10px;
    padding: 10px 16px;
}

.alert .btn-close {
    font-size: 0.75rem;
}

/* ============================================
   RESPONSIVO
   ============================================ */

@media (max-width: 768px) {
    .config-container {
        padding: 0.25rem;
    }
    
    .config-header h2 {
        font-size: 1.1rem;
    }
    
    .config-section {
        padding: 14px 14px;
        margin-bottom: 14px;
        border-radius: 10px;
    }
    
    .section-title {
        font-size: 0.8rem;
        margin-bottom: 12px;
    }
    
    .form-label {
        font-size: 0.75rem;
    }
    
    .form-control {
        font-size: 0.8rem;
        padding: 6px 10px;
    }
    
    .config-actions {
        flex-direction: column;
    }
    
    .config-actions .btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .config-section {
        padding: 10px 10px;
        margin-bottom: 10px;
        border-radius: 8px;
    }
    
    .section-title {
        font-size: 0.7rem;
        padding-bottom: 6px;
    }
    
    .form-control {
        font-size: 0.75rem;
        padding: 5px 8px;
    }
    
    .form-control-sm {
        font-size: 0.65rem;
        padding: 3px 6px;
        height: 28px;
    }
    
    .form-label {
        font-size: 0.7rem;
    }
    
    .config-actions .btn {
        font-size: 0.75rem;
        padding: 6px 16px;
    }
}
</style>