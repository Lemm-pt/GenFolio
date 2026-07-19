<!-- core/views/contacto.php -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card bg-dark text-white border-gold">
                <div class="card-header bg-gold text-dark text-center">
                    <h3><i class="fas fa-envelope"></i> Contacto</h3>
                    <p class="mb-0">Envie-nos uma mensagem</p>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['msg_sucesso'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?= $_SESSION['msg_sucesso']; unset($_SESSION['msg_sucesso']); ?>
                        </div>
                    <?php elseif (isset($_SESSION['msg_erro'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['msg_erro']; unset($_SESSION['msg_erro']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="?a=contacto" method="POST">
                        
                        <!-- Honeypot anti-spam -->
                        <input type="text" name="empresa_interna_777" style="display:none">

                        <!-- Time check -->
                        <?php $_SESSION['contact_form_time'] = time(); ?>

                        <div class="mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="Seu email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="tel" name="telefone" class="form-control" placeholder="Seu telefone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mensagem *</label>
                            <textarea name="mensagem" rows="5" class="form-control" placeholder="A sua mensagem..." required></textarea>
                        </div>

                        <button type="submit" class="btn-gold w-100">
                            <i class="fas fa-paper-plane"></i> Enviar Mensagem
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">
                            <i class="fas fa-phone"></i> <?= htmlspecialchars($config->get('telefone', '+351 900 000 000')) ?>
                            &nbsp;|&nbsp;
                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($config->get('email_contacto', 'geral@sevenlux.pt')) ?>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>