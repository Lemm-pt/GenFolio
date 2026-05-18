-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Maio-2026 às 00:54
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
-- Banco de dados: `vitrina`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `purl` varchar(50) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `email`, `slug`, `senha`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'lemm.pt@gmail.com', 'vitrine-demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-05-15 17:00:42', '2026-05-15 18:07:34', NULL),
(9, 'lubiomarona@gmail.com', 'aa-dd', '$2y$10$339s/g56OHPzHqBrlNvKFOpexmrX2vz28lXU8u8li9uNkjwcSR5pe', NULL, 1, '2026-05-15 19:28:50', '2026-05-15 19:30:40', NULL),
(12, 'lemm777@gmail.com', 'projecto3d', '$2y$10$ACTKP1Yj81Y93D8WriulSOCDs/qGM85p9.NqH8aeTLbRGskguIKSy', NULL, 1, '2026-05-15 23:41:25', '2026-05-15 23:41:59', NULL);

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
(11, 9, 'logo_parte1', 'aa', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(12, 9, 'logo_parte2', 'dd', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(13, 9, 'logo_imagem', '', '2026-05-15 19:30:40', '2026-05-15 19:30:40'),
(14, 9, 'slogan', 'Soluções Personalizadas de aa-dd', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(15, 9, 'texto_descritivo', 'Bem-vindo ao seu novo site! de aa-dd\r\nvvvvvvvvvvvvvvvvvvvvvvvvvvv\r\nvvvv\r\nkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk de aa-ddde aa-ddde aa-ddde aa-ddde aa-dd', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(16, 9, 'email_contacto', 'lubiomarona@gmail.com', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(17, 9, 'telefone', '1234567', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(18, 9, 'endereco', 'Viana do Castelo', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(19, 9, 'meta_description', 'de aa-ddde aa-ddde aa-ddde aa-ddde aa-ddde aa-ddde aa-ddde aa-ddde aa-dd', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(20, 9, 'meta_keywords', 'de aa-dd', '2026-05-15 19:30:40', '2026-05-15 19:41:09'),
(41, 12, 'logo_parte1', 'Projecto', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(42, 12, 'logo_parte2', '3D', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(43, 12, 'logo_imagem', '', '2026-05-15 23:41:59', '2026-05-15 23:41:59'),
(44, 12, 'slogan', 'Soluções Personalizadas Para Modelagem', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(45, 12, 'texto_descritivo', 'Modelagens 3D para os seus projectos elaborados co sketchup', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(46, 12, 'email_contacto', 'lemm777@gmail.com', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(47, 12, 'telefone', '12345678', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(48, 12, 'endereco', 'Ofir, Esposende', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(49, 12, 'meta_description', 'qqqqq', '2026-05-15 23:41:59', '2026-05-15 23:45:23'),
(50, 12, 'meta_keywords', 'qqqq,qqqq,qqq,rrrr', '2026-05-15 23:41:59', '2026-05-15 23:45:23');

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
(5, 1, '1778880660_6a07909401875.png', 'Redesign de marca ', 4, '2026-05-15 22:31:00');

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
(2, 9, 'aa-dd', 'www\r\neee\r\nrrrrrrrrrrrr', 'fa-hotel', 0),
(3, 1, '	Desenvolvimento Web', 'Sites, lojas online e aplicações web modernas, rápidas e responsivas, feitas à medida do seu negócio.', 'fa-code', 2),
(4, 1, 'Marketing Digital', 'Estratégias de SEO, Google Ads e redes sociais para atrair mais clientes e aumentar as suas vendas.', 'fa-chart-line', 3),
(5, 12, 'pppppp', 'ooo\r\nuuuuu', 'fa-wrench', 0);

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
-- Índices para tabela `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

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
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  ADD CONSTRAINT `configuracoes_site_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

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
