<div class="login-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <span class="logo-icon">✦</span>
                </div>
                <h2>Acesso à Área Admin</h2>
                <p class="login-subtitle"><?= htmlspecialchars(CLIENTE_SLUG) ?></p>
            </div>

            <div class="login-body">
                <?php if(isset($_SESSION['erro'])): ?>
                    <div class="alert-message error">
                        <span class="alert-icon">⚠️</span>
                        <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>index.php?a=admin_login_submit" method="POST" id="formLogin">
                    <input type="hidden" name="text_slug" value="<?= CLIENTE_SLUG ?>">
                    <input type="hidden" name="text_digitos" id="login_digitos" value="">

                    <div class="code-section">
                        <label class="input-label">Código de acesso</label>
                        <div class="code-display-modern" id="login_code_display">
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                            <span class="digit-mask">▪</span>
                        </div>
                        <div class="numpad-grid-modern" id="login_numpad"></div>
                    </div>

                    <button type="submit" class="login-btn">Entrar →</button>
                </form>

                <div class="login-footer">
                   <a href="?a=recuperar_codigo&slug=<?= htmlspecialchars(CLIENTE_SLUG) ?>" class="forgot-link">Esqueci o código</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset e base */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0a0f1e 0%, #0c1222 100%);
    padding: 1.5rem;
}

.login-container {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}

.login-card {
    background: rgba(245, 244, 246, 0.85);
    backdrop-filter: blur(20px);
    border-radius: 28px;
    border: 1px solid rgba(198, 164, 63, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(198, 164, 63, 0.05) inset;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.5);
}

.login-header {
    padding: 1.8rem 2rem 1rem 2rem;
    text-align: center;
    border-bottom: 1px solid rgba(198, 164, 63, 0.15);
}

.login-logo {
    width: 48px;
    height: 48px;
    background: rgba(198, 164, 63, 0.1);
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem auto;
    border: 1px solid rgba(198, 164, 63, 0.3);
}

.logo-icon {
    font-size: 24px;
    color: #C6A43F;
    filter: drop-shadow(0 0 4px rgba(198,164,63,0.4));
}

.login-header h2 {
    font-family: 'Inter', sans-serif;
    font-size: 1.4rem;
    font-weight: 600;
    letter-spacing: -0.3px;
    margin: 0 0 0.25rem 0;
    background: linear-gradient(135deg, #fff 0%, #e0e4f0 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.login-subtitle {
    font-size: 0.8rem;
    color: #C6A43F;
    background: rgba(198, 164, 63, 0.12);
    display: inline-block;
    padding: 0.15rem 0.9rem;
    border-radius: 40px;
    font-weight: 500;
    margin-top: 0.4rem;
}

.login-body {
    padding: 1.2rem 2rem 1.8rem 2rem;
}

/* Alert message */
.alert-message {
    background: rgba(220, 53, 69, 0.12);
    border-left: 3px solid #dc3545;
    border-radius: 10px;
    padding: 0.7rem 1rem;
    margin-bottom: 1.5rem;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    backdrop-filter: blur(4px);
}

.alert-icon {
    font-size: 1.1rem;
}

/* Code display */
.code-section {
    margin-bottom: 1.5rem;
}

.input-label {
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
    color: #9ca3af;
    margin-bottom: 0.6rem;
    text-align: center;
}

.code-display-modern {
    background: #0a0f1e;
    border-radius: 16px;
    padding: 0.8rem 0.5rem;
    text-align: center;
    border: 1px solid rgba(198, 164, 63, 0.2);
    margin-bottom: 1.2rem;
    transition: all 0.2s;
}

.digit-mask {
    display: inline-block;
    width: 32px;
    font-size: 1.3rem;
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-weight: 700;
    color: #C6A43F;
    text-shadow: 0 0 2px rgba(198,164,63,0.3);
    letter-spacing: 2px;
}

/* Numpad moderno - BOTÕES MAIS PEQUENOS */
.numpad-grid-modern {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    max-width: 240px;
    margin: 0 auto;
}

.numpad-btn-modern {
    background: #0f1420;
    border: 1px solid #252c3f;
    color: #e5e7eb;
    font-size: 1.2rem;
    font-weight: 500;
    padding: 10px 0;
    border-radius: 18px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    font-family: 'Inter', monospace;
    backdrop-filter: blur(4px);
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.numpad-btn-modern:hover {
    background: #1a2135;
    border-color: #C6A43F;
    color: #C6A43F;
    transform: scale(0.96);
}

.numpad-btn-modern:active {
    transform: scale(0.92);
    background: #C6A43F;
    color: #0a0f1e;
    border-color: #C6A43F;
}

/* Botão especial reset/apagar - mais pequeno */
.numpad-special {
    background: #1a1f2e;
    color: #9ca3af;
    font-size: 0.8rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    padding: 8px 0;
    min-height: 40px;
}

.numpad-special.reset-btn {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.numpad-special.back-btn {
    font-size: 1.2rem;
}

/* Botão principal */
.login-btn {
    width: 100%;
    background: #C6A43F;
    border: none;
    padding: 0.8rem;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #0a0f1e;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    margin-top: 0.5rem;
    box-shadow: 0 4px 12px rgba(198, 164, 63, 0.2);
}

.login-btn:hover {
    background: #dbb347;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(198, 164, 63, 0.25);
}

.login-footer {
    text-align: center;
    margin-top: 1.5rem;
}

.forgot-link {
    color: #9ca3af;
    font-size: 0.75rem;
    text-decoration: none;
    transition: color 0.2s;
    letter-spacing: 0.2px;
}

.forgot-link:hover {
    color: #C6A43F;
    text-decoration: none;
}

/* Responsivo */
@media (max-width: 480px) {
    .login-body {
        padding: 1rem 1.5rem 1.5rem 1.5rem;
    }
    
    .login-header {
        padding: 1.2rem 1.5rem 0.8rem 1.5rem;
    }
    
    .login-header h2 {
        font-size: 1.2rem;
    }
    
    .login-logo {
        width: 40px;
        height: 40px;
    }
    
    .logo-icon {
        font-size: 20px;
    }
    
    .digit-mask {
        width: 26px;
        font-size: 1.1rem;
    }
    
    .numpad-grid-modern {
        gap: 6px;
        max-width: 200px;
    }
    
    .numpad-btn-modern {
        font-size: 1rem;
        padding: 8px 0;
        min-height: 40px;
        border-radius: 14px;
    }
    
    .numpad-special {
        font-size: 0.7rem;
        min-height: 34px;
        padding: 6px 0;
    }
    
    .numpad-special.back-btn {
        font-size: 1rem;
    }
    
    .code-display-modern {
        padding: 0.6rem 0.5rem;
    }
}

@media (max-width: 360px) {
    .numpad-grid-modern {
        gap: 5px;
        max-width: 170px;
    }
    
    .numpad-btn-modern {
        font-size: 0.9rem;
        padding: 6px 0;
        min-height: 34px;
        border-radius: 12px;
    }
    
    .digit-mask {
        width: 22px;
        font-size: 0.9rem;
    }
}
</style>

<script>
// Script para o teclado moderno (compatível com o backend)
(function() {
    let digits = "";
    const MAX_DIGITS = 7;
    
    const inputHidden = document.getElementById('login_digitos');
    const displaySpans = document.querySelectorAll('#login_code_display .digit-mask');
    const container = document.getElementById('login_numpad');
    
    if (!container) {
        console.error('Container não encontrado');
        return;
    }
    
    function atualizarDisplay() {
        for (let i = 0; i < MAX_DIGITS; i++) {
            if (displaySpans[i]) {
                displaySpans[i].textContent = (i < digits.length) ? '●' : '▪';
            }
        }
        if (inputHidden) inputHidden.value = digits;
    }
    
    function adicionarDigito(num) {
        if (digits.length < MAX_DIGITS) {
            digits += num.toString();
            atualizarDisplay();
        }
    }
    
    function resetDigitos() {
        digits = "";
        atualizarDisplay();
    }
    
    function apagarDigito() {
        digits = digits.slice(0, -1);
        atualizarDisplay();
    }
    
    container.innerHTML = '';
    
    // Botões 1-9
    for (let i = 1; i <= 9; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'numpad-btn-modern';
        btn.type = 'button';
        btn.onclick = (function(num) { 
            return function() { adicionarDigito(num); }; 
        })(i);
        container.appendChild(btn);
    }
    
    // Botão 0
    const btnZero = document.createElement('button');
    btnZero.textContent = '0';
    btnZero.className = 'numpad-btn-modern';
    btnZero.type = 'button';
    btnZero.onclick = () => adicionarDigito(0);
    container.appendChild(btnZero);
    
    // Botão Reset
    const btnReset = document.createElement('button');
    btnReset.textContent = 'reset';
    btnReset.className = 'numpad-btn-modern numpad-special reset-btn';
    btnReset.type = 'button';
    btnReset.onclick = resetDigitos;
    container.appendChild(btnReset);
    
    // Botão Apagar
    const btnApagar = document.createElement('button');
    btnApagar.textContent = '⌫';
    btnApagar.className = 'numpad-btn-modern numpad-special back-btn';
    btnApagar.type = 'button';
    btnApagar.onclick = apagarDigito;
    container.appendChild(btnApagar);
    
    const form = document.getElementById('formLogin');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (digits.length === 0) {
                e.preventDefault();
                const errorDiv = document.querySelector('.alert-message');
                if(!errorDiv) {
                    const alertBox = document.createElement('div');
                    alertBox.className = 'alert-message error';
                    alertBox.innerHTML = '<span class="alert-icon">⚠️</span> Por favor, insira o código de acesso (1-7 dígitos)!';
                    document.querySelector('.login-body').insertBefore(alertBox, form);
                    setTimeout(() => alertBox.remove(), 3000);
                }
            }
        });
    }
    
    atualizarDisplay();
})();
</script>