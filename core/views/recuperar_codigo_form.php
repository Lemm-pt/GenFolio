<!-- core/views/recuperar_codigo_form.php -->
<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.2) !important;">
                <div class="card-header bg-gold text-dark text-center" style="background: #C6A43F !important; color: #0a0a1a !important; border-radius: 15px 15px 0 0 !important; padding: 20px !important;">
                    <h3 style="color: #0a0a1a !important;">Recuperar Código de Acesso</h3>
                    <p style="color: #0a0a1a !important; opacity: 0.8;">Insira o slug do seu site para recuperar o acesso</p>
                </div>
                <div class="card-body" style="background: #1a1a2e !important; color: #ffffff !important; padding: 30px !important;">
                    
                    <?php if(isset($mensagem)): ?>
                        <?php if(strpos($mensagem, '✅') !== false || strpos($mensagem, 'sucesso') !== false): ?>
                            <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.15) !important; border: 1px solid #28a745 !important; color: #5ddb6f !important;">
                                <i class="fas fa-check-circle"></i> <?= $mensagem ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.15) !important; border: 1px solid #dc3545 !important; color: #ff6b6b !important;">
                                <i class="fas fa-exclamation-triangle"></i> <?= $mensagem ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <form action="?a=recuperar_codigo_submit" method="POST">
                        <div class="mb-4">
                            <label class="form-label" style="color: #ffffff !important; font-weight: 600;">Slug do seu site</label>
                            <input type="text" name="text_slug" class="form-control" required placeholder="ex: meu-negocio" style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important;">
                            <small class="text-muted" style="color: #888 !important;">Ex: vitrine-demo, meu-negocio, etc.</small>
                        </div>
                        <button type="submit" class="btn btn-gold w-100" style="background: #C6A43F !important; color: #0a0a1a !important; font-weight: 700 !important; padding: 14px !important; border-radius: 50px !important; border: none !important;">
                            <i class="fas fa-paper-plane"></i> Enviar Link de Recuperação
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="?a=admin_login" class="text-gold" style="color: #C6A43F !important; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Voltar ao login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>