<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Recuperar Código de Acesso</h3>
                    <p>Enviaremos um link para o seu email</p>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['sucesso'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    
                    <form action="?a=recuperar_codigo_submit" method="POST">
                        <div class="mb-4">
                            <label class="form-label">Slug do seu site</label>
                            <input type="text" name="text_slug" class="form-control" required placeholder="ex: meu-negocio">
                            <small class="text-muted">Ex: vitrine-demo, meu-negocio, etc.</small>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Enviar Link de Recuperação</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="?a=admin_login" class="text-gold">Voltar ao login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>