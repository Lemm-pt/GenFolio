<div class="container py-5" style="padding-top: 100px !important;"> <!-- Aumentei o padding-top -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-gold text-center">
                    <h3>Criar Conta</h3>
                    <p>Registe-se para criar o seu site</p>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro'];
                            unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>index.php?a=criar_cliente" method="POST" id="formRegisto">
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="text_email" class="form-control" required>
                        </div>

                        <!-- Slug (nome do site) -->
                        <div class="mb-3">
                            <label class="form-label">Nome do site (slug) *</label>
                            <input type="text" name="text_slug" class="form-control" placeholder="ex: meu-negocio" required>
                            <small class="text-muted">O seu site estará disponível em: <?= BASE_URL ?>[slug]/</small>
                        </div>

                        <!-- ===== NOVOS CAMPOS: Cidade, País, Categoria ===== -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="text_cidade" class="form-control" placeholder="Ex: Lisboa, Porto, Braga">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">País</label>
                                <input type="text" name="text_pais" class="form-control" placeholder="Ex: Portugal">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoria / Tipo de negócio</label>
                            <select name="text_categoria" class="form-select">
                                <option value="">Selecione...</option>
                                <option value="Alimentação">🍔 Alimentação</option>
                                <option value="Moda">👗 Moda</option>
                                <option value="Tecnologia">💻 Tecnologia</option>
                                <option value="Saúde">🏥 Saúde</option>
                                <option value="Educação">📚 Educação</option>
                                <option value="Construção">🏗️ Construção</option>
                                <option value="Turismo">✈️ Turismo</option>
                                <option value="Beleza">💅 Beleza</option>
                                <option value="Automóvel">🚗 Automóvel</option>
                                <option value="Outro">📦 Outro</option>
                            </select>
                            <small class="text-muted">Opcional. Ajuda a categorizar o seu negócio.</small>
                        </div>
                        <!-- ===== FIM DOS NOVOS CAMPOS ===== -->

                        <!-- Access code (digits) -->
                        <div class="mb-3">
                            <label class="form-label">Código de acesso (1 a 7 dígitos) *</label>
                            <input type="hidden" name="text_digitos" id="digitos_input" value="">

                            <!-- Digit display -->
                            <div class="code-display text-center mb-3" id="codeDisplay">
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                                <span class="digit-placeholder">▪</span>
                            </div>

                            <!-- Numeric keypad -->
                            <div class="numpad-grid" id="numpadRegisto"></div>
                            <small class="text-muted">Clique nos botões para definir o seu código secreto.</small>
                        </div>

                        <!-- Security question -->
                        <?php
                        $clienteModel = new \core\models\Clientes();
                        // Usar a versão da BD se existir, senão fallback
                        $pergunta = method_exists($clienteModel, 'getPerguntaAleatoriaFromDB') 
                            ? $clienteModel->getPerguntaAleatoriaFromDB() 
                            : $clienteModel->getPerguntaAleatoria();
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Pergunta de segurança *</label>
                            <input type="hidden" name="pergunta_id" value="<?= $pergunta['id'] ?>">
                            <p class="bg-secondary p-2 rounded"><?= htmlspecialchars($pergunta['texto']) ?></p>
                            <label class="form-label">Escolha a sua resposta *</label>
                            <select name="resposta_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($pergunta['respostas'] as $idx => $resp): ?>
                                    <option value="<?= $idx + 1 ?>"><?= htmlspecialchars($resp) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-gold w-100">Registar</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-gold">Já tem conta? <br>Faça login em Entrar acima <i class="fa fa-arrow-up"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilo para o padding-top maior */
.container.py-5 {
    padding-top: 100px !important;
}

@media (max-width: 768px) {
    .container.py-5 {
        padding-top: 80px !important;
    }
}

/* Estilo do keypad (igual ao que já tinhas) */
.numpad-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    max-width: 250px;
    margin: 0 auto;
}
.numpad-btn {
    padding: 15px;
    font-size: 20px;
    font-weight: bold;
    background: #2a2a35;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}
.code-display {
    font-family: monospace;
    font-size: 24px;
    letter-spacing: 5px;
    text-align: center;
    background: #1e1e2e;
    padding: 10px;
    border-radius: 10px;
}
</style>

<script>
    // Simple keypad script for registration form
    (function () {
        let digits = "";
        const MAX_DIGITS = 7;

        const inputHidden = document.getElementById('digitos_input');
        const displaySpans = document.querySelectorAll('.digit-placeholder');
        const container = document.getElementById('numpadRegisto');

        if (!container) {
            console.error('Container not found');
            return;
        }

        function atualizarDisplay() {
            for (let i = 0; i < MAX_DIGITS; i++) {
                if (displaySpans[i]) {
                    displaySpans[i].textContent = (i < digits.length) ? '●' : '▪';
                }
            }
            if (inputHidden) inputHidden.value = digits;
            console.log('Current digits:', digits);
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

        // Buttons 1-9
        for (let i = 1; i <= 9; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'numpad-btn';
            btn.type = 'button';
            btn.onclick = (function (num) {
                return function () {
                    adicionarDigito(num);
                };
            })(i);
            container.appendChild(btn);
        }

        // Button 0
        const btnZero = document.createElement('button');
        btnZero.textContent = '0';
        btnZero.className = 'numpad-btn';
        btnZero.type = 'button';
        btnZero.onclick = () => adicionarDigito(0);
        container.appendChild(btnZero);

        // Reset button
        const btnReset = document.createElement('button');
        btnReset.textContent = 'Reset';
        btnReset.className = 'numpad-btn';
        btnReset.type = 'button';
        btnReset.onclick = resetDigitos;
        container.appendChild(btnReset);

        // Backspace button
        const btnApagar = document.createElement('button');
        btnApagar.textContent = '⌫';
        btnApagar.className = 'numpad-btn';
        btnApagar.type = 'button';
        btnApagar.onclick = apagarDigito;
        container.appendChild(btnApagar);

        // Validate before submit
        const form = document.getElementById('formRegisto');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (digits.length === 0) {
                    e.preventDefault();
                    alert('❌ Por favor, defina o código de acesso (1-7 dígitos)!');
                }
            });
        }

        atualizarDisplay();
        console.log('✅ Keypad initialised successfully!');
    })();
</script>