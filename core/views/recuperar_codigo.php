<!-- core/views/recuperar_codigo.php -->
<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.2) !important;">
                <div class="card-header bg-gold text-dark text-center" style="background: #C6A43F !important; color: #0a0a1a !important; border-radius: 15px 15px 0 0 !important; padding: 20px !important;">
                    <h3 style="color: #0a0a1a !important;"><i class="fas fa-envelope"></i> Recuperar Código de Acesso</h3>
                    <p style="color: #0a0a1a !important; opacity: 0.8;">Enviaremos um link para o seu email</p>
                </div>
                <div class="card-body" style="background: #1a1a2e !important; color: #ffffff !important; padding: 30px !important;">
                    
                    <?php if(isset($_SESSION['sucesso'])): ?>
                        <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.2) !important; border: 1px solid #28a745 !important; color: #5ddb6f !important; border-radius: 10px; padding: 15px;">
                            <i class="fas fa-check-circle"></i> <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2) !important; border: 1px solid #dc3545 !important; color: #ff6b6b !important; border-radius: 10px; padding: 15px;">
                            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
                        </div>
                    <?php endif; ?>

                    <?php 
                    // 🔥 Verificar se o slug já está definido na URL
                    $slugFromUrl = $_GET['slug'] ?? $_SESSION['cliente_slug'] ?? null;
                    ?>
                    
                    <?php if (!empty($slugFromUrl)): ?>
                        <!-- 🔥 MOSTRAR APENAS MENSAGEM INFORMATIVA (slug já está na URL) -->
                        <div class="text-center mb-4">
                            <div style="background: rgba(198, 164, 63, 0.1); border-radius: 12px; padding: 20px; border: 1px solid rgba(198, 164, 63, 0.2);">
                                <i class="fas fa-globe" style="font-size: 2.5rem; color: #C6A43F; margin-bottom: 15px; display: block;"></i>
                                <p class="mb-2" style="color: #ffffff !important; font-size: 1.1rem;">
                                    <strong>Slug:</strong> 
                                    <span style="color: #C6A43F; font-weight: 700;"><?= htmlspecialchars($slugFromUrl) ?></span>
                                </p>
                                <p class="text-muted" style="color: #aaa !important; font-size: 0.9rem;">
                                    <i class="fas fa-info-circle"></i> 
                                    Enviaremos o link de recuperação para o email associado a este site.
                                </p>
                            </div>
                        </div>
                        
                        <form action="?a=recuperar_codigo_submit" method="POST">
                            <!-- 🔥 Campo oculto com o slug -->
                            <input type="hidden" name="text_slug" value="<?= htmlspecialchars($slugFromUrl) ?>">
                            
                            <button type="submit" class="btn btn-gold w-100" style="background: #C6A43F !important; color: #0a0a1a !important; font-weight: 700 !important; padding: 14px !important; border-radius: 50px !important; border: none !important; font-size: 1.1rem;">
                                <i class="fas fa-paper-plane"></i> Enviar Link de Recuperação
                            </button>
                        </form>
                        
                    <?php else: ?>
                        <!-- 🔥 MOSTRAR FORMULÁRIO COMPLETO (slug NÃO está na URL) -->
                        <form action="?a=recuperar_codigo_submit" method="POST">
                            <div class="mb-4">
                                <label class="form-label" style="color: #ffffff !important; font-weight: 600;">
                                    <i class="fas fa-link"></i> Slug do seu site *
                                </label>
                                <input type="text" name="text_slug" class="form-control" required placeholder="ex: meu-negocio" style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 10px; padding: 12px 15px;">
                                <small class="text-muted" style="color: #888 !important; display: block; margin-top: 5px;">
                                    <i class="fas fa-info-circle"></i> Ex: vitrine-demo, meu-negocio, etc.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-gold w-100" style="background: #C6A43F !important; color: #0a0a1a !important; font-weight: 700 !important; padding: 14px !important; border-radius: 50px !important; border: none !important; font-size: 1.1rem;">
                                <i class="fas fa-paper-plane"></i> Enviar Link de Recuperação
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <div class="text-center mt-4">
                        <a href="?a=admin_login" class="text-gold" style="color: #C6A43F !important; text-decoration: none; transition: color 0.3s;">
                            <i class="fas fa-arrow-left"></i> Voltar ao login
                        </a>
                    </div>
                    
                    <!-- 🔥 Informação adicional sobre o email -->
                    <div class="mt-4" style="background: rgba(255,255,255,0.03); border-radius: 10px; padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                        <p style="color: #999 !important; font-size: 0.8rem; margin: 0; text-align: center;">
                            <i class="fas fa-envelope" style="color: #C6A43F;"></i>
                            O email pode demorar alguns minutos. Verifique também a pasta de SPAM ou LIXO.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Melhorias de estilo */
.alert-success, .alert-danger {
    border-radius: 10px !important;
    padding: 15px !important;
}

.form-control:focus {
    border-color: #C6A43F !important;
    box-shadow: 0 0 0 0.2rem rgba(198, 164, 63, 0.25) !important;
}

.btn-gold:hover {
    background: #d4b96a !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(198, 164, 63, 0.3);
}

.text-gold:hover {
    color: #d4b96a !important;
    text-decoration: underline !important;
}

/* Animação suave */
.card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>