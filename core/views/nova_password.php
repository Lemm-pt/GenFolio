<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Nova Password</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    
                    <form action="?a=nova_password_submit" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" name="text_nova_senha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Senha</label>
                            <input type="password" name="text_confirmar_senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Alterar Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>