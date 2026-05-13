// ========================================================
// VITRINE.LEMM - ADMIN INTERATIVO
// ========================================================

// Função genérica para excluir itens com AJAX
async function excluirItem(id, tipo, callback) {
    if(!confirm('Tem certeza que deseja excluir este item?')) return;
    
    try {
        const response = await axios.post(`?a=admin_${tipo}_deletar`, `id=${id}`, {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        
        if(response.data.success) {
            // Mostrar toast de sucesso
            mostrarToast('success', 'Item excluído com sucesso!');
            if(callback) callback();
            else setTimeout(() => location.reload(), 1000);
        } else {
            mostrarToast('error', 'Erro ao excluir item');
        }
    } catch(error) {
        console.error('Erro:', error);
        mostrarToast('error', 'Erro na comunicação com o servidor');
    }
}

// Função para mostrar toasts (mensagens flutuantes)
function mostrarToast(tipo, mensagem) {
    // Remover toast existente
    const toastExistente = document.querySelector('.vitrine-toast');
    if(toastExistente) toastExistente.remove();
    
    // Criar novo toast
    const toast = document.createElement('div');
    toast.className = `vitrine-toast toast-${tipo}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${mensagem}</span>
        </div>
        <div class="toast-progress"></div>
    `;
    document.body.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remover após 3 segundos
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ========================================================
// ADMIN - MENU MOBILE RESPONSIVO
// ========================================================
function toggleMobileMenu() {
    const sidebar = document.querySelector('.admin-sidebar');
    if(sidebar) {
        sidebar.classList.toggle('mobile-open');
    }
}

// ========================================================
// ADMIN - CONFIRMAÇÃO PARA EXCLUSÕES (via botões)
// ========================================================
document.addEventListener('DOMContentLoaded', function() {
    // Botões de excluir com classe 'btn-delete'
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const tipo = this.dataset.tipo;
            if(!confirm('Tem certeza que deseja excluir?')) return;
            
            try {
                const response = await axios.post(`?a=admin_${tipo}_deletar`, `id=${id}`, {
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
                
                if(response.data.success) {
                    mostrarToast('success', 'Excluído com sucesso!');
                    // Remover elemento da tabela
                    const row = this.closest('tr');
                    if(row) row.remove();
                } else {
                    mostrarToast('error', 'Erro ao excluir');
                }
            } catch(error) {
                mostrarToast('error', 'Erro na comunicação');
            }
        });
    });
});

// ========================================================
// ADMIN - FILTRO DE TABELAS (busca em tempo real)
// ========================================================
function filtrarTabela(inputId, tableId) {
    const input = document.getElementById(inputId);
    if(!input) return;
    
    input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const table = document.getElementById(tableId);
        const rows = table.getElementsByTagName('tr');
        
        for(let i = 1; i < rows.length; i++) {
            const row = rows[i];
            let text = '';
            const cells = row.getElementsByTagName('td');
            for(let j = 0; j < cells.length; j++) {
                text += cells[j].textContent.toLowerCase() + ' ';
            }
            row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
        }
    });
}

// ========================================================
// ADMIN - SELECT ALL (selecionar todos os checkboxes)
// ========================================================
function toggleSelectAll(checkbox, itemsClass) {
    document.querySelectorAll(itemsClass).forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

// ========================================================
// TOAST STYLES (injetar CSS dinamicamente)
// ========================================================
const toastStyles = `
<style>
.vitrine-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    min-width: 280px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 9999;
    overflow: hidden;
}
.vitrine-toast.show {
    transform: translateX(0);
}
.toast-content {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.toast-success .toast-content {
    background: #d4edda;
    color: #155724;
}
.toast-error .toast-content {
    background: #f8d7da;
    color: #721c24;
}
.toast-content i {
    font-size: 20px;
}
.toast-progress {
    height: 3px;
    background: #C6A43F;
    width: 100%;
    animation: progressBar 3s linear forwards;
}
@keyframes progressBar {
    0% { width: 100%; }
    100% { width: 0%; }
}
</style>
`;
document.head.insertAdjacentHTML('beforeend', toastStyles);