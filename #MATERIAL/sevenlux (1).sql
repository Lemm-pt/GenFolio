-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Jun-2026 às 23:26
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
-- Estrutura da tabela `sevenlux_audit_logs`
--

CREATE TABLE `sevenlux_audit_logs` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `acao` varchar(50) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_audit_logs`
--

INSERT INTO `sevenlux_audit_logs` (`id`, `cliente_id`, `usuario`, `acao`, `detalhes`, `ip`, `user_agent`, `created_at`) VALUES
(1, 1, 'lemm.pt@gmail.com', 'login_sucesso', 'Login do cliente: vitrine-demo', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 21:23:30'),
(2, 1, NULL, 'logout', 'Logout do cliente: vitrine-demo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:32:05'),
(3, 73, 'luciano@lemm.pt', 'login_sucesso', 'Login do cliente: teste88', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:33:26'),
(4, 73, NULL, 'criar_servico', 'Serviço criado: teste 88', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:33:56'),
(5, 73, NULL, 'logout', 'Logout do cliente: teste88', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:47:41'),
(6, 1, 'lemm.pt@gmail.com', 'login_sucesso', 'Login do cliente: vitrine-demo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:47:48'),
(7, 1, NULL, 'logout', 'Logout do cliente: vitrine-demo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 22:08:43'),
(8, NULL, NULL, 'bloqueio_tentativa', 'Bloqueio progressivo para teste88: 1 segundos', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 22:09:44'),
(9, NULL, NULL, 'bloqueio_tentativa', 'Bloqueio progressivo para teste88: 2 segundos', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 22:10:03'),
(10, NULL, NULL, 'bloqueio_tentativa', 'Bloqueio progressivo para teste88: 4 segundos', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 22:23:42'),
(11, NULL, NULL, 'bloqueio_tentativa', 'Bloqueio progressivo para teste88: 8 segundos', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 22:23:48'),
(12, NULL, NULL, 'bloqueio_tentativa', 'Bloqueio progressivo para teste88: 16 segundos', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 22:23:57'),
(13, NULL, NULL, 'bloqueio_limite', 'Limite de 7 tentativas atingido para teste88', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 22:23:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_clientes`
--

CREATE TABLE `sevenlux_clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `hash_digitos` varchar(255) DEFAULT NULL,
  `tentativas_falhas` int(11) DEFAULT 0,
  `bloqueio_ate` int(11) DEFAULT 0,
  `email_confirmation_token` varchar(50) DEFAULT NULL,
  `recovery_token` varchar(50) DEFAULT NULL,
  `email_confirmed_at` datetime DEFAULT NULL,
  `recovery_token_expires` datetime DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_clientes`
--

INSERT INTO `sevenlux_clientes` (`id_cliente`, `email`, `slug`, `cidade`, `categoria`, `pais`, `hash_digitos`, `tentativas_falhas`, `bloqueio_ate`, `email_confirmation_token`, `recovery_token`, `email_confirmed_at`, `recovery_token_expires`, `token_expires_at`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'lemm.pt@gmail.com', 'vitrine-demo', 'London', 'Tecnologia', 'England', '$argon2id$v=19$m=65536,t=4,p=2$aTRWQ0F6VWVVOTIxVlJMLw$yuaKGZ1snRXqqlMNCdV1lRIociPq8mDs1IU8NtWc3fo', 0, 0, NULL, NULL, NULL, NULL, '2026-06-24 00:25:37', 1, '2026-05-15 17:00:42', '2026-06-23 22:25:37', NULL),
(59, 'lemm777@gmail.com', 'lemm', 'porto', 'Alimentação', 'portugal', '$argon2id$v=19$m=65536,t=4,p=2$bmJxSVRjVkRZMzdqeWd6cA$qfk+dGsbnjAfAZkziyC1sm+EptytAoPrGOy0roxtyJw', 0, 0, NULL, NULL, NULL, NULL, '2026-06-24 00:42:05', 1, '2026-06-22 20:58:04', '2026-06-23 22:42:05', NULL),
(71, 'lubiomarona@gmail.com', 'teste77porto', 'EUA', 'Saúde', 'EUA', '$argon2id$v=19$m=65536,t=4,p=2$cmIxM2E3Q0NzMUZZSGVDVw$Z1e1/KPngwS6Db7IjsAYNX1Aa2ldhndQKb4KQ+mn+/8', 0, 0, 'itjPwE2s54fR', NULL, NULL, NULL, NULL, 0, '2026-06-24 20:03:09', '2026-06-24 20:03:09', NULL),
(73, 'luciano@lemm.pt', 'teste88', 'eeeee', 'Educação', 'Africa', '$argon2id$v=19$m=65536,t=4,p=2$a3NxVXBhMkQxeVBBdUNGdA$VcHtrB+vhUHpcVw7iAMiaS1nF/tt6WkPElsDfDsPgUk', 7, 1782336537, NULL, NULL, '2026-06-24 20:08:18', NULL, NULL, 1, '2026-06-24 20:07:44', '2026-06-24 22:23:57', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_configuracoes_site`
--

CREATE TABLE `sevenlux_configuracoes_site` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `chave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_configuracoes_site`
--

INSERT INTO `sevenlux_configuracoes_site` (`id`, `cliente_id`, `chave`, `valor`, `created_at`, `updated_at`) VALUES
(1, 1, 'logo_parte1', 'Seven', '2026-05-15 17:00:42', '2026-06-24 17:00:11'),
(2, 1, 'logo_parte2', 'Lux', '2026-05-15 17:00:42', '2026-06-24 17:00:11'),
(3, 1, 'logo_imagem', 'logo.png', '2026-05-15 17:00:42', '2026-05-15 22:56:41'),
(4, 1, 'slogan', 'Soluções Digitais que Transformam Negócios', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(5, 1, 'texto_descritivo', 'A Vitrine.lemm nasceu para ajudar empresas a crescer no digital. Combinamos criatividade, tecnologia e estratégia para criar experiências únicas. Do design à programação, passando pelo marketing, somos o parceiro que faltava ao seu negócio.', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(6, 1, 'meta_description', 'SevenLux - Soluções digitais', '2026-05-15 17:00:42', '2026-06-24 17:00:11'),
(7, 1, 'meta_keywords', 'web app,lemm,digital,', '2026-05-15 17:00:42', '2026-06-24 17:00:11'),
(8, 1, 'email_contacto', 'luciano@lemm.pt', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(9, 1, 'telefone', '+351 964456930', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(10, 1, 'endereco', 'Esposende, Portugal', '2026-05-15 17:00:42', '2026-05-15 22:26:51'),
(281, 59, 'logo_parte1', 'LEMM', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(282, 59, 'logo_parte2', '.PT', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(283, 59, 'logo_imagem', '1782249418_6a3af7ca24086.png', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(284, 59, 'slogan', 'Soluções Personalizadas para web apps', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(285, 59, 'texto_descritivo', 'Bem-vindo ao seu novo site!', '2026-06-22 20:58:52', '2026-06-22 20:58:52'),
(286, 59, 'email_contacto', '', '2026-06-22 20:58:52', '2026-06-22 20:58:52'),
(287, 59, 'telefone', '', '2026-06-22 20:58:52', '2026-06-22 20:58:52'),
(288, 59, 'endereco', '', '2026-06-22 20:58:52', '2026-06-22 20:58:52'),
(289, 59, 'meta_description', 'web apps e sites', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(290, 59, 'meta_keywords', 'teste, web app, websites, porto', '2026-06-22 20:58:52', '2026-06-23 22:16:58'),
(331, 73, 'logo_parte1', 'Meu', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(332, 73, 'logo_parte2', 'Negócio', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(333, 73, 'logo_imagem', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(334, 73, 'slogan', 'Soluções Personalizadas', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(335, 73, 'texto_descritivo', 'Bem-vindo ao seu novo site!', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(336, 73, 'email_contacto', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(337, 73, 'telefone', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(338, 73, 'endereco', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(339, 73, 'meta_description', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18'),
(340, 73, 'meta_keywords', '', '2026-06-24 20:08:18', '2026-06-24 20:08:18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_dispositivos`
--

CREATE TABLE `sevenlux_dispositivos` (
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
-- Estrutura da tabela `sevenlux_galeria`
--

CREATE TABLE `sevenlux_galeria` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_galeria`
--

INSERT INTO `sevenlux_galeria` (`id`, `cliente_id`, `imagem`, `legenda`, `ordem`, `created_at`) VALUES
(1, 1, '1778879330_6a078b62d16b4.png', 'Design & Branding', 1, '2026-05-15 22:08:50'),
(2, 1, '1778879354_6a078b7aa60f6.png', 'Desenvolvimento Web', 2, '2026-05-15 22:09:14'),
(3, 1, '1778879372_6a078b8c47e6c.png', 'Marketing Digital', 3, '2026-05-15 22:09:32'),
(5, 1, '1778880660_6a07909401875.png', 'Redesign de marca ', 4, '2026-05-15 22:31:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_perguntas_magicas`
--

CREATE TABLE `sevenlux_perguntas_magicas` (
  `id` int(10) UNSIGNED NOT NULL,
  `pergunta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_perguntas_magicas`
--

INSERT INTO `sevenlux_perguntas_magicas` (`id`, `pergunta`) VALUES
(1, 'Qual criatura mística protege os portais dos teus sonhos mais profundos?'),
(2, 'Que elixir ou bebida mágica purifica a tua alma antes de um ritual?'),
(3, 'Em qual pedra preciosa se encontra gravada a verdadeira essência do teu destino?'),
(4, 'Que tom ou cor de luz emana do teu ser quando canalizas o teu poder?'),
(5, 'Qual elemento da natureza elemental (luar, fogo, etc.) rege o teu santuário interior?'),
(6, 'Qual arquétipo ancestral guia os teus passos nos caminhos da noite?'),
(7, 'Que figura geométrica sagrada abre os portais do teu conhecimento oculto?');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_produtos`
--

CREATE TABLE `sevenlux_produtos` (
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
-- Extraindo dados da tabela `sevenlux_produtos`
--

INSERT INTO `sevenlux_produtos` (`id`, `cliente_id`, `nome`, `descricao`, `preco`, `imagem`, `ordem`, `created_at`) VALUES
(1, 1, 'Website Profissional', '	Site institucional completo com CMS (até 5 páginas), otimizado para SEO e responsivo.', '245.00', '1778879186_6a078ad2cccc9.png', 1, '2026-05-15 22:00:32'),
(2, 1, 'Loja Online (E‑commerce)', 'Plataforma de vendas completa, integração com pagamentos, gestão de stock e produtos ilimitados.', '477.00', '1778879168_6a078ac0d20a3.png', 2, '2026-05-15 22:01:06'),
(3, 1, 'Landing Page de Conversão', 'Página de alto impacto para campanhas, com formulário, analytics e integração com email marketing.', '167.00', '1778879238_6a078b06cc11b.png', 3, '2026-05-15 22:07:18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_publicacoes`
--

CREATE TABLE `sevenlux_publicacoes` (
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
-- Extraindo dados da tabela `sevenlux_publicacoes`
--

INSERT INTO `sevenlux_publicacoes` (`id`, `cliente_id`, `titulo`, `slug`, `conteudo`, `imagem`, `publicado`, `created_at`) VALUES
(1, 1, '5 Tendências de Design para 2026', '5-tendencias-de-design-para-2026', 'A tendência de design para 2026 une o minimalismo à tipografia ousada e ao dark mode, criando interfaces limpas e impactantes. O texto explora como essa estética reduz distrações, realça o conteúdo com títulos marcantes e melhora a experiência do utilizador com fundos escuros. Saiba como aplicar estas estratégias para criar projetos visuais modernos, sofisticados e de fácil leitura.', '1778879729_6a078cf11e6bb.png', 1, '2026-05-15 22:15:29'),
(2, 1, 'Como Escolher a Plataforma Ideal para o seu E‑commerce', 'como-escolher-a-plataforma-ideal-para-o-seu-e-commerce', ' Comparação entre Shopify, WooCommerce e Magento.\r\nO **Shopify** destaca-se pela rapidez e simplicidade com alojamento incluído, sendo ideal para quem quer focar apenas nas vendas. O **WooCommerce** oferece flexibilidade e controlo total sobre os dados, integrando-se perfeitamente em WordPress. Já o **Magento** surge como a solução robusta para grandes marcas com catálogos massivos e operações internacionais complexas.', '1778879794_6a078d328e258.png', 1, '2026-05-15 22:16:34'),
(3, 1, 'SEO em 2026: O que mudou e como se adaptar', 'seo-em-2026-o-que-mudou-e-como-se-adaptar', ' Novas regras do Google, experiência do utilizador e Core Web Vitals.\r\n\r\nAs novas diretrizes do Google priorizam a experiência do utilizador como fator crítico de posicionamento orgânico. O foco central recai sobre os Core Web Vitals, métricas que avaliam a velocidade de carregamento, interatividade e estabilidade visual das páginas. Sites que não otimizarem estes indicadores técnicos correm o risco de perder visibilidade e tráfego qualificado em 2026.', '1778880086_6a078e5697ed5.png', 1, '2026-05-15 22:21:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_rate_limits`
--

CREATE TABLE `sevenlux_rate_limits` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `tentativas` int(11) NOT NULL DEFAULT 1,
  `primeira_tentativa` datetime NOT NULL,
  `ultima_tentativa` datetime NOT NULL,
  `bloqueado_ate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_respostas_magicas`
--

CREATE TABLE `sevenlux_respostas_magicas` (
  `id` int(10) UNSIGNED NOT NULL,
  `pergunta_id` int(10) UNSIGNED NOT NULL,
  `resposta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_respostas_magicas`
--

INSERT INTO `sevenlux_respostas_magicas` (`id`, `pergunta_id`, `resposta`) VALUES
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
-- Estrutura da tabela `sevenlux_servicos`
--

CREATE TABLE `sevenlux_servicos` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `icone` varchar(50) DEFAULT 'fa-star',
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_servicos`
--

INSERT INTO `sevenlux_servicos` (`id`, `cliente_id`, `titulo`, `descricao`, `icone`, `ordem`) VALUES
(1, 1, 'Design & Branding', 'Criamos identidades visuais memoráveis, desde o logotipo à paleta de cores, que contam a sua história.', 'fa-image', 1),
(3, 1, '	Desenvolvimento Web', 'Sites, lojas online e aplicações web modernas, rápidas e responsivas, feitas à medida do seu negócio.', 'fa-code', 2),
(4, 1, 'Marketing Digital', 'Estratégias de SEO, Google Ads e redes sociais para atrair mais clientes e aumentar as suas vendas.', 'fa-chart-line', 3),
(15, 73, 'teste 88', 'primeiro serviço', 'fa-shirt', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sevenlux_warnings`
--

CREATE TABLE `sevenlux_warnings` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolvido` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sevenlux_warnings`
--

INSERT INTO `sevenlux_warnings` (`id`, `slug`, `motivo`, `ip`, `data`, `resolvido`) VALUES
(1, 'vitrine-demo', 'Recuperação de código realizada com sucesso', '::1', '2026-06-12 00:07:37', 0),
(2, 'lemmteste', 'Recuperação de código realizada com sucesso', '::1', '2026-06-12 00:10:38', 0),
(3, 'vitrine-demo', 'Recuperação de código realizada com sucesso', '::1', '2026-06-13 22:03:58', 0),
(4, 'vitrine-demo', 'Código redefinido via email', '::1', '2026-06-23 20:27:54', 0),
(5, 'vitrine-demo', 'Código redefinido via email', '::1', '2026-06-23 20:29:43', 0),
(6, 'lemm', 'Código redefinido via email', '::1', '2026-06-23 21:00:29', 0),
(7, 'lemm', 'Código redefinido via email', '::1', '2026-06-23 21:04:47', 0),
(8, 'vitrine-demo', 'Código redefinido via email', '::1', '2026-06-23 21:06:21', 0),
(9, 'lubio', 'Limite de 7 tentativas atingido', '::1', '2026-06-23 21:06:55', 0),
(10, 'lubio', 'Código redefinido via email', '::1', '2026-06-23 21:07:44', 0),
(11, 'lubio', 'Código redefinido via email', '::1', '2026-06-23 21:09:00', 0),
(12, 'lemm', 'Código redefinido via email', '::1', '2026-06-23 21:29:55', 0),
(13, 'lemm', 'Código redefinido via email', '::1', '2026-06-23 21:36:45', 0),
(14, 'teste88', 'Limite de 7 tentativas atingido', '::1', '2026-06-24 21:23:57', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `sevenlux_audit_logs`
--
ALTER TABLE `sevenlux_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cliente` (`cliente_id`),
  ADD KEY `idx_acao` (`acao`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Índices para tabela `sevenlux_clientes`
--
ALTER TABLE `sevenlux_clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `sevenlux_configuracoes_site`
--
ALTER TABLE `sevenlux_configuracoes_site`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cliente_chave` (`cliente_id`,`chave`);

--
-- Índices para tabela `sevenlux_dispositivos`
--
ALTER TABLE `sevenlux_dispositivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_device` (`slug`,`device_id`);

--
-- Índices para tabela `sevenlux_galeria`
--
ALTER TABLE `sevenlux_galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `sevenlux_perguntas_magicas`
--
ALTER TABLE `sevenlux_perguntas_magicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sevenlux_produtos`
--
ALTER TABLE `sevenlux_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `sevenlux_publicacoes`
--
ALTER TABLE `sevenlux_publicacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_cliente` (`slug`,`cliente_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `sevenlux_rate_limits`
--
ALTER TABLE `sevenlux_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ip_acao` (`ip`,`acao`),
  ADD KEY `idx_ip` (`ip`),
  ADD KEY `idx_acao` (`acao`),
  ADD KEY `idx_bloqueado_ate` (`bloqueado_ate`);

--
-- Índices para tabela `sevenlux_respostas_magicas`
--
ALTER TABLE `sevenlux_respostas_magicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices para tabela `sevenlux_servicos`
--
ALTER TABLE `sevenlux_servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices para tabela `sevenlux_warnings`
--
ALTER TABLE `sevenlux_warnings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `sevenlux_audit_logs`
--
ALTER TABLE `sevenlux_audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `sevenlux_clientes`
--
ALTER TABLE `sevenlux_clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de tabela `sevenlux_configuracoes_site`
--
ALTER TABLE `sevenlux_configuracoes_site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

--
-- AUTO_INCREMENT de tabela `sevenlux_dispositivos`
--
ALTER TABLE `sevenlux_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sevenlux_galeria`
--
ALTER TABLE `sevenlux_galeria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `sevenlux_perguntas_magicas`
--
ALTER TABLE `sevenlux_perguntas_magicas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `sevenlux_produtos`
--
ALTER TABLE `sevenlux_produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `sevenlux_publicacoes`
--
ALTER TABLE `sevenlux_publicacoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `sevenlux_rate_limits`
--
ALTER TABLE `sevenlux_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `sevenlux_respostas_magicas`
--
ALTER TABLE `sevenlux_respostas_magicas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `sevenlux_servicos`
--
ALTER TABLE `sevenlux_servicos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `sevenlux_warnings`
--
ALTER TABLE `sevenlux_warnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `sevenlux_configuracoes_site`
--
ALTER TABLE `sevenlux_configuracoes_site`
  ADD CONSTRAINT `sevenlux_configuracoes_site_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `sevenlux_clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sevenlux_dispositivos`
--
ALTER TABLE `sevenlux_dispositivos`
  ADD CONSTRAINT `sevenlux_dispositivos_ibfk_1` FOREIGN KEY (`slug`) REFERENCES `sevenlux_clientes` (`slug`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sevenlux_galeria`
--
ALTER TABLE `sevenlux_galeria`
  ADD CONSTRAINT `sevenlux_galeria_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `sevenlux_clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sevenlux_produtos`
--
ALTER TABLE `sevenlux_produtos`
  ADD CONSTRAINT `sevenlux_produtos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `sevenlux_clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sevenlux_publicacoes`
--
ALTER TABLE `sevenlux_publicacoes`
  ADD CONSTRAINT `sevenlux_publicacoes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `sevenlux_clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sevenlux_servicos`
--
ALTER TABLE `sevenlux_servicos`
  ADD CONSTRAINT `sevenlux_servicos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `sevenlux_clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
