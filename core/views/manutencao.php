<?php
/**
 * Página de Manutenção
 * Exibida quando a conta está pausada ou desativada
 */
$slug = CLIENTE_SLUG ?? 'vitrine-demo';
$config = new \core\models\Configuracao(CLIENTE_ID);
$nomeEmpresa = $config->get('logo_parte1', 'Seven') . $config->get('logo_parte2', 'Lux');
$status = $_GET['status'] ?? 'pausada';
$tempoRestante = $_GET['tempo'] ?? null;

$mensagens = [
    'pausada' => [
        'titulo' => 'Site em Manutenção',
        'subtitulo' => 'Volte em breve!',
        'descricao' => 'Estamos a fazer algumas melhorias para lhe oferecer uma experiência ainda melhor.',
        'icone' => 'fa-tools',
        'cor' => '#ffc107'
    ],
    'desativada' => [
        'titulo' => 'Site Desativado',
        'subtitulo' => 'Este site foi desativado.',
        'descricao' => 'O proprietário deste site desativou temporariamente o acesso público.',
        'icone' => 'fa-toggle-off',
        'cor' => '#6c757d'
    ],
    'pendente_eliminacao' => [
        'titulo' => 'Conta em Processo de Eliminação',
        'subtitulo' => 'Este site será removido em breve.',
        'descricao' => 'O proprietário solicitou a eliminação desta conta. O site estará disponível por mais alguns dias.',
        'icone' => 'fa-clock',
        'cor' => '#dc3545'
    ]
];

$info = $mensagens[$status] ?? $mensagens['pausada'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($info['titulo']) ?> | <?= htmlspecialchars($nomeEmpresa) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a1a;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .manutencao-container {
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .manutencao-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px auto;
            background: rgba(198, 164, 63, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(198, 164, 63, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }
        
        .manutencao-icon i {
            font-size: 50px;
            color: <?= $info['cor'] ?>;
        }
        
        .manutencao-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .manutencao-title span {
            color: #C6A43F;
        }
        
        .manutencao-subtitle {
            font-size: 1.2rem;
            color: #C6A43F;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .manutencao-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .manutencao-divider {
            width: 60px;
            height: 2px;
            background: #C6A43F;
            margin: 20px auto;
            opacity: 0.3;
        }
        
        .manutencao-status {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            background: rgba(198, 164, 63, 0.1);
            border: 1px solid rgba(198, 164, 63, 0.2);
            font-size: 0.85rem;
            color: #C6A43F;
            margin-bottom: 30px;
        }
        
        .manutencao-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-gold {
            background: #C6A43F;
            color: #0a0a1a;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        
        .btn-gold:hover {
            background: #d4b96a;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(198, 164, 63, 0.3);
            color: #0a0a1a;
        }
        
        .btn-outline-gold {
            border: 1px solid #C6A43F;
            color: #C6A43F;
            background: transparent;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-outline-gold:hover {
            background: #C6A43F;
            color: #0a0a1a;
            transform: translateY(-3px);
        }
        
        .manutencao-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.3);
        }
        
        .manutencao-footer a {
            color: #C6A43F;
            text-decoration: none;
        }
        
        .manutencao-footer a:hover {
            text-decoration: underline;
        }
        
        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        /* Partículas de fundo */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(198, 164, 63, 0.2);
            border-radius: 50%;
            animation: float linear infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Responsivo */
        @media (max-width: 576px) {
            .manutencao-title {
                font-size: 1.8rem;
            }
            
            .manutencao-icon {
                width: 80px;
                height: 80px;
            }
            
            .manutencao-icon i {
                font-size: 35px;
            }
            
            .manutencao-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-gold,
            .btn-outline-gold {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Partículas de fundo -->
<div class="particles" id="particles"></div>

<div class="manutencao-container">
    <!-- Ícone -->
    <div class="manutencao-icon">
        <i class="fas <?= $info['icone'] ?>"></i>
    </div>
    
    <!-- Título -->
    <h1 class="manutencao-title">
        <span><?= htmlspecialchars($nomeEmpresa) ?></span>
    </h1>
    
    <h2 class="manutencao-subtitle"><?= htmlspecialchars($info['subtitulo']) ?></h2>
    
    <div class="manutencao-divider"></div>
    
    <!-- Status -->
    <div class="manutencao-status">
        <i class="fas fa-circle" style="color: <?= $info['cor'] ?>; font-size: 0.6rem; margin-right: 8px;"></i>
        <?= htmlspecialchars($info['titulo']) ?>
        <?php if ($tempoRestante && $status === 'pendente_eliminacao'): ?>
            <span class="text-warning">| <?= $tempoRestante ?> dias restantes</span>
        <?php endif; ?>
    </div>
    
    <!-- Descrição -->
    <p class="manutencao-desc">
        <?= htmlspecialchars($info['descricao']) ?>
    </p>
    
    <!-- Botões -->
    <div class="manutencao-buttons">
        <?php if ($status === 'pausada'): ?>
            <a href="<?= BASE_URL . $slug ?>/admin_login" class="btn-gold">
                <i class="fas fa-lock"></i> Entrar como Administrador
            </a>
            <a href="<?= BASE_URL . $slug ?>/" class="btn-outline-gold">
                <i class="fas fa-sync-alt"></i> Tentar Novamente
            </a>
        <?php elseif ($status === 'desativada'): ?>
            <a href="<?= BASE_URL . $slug ?>/admin_login" class="btn-gold">
                <i class="fas fa-lock"></i> Entrar como Administrador
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL . $slug ?>/" class="btn-gold">
                <i class="fas fa-home"></i> Voltar ao Início
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <div class="manutencao-footer">
        <p>
            <i class="fas fa-copyright"></i> <?= date('Y') ?> 
            <a href="<?= BASE_URL . $slug ?>/"><?= htmlspecialchars($nomeEmpresa) ?></a>
            <span class="mx-2">|</span>
            <a href="<?= BASE_URL . $slug ?>/politica_privacidade">Política de Privacidade</a>
        </p>
    </div>
</div>

<script>
// Partículas de fundo
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('particles');
    if (!container) return;
    
    const count = 30;
    for (let i = 0; i < count; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.width = (Math.random() * 3 + 1) + 'px';
        particle.style.height = particle.style.width;
        particle.style.animationDuration = (Math.random() * 20 + 15) + 's';
        particle.style.animationDelay = (Math.random() * 20) + 's';
        particle.style.opacity = Math.random() * 0.3 + 0.1;
        container.appendChild(particle);
    }
});
</script>

</body>
</html>