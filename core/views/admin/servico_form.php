<div class="container mt-4">
    <h2><?= isset($servico) ? 'Editar Serviço' : 'Novo Serviço' ?></h2>
    
    <form action="" method="POST" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= $servico->titulo ?? '' ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" required><?= $servico->descricao ?? '' ?></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Ícone (Font Awesome)</label>
            <div class="input-group">
                <span class="input-group-text" id="icon-preview"><i class="fas <?= $servico->icone ?? 'fa-star' ?>"></i></span>
                <input type="text" name="icone" id="icone_input" class="form-control" value="<?= $servico->icone ?? 'fa-star' ?>" placeholder="Ex: fa-building, fa-chart-line">
                <button class="btn btn-outline-gold" type="button" onclick="toggleIconSelector()">
                    <i class="fas fa-icons"></i> Escolher Ícone
                </button>
            </div>
            <small class="text-muted">Clique em "Escolher Ícone" para ver a lista completa</small>
        </div>
        
        <!-- Seletor de Ícones (Accordion) -->
        <div id="iconSelector" style="display: none; margin-top: 15px;">
            <div class="card">
                <div class="card-header bg-gold text-dark">
                    <strong><i class="fas fa-icons"></i> Selecione um ícone</strong>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    
                    <!-- Negócios e Lojas -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-negocios')">
                                <i class="fas fa-store"></i> Negócios e Lojas
                            </button>
                        </div>
                        <div id="categoria-negocios" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-cart-shopping')"><i class="fas fa-cart-shopping fa-2x"></i><span>fa-cart-shopping</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-shirt')"><i class="fas fa-shirt fa-2x"></i><span>fa-shirt</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-fish-fins')"><i class="fas fa-fish-fins fa-2x"></i><span>fa-fish-fins</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-drumstick-bite')"><i class="fas fa-drumstick-bite fa-2x"></i><span>fa-drumstick-bite</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-book')"><i class="fas fa-book fa-2x"></i><span>fa-book</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-car')"><i class="fas fa-car fa-2x"></i><span>fa-car</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-basket-shopping')"><i class="fas fa-basket-shopping fa-2x"></i><span>fa-basket-shopping</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-ice-cream')"><i class="fas fa-ice-cream fa-2x"></i><span>fa-ice-cream</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-wine-glass')"><i class="fas fa-wine-glass fa-2x"></i><span>fa-wine-glass</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-gem')"><i class="fas fa-gem fa-2x"></i><span>fa-gem</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-ring')"><i class="fas fa-ring fa-2x"></i><span>fa-ring</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-gift')"><i class="fas fa-gift fa-2x"></i><span>fa-gift</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Serviços e Profissionais -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-servicos')">
                                <i class="fas fa-briefcase"></i> Serviços e Profissionais
                            </button>
                        </div>
                        <div id="categoria-servicos" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-phone')"><i class="fas fa-phone fa-2x"></i><span>fa-phone</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-gavel')"><i class="fas fa-gavel fa-2x"></i><span>fa-gavel</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-stethoscope')"><i class="fas fa-stethoscope fa-2x"></i><span>fa-stethoscope</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-scissors')"><i class="fas fa-scissors fa-2x"></i><span>fa-scissors</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-wrench')"><i class="fas fa-wrench fa-2x"></i><span>fa-wrench</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-calculator')"><i class="fas fa-calculator fa-2x"></i><span>fa-calculator</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-utensils')"><i class="fas fa-utensils fa-2x"></i><span>fa-utensils</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-mug-hot')"><i class="fas fa-mug-hot fa-2x"></i><span>fa-mug-hot</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-hotel')"><i class="fas fa-hotel fa-2x"></i><span>fa-hotel</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-church')"><i class="fas fa-church fa-2x"></i><span>fa-church</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-school')"><i class="fas fa-school fa-2x"></i><span>fa-school</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-hand-holding-heart')"><i class="fas fa-hand-holding-heart fa-2x"></i><span>fa-hand-holding-heart</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Logística e Localização -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-logistica')">
                                <i class="fas fa-location-dot"></i> Logística e Localização
                            </button>
                        </div>
                        <div id="categoria-logistica" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-truck')"><i class="fas fa-truck fa-2x"></i><span>fa-truck</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-store')"><i class="fas fa-store fa-2x"></i><span>fa-store</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-map-location-dot')"><i class="fas fa-map-location-dot fa-2x"></i><span>fa-map-location-dot</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-location-dot')"><i class="fas fa-location-dot fa-2x"></i><span>fa-location-dot</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-plane')"><i class="fas fa-plane fa-2x"></i><span>fa-plane</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-ship')"><i class="fas fa-ship fa-2x"></i><span>fa-ship</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-train')"><i class="fas fa-train fa-2x"></i><span>fa-train</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-bus')"><i class="fas fa-bus fa-2x"></i><span>fa-bus</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navegação e Interface -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-navegacao')">
                                <i class="fas fa-compass"></i> Navegação e Interface
                            </button>
                        </div>
                        <div id="categoria-navegacao" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-house')"><i class="fas fa-house fa-2x"></i><span>fa-house</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-magnifying-glass')"><i class="fas fa-magnifying-glass fa-2x"></i><span>fa-magnifying-glass</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-bars')"><i class="fas fa-bars fa-2x"></i><span>fa-bars</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-gear')"><i class="fas fa-gear fa-2x"></i><span>fa-gear</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-bell')"><i class="fas fa-bell fa-2x"></i><span>fa-bell</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-xmark')"><i class="fas fa-xmark fa-2x"></i><span>fa-xmark</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-check')"><i class="fas fa-check fa-2x"></i><span>fa-check</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-ellipsis-vertical')"><i class="fas fa-ellipsis-vertical fa-2x"></i><span>fa-ellipsis-vertical</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Utilizadores e Contacto -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-utilizadores')">
                                <i class="fas fa-users"></i> Utilizadores e Contacto
                            </button>
                        </div>
                        <div id="categoria-utilizadores" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-user')"><i class="fas fa-user fa-2x"></i><span>fa-user</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-users')"><i class="fas fa-users fa-2x"></i><span>fa-users</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-envelope')"><i class="fas fa-envelope fa-2x"></i><span>fa-envelope</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-phone')"><i class="fas fa-phone fa-2x"></i><span>fa-phone</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-location-dot')"><i class="fas fa-location-dot fa-2x"></i><span>fa-location-dot</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-address-card')"><i class="fas fa-address-card fa-2x"></i><span>fa-address-card</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edição e Ficheiros -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-edicao')">
                                <i class="fas fa-pen-to-square"></i> Edição e Ficheiros
                            </button>
                        </div>
                        <div id="categoria-edicao" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-pen-to-square')"><i class="fas fa-pen-to-square fa-2x"></i><span>fa-pen-to-square</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-trash-can')"><i class="fas fa-trash-can fa-2x"></i><span>fa-trash-can</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-plus')"><i class="fas fa-plus fa-2x"></i><span>fa-plus</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-floppy-disk')"><i class="fas fa-floppy-disk fa-2x"></i><span>fa-floppy-disk</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-file-lines')"><i class="fas fa-file-lines fa-2x"></i><span>fa-file-lines</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-image')"><i class="fas fa-image fa-2x"></i><span>fa-image</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-folder')"><i class="fas fa-folder fa-2x"></i><span>fa-folder</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comércio e Negócios -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-comercio')">
                                <i class="fas fa-chart-line"></i> Comércio e Negócios
                            </button>
                        </div>
                        <div id="categoria-comercio" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-cart-shopping')"><i class="fas fa-cart-shopping fa-2x"></i><span>fa-cart-shopping</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-bag-shopping')"><i class="fas fa-bag-shopping fa-2x"></i><span>fa-bag-shopping</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-credit-card')"><i class="fas fa-credit-card fa-2x"></i><span>fa-credit-card</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-chart-line')"><i class="fas fa-chart-line fa-2x"></i><span>fa-chart-line</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-briefcase')"><i class="fas fa-briefcase fa-2x"></i><span>fa-briefcase</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-chart-simple')"><i class="fas fa-chart-simple fa-2x"></i><span>fa-chart-simple</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Setas e Movimento -->
                    <div class="accordion-item mb-2">
                        <div class="accordion-header">
                            <button class="btn btn-sm btn-outline-secondary w-100 text-start" type="button" onclick="toggleCategory('categoria-setas')">
                                <i class="fas fa-arrow-right"></i> Setas e Movimento
                            </button>
                        </div>
                        <div id="categoria-setas" class="icon-category" style="display: none;">
                            <div class="p-2">
                                <div class="icon-grid">
                                    <div class="icon-option" onclick="selectIcon('fa-arrow-right')"><i class="fas fa-arrow-right fa-2x"></i><span>fa-arrow-right</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-chevron-down')"><i class="fas fa-chevron-down fa-2x"></i><span>fa-chevron-down</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-up-right-from-square')"><i class="fas fa-up-right-from-square fa-2x"></i><span>fa-up-right-from-square</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-download')"><i class="fas fa-download fa-2x"></i><span>fa-download</span></div>
                                    <div class="icon-option" onclick="selectIcon('fa-upload')"><i class="fas fa-upload fa-2x"></i><span>fa-upload</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Ordem</label>
            <input type="number" name="ordem" class="form-control" value="<?= $servico->ordem ?? 0 ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="?a=admin_servicos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<style>
.icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}
.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}
.icon-option:hover {
    background: #C6A43F;
    color: white;
    border-color: #C6A43F;
    transform: scale(1.02);
}
.icon-option i {
    font-size: 24px;
    margin-bottom: 5px;
}
.icon-option span {
    font-size: 11px;
    text-align: center;
    word-break: break-all;
}
.accordion-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}
.btn-outline-secondary {
    border-radius: 0;
    text-align: left;
    padding: 10px;
}
.icon-category {
    border-top: 1px solid #eee;
    background: #f9f9f9;
}
</style>

<script>
function toggleIconSelector() {
    var selector = document.getElementById('iconSelector');
    if (selector.style.display === 'none') {
        selector.style.display = 'block';
    } else {
        selector.style.display = 'none';
    }
}

function toggleCategory(categoryId) {
    var category = document.getElementById(categoryId);
    if (category.style.display === 'none') {
        category.style.display = 'block';
    } else {
        category.style.display = 'none';
    }
}

function selectIcon(iconName) {
    // Atualizar o campo input
    document.getElementById('icone_input').value = iconName;
    
    // Atualizar a pré-visualização
    document.getElementById('icon-preview').innerHTML = '<i class="fas ' + iconName + '"></i>';
    
    // Fechar o seletor (opcional)
    // document.getElementById('iconSelector').style.display = 'none';
    
    // Opcional: mostrar mensagem de confirmação
    // alert('Ícone ' + iconName + ' selecionado!');
}
</script>