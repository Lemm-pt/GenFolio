<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card bg-dark text-white">
                <div class="card-header bg-success text-white">
                    <h3><i class="fas fa-check-circle"></i> Conta Confirmada!</h3>
                </div>
                <div class="card-body">
                    <p>A sua conta foi ativada com sucesso!</p>
                    <p>Já pode fazer login e começar a personalizar o seu site.</p>
                    <a href="<?= BASE_URL . ($slug ?? '') ?>/admin_login" class="btn btn-gold mt-3">Aceder ao Admin</a>
                    <a href="<?= BASE_URL . ($slug ?? '') ?>/" class="btn btn-outline-gold mt-3">Ver Site</a>
                </div>
            </div>
        </div>
    </div>
</div>