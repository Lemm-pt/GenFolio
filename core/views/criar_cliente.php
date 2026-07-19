<!-- core/views/criar_cliente.php -->
<div class="container py-5" style="padding-top: 120px !important; padding-bottom: 60px !important;">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card bg-dark text-white" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.2) !important; border-radius: 20px !important;">
                <div class="card-header bg-gold text-dark text-center" style="background: #C6A43F !important; color: #0a0a1a !important; border-radius: 20px 20px 0 0 !important; padding: 25px !important;">
                    <h3 style="color: #0a0a1a !important; font-weight: 700;">🌿 Criar Conta</h3>
                    <p style="color: #0a0a1a !important; opacity: 0.8; margin-bottom: 0;">Registe-se para criar o seu site</p>
                </div>
                <div class="card-body" style="background: #1a1a2e !important; color: #ffffff !important; padding: 30px 35px !important;">
                    
                    <?php if (isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.15) !important; border: 1px solid #dc3545 !important; color: #ff6b6b !important; border-radius: 12px; padding: 12px 16px;">
                            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>index.php?a=criar_cliente" method="POST" id="formRegisto">
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-envelope text-gold"></i> Email *
                            </label>
                            <input type="email" name="text_email" class="form-control" required 
                                   placeholder="seu@email.com"
                                   style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 10px; padding: 12px 15px;">
                            <small class="text-muted" style="color: #888 !important; font-size: 0.7rem;">Usado para login e recuperação de conta</small>
                        </div>

                        <!-- Slug (nome do site) -->
                        <div class="mb-3">
                            <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-link text-gold"></i> Nome do site (slug) *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #888; font-size: 0.8rem; border-radius: 10px 0 0 10px;">
                                    <?= BASE_URL ?>
                                </span>
                                <input type="text" name="text_slug" class="form-control" required 
                                       placeholder="ex: meu-negocio"
                                       style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 0 10px 10px 0; padding: 12px 15px;">
                            </div>
                            <small class="text-muted" style="color: #888 !important; font-size: 0.7rem;">O seu site estará disponível em: <?= BASE_URL ?><strong style="color: #C6A43F;">nome-do-site</strong>/</small>
                        </div>

                        <!-- Categoria -->
                        <div class="mb-3">
                            <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-tag text-gold"></i> Categoria / Tipo de negócio *
                            </label>
                            <select name="text_categoria" class="form-select" required style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 10px; padding: 12px 15px;">
                                <option value="" style="background: #1a1a2e; color: #888;">Selecione a sua categoria...</option>
                                <option value="Alimentação" style="background: #1a1a2e; color: #ffffff;">📊 Indústria</option>
                                <option value="Alimentação" style="background: #1a1a2e; color: #ffffff;">🍔 Alimentação</option>
                                <option value="Moda" style="background: #1a1a2e; color: #ffffff;">👗 Moda</option>
                                <option value="Tecnologia" style="background: #1a1a2e; color: #ffffff;">💻 Tecnologia</option>
                                <option value="Saúde" style="background: #1a1a2e; color: #ffffff;">🏥 Saúde</option>
                                <option value="Educação" style="background: #1a1a2e; color: #ffffff;">📚 Educação</option>
                                <option value="Construção" style="background: #1a1a2e; color: #ffffff;">🏗️ Construção</option>
                                <option value="Turismo" style="background: #1a1a2e; color: #ffffff;">✈️ Turismo</option>
                                <option value="Beleza" style="background: #1a1a2e; color: #ffffff;">💅 Beleza</option>
                                <option value="Automóvel" style="background: #1a1a2e; color: #ffffff;">🚗 Automóvel</li>
                                <option value="Imobiliário" style="background: #1a1a2e; color: #ffffff;">🏠 Imobiliário</option>
                                <option value="Financeiro" style="background: #1a1a2e; color: #ffffff;">💰 Financeiro</option>
                                <option value="Arquitetura / Urbanismo" style="background: #1a1a2e; color: #ffffff;">🏛️ Arquitetura / Urbanismo</option>
                                <option value="Artes / Cultura" style="background: #1a1a2e; color: #ffffff;">🎨 Artes / Cultura</option>
                                <option value="Desporto" style="background: #1a1a2e; color: #ffffff;">⚽ Desporto</option>
                                <option value="Agricultura" style="background: #1a1a2e; color: #ffffff;">🌾 Agricultura</option>
                                <option value="Logística" style="background: #1a1a2e; color: #ffffff;">📦 Logística</option>
                                <option value="Consultoria" style="background: #1a1a2e; color: #ffffff;">📊 Consultoria</option>
                                <option value="Marketing" style="background: #1a1a2e; color: #ffffff;">📈 Marketing</option>
                                <option value="Design" style="background: #1a1a2e; color: #ffffff;">✏️ Design</option>
                                <option value="Fotografia" style="background: #1a1a2e; color: #ffffff;">📷 Fotografia</option>
                                <option value="Música" style="background: #1a1a2e; color: #ffffff;">🎵 Música</option>
                                <option value="Outro" style="background: #1a1a2e; color: #ffffff;">📦 Outro</option>
                            </select>
                            <small class="text-muted" style="color: #888 !important; font-size: 0.7rem;">A sua categoria define a sua identidade na plataforma.</small>
                        </div>

                        <!-- Cidade e País -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                    <i class="fas fa-city text-gold"></i> Cidade
                                </label>
                                <input type="text" name="text_cidade" class="form-control" 
                                       placeholder="Ex: Lisboa"
                                       style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 10px; padding: 12px 15px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                    <i class="fas fa-globe text-gold"></i> País *
                                </label>
                                <select name="text_pais" class="form-select" required id="paisSelect" style="background: #0a0a1a !important; border: 1px solid #333 !important; color: #ffffff !important; border-radius: 10px; padding: 12px 15px;">
                                    <option value="" style="background: #1a1a2e; color: #888;">Selecione o país...</option>
                                    <option value="Portugal" data-locale="pt" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇵🇹 Portugal</option>
                                    <option value="Brasil" data-locale="pt-br" data-currency="BRL" style="background: #1a1a2e; color: #ffffff;">🇧🇷 Brasil</option>
                                    <option value="Angola" data-locale="pt" data-currency="AOA" style="background: #1a1a2e; color: #ffffff;">🇦🇴 Angola</option>
                                    <option value="Moçambique" data-locale="pt" data-currency="MZN" style="background: #1a1a2e; color: #ffffff;">🇲🇿 Moçambique</option>
                                    <option value="Cabo Verde" data-locale="pt" data-currency="CVE" style="background: #1a1a2e; color: #ffffff;">🇨🇻 Cabo Verde</option>
                                    <option value="Guiné-Bissau" data-locale="pt" data-currency="XOF" style="background: #1a1a2e; color: #ffffff;">🇬🇼 Guiné-Bissau</option>
                                    <option value="São Tomé e Príncipe" data-locale="pt" data-currency="STN" style="background: #1a1a2e; color: #ffffff;">🇸🇹 São Tomé</option>
                                    <option value="Timor-Leste" data-locale="pt" data-currency="USD" style="background: #1a1a2e; color: #ffffff;">🇹🇱 Timor-Leste</option>
                                    <option value="Espanha" data-locale="es" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇪🇸 Espanha</option>
                                    <option value="França" data-locale="fr" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇫🇷 França</option>
                                    <option value="Reino Unido" data-locale="en" data-currency="GBP" style="background: #1a1a2e; color: #ffffff;">🇬🇧 Reino Unido</option>
                                    <option value="Estados Unidos" data-locale="en" data-currency="USD" style="background: #1a1a2e; color: #ffffff;">🇺🇸 Estados Unidos</option>
                                    <option value="Alemanha" data-locale="de" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇩🇪 Alemanha</option>
                                    <option value="Itália" data-locale="it" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇮🇹 Itália</option>
                                    <option value="Holanda" data-locale="nl" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇳🇱 Holanda</option>
                                    <option value="Bélgica" data-locale="nl" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇧🇪 Bélgica</option>
                                    <option value="Suíça" data-locale="de" data-currency="CHF" style="background: #1a1a2e; color: #ffffff;">🇨🇭 Suíça</option>
                                    <option value="Áustria" data-locale="de" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇦🇹 Áustria</option>
                                    <option value="Irlanda" data-locale="en" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇮🇪 Irlanda</option>
                                    <option value="Canadá" data-locale="en" data-currency="CAD" style="background: #1a1a2e; color: #ffffff;">🇨🇦 Canadá</option>
                                    <option value="Austrália" data-locale="en" data-currency="AUD" style="background: #1a1a2e; color: #ffffff;">🇦🇺 Austrália</option>
                                    <option value="Japão" data-locale="ja" data-currency="JPY" style="background: #1a1a2e; color: #ffffff;">🇯🇵 Japão</option>
                                    <option value="China" data-locale="zh" data-currency="CNY" style="background: #1a1a2e; color: #ffffff;">🇨🇳 China</option>
                                    <option value="Rússia" data-locale="ru" data-currency="RUB" style="background: #1a1a2e; color: #ffffff;">🇷🇺 Rússia</option>
                                    <option value="México" data-locale="es" data-currency="MXN" style="background: #1a1a2e; color: #ffffff;">🇲🇽 México</option>
                                    <option value="Argentina" data-locale="es" data-currency="ARS" style="background: #1a1a2e; color: #ffffff;">🇦🇷 Argentina</option>
                                    <option value="Colômbia" data-locale="es" data-currency="COP" style="background: #1a1a2e; color: #ffffff;">🇨🇴 Colômbia</option>
                                    <option value="Peru" data-locale="es" data-currency="PEN" style="background: #1a1a2e; color: #ffffff;">🇵🇪 Peru</option>
                                    <option value="Chile" data-locale="es" data-currency="CLP" style="background: #1a1a2e; color: #ffffff;">🇨🇱 Chile</option>
                                    <option value="Venezuela" data-locale="es" data-currency="VES" style="background: #1a1a2e; color: #ffffff;">🇻🇪 Venezuela</option>
                                    <option value="Índia" data-locale="hi" data-currency="INR" style="background: #1a1a2e; color: #ffffff;">🇮🇳 Índia</option>
                                    <option value="África do Sul" data-locale="en" data-currency="ZAR" style="background: #1a1a2e; color: #ffffff;">🇿🇦 África do Sul</option>
                                    <option value="Egito" data-locale="ar" data-currency="EGP" style="background: #1a1a2e; color: #ffffff;">🇪🇬 Egito</option>
                                    <option value="Israel" data-locale="he" data-currency="ILS" style="background: #1a1a2e; color: #ffffff;">🇮🇱 Israel</option>
                                    <option value="Coreia do Sul" data-locale="ko" data-currency="KRW" style="background: #1a1a2e; color: #ffffff;">🇰🇷 Coreia do Sul</option>
                                    <option value="Singapura" data-locale="en" data-currency="SGD" style="background: #1a1a2e; color: #ffffff;">🇸🇬 Singapura</option>
                                    <option value="Malásia" data-locale="ms" data-currency="MYR" style="background: #1a1a2e; color: #ffffff;">🇲🇾 Malásia</option>
                                    <option value="Indonésia" data-locale="id" data-currency="IDR" style="background: #1a1a2e; color: #ffffff;">🇮🇩 Indonésia</option>
                                    <option value="Turquia" data-locale="tr" data-currency="TRY" style="background: #1a1a2e; color: #ffffff;">🇹🇷 Turquia</option>
                                    <option value="Grécia" data-locale="el" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇬🇷 Grécia</option>
                                    <option value="Polónia" data-locale="pl" data-currency="PLN" style="background: #1a1a2e; color: #ffffff;">🇵🇱 Polónia</option>
                                    <option value="Suécia" data-locale="sv" data-currency="SEK" style="background: #1a1a2e; color: #ffffff;">🇸🇪 Suécia</option>
                                    <option value="Noruega" data-locale="no" data-currency="NOK" style="background: #1a1a2e; color: #ffffff;">🇳🇴 Noruega</option>
                                    <option value="Dinamarca" data-locale="da" data-currency="DKK" style="background: #1a1a2e; color: #ffffff;">🇩🇰 Dinamarca</option>
                                    <option value="Finlândia" data-locale="fi" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇫🇮 Finlândia</option>
                                    <option value="Ucrânia" data-locale="uk" data-currency="UAH" style="background: #1a1a2e; color: #ffffff;">🇺🇦 Ucrânia</option>
                                    <option value="Roménia" data-locale="ro" data-currency="RON" style="background: #1a1a2e; color: #ffffff;">🇷🇴 Roménia</option>
                                    <option value="Bulgária" data-locale="bg" data-currency="BGN" style="background: #1a1a2e; color: #ffffff;">🇧🇬 Bulgária</option>
                                    <option value="Hungria" data-locale="hu" data-currency="HUF" style="background: #1a1a2e; color: #ffffff;">🇭🇺 Hungria</option>
                                    <option value="República Checa" data-locale="cs" data-currency="CZK" style="background: #1a1a2e; color: #ffffff;">🇨🇿 República Checa</option>
                                    <option value="Eslováquia" data-locale="sk" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇸🇰 Eslováquia</option>
                                    <option value="Eslovénia" data-locale="sl" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇸🇮 Eslovénia</option>
                                    <option value="Croácia" data-locale="hr" data-currency="EUR" style="background: #1a1a2e; color: #ffffff;">🇭🇷 Croácia</option>
                                    <option value="Sérvia" data-locale="sr" data-currency="RSD" style="background: #1a1a2e; color: #ffffff;">🇷🇸 Sérvia</option>
                                    <option value="Marrocos" data-locale="ar" data-currency="MAD" style="background: #1a1a2e; color: #ffffff;">🇲🇦 Marrocos</option>
                                    <option value="Emirados Árabes" data-locale="ar" data-currency="AED" style="background: #1a1a2e; color: #ffffff;">🇦🇪 Emirados Árabes</option>
                                    <option value="Arábia Saudita" data-locale="ar" data-currency="SAR" style="background: #1a1a2e; color: #ffffff;">🇸🇦 Arábia Saudita</option>
                                    <option value="Tailândia" data-locale="th" data-currency="THB" style="background: #1a1a2e; color: #ffffff;">🇹🇭 Tailândia</option>
                                    <option value="Vietname" data-locale="vi" data-currency="VND" style="background: #1a1a2e; color: #ffffff;">🇻🇳 Vietname</option>
                                    <option value="Filipinas" data-locale="tl" data-currency="PHP" style="background: #1a1a2e; color: #ffffff;">🇵🇭 Filipinas</option>
                                    <option value="Paquistão" data-locale="ur" data-currency="PKR" style="background: #1a1a2e; color: #ffffff;">🇵🇰 Paquistão</option>
                                    <option value="Bangladesh" data-locale="bn" data-currency="BDT" style="background: #1a1a2e; color: #ffffff;">🇧🇩 Bangladesh</option>
                                    <option value="Nigéria" data-locale="en" data-currency="NGN" style="background: #1a1a2e; color: #ffffff;">🇳🇬 Nigéria</option>
                                    <option value="Quénia" data-locale="sw" data-currency="KES" style="background: #1a1a2e; color: #ffffff;">🇰🇪 Quénia</option>
                                    <option value="Nova Zelândia" data-locale="en" data-currency="NZD" style="background: #1a1a2e; color: #ffffff;">🇳🇿 Nova Zelândia</option>
                                </select>
                                <small class="text-muted" style="color: #888 !important; font-size: 0.7rem;">O idioma e a moeda serão adaptados ao país escolhido.</small>
                            </div>
                        </div>

                        <!-- Access code (digits) -->
                        <div class="mb-3">
                            <label class="form-label" style="color: #ffffff !important; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-key text-gold"></i> Código de acesso (1 a 7 dígitos) *
                            </label>
                            <input type="hidden" name="text_digitos" id="digitos_input" value="">

                            <!-- Digit display -->
                            <div class="code-display text-center mb-3" id="codeDisplay" style="background: #0a0a1a !important; border: 2px solid #C6A43F !important; color: #C6A43F !important; border-radius: 12px; padding: 15px; font-family: monospace; font-size: 28px; letter-spacing: 8px;">
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                                <span class="digit-placeholder" style="color: #C6A43F !important;">▪</span>
                            </div>

                            <!-- Numeric keypad -->
                            <div class="numpad-grid" id="numpadRegisto" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 260px; margin: 0 auto;"></div>
                            <small class="text-muted d-block text-center mt-2" style="color: #888 !important; font-size: 0.7rem;">Clique nos botões para definir o seu código secreto.</small>
                        </div>

                        <button type="submit" class="btn btn-gold w-100" style="background: #C6A43F !important; color: #0a0a1a !important; font-weight: 700 !important; padding: 14px !important; border-radius: 50px !important; border: none !important; font-size: 1.05rem; transition: all 0.3s ease;">
                            <i class="fas fa-crown"></i> Criar Conta
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p style="color: #888 !important; font-size: 0.85rem;">
                            Já tem conta? 
                            <a href="?a=admin_login" class="text-gold" style="color: #C6A43F !important; text-decoration: none; font-weight: 600;">
                                Faça login <i class="fas fa-arrow-right"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para o registo */
.container.py-5 {
    padding-top: 120px !important;
    padding-bottom: 60px !important;
}

@media (max-width: 768px) {
    .container.py-5 {
        padding-top: 90px !important;
        padding-bottom: 40px !important;
    }
    .card-body {
        padding: 20px !important;
    }
}

.numpad-btn {
    padding: 12px;
    font-size: 20px;
    font-weight: bold;
    background: #2a2a35 !important;
    color: white !important;
    border: 2px solid #C6A43F !important;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.numpad-btn:active {
    transform: scale(0.95);
    background: #C6A43F !important;
    color: #0a0a1a !important;
}

.numpad-btn:hover {
    background: #3a3a45 !important;
}

.form-control, .form-select {
    background: #0a0a1a !important;
    border: 1px solid #333 !important;
    color: #ffffff !important;
    border-radius: 10px;
    padding: 12px 15px;
}

.form-control::placeholder {
    color: #666 !important;
}

.form-control:focus, .form-select:focus {
    background: #0a0a1a !important;
    color: #ffffff !important;
    border-color: #C6A43F !important;
    box-shadow: 0 0 0 0.2rem rgba(198, 164, 63, 0.25) !important;
}

.form-select option {
    background: #1a1a2e !important;
    color: #ffffff !important;
    padding: 8px;
}

.input-group-text {
    background: #0a0a1a !important;
    border: 1px solid #333 !important;
    color: #888 !important;
    font-size: 0.8rem;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.15) !important;
    border: 1px solid #dc3545 !important;
    color: #ff6b6b !important;
    border-radius: 12px;
}

.btn-gold:hover {
    background: #d4b96a !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(198, 164, 63, 0.3);
}

.text-gold {
    color: #C6A43F !important;
}

.text-gold:hover {
    color: #d4b96a !important;
}
</style>

<script>
// Keypad script for registration form
(function() {
    let digits = "";
    const MAX_DIGITS = 7;

    const inputHidden = document.getElementById('digitos_input');
    const displaySpans = document.querySelectorAll('.digit-placeholder');
    const container = document.getElementById('numpadRegisto');

    if (!container) return;

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

    for (let i = 1; i <= 9; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'numpad-btn';
        btn.type = 'button';
        btn.onclick = (function(num) {
            return function() { adicionarDigito(num); };
        })(i);
        container.appendChild(btn);
    }

    const btnZero = document.createElement('button');
    btnZero.textContent = '0';
    btnZero.className = 'numpad-btn';
    btnZero.type = 'button';
    btnZero.onclick = () => adicionarDigito(0);
    container.appendChild(btnZero);

    const btnReset = document.createElement('button');
    btnReset.textContent = 'Reset';
    btnReset.className = 'numpad-btn';
    btnReset.type = 'button';
    btnReset.onclick = resetDigitos;
    container.appendChild(btnReset);

    const btnApagar = document.createElement('button');
    btnApagar.textContent = '⌫';
    btnApagar.className = 'numpad-btn';
    btnApagar.type = 'button';
    btnApagar.onclick = apagarDigito;
    container.appendChild(btnApagar);

    const form = document.getElementById('formRegisto');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (digits.length === 0) {
                e.preventDefault();
                alert('❌ Por favor, defina o código de acesso (1-7 dígitos)!');
            }
        });
    }

    atualizarDisplay();
})();
</script>