<!-- core/views/admin/layouts/luxor_message.php -->
<?php
/**
 * Componente Luxor - Mensagem do Guardião (VERSÃO DISCRETA)
 * 
 * Exibe uma mensagem do Luxor com accordion, baseada na luz do cliente
 */

// 🔥 DEFINIR A SECÇÃO POR DEFAULT SE NÃO ESTIVER DEFINIDA
if (!isset($secao)) {
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
        'default' => 'Todo o reino começa pelo seu nome. Um bom nome é lembrado. Uma boa identidade inspira confiança.'
    ],
    'servicos' => [
        'esmeralda' => 'O que cultivas? Cada serviço é uma semente que lanças ao mundo. Planta com propósito.',
        'safira' => 'Que rotas navegas? Cada serviço é uma viagem que ofereces aos que te procuram.',
        'rubi' => 'Cada serviço é uma obra-prima. Talha a tua arte com paixão e precisão.',
        'topazio' => 'Cada serviço é uma coluna que sustenta o teu império. Constrói com solidez.',
        'perola' => 'Cada serviço é um toque de cura. Oferece aos outros o que há de melhor em ti.',
        'ametista' => 'Cada serviço é uma lição. Ensina o que sabes e verás o mundo crescer.',
        'diamante' => 'Cada serviço é uma porta que abres. Que outros encontrem o caminho através de ti.',
        'default' => 'Os antigos diziam: "Nenhuma cidade prospera sem os seus ofícios." Mostra aquilo que sabes fazer.'
    ],
    'produtos' => [
        'esmeralda' => 'Cada artefacto é um fruto da tua terra. Mostra a tua colheita com orgulho.',
        'safira' => 'Tesouros do mar profundo. Cada artefacto traz consigo uma história de descoberta.',
        'rubi' => 'Cada artefacto é uma criação única. O fogo da tua criatividade transforma matéria em magia.',
        'topazio' => 'Cada artefacto é uma estrutura. Planeia, desenha, constrói. A perfeição está nos detalhes.',
        'perola' => 'Cada artefacto é um elixir de bem-estar. Cria com cuidado e atenção aos detalhes.',
        'ametista' => 'Cada artefacto é uma ferramenta de saber. Cria com a mente e toca a alma.',
        'diamante' => 'Cada artefacto é uma estrela. Que brilhe no teu firmamento.',
        'default' => 'Cada artefacto conta uma história. Não vendas apenas objetos. Mostra o valor que transportam.'
    ],
    'galeria' => [
        'esmeralda' => 'As imagens são as folhas da tua história. Deixa que os viajantes vejam a beleza do teu jardim.',
        'safira' => 'As imagens são as ondas do teu oceano. Deixa que os viajantes vejam a imensidão do teu mundo.',
        'rubi' => 'As imagens são as chamas da tua arte. Mostra ao mundo a beleza que criaste.',
        'topazio' => 'As imagens são as janelas do teu edifício. Deixa que a luz entre e mostre a tua obra.',
        'perola' => 'As imagens são o reflexo da tua essência. Mostra ao mundo a beleza que habita em ti.',
        'ametista' => 'As imagens são as estrelas do teu céu. Mostra o universo que carregas dentro de ti.',
        'diamante' => 'As imagens são os fragmentos da tua visão. Que cada uma conte uma história.',
        'default' => 'Há quem acredite nas palavras. Outros acreditam apenas quando veem. Mostra o teu trabalho.'
    ],
    'publicacoes' => [
        'esmeralda' => 'Escreve sobre o que fazes crescer. O conhecimento da terra é a sabedoria mais antiga.',
        'safira' => 'Escreve as tuas crónicas de viagem. Cada palavra é um farol para quem procura o caminho.',
        'rubi' => 'Escreve como quem esculpe. Cada palavra é um cinzel que molda a tua história.',
        'topazio' => 'Escreve como quem traça planos. Cada palavra é um tijolo na construção do saber.',
        'perola' => 'Escreve como quem partilha sabedoria. Cada palavra é uma gota de serenidade.',
        'ametista' => 'Escreve como quem planta ideias. Cada palavra é uma semente de mudança.',
        'diamante' => 'Escreve como quem constrói pontes. Cada palavra liga mundos.',
        'default' => 'O conhecimento partilhado regressa sempre ao seu criador. Ensina. Inspira. Sê encontrado.'
    ],
    'social' => [
        'esmeralda' => 'A tua voz ecoa além-fronteiras. Leva a mensagem da natureza a todos os cantos.',
        'safira' => 'A tua voz viaja como o vento sobre as águas. Que alcance todos os horizontes.',
        'rubi' => 'A tua voz é a tua marca. Que ressoe com a força do fogo que te move.',
        'topazio' => 'A tua voz é a ponte que liga mundos. Constrói conexões que resistem ao tempo.',
        'perola' => 'A tua voz é um bálsamo. Que as tuas palavras tragam paz a quem as ouve.',
        'ametista' => 'A tua voz é a luz que ilumina mentes. Partilha o teu conhecimento sem limites.',
        'diamante' => 'A tua voz ecoa no cosmos. Que todos os reinos ouçam a tua chamada.',
        'default' => 'As antigas cidades comunicavam através da Luz. Hoje ela liga o teu negócio ao mundo.'
    ],
    'gestao_conta' => [
        'esmeralda' => 'Cuidar do teu reino é como cuidar de um jardim. Regas, podas, esperas. E um dia floresce.',
        'safira' => 'Cuidar do teu reino é como navegar. Ajustas as velas, lês os ventos, e segues em frente.',
        'rubi' => 'Cuidar do teu reino é como forjar. Aqueces, moldas, temperas. E um dia brilha.',
        'topazio' => 'Cuidar do teu reino é como construir. Planeias, executas, revês. E um dia está de pé.',
        'perola' => 'Cuidar do teu reino é como cuidar de ti. Respiras, equilibras, renovas. E um dia floresces.',
        'ametista' => 'Cuidar do teu reino é como aprender. Observas, refletes, evoluís. E um dia ensinas.',
        'diamante' => 'Cuidar do teu reino é como liderar. Inspiras, guias, proteges. E um dia deixas um legado.',
        'default' => 'Todo o Guardião protege o seu reino. Mantém os teus dados seguros.'
    ],
    'logs' => [
        'esmeralda' => 'Cada ação fica registada como uma pegada na terra. O passado ensina o futuro.',
        'safira' => 'As águas guardam memórias profundas. Cada log é uma onda que revela o que aconteceu.',
        'rubi' => 'O fogo purifica e revela. Nos logs encontras a verdade do teu reino.',
        'topazio' => 'A luz ilumina o que ficou nas sombras. Consulta os logs para ver o caminho percorrido.',
        'perola' => 'A serenidade vem da compreensão. Os logs ajudam a ver o quadro completo.',
        'ametista' => 'O conhecimento cresce quando olhas para trás. Os logs são a memória do teu império.',
        'diamante' => 'Cada ação conta. Os logs são as estrelas que marcam a tua jornada.',
        'default' => 'Cada ação fica registada. O conhecimento do passado ilumina o futuro.'
    ]
];

// Obter a mensagem para a secção atual
$mensagem = '';
if (isset($mensagens[$secao])) {
    $mensagem = $mensagens[$secao][$cristalPrincipal] ?? $mensagens[$secao]['default'] ?? '';
}

// Se não houver mensagem, usar padrão
if (empty($mensagem)) {
    $mensagensPadrao = [
        'configuracoes' => 'Todo o reino começa pelo seu nome. Um bom nome é lembrado.',
        'servicos' => 'Nenhuma cidade prospera sem os seus ofícios. Mostra o que sabes fazer.',
        'produtos' => 'Cada artefacto conta uma história. Mostra o valor que transportam.',
        'galeria' => 'Há quem acredite nas palavras. Outros acreditam apenas quando veem.',
        'publicacoes' => 'O conhecimento partilhado regressa sempre ao seu criador.',
        'social' => 'As antigas cidades comunicavam através da Luz.',
        'gestao_conta' => 'Todo o Guardião protege o seu reino.',
        'logs' => 'Cada ação fica registada. O conhecimento do passado ilumina o futuro.'
    ];
    $mensagem = $mensagensPadrao[$secao] ?? 'Bem-vindo, Guardião. Este é o teu reino.';
}

// Nome e ícone da luz
$nomeLuz = $infoCristal['nome'] ?? 'Guardião';
$iconeLuz = $infoCristal['icone'] ?? 'fa-crown';
$corLuz = $infoCristal['cor'] ?? '#C6A43F';
?>

<div class="luxor-mini mb-3">
    <button class="luxor-toggle" onclick="toggleLuxorSecao(this)">
        <!-- 🔥 ÍCONE DO LUXOR (NOVO) -->
        <img src="<?= BASE_URL ?>assets/images/icone.png" alt="Luxor" style="width: 77px; height: 77px; border-radius: 50%; object-fit: cover; margin-right: 4px;">
        <span class="luxor-label">Mensagem do Luxor</span>
        <span class="luxor-cristal"><?= $nomeLuz ?></span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="luxor-conteudo" style="display: none;">
        <p>
            "<?= $mensagem ?>"
        </p>
    </div>
</div>

<script>
function toggleLuxorSecao(btn) {
    const conteudo = btn.nextElementSibling;
    const icon = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (conteudo.style.display === 'none') {
        conteudo.style.display = 'block';
        icon.className = 'fas fa-chevron-up';
    } else {
        conteudo.style.display = 'none';
        icon.className = 'fas fa-chevron-down';
    }
}
</script>

<style>
.luxor-mini {
    background: rgba(255,255,255,0.02);
    border-radius: 8px;
    padding: 2px 12px;
}

.luxor-toggle {
    background: none;
    border: none;
    color: #888;
    font-size: 0.75rem;
    padding: 8px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    transition: color 0.2s;
}

.luxor-toggle:hover {
    color: #C6A43F;
}

.luxor-toggle .luxor-label {
    font-weight: 500;
}

.luxor-toggle .luxor-cristal {
    font-size: 0.6rem;
    background: rgba(198, 164, 63, 0.1);
    padding: 1px 10px;
    border-radius: 10px;
    color: #C6A43F;
    margin-left: auto;
}

.luxor-toggle .fa-chevron-down,
.luxor-toggle .fa-chevron-up {
    font-size: 0.7rem;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.luxor-toggle:hover .fa-chevron-down,
.luxor-toggle:hover .fa-chevron-up {
    opacity: 1;
}

.luxor-conteudo {
    display: none;
    padding: 10px 15px;
    background: rgba(198, 164, 63, 0.04);
    border-radius: 8px;
    margin-top: 2px;
    border-left: 3px solid <?= $corLuz ?>;
    transition: all 0.3s ease;
}

.luxor-conteudo p {
    color: #b0b0c0;
    font-size: 0.9rem;
    font-style: italic;
    margin: 0;
    line-height: 1.6;
}
</style>