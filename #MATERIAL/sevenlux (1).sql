-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Jun-2026 às 00:46
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sevenlux`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `salt` varchar(64) DEFAULT NULL,
  `hash_digitos` varchar(64) DEFAULT NULL,
  `pergunta_id` int(11) DEFAULT NULL,
  `resposta_id` int(11) DEFAULT NULL,
  `tentativas_falhas` int(11) DEFAULT 0,
  `bloqueio_ate` int(11) DEFAULT 0,
  `purl` varchar(50) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `email`, `slug`, `cidade`, `categoria`, `pais`, `salt`, `hash_digitos`, `pergunta_id`, `resposta_id`, `tentativas_falhas`, `bloqueio_ate`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'lemm.pt@gmail.com', 'vitrine-demo', NULL, NULL, NULL, 'f4f6d89255f13fa0d395b7c5aea6723d25e9cc426f37c220f778f60918f0482e', '36a2b3dfff85453c457bc918a11f10eea8f3f1deecdf7369f79b2985e399812e', 1, 1, 0, 0, NULL, 1, '2026-05-15 17:00:42', '2026-06-06 18:38:25', NULL),
(35, 'lemm777@gmail.com', 'tester', NULL, NULL, NULL, '52d87781d8b0753514656abd23607645c15b883b9a07d18967b53fd2b3ed314b', '5c3671a8f738a588c98a7260fb9df6c48af8bbb0c63fde854b669b8dce814e8f', 15, 1, 0, 0, NULL, 1, '2026-05-31 20:34:00', '2026-06-06 18:38:46', NULL),
(36, 'lubiomarona@gmail.com', 'lemm', NULL, NULL, NULL, '32e159abf28663bc93f7ac5d588f5b45c742c201de5dd9c092f35c3445704b0f', 'e6271fffa4275e07fe336f84d7b31eab5e9d2d252c0b41e1f678ace23a500b60', 6, 2, 0, 0, NULL, 1, '2026-06-06 18:46:01', '2026-06-09 19:48:53', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracoes_site`
--

CREATE TABLE `configuracoes_site` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `chave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `configuracoes_site`
--

INSERT INTO `configuracoes_site` (`id`, `cliente_id`, `chave`, `valor`, `created_at`, `updated_at`) VALUES
(1, 1, 'logo_parte1', 'Vitrine', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(2, 1, 'logo_parte2', '.lemm', '2026-05-15 17:00:42', '2026-05-15 17:00:42'),
(3, 1, 'logo_imagem', 'logo.png', '2026-05-15 17:00:42', '2026-05-15 22:56:41'),
(4, 1, 'slogan', 'Soluções Digitais que Transformam Negócios', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(5, 1, 'texto_descritivo', 'A Vitrine.lemm nasceu para ajudar empresas a crescer no digital. Combinamos criatividade, tecnologia e estratégia para criar experiências únicas. Do design à programação, passando pelo marketing, somos o parceiro que faltava ao seu negócio.', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(6, 1, 'meta_description', 'Vitrine.lemm - Soluções digitais', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(7, 1, 'meta_keywords', 'vitrine,lemm,digital,', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(8, 1, 'email_contacto', 'luciano@lemm.pt', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(9, 1, 'telefone', '+351 964456930', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(10, 1, 'endereco', 'Esposende, Portugal', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(211, 35, 'logo_parte1', 'MY', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(212, 35, 'logo_parte2', 'Tester', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(213, 35, 'logo_imagem', '1780256521_6a1c8f0908a88.webp', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(214, 35, 'slogan', 'Soluções Personalizadas do meu tester', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(215, 35, 'texto_descritivo', 'Bem-vindo ao seu novo site! do tester', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(216, 35, 'email_contacto', 'lemm777@gmail.com', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(217, 35, 'telefone', '12333333333', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(218, 35, 'endereco', 'Povoa Varzim', '2026-05-31 20:34:59', '2026-05-31 20:42:01'),
(219, 35, 'meta_description', '', '2026-05-31 20:34:59', '2026-05-31 20:34:59'),
(220, 35, 'meta_keywords', '', '2026-05-31 20:34:59', '2026-05-31 20:34:59'),
(221, 36, 'logo_parte1', 'Meu', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(222, 36, 'logo_parte2', 'Negócio', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(223, 36, 'logo_imagem', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(224, 36, 'slogan', 'Soluções Personalizadas', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(225, 36, 'texto_descritivo', 'Bem-vindo ao seu novo site!', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(226, 36, 'email_contacto', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(227, 36, 'telefone', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(228, 36, 'endereco', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(229, 36, 'meta_description', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52'),
(230, 36, 'meta_keywords', '', '2026-06-06 18:49:52', '2026-06-06 18:49:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `fingerprint_hash` varchar(255) NOT NULL,
  `primeiro_uso` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acesso` timestamp NULL DEFAULT NULL,
  `ip_registo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `galeria`
--

CREATE TABLE `galeria` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `galeria`
--

INSERT INTO `galeria` (`id`, `cliente_id`, `imagem`, `legenda`, `ordem`, `created_at`) VALUES
(1, 1, '1778879330_6a078b62d16b4.png', 'Design & Branding', 1, '2026-05-15 22:08:50'),
(2, 1, '1778879354_6a078b7aa60f6.png', 'Desenvolvimento Web', 2, '2026-05-15 22:09:14'),
(3, 1, '1778879372_6a078b8c47e6c.png', 'Marketing Digital', 3, '2026-05-15 22:09:32'),
(5, 1, '1778880660_6a07909401875.png', 'Redesign de marca ', 4, '2026-05-15 22:31:00'),
(14, 36, '1780768557_6a245f2de5099.jpg', '', 1, '2026-06-06 18:55:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perguntas_magicas`
--

CREATE TABLE `perguntas_magicas` (
  `id` int(10) UNSIGNED NOT NULL,
  `pergunta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `perguntas_magicas`
--

INSERT INTO `perguntas_magicas` (`id`, `pergunta`) VALUES
(1, 'Qual criatura mística protege os portais dos teus sonhos mais profundos?'),
(2, 'Que elixir ou bebida mágica purifica a tua alma antes de um ritual?'),
(3, 'Em qual pedra preciosa se encontra gravada a verdadeira essência do teu destino?'),
(4, 'Que tom ou cor de luz emana do teu ser quando canalizas o teu poder?'),
(5, 'Qual elemento da natureza elemental (luar, fogo, etc.) rege o teu santuário interior?'),
(6, 'Qual arquétipo ancestral guia os teus passos nos caminhos da noite?'),
(7, 'Que figura geométrica sagrada abre os portais do teu conhecimento oculto?');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `cliente_id`, `nome`, `descricao`, `preco`, `imagem`, `ordem`, `created_at`) VALUES
(1, 1, 'Website Profissional', '	Site institucional completo com CMS (até 5 páginas), otimizado para SEO e responsivo.', '245.00', '1778879186_6a078ad2cccc9.png', 1, '2026-05-15 22:00:32'),
(2, 1, 'Loja Online (E‑commerce)', 'Plataforma de vendas completa, integração com pagamentos, gestão de stock e produtos ilimitados.', '477.00', '1778879168_6a078ac0d20a3.png', 2, '2026-05-15 22:01:06'),
(3, 1, 'Landing Page de Conversão', 'Página de alto impacto para campanhas, com formulário, analytics e integração com email marketing.', '167.00', '1778879238_6a078b06cc11b.png', 3, '2026-05-15 22:07:18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicacoes`
--

CREATE TABLE `publicacoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `publicado` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `publicacoes`
--

INSERT INTO `publicacoes` (`id`, `cliente_id`, `titulo`, `slug`, `conteudo`, `imagem`, `publicado`, `created_at`) VALUES
(1, 1, '5 Tendências de Design para 2026', '5-tendencias-de-design-para-2026', 'A tendência de design para 2026 une o minimalismo à tipografia ousada e ao dark mode, criando interfaces limpas e impactantes. O texto explora como essa estética reduz distrações, realça o conteúdo com títulos marcantes e melhora a experiência do utilizador com fundos escuros. Saiba como aplicar estas estratégias para criar projetos visuais modernos, sofisticados e de fácil leitura.', '1778879729_6a078cf11e6bb.png', 1, '2026-05-15 22:15:29'),
(2, 1, 'Como Escolher a Plataforma Ideal para o seu E‑commerce', 'como-escolher-a-plataforma-ideal-para-o-seu-e-commerce', ' Comparação entre Shopify, WooCommerce e Magento.\r\nO **Shopify** destaca-se pela rapidez e simplicidade com alojamento incluído, sendo ideal para quem quer focar apenas nas vendas. O **WooCommerce** oferece flexibilidade e controlo total sobre os dados, integrando-se perfeitamente em WordPress. Já o **Magento** surge como a solução robusta para grandes marcas com catálogos massivos e operações internacionais complexas.', '1778879794_6a078d328e258.png', 1, '2026-05-15 22:16:34'),
(3, 1, 'SEO em 2026: O que mudou e como se adaptar', 'seo-em-2026-o-que-mudou-e-como-se-adaptar', ' Novas regras do Google, experiência do utilizador e Core Web Vitals.\r\n\r\nAs novas diretrizes do Google priorizam a experiência do utilizador como fator crítico de posicionamento orgânico. O foco central recai sobre os Core Web Vitals, métricas que avaliam a velocidade de carregamento, interatividade e estabilidade visual das páginas. Sites que não otimizarem estes indicadores técnicos correm o risco de perder visibilidade e tráfego qualificado em 2026.', '1778880086_6a078e5697ed5.png', 1, '2026-05-15 22:21:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `respostas_magicas`
--

CREATE TABLE `respostas_magicas` (
  `id` int(10) UNSIGNED NOT NULL,
  `pergunta_id` int(10) UNSIGNED NOT NULL,
  `resposta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `respostas_magicas`
--

INSERT INTO `respostas_magicas` (`id`, `pergunta_id`, `resposta`) VALUES
(1, 1, 'O Fénix de Cinzas Eternas'),
(2, 1, 'O Dragão de Névoa Argêntea'),
(3, 1, 'O Quimera dos Espelhos'),
(4, 1, 'O Unicórnio de Sangue Negro'),
(5, 1, 'O Corvo de Três Olhos'),
(6, 1, 'A Serpente de Ouroboros'),
(7, 1, 'O Grifo das Tempestades Astral'),
(8, 2, 'Lágrimas de Sereia Destiladas'),
(9, 2, 'Néctar de Flores de Lótus Noturna'),
(10, 2, 'Elixir de Estrelas Cadentes'),
(11, 2, 'Hidromel Infundido com Raiz de Mandrágora'),
(12, 2, 'Sangue da Lua Cheia Crio-preservado'),
(13, 2, 'Infusão de Névoa Estelar e Absinto'),
(14, 2, 'Orvalho de Carvalho Sagrado dos Druidas'),
(15, 3, 'Obsidiana Negra de Fogo Primordial'),
(16, 3, 'Pedra da Lua de Brilho Espectral'),
(17, 3, 'Lápis-lazúli dos Reis Magos'),
(18, 3, 'Ametista de Transmutação Cósmica'),
(19, 3, 'Esmeralda do Coração da Terra'),
(20, 3, 'Turmalina Negra de Proteção Absoluta'),
(21, 3, 'Opala de Fogo Alquímica'),
(22, 4, 'Luz Violeta de Transmutação Espiritual'),
(23, 4, 'Brilho Índigo do Terceiro Olho'),
(24, 4, 'Chama Azul de Energia Astral'),
(25, 4, 'Esplendor Dourado Solar'),
(26, 4, 'Luminescência Verde Esmeralda dos Bosques'),
(27, 4, 'Brilho Argênteo do Luar Místico'),
(28, 4, 'Fascínio Vermelho Carmesim Oculto'),
(29, 5, 'O Silêncio Revelador do Luar de Inverno'),
(30, 5, 'O Fogo Sagrado que Tudo Devora e Renova'),
(31, 5, 'Os Ventos Sussurrantes do Esquecimento'),
(32, 5, 'As Águas Profundas do Mar Abissal'),
(33, 5, 'A Terra Fértil dos Antigos Cemitérios'),
(34, 5, 'A Eletricidade Estática do Relâmpago Noturno'),
(35, 5, 'O Éter Vazio do Espaço Sombrio'),
(36, 6, 'O Eremita dos Caminhos Esquecidos'),
(37, 6, 'A Alta Sacerdotisa dos Véus Ocultos'),
(38, 6, 'O Alquimista das Almas Perdidas'),
(39, 6, 'A Sombra Espelhada'),
(40, 6, 'O Tecelão do Destino Universal'),
(41, 6, 'O Guardião das Chaves Secretas'),
(42, 6, 'O Rebelde Caótico do Cosmos'),
(43, 7, 'O Pentagrama da Harmonização Elemental'),
(44, 7, 'O Hexagrama do Selo de Salomão'),
(45, 7, 'O Triângulo de Manifestação Divina'),
(46, 7, 'A Espiral Áurea de Fibonacci do Infinito'),
(47, 7, 'O Cubo de Metatron Cosmopoderoso'),
(48, 7, 'O Círculo Concêntrico da Eternidade'),
(49, 7, 'A Merkabah do Transporte Dimensional');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `icone` varchar(50) DEFAULT 'fa-star',
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id`, `cliente_id`, `titulo`, `descricao`, `icone`, `ordem`) VALUES
(1, 1, 'Design & Branding', 'Criamos identidades visuais memoráveis, desde o logotipo à paleta de cores, que contam a sua história.', 'fa-image', 1),
(3, 1, '	Desenvolvimento Web', 'Sites, lojas online e aplicações web modernas, rápidas e responsivas, feitas à medida do seu negócio.', 'fa-code', 2),
(4, 1, 'Marketing Digital', 'Estratégias de SEO, Google Ads e redes sociais para atrair mais clientes e aumentar as suas vendas.', 'fa-chart-line', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `warnings`
--

CREATE TABLE `warnings` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolvido` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cliente_chave` (`cliente_id`,`chave`);

--
-- Índices para tabela `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_device` (`slug`,`device_id`);

--
-- Índices para tabela `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `perguntas_magicas`
--
ALTER TABLE `perguntas_magicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_cliente` (`slug`,`cliente_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `respostas_magicas`
--
ALTER TABLE `respostas_magicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT de tabela `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `perguntas_magicas`
--
ALTER TABLE `perguntas_magicas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `respostas_magicas`
--
ALTER TABLE `respostas_magicas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `warnings`
--
ALTER TABLE `warnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  ADD CONSTRAINT `configuracoes_site_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD CONSTRAINT `dispositivos_ibfk_1` FOREIGN KEY (`slug`) REFERENCES `clientes` (`slug`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `galeria`
--
ALTER TABLE `galeria`
  ADD CONSTRAINT `galeria_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  ADD CONSTRAINT `publicacoes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
