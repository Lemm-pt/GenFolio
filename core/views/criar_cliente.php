<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-gold text-center">
                    <h3>Criar Conta</h3>
                    <p>Registe-se para criar o seu site</p>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    
                  <form action="<?= BASE_URL ?>index.php?a=criar_cliente" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="text_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nome do site (slug) *</label>
                            <input type="text" name="text_slug" class="form-control" placeholder="ex: meu-negocio" required>
                            <small class="text-muted">O seu site estará disponível em: <?= BASE_URL ?>[slug]/</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha * (mínimo 6 caracteres)</label>
                            <input type="password" name="text_senha_1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Senha *</label>
                            <input type="password" name="text_senha_2" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Registar</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="?a=admin_login" class="text-gold">Já tem conta? Faça login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>