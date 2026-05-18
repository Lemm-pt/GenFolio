
<?php if(isset($_SESSION['sucesso_login'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso_login']; unset($_SESSION['sucesso_login']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Login Admin</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    <form action="<?= BASE_URL ?>index.php?a=admin_login_submit" method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="text_admin" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Senha</label>
                            <input type="password" name="text_senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>