<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-header bg-gold text-dark text-center">
                    <h3>Recuperar Código de Acesso</h3>
                    <p>Responda à pergunta de segurança para criar um novo código</p>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['sucesso'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
                    <?php endif; ?>
                    
                    <form action="?a=recuperar_codigo_submit" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Slug do seu site</label>
                            <input type="text" name="text_slug" class="form-control" required placeholder="ex: meu-negocio">
                        </div>
                        
                        <?php
                        // Mostrar pergunta apenas se o slug for submetido? 
                        // Para simplificar, o controller vai carregar a pergunta após POST.
                        // Neste formulário, o utilizador insere o slug, depois submete e vê a pergunta.
                        // Mas para melhor UX, podes fazer via AJAX. Aqui faremos por passos:
                        ?>
                        <div class="mb-3" id="perguntaDiv" style="display: none;">
                            <label class="form-label">Pergunta de segurança</label>
                            <p id="perguntaTexto" class="bg-secondary p-2 rounded"></p>
                            <label class="form-label">Resposta</label>
                            <select name="resposta_id" id="respostaSelect" class="form-select">
                                <option value="">Selecione...</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Novo código (1-7 dígitos)</label>
                            <input type="hidden" name="novos_digitos" id="novos_digitos" value="">
                            <div class="code-display mb-2" id="newCodeDisplay">▪ ▪ ▪ ▪ ▪ ▪ ▪</div>
                            <div class="numpad-grid" id="numpadRecupera"></div>
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
// Gestão do teclado numérico para o novo código
let newDigits = "";
const maxLen = 7;
const inputNew = document.getElementById('novos_digitos');
const displayNew = document.getElementById('newCodeDisplay');
const containerNew = document.getElementById('numpadRecupera');

function atualizarNew() {
    let masked = newDigits.split('').map(() => '▪').join(' ');
    masked += ' ▪'.repeat(maxLen - newDigits.length);
    displayNew.innerText = masked.trim();
    inputNew.value = newDigits;
}
function addDigitNew(d) {
    if(newDigits.length < maxLen) { newDigits += d; atualizarNew(); }
}
function resetNew() { newDigits = ""; atualizarNew(); }
function backspaceNew() { newDigits = newDigits.slice(0, -1); atualizarNew(); }

// No final do ficheiro, substitui o script do teclado
for(let i=1; i<=9; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.className = 'numpad-btn';
    btn.type = 'button';  // <-- ADICIONAR ESTA LINHA
    btn.onclick = () => addDigitNew(i.toString());
    containerNew.appendChild(btn);
}
const zeroBtn = document.createElement('button');
zeroBtn.textContent = '0';
zeroBtn.type = 'button';  // <-- ADICIONAR
zeroBtn.onclick = () => addDigitNew('0');
containerNew.appendChild(zeroBtn);
const resetBtn = document.createElement('button');
resetBtn.textContent = 'Reset';
resetBtn.type = 'button';  // <-- ADICIONAR
resetBtn.onclick = resetNew;
containerNew.appendChild(resetBtn);
const backBtn = document.createElement('button');
backBtn.textContent = '⌫';
backBtn.type = 'button';  // <-- ADICIONAR
backBtn.onclick = backspaceNew;
containerNew.appendChild(backBtn);

atualizarNew();

// Carregar pergunta quando o slug for preenchido (perda de foco)
document.querySelector('input[name="text_slug"]').addEventListener('blur', async function() {
    const slug = this.value;
    if(!slug) return;
    const resp = await fetch(`?a=ajax_get_pergunta&slug=${slug}`);
    const data = await resp.json();
    if(data.pergunta) {
        document.getElementById('perguntaDiv').style.display = 'block';
        document.getElementById('perguntaTexto').innerText = data.pergunta;
        const select = document.getElementById('respostaSelect');
        select.innerHTML = '<option value="">Selecione...</option>';
        data.respostas.forEach((r, idx) => {
            select.innerHTML += `<option value="${idx+1}">${r}</option>`;
        });
    } else {
        alert('Slug não encontrado ou conta inativa.');
    }
});
</script>