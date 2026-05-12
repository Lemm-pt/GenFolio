-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Maio-2026 às 00:59
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
-- Estrutura da tabela `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$jQUpZlu/id4cBVYOzk9FEO01/TpjA48.3dfxkfoOqIlTMZ0diY/N.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(250) DEFAULT NULL,
  `nome_completo` varchar(250) DEFAULT NULL,
  `morada` varchar(250) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `purl` varchar(50) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `email`, `senha`, `nome_completo`, `morada`, `cidade`, `telefone`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'teste@teste.pt', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-22 22:40:35', '2021-01-22 23:39:13', NULL),
(2, 'ana@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-22 22:40:35', '2021-01-22 23:39:18', NULL),
(3, 'carlos@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-22 22:40:35', '2021-01-22 23:39:24', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `chave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `chave`, `valor`, `descricao`) VALUES
(1, 'email_contacto', 'esposende@dsprivate.com', 'Email que recebe as mensagens do formulário'),
(2, 'telefone_contacto', '+351 938630655', 'Telefone exibido no rodapé e contacto'),
(3, 'morada', 'Esposende, Portugal', 'Morada exibida'),
(4, 'logo_parte1', 'Jo', 'Primeira parte do logotipo'),
(5, 'logo_parte2', 'Folio', 'Segunda parte do logotipo (cor dourada)'),
(6, 'nome_site', 'JoFolio', 'Nome do site/título'),
(7, 'facebook_url', '#', 'Link do Facebook'),
(8, 'instagram_url', '#', 'Link do Instagram'),
(9, 'linkedin_url', '#', 'Link do LinkedIn');

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
(1, 1, 'logo_parte1', 'Vitrine', '2026-05-12 21:45:17', '2026-05-12 22:48:26'),
(2, 1, 'logo_parte2', '.lemm', '2026-05-12 21:45:17', '2026-05-12 21:45:17'),
(3, 1, 'slogan', 'Soluções Personalizadas para o seu negócioteste', '2026-05-12 21:45:17', '2026-05-12 21:55:01'),
(4, 1, 'meta_description', 'Vitrine.lemm - Soluções digitais para o seu negócioteste', '2026-05-12 21:45:17', '2026-05-12 21:55:01'),
(5, 1, 'meta_keywords', 'vitrine,lemm,digital,negócioteste', '2026-05-12 21:45:17', '2026-05-12 21:55:01'),
(6, 1, 'email_contacto', 'contato@vitrine.lemmqqqqqq', '2026-05-12 21:45:17', '2026-05-12 22:09:42'),
(7, 1, 'telefone', '+351 93863065523', '2026-05-12 21:45:17', '2026-05-12 21:55:01'),
(8, 1, 'endereco', 'Esposende, Portugal234', '2026-05-12 21:45:17', '2026-05-12 21:55:01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `galeria`
--

CREATE TABLE `galeria` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `imagem` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `galeria`
--

INSERT INTO `galeria` (`id`, `cliente_id`, `imagem`, `legenda`, `ordem`, `created_at`) VALUES
(1, 0, '1778624394_6a03a78a6d1b7.jpg', 'qqqqq', 1, '2026-05-12 23:19:54'),
(2, 0, '1778624409_6a03a799f0512.jpg', 'weeeerr', 2, '2026-05-12 23:20:09'),
(3, 1, '1778624499_6a03a7f35da66.jpg', 'ertrtyyy', 1, '2026-05-12 23:21:39'),
(4, 1, '1778624520_6a03a8087f4ae.jpg', 'aaaaaa', 2, '2026-05-12 23:22:00'),
(5, 1, '1778624566_6a03a836cfaf5.jpg', '22www', 3, '2026-05-12 23:22:46'),
(6, 1, '1778624654_6a03a88e8038d.jpg', 'sumo', 4, '2026-05-12 23:24:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imoveis`
--

CREATE TABLE `imoveis` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `status` enum('disponivel','vendido','reservado') DEFAULT 'disponivel',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `imoveis`
--

INSERT INTO `imoveis` (`id`, `titulo`, `slug`, `descricao`, `preco`, `localizacao`, `tipo`, `imagem`, `destaque`, `status`, `created_at`) VALUES
(1, 'Apartamento Esposende', 'apartamento-esposende', 'Apartamento Esposende descricao perto do rio', '1123.00', 'Marinhas', 'Moradis', NULL, 1, 'disponivel', '2026-04-29 18:49:51');

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
(3, 1, 'teste r', 'wwwiwiwiw', '13.00', '1778621225_6a039b2957f94.png', 1, '2026-05-12 22:27:05'),
(4, 1, 'eeeee', '', '1.00', '1778622264_6a039f385711a.png', 2, '2026-05-12 22:44:24'),
(5, 1, 'tshirt verde', 'de flanela e licra', '15.00', '1778623026_6a03a232a190c.png', 3, '2026-05-12 22:56:54'),
(6, 1, 'www', 'wertttttb ttttt', '45.00', '1778623189_6a03a2d5328c3.png', 4, '2026-05-12 22:59:49'),
(7, 1, 'www', 'wwwwwww', '123.00', NULL, 5, '2026-05-12 23:02:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicacoes`
--

CREATE TABLE `publicacoes` (
  `id` int(11) NOT NULL,
  `cliente_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `publicado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `publicacoes`
--

INSERT INTO `publicacoes` (`id`, `cliente_id`, `titulo`, `slug`, `conteudo`, `imagem`, `publicado`, `created_at`) VALUES
(1, 1, 'my first puliction online', 'my-first-puliction-online', 'very good now I will learn a little english', '1778623995_6a03a5fb833c7.jpg', 1, '2026-05-12 22:13:15'),
(2, 1, 'my orange juyce', 'my-orange-juyce', 'I love orange juyce in the morning when I usually run', '1778625051_6a03aa1badc5b.jpg', 1, '2026-05-12 22:30:51'),
(3, 1, 'ttttt', 'ttttt', 'ttttttttttttttt', '1778625092_6a03aa44cb03b.jpg', 0, '2026-05-12 22:31:32');

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
(1, 1, 'Mediação fixe', 'Curadoria de imóveisde renome', 'fa-home', 2),
(2, 1, 'Intermediaçãode teste agora com left', 'testeb teste bem fixe', 'fa-car', 3),
(3, 1, 'Construçãoteste', 'Serviço \"chave na m', 'fa-check', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);

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
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `imoveis`
--
ALTER TABLE `imoveis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
  ADD UNIQUE KEY `slug` (`slug`),
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
-- AUTO_INCREMENT de tabela `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `imoveis`
--
ALTER TABLE `imoveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `configuracoes_site`
--
ALTER TABLE `configuracoes_site`
  ADD CONSTRAINT `configuracoes_site_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
