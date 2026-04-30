-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           10.4.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Versão:              11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for php_store
CREATE DATABASE IF NOT EXISTS `php_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `php_store`;

-- Dumping structure for table php_store.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id_admin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) DEFAULT NULL,
  `senha` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table php_store.admins: ~0 rows (approximately)
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` (`id_admin`, `usuario`, `senha`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'admin@admin.com', '$2y$10$uJT4sQI7FMQ2zi3qv09yEOB/AqVKiO1JBPqneF2.gnqWXuihPVbOS', '2021-04-13 22:51:36', '2021-04-13 22:51:36', NULL);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;

-- Dumping structure for table php_store.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table php_store.clientes: ~3 rows (approximately)
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id_cliente`, `email`, `senha`, `nome_completo`, `morada`, `cidade`, `telefone`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'sys4soft.phpstore@gmail.com', '$2y$10$N41Q1.w2psarKyW1kCK9COr/GPldE.9lygsYICW6uDVEsSQJe6Yti', 'Joao ribeiro alterado', 'morada do cliente alterada', 'cidade do cliente alterada', '123456', NULL, 1, '2021-01-30 17:31:06', '2021-03-23 23:15:50', NULL),
	(3, 'cliente1@gmail.com', '$2y$10$StumuKlVUQTIY3d/gCSI1u9kkCngnrwj4YjzG.6rHMGEZDVho0qS.', 'Cliente 1', 'Morada 1', 'Cidade 1', '', NULL, 1, '2021-01-30 17:31:06', '2021-05-01 16:56:53', NULL),
	(4, 'cliente2@gmail.com', '$2y$10$StumuKlVUQTIY3d/gCSI1u9kkCngnrwj4YjzG.6rHMGEZDVho0qS.', 'Cliente 2', 'Morada 2', 'Cidade 2', '', NULL, 0, '2021-01-30 17:31:06', '2021-05-01 16:56:59', '2021-05-01 16:56:58'),
	(5, 'cliente3@gmail.com', '$2y$10$StumuKlVUQTIY3d/gCSI1u9kkCngnrwj4YjzG.6rHMGEZDVho0qS.', 'Cliente 3', 'Morada 3', 'Cidade 3', '', NULL, 1, '2021-01-30 17:31:06', '2021-03-23 22:08:35', NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Dumping structure for table php_store.encomendas
CREATE TABLE IF NOT EXISTS `encomendas` (
  `id_encomenda` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(10) unsigned DEFAULT NULL,
  `data_encomenda` datetime DEFAULT NULL,
  `morada` varchar(200) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `codigo_encomenda` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `mensagem` varchar(1000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_encomenda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table php_store.encomendas: ~5 rows (approximately)
/*!40000 ALTER TABLE `encomendas` DISABLE KEYS */;
INSERT INTO `encomendas` (`id_encomenda`, `id_cliente`, `data_encomenda`, `morada`, `cidade`, `email`, `telefone`, `codigo_encomenda`, `status`, `mensagem`, `created_at`, `updated_at`) VALUES
	(1, 1, '2021-03-18 22:17:45', 'morada do cliente', 'cidade do cliente', 'sys4soft.phpstore@gmail.com', '', 'HC465769', 'ENVIADA', '', '2021-03-18 22:17:45', '2021-05-21 21:07:28'),
	(2, 1, '2021-03-18 22:19:15', 'morada do cliente', 'cidade do cliente', 'sys4soft.phpstore@gmail.com', '', 'CZ503792', 'EM PROCESSAMENTO', '', '2021-03-18 22:19:15', '2021-04-05 23:05:43'),
	(3, 4, '2021-05-21 21:22:11', 'Rua Direita', 'Viseu', 'cliente2@gmail.com', '111222333', 'NL755592', 'PENDENTE', '', '2021-05-21 21:22:11', '2021-05-21 21:22:11'),
	(4, 3, '2021-05-21 21:22:27', 'Rua da Calçada', 'Lisboa', 'cliente1@gmail.com', '232323233', 'BF781979', 'PENDENTE', '', '2021-05-21 21:22:27', '2021-05-21 21:22:27'),
	(5, 3, '2021-05-21 21:22:41', 'Rua da Calçada', 'Lisboa', 'cliente1@gmail.com', '232323233', 'MV810587', 'PENDENTE', '', '2021-05-21 21:22:41', '2021-05-21 21:22:41');
/*!40000 ALTER TABLE `encomendas` ENABLE KEYS */;

-- Dumping structure for table php_store.encomenda_produto
CREATE TABLE IF NOT EXISTS `encomenda_produto` (
  `id_encomenda_produto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_encomenda` int(10) unsigned DEFAULT NULL,
  `designacao_produto` varchar(200) DEFAULT NULL,
  `preco_unidade` decimal(6,2) unsigned DEFAULT NULL,
  `quantidade` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_encomenda_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table php_store.encomenda_produto: ~9 rows (approximately)
/*!40000 ALTER TABLE `encomenda_produto` DISABLE KEYS */;
INSERT INTO `encomenda_produto` (`id_encomenda_produto`, `id_encomenda`, `designacao_produto`, `preco_unidade`, `quantidade`, `created_at`) VALUES
	(1, 1, 'Tshirt Vermelha', 45.70, 1, '2021-03-18 22:17:45'),
	(2, 1, 'Tshirt Azul', 55.25, 1, '2021-03-18 22:17:45'),
	(3, 1, 'Vestido Vermelho', 75.20, 1, '2021-03-18 22:17:45'),
	(4, 2, 'Vertido Azul', 86.00, 3, '2021-03-18 22:19:15'),
	(5, 3, 'Tshirt Azul', 55.25, 3, '2021-05-21 21:22:11'),
	(6, 4, 'Tshirt Vermelha', 45.70, 1, '2021-05-21 21:22:27'),
	(7, 4, 'Tshirt Amarela', 32.00, 1, '2021-05-21 21:22:27'),
	(8, 4, 'Vestido Vermelho', 75.20, 1, '2021-05-21 21:22:27'),
	(9, 5, 'Vestido Verde', 48.85, 2, '2021-05-21 21:22:41');
/*!40000 ALTER TABLE `encomenda_produto` ENABLE KEYS */;

-- Dumping structure for table php_store.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) DEFAULT NULL,
  `nome_produto` varchar(50) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `imagem` varchar(200) DEFAULT NULL,
  `preco` decimal(6,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table php_store.produtos: ~9 rows (approximately)
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` (`id_produto`, `categoria`, `nome_produto`, `descricao`, `imagem`, `preco`, `stock`, `visivel`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'homem', 'Tshirt Vermelha', 'Ab laborum, commodi aspernatur, quas distinctio cum quae omnis autem ea, odit sint quisquam similique! Labore aliquam amet veniam ad fugiat optio.', 'tshirt_vermelha.png', 45.70, 100, 1, '2021-02-06 19:45:18', '2021-02-06 19:45:25', NULL),
	(2, 'homem', 'Tshirt Azul', 'Possimus iusto esse atque autem rem, porro officiis sapiente quos velit laboriosam id expedita odio obcaecati voluptate repudiandae dignissimos eveniet repellat blanditiis.', 'tshirt_azul.png', 55.25, 100, 1, '2021-02-06 19:45:19', '2021-02-06 19:45:25', NULL),
	(3, 'homem', 'Tshirt Verde', 'Nostrum quisquam dolorum dolor autem accusamus fugit nesciunt, atque et? Quis eum nemo quidem officia cum dolorem voluptates! Autem, earum. Similique, fugit.', 'tshirt_verde.png', 35.15, 0, 1, '2021-02-06 19:45:20', '2021-02-06 19:45:26', NULL),
	(4, 'homem', 'Tshirt Amarela', 'Molestiae quaerat distinctio, facere perferendis necessitatibus optio repellat alias commodi voluptatem velit corrupti natus exercitationem quos amet facilis sint nulla delectus.', 'tshirt_amarela.png', 32.00, 100, 1, '2021-02-06 19:45:20', '2021-02-06 19:45:27', NULL),
	(5, 'mulher', 'Vestido Vermelho', 'Labore voluptatem sed in distinctio iste tempora quo assumenda impedit illo soluta repudiandae animi earum suscipit, sequi excepturi inventore magnam velit voluptatibus.', 'dress_vermelho.png', 75.20, 100, 1, '2021-02-06 19:45:21', '2021-02-06 19:45:27', NULL),
	(6, 'mulher', 'Vertido Azul', 'Provident ipsum earum magnam odit in, illum nostrum est illo pariatur molestias esse delectus aliquam ullam maxime mollitia tempore, sunt officia suscipit.', 'dress_azul.png', 86.00, 100, 1, '2021-02-06 19:45:21', '2021-02-06 19:45:28', NULL),
	(7, 'mulher', 'Vestido Verde', 'Qui aliquid sed quisquam autem quas recusandae labore neque laudantium iusto modi repudiandae doloremque ipsam ad omnis inventore, cum ducimus praesentium. Consectetur!', 'dress_verde.png', 48.85, 100, 1, '2021-02-06 19:45:22', '2021-02-06 19:45:28', NULL),
	(8, 'mulher', 'Vestido Amarelo', 'Aspernatur labore corporis modi quis temporibus eos hic? Sed fugiat, repudiandae distinctio, labore temporibus, non magni consectetur dolorum earum amet impedit nesciunt.', 'dress_amarelo.png', 46.45, 100, 1, '2021-02-06 19:45:22', '2021-02-06 19:45:29', NULL);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
