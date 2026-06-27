<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card bg-dark text-white">
                <div class="card-header bg-success text-white">
                    <h3><i class="fas fa-check-circle"></i> Registo Efetuado!</h3>
                </div>
                <div class="card-body" style="color: #013f04 !important;">
                    <p>Enviamos um email de confirmação para <strong><?= htmlspecialchars($_SESSION['email_temporario'] ?? 'seu email') ?></strong>.</p>
                    <p>Por favor, verifique a sua caixa de entrada e clique no link de confirmação para ativar a sua conta.</p>
                    <p>Após confirmar o email, será redirecionado automaticamente para o login.</p>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-envelope"></i> Não recebeu o email?  (Por vezes, não é reconhecido e poderá estar na pasta SPAN ou LIXO)
                        <a href="?a=recuperar_codigo" class="alert-link">Clique aqui</a> para recuperar o acesso.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['email_temporario']); ?>