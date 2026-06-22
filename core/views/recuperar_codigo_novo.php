<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Criar Novo Código</h3>
                    <p>Defina o seu novo código de acesso (1 a 7 dígitos)</p>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    
                    <form action="?a=recuperar_codigo_novo_submit" method="POST" id="formNovoCodigo">
                        <div class="mb-3">
                            <label class="form-label">Novo código (1 a 7 dígitos) *</label>
                            <input type="hidden" name="novos_digitos" id="novos_digitos" value="">
                            
                            <div class="code-display text-center mb-3" id="codeDisplay">▪ ▪ ▪ ▪ ▪ ▪ ▪</div>
                            <div class="numpad-grid" id="numpadNovoCodigo"></div>
                            <small class="text-muted">Clique nos botões para definir o seu novo código secreto.</small>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Redefinir Código</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
(function() {
    let digits = "";
    const MAX_DIGITS = 7;
    const inputHidden = document.getElementById('novos_digitos');
    const codeDisplay = document.getElementById('codeDisplay');
    const container = document.getElementById('numpadNovoCodigo');
    
    if (!container) return;
    
    function atualizarDisplay() {
        let masked = '';
        for (let i = 0; i < digits.length; i++) masked += '● ';
        for (let i = digits.length; i < MAX_DIGITS; i++) masked += '▪ ';
        codeDisplay.innerText = masked.trim();
        inputHidden.value = digits;
    }
    
    function adicionarDigito(num) {
        if (digits.length < MAX_DIGITS) {
            digits += num.toString();
            atualizarDisplay();
        }
    }
    
    function resetDigitos() { digits = ""; atualizarDisplay(); }
    function apagarDigito() { digits = digits.slice(0, -1); atualizarDisplay(); }
    
    container.innerHTML = '';
    for (let i = 1; i <= 9; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'numpad-btn';
        btn.type = 'button';
        btn.onclick = (function(num) { return function() { adicionarDigito(num); }; })(i);
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
    
    atualizarDisplay();
    
    document.getElementById('formNovoCodigo')?.addEventListener('submit', function(e) {
        if (digits.length === 0) {
            e.preventDefault();
            alert('❌ Por favor, defina o novo código de acesso!');
        }
    });
})();
</script>