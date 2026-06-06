/**
 * Vitrine.Lemm – Sphere Guardian
 * Authentication system with slug + 7‑digit code + security questions
 * 
 * @package SevenLux
 */

// ============================================================
// NUMERIC KEYPAD (generic)
// ============================================================

/**
 * Creates an interactive numeric keypad
 * @param {string} containerId - ID of the element where buttons will be created
 * @param {string} inputId - ID of the hidden input that stores the digits
 * @param {string} displayId - ID of the element that shows the dots (▪)
 * @param {number} maxLen - Maximum number of digits (default 7)
 * @returns {object|null} Functions to control the keypad (addDigit, reset, backspace, getDigits, setDigits)
 */
function criarTecladoNumerico(containerId, inputId, displayId, maxLen = 7) {
    const container = document.getElementById(containerId);
    const inputHidden = document.getElementById(inputId);
    const display = document.getElementById(displayId);

    if (!container || !inputHidden || !display) {
        console.error('Keypad: elements not found', { containerId, inputId, displayId });
        return null;
    }

    let digits = "";

    function updateDisplay() {
        let masked = digits.split('').map(() => '▪').join(' ');
        const missingSpaces = maxLen - digits.length;
        if (missingSpaces > 0) {
            masked += ' ▪'.repeat(missingSpaces);
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

    // Clear container before creating (prevents duplication)
    container.innerHTML = '';

    // Create buttons 1-9
    for (let i = 1; i <= 9; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'numpad-btn';
        btn.type = 'button';  // Crucial: prevents form submission
        btn.addEventListener('click', () => addDigit(i));
        container.appendChild(btn);
    }

    // Button 0
    const zeroBtn = document.createElement('button');
    zeroBtn.textContent = '0';
    zeroBtn.className = 'numpad-btn';
    zeroBtn.type = 'button';
    zeroBtn.addEventListener('click', () => addDigit(0));
    container.appendChild(zeroBtn);

    // Reset button
    const resetBtn = document.createElement('button');
    resetBtn.textContent = 'Reset';
    resetBtn.className = 'numpad-btn numpad-reset';
    resetBtn.type = 'button';
    resetBtn.addEventListener('click', resetDigits);
    container.appendChild(resetBtn);

    // Backspace button
    const backBtn = document.createElement('button');
    backBtn.textContent = '⌫';
    backBtn.className = 'numpad-btn numpad-back';
    backBtn.type = 'button';
    backBtn.addEventListener('click', backspace);
    container.appendChild(backBtn);

    updateDisplay();

    return {
        addDigit,
        reset: resetDigits,
        backspace,
        getDigits,
        setDigits: (newDigits) => {
            digits = newDigits.toString().slice(0, maxLen);
            updateDisplay();
        }
    };
}

// ============================================================
// DEVICE MANAGEMENT (Fingerprint) – currently unused
// ============================================================

/**
 * Generates a simple device fingerprint (browser identification)
 * @returns {Promise<string>} Fingerprint hash
 */
async function gerarFingerprint() {
    const components = [
        navigator.userAgent,
        screen.width + 'x' + screen.height,
        screen.colorDepth,
        new Date().getTimezoneOffset(),
        navigator.language,
        !!window.localStorage,
        !!window.sessionStorage
    ];

    const text = components.join('|');
    if (typeof CryptoJS !== 'undefined' && CryptoJS.SHA256) {
        return CryptoJS.SHA256(text).toString();
    }
    // Fallback: simple hash
    let hash = 0;
    for (let i = 0; i < text.length; i++) {
        const char = text.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    return Math.abs(hash).toString(16);
}

/**
 * Gets or creates a persistent device_id (localStorage + cookie)
 * @returns {string} Unique device_id
 */
function obterDeviceId() {
    let deviceId = localStorage.getItem('vitrine_device_id');
    if (!deviceId) {
        deviceId = 'dev_' + Date.now() + '_' + Math.random().toString(36).substr(2, 16);
        localStorage.setItem('vitrine_device_id', deviceId);
        // Also store in cookie for redundancy
        document.cookie = `vitrine_device_id=${deviceId}; path=/; max-age=31536000`;
    }
    return deviceId;
}

/**
 * Sends device data to the server for authorization
 * @param {string} slug - User slug
 * @returns {Promise<boolean>}
 */
async function registrarDispositivo(slug) {
    const deviceId = obterDeviceId();
    const fingerprint = await gerarFingerprint();

    try {
        const response = await fetch(`?a=ajax_registar_dispositivo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `slug=${encodeURIComponent(slug)}&device_id=${deviceId}&fingerprint=${fingerprint}`
        });
        const data = await response.json();
        return data.success === true;
    } catch (error) {
        console.error('Error registering device:', error);
        return false;
    }
}

// ============================================================
// TOAST NOTIFICATIONS
// ============================================================

/**
 * Displays a toast notification
 * @param {string} type - 'success', 'error', 'warning', 'info'
 * @param {string} message - Notification text
 */
function mostrarToast(type, message) {
    // Remove existing toast
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
// FORM VALIDATION (before submit)
// ============================================================

/**
 * Validates the registration form before submission
 * @returns {boolean}
 */
function validarFormRegisto() {
    const digits = document.getElementById('digitos_input')?.value || '';
    const answerId = document.querySelector('select[name="resposta_id"]')?.value;

    if (digits.length < 1) {
        mostrarToast('error', 'Por favor, defina um código de 1 a 7 dígitos.');
        return false;
    }
    if (!answerId || answerId === '') {
        mostrarToast('error', 'Por favor, escolha uma resposta para a pergunta de segurança.');
        return false;
    }
    return true;
}

/**
 * Validates the login form before submission
 * @returns {boolean}
 */
function validarFormLogin() {
    const digits = document.getElementById('login_digitos')?.value || '';
    const slug = document.querySelector('input[name="text_slug"]')?.value;

    if (digits.length < 1) {
        mostrarToast('error', 'Por favor, insira o seu código de acesso (1 a 7 dígitos).');
        return false;
    }
    if (!slug || slug.trim() === '') {
        mostrarToast('error', 'Por favor, insira o slug do seu site.');
        return false;
    }
    return true;
}

/**
 * Validates the code recovery form before submission
 * @returns {boolean}
 */
function validarFormRecuperacao() {
    const newDigits = document.getElementById('novos_digitos')?.value || '';
    const answerId = document.querySelector('select[name="resposta_id"]')?.value;
    const slug = document.querySelector('input[name="text_slug"]')?.value;

    if (!slug || slug.trim() === '') {
        mostrarToast('error', 'Insira o slug do seu site.');
        return false;
    }
    if (newDigits.length < 1) {
        mostrarToast('error', 'Defina um novo código de 1 a 7 dígitos.');
        return false;
    }
    if (!answerId || answerId === '') {
        mostrarToast('error', 'Selecione a resposta à pergunta de segurança.');
        return false;
    }
    return true;
}

// ============================================================
// LOAD SECURITY QUESTION VIA AJAX (for recovery)
// ============================================================

/**
 * Loads the security question for a given slug
 * @param {string} slug - User slug
 * @param {HTMLElement} questionDiv - Element where to display the question
 * @param {HTMLElement} answerSelect - Select element to populate with answers
 * @returns {Promise<boolean>}
 */
async function carregarPerguntaPorSlug(slug, questionDiv, answerSelect) {
    if (!slug || slug.trim() === '') return false;

    try {
        const response = await fetch(`?a=ajax_get_pergunta&slug=${encodeURIComponent(slug)}`);
        const data = await response.json();

        if (data.pergunta && data.respostas) {
            if (questionDiv) questionDiv.style.display = 'block';
            if (document.getElementById('perguntaTexto')) {
                document.getElementById('perguntaTexto').innerText = data.pergunta;
            }
            if (answerSelect) {
                answerSelect.innerHTML = '<option value="">Selecione...</option>';
                data.respostas.forEach((answer, idx) => {
                    answerSelect.innerHTML += `<option value="${idx + 1}">${escapeHtml(answer)}</option>`;
                });
            }
            return true;
        } else {
            if (questionDiv) questionDiv.style.display = 'none';
            mostrarToast('error', 'Slug não encontrado ou conta inativa.');
            return false;
        }
    } catch (error) {
        console.error('Error loading question:', error);
        return false;
    }
}

// ============================================================
// UTILITIES
// ============================================================

/**
 * Escapes HTML characters to prevent XSS
 * @param {string} text
 * @returns {string}
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Prevents duplicate form submission
 * @param {string} formId
 */
function prevenirSubmissaoDuplicada(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    let submitted = false;
    form.addEventListener('submit', function (e) {
        if (submitted) {
            e.preventDefault();
            mostrarToast('warning', 'Aguarde, processando...');
        }
        submitted = true;
        setTimeout(() => { submitted = false; }, 3000);
    });
}

// ============================================================
// ADMIN – DELETE ITEMS (AJAX)
// ============================================================

/**
 * Deletes an item via AJAX (gallery, product, blog post, service)
 * @param {number} id - Item ID
 * @param {string} type - 'galeria', 'produto', 'publicacao', 'servico'
 */
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
// ADMIN – MOBILE MENU TOGGLE
// ============================================================

function toggleMobileMenu() {
    const sidebar = document.querySelector('.admin-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('mobile-open');
    }
}

// ============================================================
// GENERAL INITIALIZATION
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    // Attach validation events to forms
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

    const recoveryForm = document.querySelector('form[action*="recuperar_codigo_submit"]');
    if (recoveryForm) {
        recoveryForm.addEventListener('submit', (e) => {
            if (!validarFormRecuperacao()) e.preventDefault();
        });
    }

    // Load question automatically on recovery form (on blur)
    const slugInput = document.querySelector('input[name="text_slug"]');
    const questionDiv = document.getElementById('perguntaDiv');
    const answerSelect = document.getElementById('respostaSelect');

    if (slugInput && questionDiv && answerSelect) {
        slugInput.addEventListener('blur', function () {
            carregarPerguntaPorSlug(this.value, questionDiv, answerSelect);
        });
    }

    // Prevent duplicate submissions
    prevenirSubmissaoDuplicada('formRegisto');
    prevenirSubmissaoDuplicada('formLogin');
});

// ============================================================
// EXPOSE GLOBAL FUNCTIONS (for use in views)
// ============================================================
window.criarTecladoNumerico = criarTecladoNumerico;
window.mostrarToast = mostrarToast;
window.excluirItem = excluirItem;
window.toggleMobileMenu = toggleMobileMenu;
window.gerarFingerprint = gerarFingerprint;
window.obterDeviceId = obterDeviceId;
window.registrarDispositivo = registrarDispositivo;
window.carregarPerguntaPorSlug = carregarPerguntaPorSlug;