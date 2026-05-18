<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card bg-dark text-white">
                <div class="card-header bg-success text-white">
                    <h3><i class="fas fa-check-circle"></i> Registo Efetuado!</h3>
                </div>
                <div class="card-body">
                    <p>Enviamos um email de confirmação para <strong><?= htmlspecialchars($_SESSION['email_temporario'] ?? 'seu email') ?></strong>.</p>
                    <p>Por favor, verifique a sua caixa de entrada e clique no link de confirmação para ativar a sua conta.</p>
                    <p>Após confirmar o email, poderá fazer login e começar a personalizar o seu site!</p>
                   <!-- <a href="<?= BASE_URL ?>index.php?a=login" class="btn btn-gold mt-3">Ir para o Login</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['email_temporario']); ?>