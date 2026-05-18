<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Recuperar Password</h3>
                    <p>Enviaremos um link para o seu email</p>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['sucesso'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    
                    <form action="?a=recuperar_password_submit" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="text_email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Enviar</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="?a=login" class="text-gold">Voltar ao login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>