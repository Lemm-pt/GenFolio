/**
 * Vitrine.Lemm – Frontend JavaScript
 * Versão limpa - sem funções de perguntas de segurança (obsoletas)
 */

// ============================================================
// NUMERIC KEYPAD (generic)
// ============================================================

function criarTecladoNumerico(containerId, inputId, displayId, maxLen = 7) {
    const container = document.getElementById(containerId);
    const inputHidden = document.getElementById(inputId);
    const display = document.getElementById(displayId);

    if (!container || !inputHidden || !display) {
        console.error('Keypad: elements not found');
        return null;
    }

    let digits = "";

    function updateDisplay() {
        let masked = '';
        for (let i = 0; i < digits.length; i++) {
            masked += '● ';
        }
        for (let i = digits.length; i < maxLen; i++) {
            masked += '▪ ';
        }
        display.innerText = masked.trim();
        inputHidden.value = digits;
    }

    function addDigit(d) {
        if (digits.length < maxLen) {
            digits += d.toString();
            updateDisplay();
        }
    }

    function resetDigits() {
        digits = "";
        updateDisplay();
    }

    function backspace() {
        digits = digits.slice(0, -1);
        updateDisplay();
    }

    function getDigits() {
        return digits;
    }

    container.innerHTML = '';

    for (let i = 1; i <= 9; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'numpad-btn';
        btn.type = 'button';
        btn.addEventListener('click', () => addDigit(i));
        container.appendChild(btn);
    }

    const zeroBtn = document.createElement('button');
    zeroBtn.textContent = '0';
    zeroBtn.className = 'numpad-btn';
    zeroBtn.type = 'button';
    zeroBtn.addEventListener('click', () => addDigit(0));
    container.appendChild(zeroBtn);

    const resetBtn = document.createElement('button');
    resetBtn.textContent = 'Reset';
    resetBtn.className = 'numpad-btn';
    resetBtn.type = 'button';
    resetBtn.addEventListener('click', resetDigits);
    container.appendChild(resetBtn);

    const backBtn = document.createElement('button');
    backBtn.textContent = '⌫';
    backBtn.className = 'numpad-btn';
    backBtn.type = 'button';
    backBtn.addEventListener('click', backspace);
    container.appendChild(backBtn);

    updateDisplay();

    return { addDigit, reset: resetDigits, backspace, getDigits };
}

// ============================================================
// TOAST NOTIFICATIONS
// ============================================================

function mostrarToast(type, message) {
    const existingToast = document.querySelector('.vitrine-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = `vitrine-toast toast-${type}`;

    let icon = '';
    switch (type) {
        case 'success': icon = 'fa-check-circle'; break;
        case 'error':   icon = 'fa-exclamation-circle'; break;
        case 'warning': icon = 'fa-exclamation-triangle'; break;
        default:        icon = 'fa-info-circle';
    }

    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
        <div class="toast-progress"></div>
    `;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// ============================================================
// FORM VALIDATION
// ============================================================

function validarFormRegisto() {
    const digits = document.getElementById('digitos_input')?.value || '';
    if (digits.length < 1) {
        mostrarToast('error', 'Por favor, defina um código de 1 a 7 dígitos.');
        return false;
    }
    return true;
}

function validarFormLogin() {
    const digits = document.getElementById('login_digitos')?.value || '';
    if (digits.length < 1) {
        mostrarToast('error', 'Por favor, insira o seu código de acesso (1 a 7 dígitos).');
        return false;
    }
    return true;
}

// ============================================================
// ADMIN – DELETE ITEMS
// ============================================================

async function excluirItem(id, type) {
    if (!confirm('Tem certeza que deseja excluir este item?')) return;

    try {
        const response = await fetch(`?a=admin_${type}_deletar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
        const data = await response.json();

        if (data.success) {
            mostrarToast('success', 'Item excluído com sucesso!');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarToast('error', 'Erro ao excluir item.');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('error', 'Erro na comunicação com o servidor.');
    }
}

// ============================================================
// ADMIN – MOBILE MENU
// ============================================================

function toggleMobileMenu() {
    const sidebar = document.querySelector('.admin-sidebar');
    if (sidebar) sidebar.classList.toggle('mobile-open');
}

// ============================================================
// INITIALIZATION
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.querySelector('form[action*="criar_cliente"]');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            if (!validarFormRegisto()) e.preventDefault();
        });
    }

    const loginForm = document.querySelector('form[action*="login_submit"]');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            if (!validarFormLogin()) e.preventDefault();
        });
    }
});

// Expor funções globais
window.criarTecladoNumerico = criarTecladoNumerico;
window.mostrarToast = mostrarToast;
window.excluirItem = excluirItem;
window.toggleMobileMenu = toggleMobileMenu;



// assets/js/app.js - Adicionar ao final do arquivo
// Inicialização do Seven Lux
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o SevenLux já foi inicializado
    if (typeof window.sevenLux === 'undefined') {
        // Carregar o script dinamicamente se necessário
        const script = document.createElement('script');
        script.src = BASE_URL + 'assets/js/seven-lux.js';
        script.onload = function() {
            // O SevenLux se inicializa automaticamente
        };
        document.head.appendChild(script);
    }
});

// ============================================
// FUNÇÕES AUXILIARES PARA TEMAS
// ============================================

/**
 * Obtém o tema atual
 * @returns {string} Nome do tema atual
 */
function getCurrentTheme() {
    return localStorage.getItem('sevenlux_theme') || 'ruby';
}

/**
 * Aplica um tema específico
 * @param {string} themeKey - Nome do tema (ruby, sapphire, emerald, etc.)
 */
function setTheme(themeKey) {
    if (window.sevenLux) {
        window.sevenLux.selectTheme(themeKey);
    } else {
        // Fallback: salvar para quando o script carregar
        localStorage.setItem('sevenlux_theme', themeKey);
    }
}

/**
 * Obtém as cores do tema atual
 * @returns {Object} Cores do tema
 */
function getThemeColors() {
    const themes = {
        ruby: { primary: '#E74C3C', secondary: '#C0392B' },
        sapphire: { primary: '#3498DB', secondary: '#2980B9' },
        emerald: { primary: '#2ECC71', secondary: '#27AE60' },
        amethyst: { primary: '#9B59B6', secondary: '#8E44AD' },
        topaz: { primary: '#F39C12', secondary: '#E67E22' },
        pearl: { primary: '#ECF0F1', secondary: '#BDC3C7' },
        diamond: { primary: '#00D4FF', secondary: '#0099CC' }
    };
    return themes[getCurrentTheme()] || themes.ruby;
}

