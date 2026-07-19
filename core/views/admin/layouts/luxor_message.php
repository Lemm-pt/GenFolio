<!-- core/views/admin/layouts/luxor_message.php -->
<?php
/**
 * Componente Luxor - Mensagem do Guardião
 * 
 * Exibe uma mensagem do Luxor com base na luz do cliente e na secção atual
 * 
 * @var string $secao Nome da secção (configuracoes, servicos, produtos, galeria, publicacoes, social, gestao_conta)
 */

// 🔥 DEFINIR A SECÇÃO POR DEFAULT SE NÃO ESTIVER DEFINIDA
if (!isset($secao)) {
    // Tentar determinar a secção com base na URL
    $acao = $_GET['a'] ?? '';
    $mapaSecoes = [
        'admin_configuracoes' => 'configuracoes',
        'admin_servicos' => 'servicos',
        'admin_servico_criar' => 'servicos',
        'admin_servico_editar' => 'servicos',
        'admin_produtos' => 'produtos',
        'admin_produto_criar' => 'produtos',
        'admin_produto_editar' => 'produtos',
        'admin_galeria' => 'galeria',
        'admin_publicacoes' => 'publicacoes',
        'admin_publicacao_criar' => 'publicacoes',
        'admin_publicacao_editar' => 'publicacoes',
        'admin_social' => 'social',
        'admin_gestao_conta' => 'gestao_conta',
        'admin_logs' => 'logs'
    ];
    $secao = $mapaSecoes[$acao] ?? 'dashboard';
}

// Determinar a luz do cliente
$cristaisModel = new \core\models\Cristais(CLIENTE_ID);
$cristalPrincipal = $cristaisModel->getCristalPrincipal();
$infoCristal = $cristalPrincipal ? $cristaisModel->getInfo($cristalPrincipal) : null;

// Definir a mensagem com base na secção e na luz
$mensagens = [
    'configuracoes' => [
        'esmeralda' => 'Dá nome ao teu jardim, Guardião. Que cada visitante sinta o cheiro da terra e o calor da tua colheita.',
        'safira' => 'Define o teu porto, Guardião. Que os viajantes saibam onde atracar quando cruzarem o teu mar.',
        'rubi' => 'Forja o teu símbolo, Guardião. Que o teu nome ecoe como o martelo na bigorna.',
        'topazio' => 'Ergue os teus alicerces, Guardião. Que o teu nome seja a pedra fundamental do teu reino.',
        'perola' => 'Define o teu equilíbrio, Guardião. Que o teu nome inspire calma e confiança.',
        'ametista' => 'Dá nome ao teu conhecimento, Guardião. Que o teu nome seja sinónimo de sabedoria.',
        'diamante' => 'Dá nome ao teu império, Guardião. Que a tua marca ecoe além-fronteiras.',
        'default' => 'Define a identidade do teu reino. O teu nome, o teu símbolo, a tua essência.'
    ],
    'servicos' => [
        'esmeralda' => 'O que cultivas? Cada serviço é uma semente que lanças ao mundo. Planta com propósito.',
        'safira' => 'Que rotas navegas? Cada serviço é uma viagem que ofereces aos que te procuram.',
        'rubi' => 'Cada serviço é uma obra-prima. Talha a tua arte com paixão e precisão.',
        'topazio' => 'Cada serviço é uma coluna que sustenta o teu império. Constrói com solidez.',
        'perola' => 'Cada serviço é um toque de cura. Oferece aos outros o que há de melhor em ti.',
        'ametista' => 'Cada serviço é uma lição. Ensina o que sabes e verás o mundo crescer.',
        'diamante' => 'Cada serviço é uma porta que abres. Que outros encontrem o caminho através de ti.',
        'default' => 'Os teus ofícios são a tua contribuição para o mundo. O que fazes quando o sol nasce?'
    ],
    'produtos' => [
        'esmeralda' => 'Cada artefacto é um fruto da tua terra. Mostra a tua colheita com orgulho.',
        'safira' => 'Tesouros do mar profundo. Cada artefacto traz consigo uma história de descoberta.',
        'rubi' => 'Cada artefacto é uma criação única. O fogo da tua criatividade transforma matéria em magia.',
        'topazio' => 'Cada artefacto é uma estrutura. Planeia, desenha, constrói. A perfeição está nos detalhes.',
        'perola' => 'Cada artefacto é um elixir de bem-estar. Cria com cuidado e atenção aos detalhes.',
        'ametista' => 'Cada artefacto é uma ferramenta de saber. Cria com a mente e toca a alma.',
        'diamante' => 'Cada artefacto é uma estrela. Que brilhe no teu firmamento.',
        'default' => 'Cada artefacto é uma semente. Planta bem, e a colheita será abundante.'
    ],
    'galeria' => [
        'esmeralda' => 'As imagens são as folhas da tua história. Deixa que os viajantes vejam a beleza do teu jardim.',
        'safira' => 'As imagens são as ondas do teu oceano. Deixa que os viajantes vejam a imensidão do teu mundo.',
        'rubi' => 'As imagens são as chamas da tua arte. Mostra ao mundo a beleza que criaste.',
        'topazio' => 'As imagens são as janelas do teu edifício. Deixa que a luz entre e mostre a tua obra.',
        'perola' => 'As imagens são o reflexo da tua essência. Mostra ao mundo a beleza que habita em ti.',
        'ametista' => 'As imagens são as estrelas do teu céu. Mostra o universo que carregas dentro de ti.',
        'diamante' => 'As imagens são os fragmentos da tua visão. Que cada uma conte uma história.',
        'default' => 'Mostra o teu mundo. Cada imagem é uma janela para o teu reino.'
    ],
    'publicacoes' => [
        'esmeralda' => 'Escreve sobre o que fazes crescer. O conhecimento da terra é a sabedoria mais antiga.',
        'safira' => 'Escreve as tuas crónicas de viagem. Cada palavra é um farol para quem procura o caminho.',
        'rubi' => 'Escreve como quem esculpe. Cada palavra é um cinzel que molda a tua história.',
        'topazio' => 'Escreve como quem traça planos. Cada palavra é um tijolo na construção do saber.',
        'perola' => 'Escreve como quem partilha sabedoria. Cada palavra é uma gota de serenidade.',
        'ametista' => 'Escreve como quem planta ideias. Cada palavra é uma semente de mudança.',
        'diamante' => 'Escreve como quem constrói pontes. Cada palavra liga mundos.',
        'default' => 'Escreve a tua história. O conhecimento que partilhas hoje será a sabedoria de amanhã.'
    ],
    'social' => [
        'esmeralda' => 'A tua voz ecoa além-fronteiras. Leva a mensagem da natureza a todos os cantos.',
        'safira' => 'A tua voz viaja como o vento sobre as águas. Que alcance todos os horizontes.',
        'rubi' => 'A tua voz é a tua marca. Que ressoe com a força do fogo que te move.',
        'topazio' => 'A tua voz é a ponte que liga mundos. Constrói conexões que resistem ao tempo.',
        'perola' => 'A tua voz é um bálsamo. Que as tuas palavras tragam paz a quem as ouve.',
        'ametista' => 'A tua voz é a luz que ilumina mentes. Partilha o teu conhecimento sem limites.',
        'diamante' => 'A tua voz ecoa no cosmos. Que todos os reinos ouçam a tua chamada.',
        'default' => 'A tua voz ecoa além-fronteiras. Conecta-te ao mundo.'
    ],
    'gestao_conta' => [
        'esmeralda' => 'Cuidar do teu reino é como cuidar de um jardim. Regas, podas, esperas. E um dia floresce.',
        'safira' => 'Cuidar do teu reino é como navegar. Ajustas as velas, lês os ventos, e segues em frente.',
        'rubi' => 'Cuidar do teu reino é como forjar. Aqueces, moldas, temperas. E um dia brilha.',
        'topazio' => 'Cuidar do teu reino é como construir. Planeias, executas, revês. E um dia está de pé.',
        'perola' => 'Cuidar do teu reino é como cuidar de ti. Respiras, equilibras, renovas. E um dia floresces.',
        'ametista' => 'Cuidar do teu reino é como aprender. Observas, refletes, evoluís. E um dia ensinas.',
        'diamante' => 'Cuidar do teu reino é como liderar. Inspiras, guias, proteges. E um dia deixas um legado.',
        'default' => 'Cuidar do teu reino é a arte mais nobre. Cada decisão molda o teu legado.'
    ]
];

// Obter a mensagem para a secção atual
$mensagem = '';
if (isset($mensagens[$secao])) {
    $mensagem = $mensagens[$secao][$cristalPrincipal] ?? $mensagens[$secao]['default'] ?? '';
}

// Se não houver mensagem para a secção, usar a mensagem padrão do sistema
if (empty($mensagem)) {
    $mensagensPadrao = [
        'configuracoes' => 'Define a identidade do teu reino. O teu nome, o teu símbolo, a tua essência.',
        'servicos' => 'Os teus ofícios são a tua contribuição para o mundo. O que fazes quando o sol nasce?',
        'produtos' => 'Cada artefacto é uma semente. Planta bem, e a colheita será abundante.',
        'galeria' => 'Mostra o teu mundo. Cada imagem é uma janela para o teu reino.',
        'publicacoes' => 'Escreve a tua história. O conhecimento que partilhas hoje será a sabedoria de amanhã.',
        'social' => 'A tua voz ecoa além-fronteiras. Conecta-te ao mundo.',
        'gestao_conta' => 'Cuidar do teu reino é a arte mais nobre. Cada decisão molda o teu legado.'
    ];
    $mensagem = $mensagensPadrao[$secao] ?? 'Bem-vindo, Guardião. Este é o teu reino.';
}

// Nome e ícone da luz
$nomeLuz = $infoCristal['nome'] ?? 'Guardião';
$iconeLuz = $infoCristal['icone'] ?? 'fa-crown';
$corLuz = $infoCristal['cor'] ?? '#C6A43F';
?>

<div class="luxor-message-box mb-4" style="background: rgba(198, 164, 63, 0.05); border-radius: 12px; border-left: 4px solid <?= $corLuz ?>; padding: 16px 20px;">
    <div class="d-flex align-items-start gap-3">
        <div class="luxor-mini-avatar" style="width: 40px; height: 40px; background: rgba(198, 164, 63, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid <?= $corLuz ?>; flex-shrink: 0;">
            <!-- <i class="fas fa-robot" style="color: <?= $corLuz ?>; font-size: 1.2rem;"></i> -->
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge" style="background: <?= $corLuz ?>; color: #0a0a1a; font-weight: 600;">
                    <i class="fas <?= $iconeLuz ?>"></i> <?= $nomeLuz ?>
                </span>
                <span class="text-muted small">🧙‍♂️ Luxor, o Guardião</span>
            </div>
            <p class="mb-0 mt-1" style="color: #e0e0e0; font-size: 0.9rem; font-style: italic;">
                "<?= $mensagem ?>"
            </p>
        </div>
    </div>
</div>

<style>
.luxor-message-box {
    transition: all 0.3s ease;
}

.luxor-message-box:hover {
    background: rgba(198, 164, 63, 0.08) !important;
}

.luxor-mini-avatar {
    transition: all 0.3s ease;
}

.luxor-message-box:hover .luxor-mini-avatar {
    transform: scale(1.05);
}
</style>