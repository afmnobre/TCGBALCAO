-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/02/2026 às 13:26
-- Versão do servidor: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `TCGBALCAO`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_lojas`
--

CREATE TABLE `admin_lojas` (
  `id_admin` int(11) NOT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admin_lojas`
--

INSERT INTO `admin_lojas` (`id_admin`, `nome`, `email`, `senha`) VALUES
(1, 'Admin Master', 'admin@tcgbalcao.com', '123456');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardgames`
--

CREATE TABLE `cardgames` (
  `id_cardgame` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem_fundo_card` varchar(255) DEFAULT NULL,
  `imagem_card_game` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cardgames`
--

INSERT INTO `cardgames` (`id_cardgame`, `nome`, `imagem_fundo_card`, `imagem_card_game`) VALUES
(1, 'Magic: The Gathering', 'MTG.webp', NULL),
(2, 'Pokémon TCG', 'Pokemon.webp', NULL),
(3, 'YuGiOh!', 'YuGOh.webp', NULL),
(4, 'Digimon TCG', 'Digimon.png', NULL),
(5, 'Flesh and Blood', 'Flesh_and_Blood.webp', NULL),
(6, 'Dungeons & Dragons', 'DeD.png', NULL),
(7, 'Star Wars: Unlimited', 'Star_Wars_Unlimited.webp', NULL),
(8, 'One Piece Tcg', 'One_Piece.webp', NULL),
(9, 'Beyblade', 'BeyBlade.webp', NULL),
(10, 'Lorcana', 'Lorcana.webp', NULL),
(11, 'Vanguard', 'Vanguard.webp', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `telefone`, `email`, `documento`, `data_cadastro`) VALUES
(1, 'Carlos Silva', '11999990001', 'carlos@teste.com', '12345678900', '2026-02-13 02:11:01'),
(2, 'Ana Souza', '11999990002', 'ana@teste.com', '12345678901', '2026-02-13 02:11:01'),
(4, 'Mariana Costa', '11999990004', 'mariana@teste.com', '12345678903', '2026-02-13 02:11:01'),
(5, 'Felipe Santos', '11999990005', 'felipe@teste.com', '12345678904', '2026-02-13 02:11:01'),
(9, 'Adilson Ferreira Martins', '119628511521', 'lnfm1987@gmail.com', NULL, '2026-02-13 22:29:45'),
(10, 'Adilson Ferreira Martins', '11962851151', 'lnfm1987@gmail.com', NULL, '2026-02-13 23:11:53'),
(11, '0 - Balcão', '11111111111', 'teste@teste.com.br', NULL, '2026-02-15 20:11:42'),
(12, 'Daniel Nobre Ferreira Martins', '1196281513', 'daniel@hotmail.com', NULL, '2026-02-17 12:05:52'),
(13, 'Lucas Nobre Ferreira Martins', '11962815638', 'lnfm1987@gmail.com', NULL, '2026-02-17 12:27:41'),
(14, 'Marta Nobre Maciel Martins', '22222222222', 'marta@marta.com.br', NULL, '2026-02-17 15:51:58'),
(15, 'Matheus Nobre Ferreira Martins', '11962851513', 'matheus@gmail.com', NULL, '2026-02-17 15:56:22'),
(16, 'Maria Edicia Nobre', '11958151452', 'maria@maria.com.br', NULL, '2026-02-19 04:01:29'),
(17, 'Eduardo Bolsonaro Junior', '15518418185', 'eduardo@presida.com.br', NULL, '2026-02-19 04:02:22'),
(18, 'Jefferson Ferreira da Silva', '45181815188', 'feff@jeff.com.br', NULL, '2026-02-19 04:03:56'),
(19, 'Reginaldo Mauro da Silva', '54818181545', 'reginaldo@teste.com.br', NULL, '2026-02-19 09:00:35'),
(20, 'Mariana Leme de Oliveira', '34256673663', 'mariana@euanosei.com.br', NULL, '2026-02-19 09:01:02'),
(21, 'Marcos Antonio Neto', '35266262362', 'marcos@teste.com.br', NULL, '2026-02-19 09:01:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes_cardgames`
--

CREATE TABLE `clientes_cardgames` (
  `id_cliente` int(11) NOT NULL,
  `id_cardgame` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes_cardgames`
--

INSERT INTO `clientes_cardgames` (`id_cliente`, `id_cardgame`) VALUES
(1, 1),
(1, 2),
(1, 4),
(2, 2),
(2, 8),
(4, 2),
(4, 3),
(4, 8),
(5, 2),
(9, 1),
(10, 1),
(10, 2),
(10, 3),
(10, 5),
(10, 8),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10),
(11, 11),
(12, 2),
(12, 8),
(12, 10),
(13, 2),
(13, 9),
(13, 11),
(14, 1),
(14, 2),
(14, 4),
(14, 9),
(15, 1),
(15, 8),
(16, 1),
(16, 8),
(17, 1),
(17, 8),
(18, 1),
(18, 8),
(19, 2),
(20, 2),
(21, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes_lojas`
--

CREATE TABLE `clientes_lojas` (
  `id_cliente` int(11) NOT NULL,
  `id_loja` int(11) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_vinculo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes_lojas`
--

INSERT INTO `clientes_lojas` (`id_cliente`, `id_loja`, `status`, `data_vinculo`) VALUES
(1, 1, 'ativo', '2026-02-13 02:11:01'),
(2, 1, 'ativo', '2026-02-13 02:11:01'),
(4, 1, 'ativo', '2026-02-13 02:11:01'),
(5, 1, 'ativo', '2026-02-13 02:11:01'),
(10, 1, 'ativo', '2026-02-13 23:11:53'),
(11, 1, 'ativo', '2026-02-15 20:11:42'),
(11, 2, 'ativo', '2026-02-17 00:23:32'),
(12, 1, 'ativo', '2026-02-17 12:05:52'),
(13, 1, 'ativo', '2026-02-17 12:27:41'),
(14, 1, 'ativo', '2026-02-17 15:52:46'),
(14, 2, 'ativo', '2026-02-17 15:51:58'),
(15, 2, 'ativo', '2026-02-17 15:56:22'),
(16, 2, 'ativo', '2026-02-19 04:01:29'),
(17, 2, 'ativo', '2026-02-19 04:02:22'),
(18, 2, 'ativo', '2026-02-19 04:03:56'),
(19, 1, 'ativo', '2026-02-19 09:00:35'),
(20, 1, 'ativo', '2026-02-19 09:01:02'),
(21, 1, 'ativo', '2026-02-19 09:01:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL,
  `id_loja` int(11) DEFAULT NULL,
  `tipo` enum('teste','mensal','anual') NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `status` enum('ativo','suspenso','cancelado') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `id_loja`, `tipo`, `data_inicio`, `data_fim`, `status`) VALUES
(1, 1, 'mensal', '2026-01-01', '2026-02-01', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_movimentacoes`
--

CREATE TABLE `estoque_movimentacoes` (
  `id_mov` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `tipo` enum('entrada','saida') DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `data_mov` datetime DEFAULT current_timestamp(),
  `referencia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque_movimentacoes`
--

INSERT INTO `estoque_movimentacoes` (`id_mov`, `id_produto`, `tipo`, `quantidade`, `data_mov`, `referencia`) VALUES
(1, 1, 'entrada', 50, '2026-02-13 02:11:01', 'NF001'),
(2, 2, 'entrada', 200, '2026-02-13 02:11:01', 'NF002'),
(3, 3, 'entrada', 100, '2026-02-13 02:11:01', 'NF003'),
(4, 4, 'entrada', 30, '2026-02-13 02:11:01', 'NF004'),
(5, 5, 'entrada', 20, '2026-02-13 02:11:01', 'NF005');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id_fornecedor` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id_fornecedor`, `nome`, `telefone`, `email`) VALUES
(1, 'Fornecedor A', '11333330001', 'fa@teste.com'),
(2, 'Fornecedor B', '11333330002', 'fb@teste.com'),
(3, 'Fornecedor C', '11333330003', 'fc@teste.com'),
(4, 'Fornecedor D', '11333330004', 'fd@teste.com'),
(5, 'Fornecedor E', '11333330005', 'fe@teste.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_pedidos`
--

CREATE TABLE `logs_pedidos` (
  `id_log` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `acao` enum('criado','alterado','excluido') DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data_log` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas`
--

CREATE TABLE `lojas` (
  `id_loja` int(11) NOT NULL,
  `nome_loja` varchar(150) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cor_tema` varchar(20) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `numero_contrato` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lojas`
--

INSERT INTO `lojas` (`id_loja`, `nome_loja`, `cnpj`, `endereco`, `cor_tema`, `logo`, `favicon`, `data_cadastro`, `numero_contrato`) VALUES
(1, 'Player\'s Stop Tcg', '29.836.936/0001-39', 'Rua A, 100', '#FF0000', 'logo.jpg', 'favicon.png', '2026-02-13 02:11:01', 'C001'),
(2, 'Neowalkers Geek Store', '43.395.867/0001-04', 'Rua B, 200', '#540a15', 'logo.jpg', 'logo.ico', '2026-02-16 21:27:16', 'C002');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas_fiscais`
--

CREATE TABLE `notas_fiscais` (
  `id_nf` int(11) NOT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `numero_nf` varchar(50) DEFAULT NULL,
  `data_nf` date DEFAULT NULL,
  `imagem_nf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notas_fiscais`
--

INSERT INTO `notas_fiscais` (`id_nf`, `id_fornecedor`, `numero_nf`, `data_nf`, `imagem_nf`) VALUES
(1, 1, 'NF001', '2026-01-01', NULL),
(2, 2, 'NF002', '2026-01-02', NULL),
(3, 3, 'NF003', '2026-01-03', NULL),
(4, 4, 'NF004', '2026-01-04', NULL),
(5, 5, 'NF005', '2026-01-05', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_loja` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `data_pedido` date DEFAULT NULL,
  `valor_variado` decimal(10,2) DEFAULT 0.00,
  `observacao_variado` text DEFAULT NULL,
  `pedido_pago` tinyint(1) DEFAULT 0,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_loja`, `id_cliente`, `data_pedido`, `valor_variado`, `observacao_variado`, `pedido_pago`, `criado_em`) VALUES
(18, 1, 2, '2026-01-10', 120.00, 'Pedido gerado para teste', 1, '2026-02-16 14:06:20'),
(19, 1, 4, '2026-01-15', 50.00, 'Pedido gerado para teste', 1, '2026-02-16 14:06:20'),
(20, 1, 5, '2026-01-20', 10.00, 'Pedido gerado para teste', 1, '2026-02-16 14:06:20'),
(21, 1, 10, '2026-01-25', 250.00, 'Pedido gerado para teste', 1, '2026-02-16 14:06:20'),
(22, 1, 11, '2026-01-28', 200.00, 'Pedido gerado para teste', 1, '2026-02-16 14:06:20'),
(23, 1, 1, '2026-01-05', 0.00, 'Pedido gerado para teste', 1, '2026-02-16 14:07:23'),
(24, 1, 2, '2026-01-10', 220.00, 'Pedido gerado para teste', 1, '2026-02-16 14:07:23'),
(25, 1, 4, '2026-01-15', 150.00, 'Pedido gerado para teste', 1, '2026-02-16 14:07:23'),
(26, 1, 1, '2026-01-05', 180.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(27, 1, 2, '2026-01-10', 220.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(28, 1, 4, '2026-01-15', 150.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(29, 1, 5, '2026-01-20', 300.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(30, 1, 10, '2026-01-25', 250.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(31, 1, 11, '2026-01-28', 200.00, 'Pedido gerado para teste', 1, '2026-02-16 14:09:11'),
(32, 1, 1, '2026-02-05', 110.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(33, 1, 2, '2026-02-10', 20.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(34, 1, 4, '2026-02-15', 10.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(35, 1, 5, '2026-02-20', 29.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(36, 1, 10, '2026-02-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(37, 1, 11, '2026-02-28', 10.00, 'Pedido gerado para teste', 1, '2026-02-16 14:12:31'),
(38, 1, 1, '2026-03-05', 11.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(39, 1, 2, '2026-03-10', 20.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(40, 1, 4, '2026-03-15', 10.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(41, 1, 5, '2026-03-20', 29.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(42, 1, 10, '2026-03-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(43, 1, 11, '2026-03-28', 15.00, 'Pedido gerado para teste', 1, '2026-02-16 14:15:33'),
(44, 1, 1, '2026-04-05', 110.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(45, 1, 2, '2026-04-10', 201.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(46, 1, 4, '2026-04-15', 70.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(47, 1, 5, '2026-04-20', 32.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(48, 1, 10, '2026-04-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(49, 1, 11, '2026-04-28', 15.00, 'Pedido gerado para teste', 1, '2026-02-16 14:19:07'),
(50, 1, 1, '2026-05-05', 1010.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(51, 1, 2, '2026-05-10', 201.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(52, 1, 4, '2026-05-15', 70.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(53, 1, 5, '2026-05-20', 32.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(54, 1, 10, '2026-05-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(55, 1, 11, '2026-05-28', 15.00, 'Pedido gerado para teste', 1, '2026-02-16 14:43:32'),
(56, 1, 1, '2026-06-05', 110.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(57, 1, 2, '2026-06-10', 601.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(58, 1, 4, '2026-06-15', 71.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(59, 1, 5, '2026-06-20', 302.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(60, 1, 10, '2026-06-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(61, 1, 11, '2026-06-28', 2.00, 'Pedido gerado para teste', 1, '2026-02-16 14:59:44'),
(62, 1, 1, '2026-07-05', 210.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(63, 1, 2, '2026-07-10', 201.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(64, 1, 4, '2026-07-15', 71.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(65, 1, 5, '2026-07-20', 1302.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(66, 1, 10, '2026-07-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(67, 1, 11, '2026-07-28', 2.00, 'Pedido gerado para teste', 1, '2026-02-16 15:05:03'),
(68, 1, 1, '2026-08-05', 110.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(69, 1, 2, '2026-08-10', 201.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(70, 1, 4, '2026-08-15', 71.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(71, 1, 5, '2026-08-20', 1302.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(72, 1, 10, '2026-08-25', 25.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(73, 1, 11, '2026-08-28', 2.00, 'Pedido gerado para teste', 1, '2026-02-16 15:09:43'),
(74, 1, 1, '2026-09-05', 80.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(75, 1, 2, '2026-09-10', 299.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(76, 1, 4, '2026-09-15', 71.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(77, 1, 5, '2026-09-20', 198.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(78, 1, 10, '2026-09-25', 250.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(79, 1, 11, '2026-09-28', 29.00, 'Pedido gerado para teste', 1, '2026-02-16 15:13:37'),
(80, 1, 1, '2026-10-05', 50.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(81, 1, 2, '2026-10-10', 299.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(82, 1, 4, '2026-10-15', 71.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(83, 1, 5, '2026-10-20', 178.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(84, 1, 10, '2026-10-25', 198.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(85, 1, 11, '2026-10-28', 19.00, 'Pedido gerado para teste', 1, '2026-02-16 15:41:24'),
(86, 1, 1, '2026-11-05', 5.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(87, 1, 2, '2026-11-10', 99.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(88, 1, 4, '2026-11-15', 99.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(89, 1, 5, '2026-11-20', 21.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(90, 1, 10, '2026-11-25', 198.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(91, 1, 11, '2026-11-28', 19.00, 'Pedido gerado para teste', 1, '2026-02-16 15:43:24'),
(92, 1, 1, '2026-12-05', 50.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(93, 1, 2, '2026-12-10', 99.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(94, 1, 4, '2026-12-15', 99.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(95, 1, 5, '2026-12-20', 201.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(96, 1, 10, '2026-12-25', 198.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(97, 1, 11, '2026-12-28', 19.00, 'Pedido gerado para teste', 1, '2026-02-16 15:44:54'),
(108, 1, 11, '2026-02-17', 0.00, '', 1, '2026-02-17 10:42:30'),
(109, 2, 11, '2026-02-17', 132.00, 'Carta fora do estoque vendida: \r\nSnapcaster Mage', 1, '2026-02-17 10:51:44'),
(112, 1, 13, '2025-02-11', 0.00, '', 1, '2026-02-17 14:30:49'),
(118, 1, 4, '2026-02-17', 0.00, '', 1, '2026-02-17 19:57:33'),
(119, 1, 14, '2026-02-17', 0.00, '', 1, '2026-02-17 20:26:36'),
(126, 1, 10, '2026-02-17', 0.00, '', 1, '2026-02-17 20:37:24'),
(127, 1, 5, '2026-02-19', 0.00, '', 1, '2026-02-17 20:38:00'),
(128, 1, 11, '2026-02-19', 999.00, 'teste BALCÂO.', 1, '2026-02-17 20:47:26'),
(133, 1, 11, '2026-02-05', 0.00, '', 1, '2026-02-17 21:59:59'),
(135, 1, 10, '2026-02-18', 1.53, 'Vendi a loja.', 1, '2026-02-17 22:08:02'),
(137, 1, 2, '2026-02-19', 0.00, '', 1, '2026-02-17 22:14:03'),
(138, 1, 1, '2026-02-19', 0.00, '', 1, '2026-02-17 22:15:03'),
(139, 1, 13, '2026-02-19', 0.00, '', 1, '2026-02-17 22:26:24'),
(140, 1, 12, '2026-02-19', 0.00, '', 1, '2026-02-17 22:32:55'),
(141, 1, 10, '2026-02-19', 0.00, '', 1, '2026-02-17 22:36:26'),
(142, 1, 2, '2026-02-17', 0.00, '', 1, '2026-02-17 22:57:06'),
(143, 1, 1, '2026-02-17', 0.00, '', 1, '2026-02-17 22:57:06'),
(144, 1, 12, '2026-02-17', 0.00, '', 1, '2026-02-17 23:18:05'),
(148, 1, 2, '2026-02-18', 10.00, 'teste lucas', 1, '2026-02-17 23:42:26'),
(149, 1, 14, '2026-02-18', 5.00, 'Marta', 1, '2026-02-17 23:47:53'),
(150, 1, 4, '2026-02-19', 34.00, 'Sem salvar.', 1, '2026-02-17 23:53:14'),
(151, 1, 4, '2026-02-18', 50.00, 'Sem SALVAR MARIANA', 1, '2026-02-17 23:54:15'),
(152, 2, 11, '2026-02-18', 50.00, '4 Boosters', 1, '2026-02-17 23:56:08'),
(153, 1, 12, '2026-02-18', 0.00, '', 1, '2026-02-18 11:25:45'),
(154, 1, 13, '2026-02-18', 0.00, '', 1, '2026-02-18 11:25:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_itens`
--

CREATE TABLE `pedidos_itens` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT 0,
  `valor_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos_itens`
--

INSERT INTO `pedidos_itens` (`id_item`, `id_pedido`, `id_produto`, `quantidade`, `valor_unitario`) VALUES
(220, 24, 3, 3, 40.00),
(221, 24, 4, 1, 100.00),
(222, 25, 2, 2, 75.00),
(223, 25, 5, 1, 60.00),
(224, 26, 1, 2, 50.00),
(225, 26, 2, 1, 80.00),
(226, 27, 3, 3, 40.00),
(227, 27, 4, 1, 100.00),
(228, 28, 2, 2, 75.00),
(229, 28, 5, 1, 60.00),
(230, 29, 6, 1, 120.00),
(231, 29, 7, 2, 90.00),
(232, 30, 8, 2, 70.00),
(233, 30, 9, 1, 110.00),
(238, 33, 3, 3, 4.00),
(239, 33, 4, 1, 10.00),
(240, 34, 2, 2, 7.00),
(241, 34, 5, 1, 6.00),
(244, 36, 8, 2, 7.00),
(245, 36, 9, 1, 10.00),
(248, 38, 1, 2, 50.00),
(249, 38, 2, 1, 8.00),
(250, 39, 3, 3, 4.00),
(251, 39, 4, 1, 100.00),
(252, 40, 2, 2, 70.00),
(253, 40, 5, 1, 65.00),
(254, 41, 6, 1, 12.00),
(255, 41, 7, 2, 90.00),
(256, 42, 8, 2, 25.00),
(257, 42, 9, 1, 11.00),
(260, 44, 1, 2, 25.00),
(261, 44, 2, 1, 899.00),
(262, 45, 3, 3, 43.00),
(263, 45, 4, 1, 2.00),
(264, 46, 2, 2, 5.00),
(265, 46, 5, 1, 6.00),
(266, 47, 6, 1, 10.00),
(267, 47, 7, 2, 97.00),
(268, 48, 8, 2, 25.00),
(269, 48, 9, 1, 110.00),
(272, 50, 1, 2, 25.00),
(273, 50, 2, 1, 899.00),
(274, 51, 3, 3, 43.00),
(275, 51, 4, 1, 2.00),
(276, 52, 2, 2, 5.00),
(277, 52, 5, 1, 6.00),
(278, 53, 6, 1, 110.00),
(279, 53, 7, 2, 97.00),
(280, 54, 8, 2, 25.00),
(281, 54, 9, 1, 11.00),
(284, 56, 1, 2, 25.00),
(285, 56, 2, 1, 899.00),
(286, 57, 3, 3, 13.00),
(287, 57, 4, 1, 12.00),
(288, 58, 2, 2, 300.00),
(289, 58, 5, 1, 125.00),
(290, 59, 6, 1, 110.00),
(291, 59, 7, 2, 97.00),
(292, 60, 8, 2, 25.00),
(293, 60, 9, 1, 11.00),
(296, 62, 1, 2, 25.00),
(297, 62, 2, 1, 899.00),
(298, 63, 3, 3, 13.00),
(299, 63, 4, 1, 12.00),
(300, 64, 2, 2, 300.00),
(301, 64, 5, 1, 125.00),
(302, 65, 6, 1, 10.00),
(303, 65, 7, 2, 97.00),
(304, 66, 8, 2, 25.00),
(305, 66, 9, 1, 11.00),
(308, 68, 1, 2, 25.00),
(309, 68, 2, 1, 200.00),
(310, 69, 3, 3, 13.00),
(311, 69, 4, 1, 12.00),
(312, 70, 2, 2, 300.00),
(313, 70, 5, 1, 125.00),
(314, 71, 6, 1, 10.00),
(315, 71, 7, 2, 97.00),
(316, 72, 8, 2, 25.00),
(317, 72, 9, 1, 100.00),
(320, 74, 1, 2, 25.00),
(321, 74, 2, 1, 150.00),
(322, 75, 3, 3, 13.00),
(323, 75, 4, 1, 112.00),
(324, 76, 2, 2, 400.00),
(325, 76, 5, 1, 125.00),
(326, 77, 6, 1, 100.00),
(327, 77, 7, 2, 97.00),
(328, 78, 8, 2, 25.00),
(329, 78, 9, 1, 100.00),
(332, 80, 1, 2, 25.00),
(333, 80, 2, 1, 610.00),
(334, 81, 3, 3, 223.00),
(335, 81, 4, 1, 112.00),
(336, 82, 2, 2, 400.00),
(337, 82, 5, 1, 125.00),
(338, 83, 6, 1, 100.00),
(339, 83, 7, 2, 9.00),
(340, 84, 8, 2, 285.00),
(341, 84, 9, 1, 10.00),
(344, 86, 1, 2, 89.00),
(345, 86, 2, 1, 64.00),
(346, 87, 3, 3, 223.00),
(347, 87, 4, 1, 12.00),
(348, 88, 2, 2, 400.00),
(349, 88, 5, 1, 125.00),
(350, 89, 6, 1, 100.00),
(351, 89, 7, 2, 98.00),
(352, 90, 8, 2, 285.00),
(353, 90, 9, 1, 300.00),
(356, 92, 1, 2, 89.00),
(357, 92, 2, 1, 614.00),
(358, 93, 3, 3, 123.00),
(359, 93, 4, 1, 12.00),
(360, 94, 2, 2, 400.00),
(361, 94, 5, 1, 225.00),
(362, 95, 6, 1, 100.00),
(363, 95, 7, 2, 198.00),
(364, 96, 8, 2, 285.00),
(365, 96, 9, 1, 160.00),
(371, 35, 7, 1, 10.00),
(372, 23, 1, 1, 10.00),
(373, 23, 2, 1, 10.00),
(374, 20, 8, 1, 10.00),
(375, 98, 4, 1, 10.00),
(376, 99, 4, 1, 10.00),
(377, 100, 4, 1, 10.00),
(404, 109, 16, 2, 4.00),
(405, 109, 15, 1, 8.50),
(406, 109, 17, 2, 5.00),
(411, 112, 4, 1, 4.00),
(412, 112, 18, 1, 8.50),
(637, 32, 1, 2, 15.00),
(858, 108, 4, 1, 4.00),
(859, 126, 1, 1, 15.00),
(860, 126, 18, 1, 8.50),
(861, 142, 4, 1, 4.00),
(862, 142, 5, 1, 6.00),
(863, 142, 8, 1, 9.00),
(864, 142, 18, 1, 8.50),
(865, 142, 7, 1, 15.00),
(866, 142, 6, 1, 25.00),
(867, 143, 1, 1, 15.00),
(868, 118, 4, 1, 4.00),
(869, 119, 5, 1, 6.00),
(870, 119, 1, 2, 15.00),
(871, 144, 4, 2, 4.00),
(1027, 141, 5, 1, 6.00),
(1028, 141, 8, 3, 9.00),
(1029, 137, 1, 1, 15.00),
(1030, 137, 7, 2, 15.00),
(1031, 138, 5, 1, 6.00),
(1032, 138, 8, 1, 9.00),
(1033, 140, 4, 1, 4.00),
(1034, 127, 5, 1, 6.00),
(1035, 127, 18, 1, 8.50),
(1036, 127, 7, 1, 15.00),
(1037, 139, 4, 1, 4.00),
(1038, 128, 5, 1, 6.00),
(1039, 128, 1, 1, 15.00),
(1040, 128, 18, 1, 8.50),
(1041, 128, 7, 23, 15.00),
(1042, 150, 4, 2, 4.00),
(1043, 150, 5, 1, 6.00),
(1044, 150, 8, 1, 9.00),
(1048, 152, 16, 1, 4.00),
(1193, 133, 5, 1, 6.00),
(1194, 133, 1, 1, 15.00),
(1275, 135, 4, 1, 4.00),
(1276, 135, 5, 1, 6.00),
(1277, 135, 18, 2, 8.50),
(1278, 135, 6, 2, 25.00),
(1279, 148, 5, 1, 6.00),
(1280, 148, 1, 1, 15.00),
(1281, 153, 1, 1, 15.00),
(1282, 153, 7, 1, 15.00),
(1283, 154, 4, 1, 4.00),
(1284, 154, 8, 1, 9.00),
(1285, 151, 4, 1, 4.00),
(1286, 151, 1, 2, 15.00),
(1287, 151, 7, 10, 15.00),
(1288, 149, 4, 1, 4.00),
(1289, 149, 18, 1, 8.50),
(1290, 149, 7, 1, 15.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_pagamento`
--

CREATE TABLE `pedido_pagamento` (
  `id_pedido` int(11) NOT NULL,
  `id_pagamento` int(11) NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido_pagamento`
--

INSERT INTO `pedido_pagamento` (`id_pedido`, `id_pagamento`, `valor`) VALUES
(108, 1, NULL),
(108, 2, NULL),
(119, 2, NULL),
(119, 3, NULL),
(119, 5, NULL),
(126, 3, NULL),
(126, 5, NULL),
(127, 1, NULL),
(127, 5, NULL),
(128, 4, NULL),
(128, 5, NULL),
(133, 2, NULL),
(137, 1, NULL),
(137, 5, NULL),
(138, 3, NULL),
(138, 5, NULL),
(139, 1, NULL),
(139, 2, NULL),
(140, 1, NULL),
(140, 3, NULL),
(141, 1, NULL),
(141, 2, NULL),
(144, 1, NULL),
(144, 3, NULL),
(148, 3, NULL),
(148, 5, NULL),
(149, 1, NULL),
(149, 2, NULL),
(150, 1, NULL),
(151, 1, NULL),
(152, 2, NULL),
(153, 1, NULL),
(154, 2, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `id_loja` int(11) DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `emoji` varchar(10) DEFAULT NULL,
  `valor_venda` decimal(10,2) DEFAULT NULL,
  `valor_compra` decimal(10,2) DEFAULT NULL,
  `controlar_estoque` tinyint(1) DEFAULT 0,
  `estoque_atual` int(11) DEFAULT 0,
  `estoque_alerta` int(11) DEFAULT 0,
  `ordem_exibicao` int(11) DEFAULT 0,
  `id_fornecedor` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `id_loja`, `nome`, `emoji`, `valor_venda`, `valor_compra`, `controlar_estoque`, `estoque_atual`, `estoque_alerta`, `ordem_exibicao`, `id_fornecedor`, `ativo`) VALUES
(1, 1, 'Monster', '⚡', 15.00, 9.00, 1, 34, 10, 3, 1, 1),
(2, 1, 'Produto 10 Reais', '🎴', 10.00, 10.00, 1, 14, 10, 9, 1, 0),
(3, 1, 'Produto 10 Reais', '📦', 10.00, 10.00, 0, 0, 0, 8, 3, 0),
(4, 1, 'Água', '💧', 4.00, 0.85, 1, 11, 5, 1, 4, 1),
(5, 1, 'Refri', '🥤', 6.00, 4.50, 1, 8, 5, 2, 5, 1),
(6, 1, 'Torneio', '🏆', 25.00, 25.00, 0, 0, 0, 7, 1, 1),
(7, 1, 'Liguinha', '🧒', 15.00, 15.00, 0, 0, 0, 6, 4, 1),
(8, 1, 'Cerveja', '🍺', 9.00, 6.00, 1, 19, 5, 4, 2, 1),
(9, 1, 'Produto 10 Reais', '💧', 10.00, 10.00, 1, 20, 0, 10, 2, 0),
(15, 2, 'Hamburgão', '🍔', 8.50, 4.00, 0, 35, 0, 2, 1, 1),
(16, 2, 'Água', '💧', 4.00, 0.85, 1, 23, 0, 1, 1, 1),
(17, 2, 'Chocolate', '🍫', 5.00, 2.50, 0, 12, 0, 3, 3, 1),
(18, 1, 'Hamburgão', '🍔', 8.50, 4.00, 1, 6, 5, 5, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_pagamento`
--

CREATE TABLE `tipos_pagamento` (
  `id_pagamento` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `imagem` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipos_pagamento`
--

INSERT INTO `tipos_pagamento` (`id_pagamento`, `nome`, `imagem`) VALUES
(1, 'Dinheiro', NULL),
(2, 'Débito', NULL),
(3, 'Crédito', NULL),
(4, 'Pix', NULL),
(5, 'Crédito (Ligamagic)', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `torneios`
--

CREATE TABLE `torneios` (
  `id_torneio` int(11) NOT NULL,
  `id_loja` int(11) NOT NULL,
  `id_cardgame` int(11) NOT NULL,
  `nome_torneio` varchar(150) NOT NULL,
  `tipo_torneio` enum('suico_bo1','suico_bo3','elim_dupla_bo1','elim_dupla_bo3') NOT NULL,
  `tempo_rodada` int(11) DEFAULT 50,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `status` enum('em_andamento','finalizado','cancelado') DEFAULT 'em_andamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `torneios`
--

INSERT INTO `torneios` (`id_torneio`, `id_loja`, `id_cardgame`, `nome_torneio`, `tipo_torneio`, `tempo_rodada`, `data_criacao`, `status`) VALUES
(1, 1, 2, 'Torneio Pokemon', 'suico_bo3', 50, '2026-02-19 00:27:30', 'em_andamento'),
(2, 1, 2, 'Torneio Pokemon Sabado', 'suico_bo3', 50, '2026-02-19 00:40:51', 'em_andamento'),
(3, 1, 2, 'Torneio Pokemon Domingo', 'suico_bo3', 50, '2026-02-19 00:50:35', 'em_andamento'),
(4, 1, 2, 'Torneio Pokemon Segunda', 'suico_bo3', 50, '2026-02-19 00:53:34', 'em_andamento'),
(5, 1, 2, 'Torneio Pokemon terça', 'suico_bo3', 50, '2026-02-19 00:56:18', 'em_andamento'),
(6, 1, 2, 'Torneio Pokemon', 'elim_dupla_bo3', 50, '2026-02-19 01:25:46', 'em_andamento'),
(7, 1, 2, 'Torneio Pokemon Domingo', 'suico_bo3', 50, '2026-02-19 01:51:34', 'em_andamento'),
(8, 1, 2, 'Torneio Pokemon Sabado', 'suico_bo1', 50, '2026-02-19 01:56:01', 'em_andamento'),
(9, 1, 2, 'Torneio Pokemon Teste', 'elim_dupla_bo1', 50, '2026-02-19 02:03:58', 'em_andamento'),
(10, 1, 2, 'Torneio Pokemon', 'suico_bo1', 50, '2026-02-19 02:06:31', 'em_andamento'),
(11, 1, 8, 'One Piece Semanal', 'suico_bo3', 50, '2026-02-19 03:54:50', 'em_andamento'),
(12, 2, 1, 'Magic', 'suico_bo3', 50, '2026-02-19 04:03:00', 'em_andamento'),
(13, 2, 1, 'Commander', 'suico_bo3', 50, '2026-02-19 04:22:27', 'em_andamento'),
(14, 2, 1, 'Torneio Pokemon Sabado', 'suico_bo3', 50, '2026-02-19 05:11:45', 'em_andamento'),
(15, 2, 1, 'Torneio Pokemon', 'suico_bo3', 50, '2026-02-19 05:22:20', 'em_andamento'),
(16, 2, 1, 'Torneio Pokemon Sabado', 'suico_bo3', 50, '2026-02-19 05:36:04', 'em_andamento'),
(17, 2, 1, 'Torneio Pokemon', 'elim_dupla_bo1', 50, '2026-02-19 06:34:49', 'em_andamento'),
(18, 2, 1, 'Commander Semanal', 'suico_bo3', 50, '2026-02-19 06:35:18', 'em_andamento'),
(19, 2, 1, 'Torneio Pokemon', 'suico_bo3', 50, '2026-02-19 06:49:06', 'em_andamento'),
(20, 2, 8, 'Teste de MD1', 'suico_bo1', 50, '2026-02-19 07:10:54', 'em_andamento'),
(21, 2, 8, 'Teste de MD1', 'suico_bo1', 50, '2026-02-19 07:12:18', 'em_andamento'),
(22, 2, 1, 'Outro MD1 teste', 'suico_bo1', 50, '2026-02-19 07:23:11', 'em_andamento'),
(23, 2, 1, 'MD1 com BY', 'suico_bo1', 50, '2026-02-19 07:25:50', 'em_andamento'),
(24, 1, 8, 'One Piece Semanal', 'suico_bo3', 50, '2026-02-19 08:57:12', 'em_andamento'),
(25, 1, 2, 'Torneio Pokemon', 'suico_bo3', 50, '2026-02-19 08:58:33', 'em_andamento'),
(26, 1, 2, 'Torneio Pokemon', 'suico_bo3', 50, '2026-02-19 09:01:48', 'em_andamento'),
(27, 1, 8, 'Torneio Pokemon', 'elim_dupla_bo1', 50, '2026-02-19 09:24:02', 'em_andamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `torneio_participantes`
--

CREATE TABLE `torneio_participantes` (
  `id_torneio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `torneio_participantes`
--

INSERT INTO `torneio_participantes` (`id_torneio`, `id_cliente`) VALUES
(1, 1),
(1, 2),
(1, 4),
(1, 5),
(1, 10),
(1, 12),
(1, 13),
(2, 1),
(2, 2),
(2, 4),
(2, 5),
(2, 10),
(2, 12),
(2, 13),
(2, 14),
(3, 1),
(3, 4),
(3, 5),
(3, 10),
(3, 12),
(3, 13),
(3, 14),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(4, 10),
(4, 12),
(4, 13),
(4, 14),
(5, 1),
(5, 2),
(5, 4),
(5, 5),
(5, 10),
(5, 12),
(5, 13),
(5, 14),
(6, 1),
(6, 2),
(6, 4),
(6, 5),
(6, 10),
(6, 12),
(6, 13),
(6, 14),
(7, 1),
(7, 2),
(7, 4),
(7, 5),
(7, 10),
(7, 12),
(7, 13),
(7, 14),
(8, 1),
(8, 2),
(8, 4),
(8, 5),
(8, 10),
(8, 12),
(8, 13),
(8, 14),
(9, 1),
(9, 2),
(9, 4),
(9, 5),
(9, 10),
(9, 12),
(9, 13),
(9, 14),
(10, 1),
(10, 2),
(10, 4),
(10, 5),
(10, 10),
(10, 12),
(10, 13),
(10, 14),
(11, 2),
(11, 4),
(11, 10),
(11, 12),
(12, 14),
(12, 15),
(12, 16),
(12, 17),
(12, 18),
(13, 14),
(13, 15),
(13, 16),
(13, 17),
(13, 18),
(15, 14),
(15, 15),
(15, 16),
(15, 17),
(15, 18),
(16, 14),
(16, 15),
(16, 16),
(16, 17),
(16, 18),
(17, 14),
(17, 15),
(17, 16),
(17, 17),
(17, 18),
(18, 14),
(18, 15),
(18, 16),
(18, 17),
(18, 18),
(19, 14),
(19, 15),
(19, 16),
(19, 17),
(19, 18),
(20, 15),
(20, 16),
(20, 17),
(20, 18),
(21, 15),
(21, 16),
(21, 17),
(21, 18),
(22, 14),
(22, 16),
(22, 17),
(22, 18),
(23, 14),
(23, 15),
(23, 16),
(23, 17),
(23, 18),
(24, 2),
(24, 4),
(24, 10),
(24, 12),
(25, 1),
(25, 2),
(25, 4),
(25, 5),
(25, 10),
(25, 12),
(25, 13),
(26, 1),
(26, 2),
(26, 4),
(26, 5),
(26, 10),
(26, 12),
(26, 13),
(26, 14),
(26, 19),
(26, 20),
(26, 21),
(27, 2),
(27, 4),
(27, 10),
(27, 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `torneio_partidas`
--

CREATE TABLE `torneio_partidas` (
  `id_partida` int(11) NOT NULL,
  `id_rodada` int(11) NOT NULL,
  `id_jogador1` int(11) NOT NULL,
  `id_jogador2` int(11) DEFAULT NULL,
  `resultado` enum('jogador1_vitoria','jogador2_vitoria','empate','jogador1_2x1','jogador2_2x1','jogador1_2x0','jogador2_2x0') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `torneio_partidas`
--

INSERT INTO `torneio_partidas` (`id_partida`, `id_rodada`, `id_jogador1`, `id_jogador2`, `resultado`) VALUES
(1, 1, 1, 2, 'jogador1_2x0'),
(2, 1, 4, 5, 'jogador1_2x1'),
(3, 1, 10, 12, 'jogador2_2x1'),
(4, 1, 13, 14, 'jogador1_2x0'),
(5, 2, 1, 4, 'jogador1_2x0'),
(6, 2, 12, 13, 'jogador2_2x0'),
(7, 2, 2, 5, 'jogador2_2x1'),
(8, 2, 10, 14, 'jogador2_2x0'),
(9, 3, 1, 4, 'jogador2_2x0'),
(10, 3, 12, 13, 'jogador1_2x1'),
(11, 3, 2, 5, 'jogador1_2x1'),
(12, 3, 10, 14, 'jogador1_2x1'),
(13, 4, 13, 1, 'jogador1_2x0'),
(14, 4, 4, 12, 'jogador2_2x0'),
(15, 4, 14, 5, 'jogador2_2x1'),
(16, 4, 2, 10, 'jogador2_2x1'),
(17, 5, 1, 4, NULL),
(18, 5, 12, 13, NULL),
(19, 5, 2, 5, NULL),
(20, 5, 10, 14, NULL),
(21, 6, 12, 13, NULL),
(22, 6, 4, 1, NULL),
(23, 6, 10, 5, NULL),
(24, 6, 14, 2, NULL),
(25, 7, 12, 13, NULL),
(26, 7, 4, 1, NULL),
(27, 7, 10, 5, NULL),
(28, 7, 14, 2, NULL),
(29, 8, 12, 13, NULL),
(30, 8, 4, 1, NULL),
(31, 8, 10, 5, NULL),
(32, 8, 14, 2, NULL),
(33, 9, 12, 13, 'jogador2_2x1'),
(34, 9, 4, 1, 'jogador1_2x0'),
(35, 9, 10, 5, 'jogador2_2x0'),
(36, 9, 14, 2, 'jogador1_2x0'),
(37, 10, 13, 12, NULL),
(38, 10, 4, 5, NULL),
(39, 10, 1, 10, NULL),
(40, 10, 14, 2, NULL),
(41, 11, 13, 12, NULL),
(42, 11, 4, 5, NULL),
(43, 11, 1, 10, NULL),
(44, 11, 14, 2, NULL),
(45, 12, 1, 2, 'jogador1_2x0'),
(46, 12, 4, 5, 'jogador1_2x1'),
(47, 12, 10, 12, 'jogador2_2x1'),
(48, 12, 13, 14, 'jogador1_2x0'),
(49, 13, 1, 4, 'jogador2_2x0'),
(50, 13, 12, 13, 'jogador1_2x1'),
(51, 13, 2, 5, 'jogador1_2x1'),
(52, 13, 10, 14, 'jogador1_2x0'),
(53, 14, 4, 12, 'jogador2_2x1'),
(54, 14, 1, 2, 'jogador2_2x0'),
(55, 14, 10, 13, 'jogador1_2x1'),
(56, 14, 5, 14, 'jogador1_2x0'),
(57, 15, 12, 2, 'jogador1_2x0'),
(58, 15, 4, 10, 'jogador2_2x1'),
(59, 15, 1, 5, 'jogador2_2x0'),
(60, 15, 13, 14, 'jogador2_2x0'),
(61, 16, 12, 2, NULL),
(62, 16, 4, 10, NULL),
(63, 16, 1, 5, NULL),
(64, 16, 13, 14, NULL),
(65, 17, 12, 2, NULL),
(66, 17, 4, 10, NULL),
(67, 17, 1, 5, NULL),
(68, 17, 13, 14, NULL),
(69, 18, 12, 2, NULL),
(70, 18, 4, 10, NULL),
(71, 18, 1, 5, NULL),
(72, 18, 13, 14, NULL),
(73, 19, 12, 2, NULL),
(74, 19, 4, 10, NULL),
(75, 19, 1, 5, NULL),
(76, 19, 13, 14, NULL),
(77, 20, 12, 2, NULL),
(78, 20, 4, 10, NULL),
(79, 20, 1, 5, NULL),
(80, 20, 13, 14, NULL),
(81, 21, 12, 2, NULL),
(82, 21, 4, 10, NULL),
(83, 21, 1, 5, NULL),
(84, 21, 13, 14, NULL),
(85, 22, 12, 2, NULL),
(86, 22, 4, 10, NULL),
(87, 22, 1, 5, NULL),
(88, 22, 13, 14, NULL),
(89, 23, 12, 2, NULL),
(90, 23, 4, 10, NULL),
(91, 23, 1, 5, NULL),
(92, 23, 13, 14, NULL),
(93, 24, 12, 2, NULL),
(94, 24, 4, 10, NULL),
(95, 24, 1, 5, NULL),
(96, 24, 13, 14, NULL),
(97, 25, 12, 2, NULL),
(98, 25, 4, 10, NULL),
(99, 25, 1, 5, NULL),
(100, 25, 13, 14, NULL),
(101, 27, 12, 10, NULL),
(102, 27, 2, 4, NULL),
(103, 27, 5, 1, NULL),
(104, 27, 13, 14, NULL),
(105, 28, 1, 2, 'jogador1_2x0'),
(106, 28, 4, 5, 'jogador2_2x0'),
(107, 28, 10, 12, 'jogador1_2x1'),
(108, 28, 13, 14, 'jogador2_2x1'),
(109, 29, 1, 5, 'jogador2_2x1'),
(110, 29, 10, 14, 'jogador2_2x0'),
(111, 29, 2, 4, 'jogador2_2x0'),
(112, 29, 12, 13, 'jogador2_2x1'),
(113, 30, 5, 14, 'jogador2_2x0'),
(114, 30, 1, 4, 'jogador1_2x0'),
(115, 30, 10, 13, 'jogador1_2x1'),
(116, 30, 2, 12, 'jogador1_2x1'),
(117, 31, 1, 2, NULL),
(118, 31, 4, 5, NULL),
(119, 31, 10, 12, NULL),
(120, 31, 13, 14, NULL),
(121, 32, 1, 2, 'jogador1_vitoria'),
(122, 32, 4, 5, 'jogador1_vitoria'),
(123, 32, 10, 12, 'jogador2_vitoria'),
(124, 32, 13, 14, 'jogador1_vitoria'),
(125, 33, 1, 4, 'jogador1_vitoria'),
(126, 33, 12, 13, 'jogador1_vitoria'),
(127, 33, 2, 5, 'jogador2_vitoria'),
(128, 33, 10, 14, 'jogador2_vitoria'),
(129, 34, 1, 12, 'jogador1_vitoria'),
(130, 34, 4, 5, 'jogador2_vitoria'),
(131, 34, 13, 14, 'empate'),
(132, 34, 2, 10, 'jogador1_vitoria'),
(133, 35, 1, 2, 'jogador1_vitoria'),
(134, 35, 4, 5, 'jogador1_vitoria'),
(135, 35, 10, 12, 'jogador2_vitoria'),
(136, 35, 13, 14, 'jogador2_vitoria'),
(137, 36, 1, 4, 'jogador1_vitoria'),
(138, 36, 12, 14, 'jogador1_vitoria'),
(139, 36, 2, 5, 'jogador2_vitoria'),
(140, 36, 10, 13, 'jogador2_vitoria'),
(141, 37, 1, 12, 'jogador2_vitoria'),
(142, 37, 4, 14, 'jogador1_vitoria'),
(143, 37, 5, 13, 'jogador2_vitoria'),
(144, 37, 2, 10, 'jogador2_vitoria'),
(145, 38, 2, 4, 'jogador1_2x1'),
(146, 38, 10, 12, 'jogador1_2x0'),
(147, 39, 2, 10, 'jogador2_2x0'),
(148, 39, 4, 12, 'jogador1_2x1'),
(149, 40, 14, 15, 'jogador2_2x0'),
(150, 40, 16, 17, 'jogador1_2x1'),
(151, 41, 15, 16, 'jogador2_2x0'),
(152, 41, 14, 17, 'jogador1_2x1'),
(153, 42, 16, 15, 'jogador1_2x1'),
(154, 42, 14, 17, 'jogador2_2x1'),
(159, 1, 15, NULL, 'jogador1_vitoria'),
(160, 1, 17, 18, NULL),
(161, 1, 16, 14, NULL),
(162, 1, 15, NULL, 'jogador1_vitoria'),
(163, 1, 17, 18, NULL),
(164, 1, 16, 14, NULL),
(165, 1, 15, NULL, 'jogador1_vitoria'),
(166, 1, 17, 18, NULL),
(167, 1, 16, 14, NULL),
(168, 1, 15, NULL, 'jogador1_vitoria'),
(169, 1, 17, 18, NULL),
(170, 1, 16, 14, NULL),
(171, 1, 15, NULL, 'jogador1_vitoria'),
(172, 1, 17, 18, NULL),
(173, 1, 16, 14, NULL),
(174, 1, 15, NULL, 'jogador1_vitoria'),
(175, 1, 17, 18, NULL),
(176, 1, 16, 14, NULL),
(177, 1, 15, NULL, 'jogador1_vitoria'),
(178, 1, 17, 18, NULL),
(179, 1, 16, 14, NULL),
(180, 1, 15, NULL, 'jogador1_vitoria'),
(181, 1, 17, 18, NULL),
(182, 1, 16, 14, NULL),
(183, 1, 15, NULL, 'jogador1_vitoria'),
(184, 1, 17, 18, NULL),
(185, 1, 16, 14, NULL),
(186, 1, 15, NULL, 'jogador1_vitoria'),
(187, 1, 17, 18, NULL),
(188, 1, 16, 14, NULL),
(189, 1, 15, NULL, 'jogador1_vitoria'),
(190, 1, 17, 18, NULL),
(191, 1, 16, 14, NULL),
(192, 1, 15, NULL, 'jogador1_vitoria'),
(193, 1, 17, 18, NULL),
(194, 1, 16, 14, NULL),
(195, 1, 15, NULL, 'jogador1_vitoria'),
(196, 1, 17, 18, NULL),
(197, 1, 16, 14, NULL),
(198, 1, 15, NULL, 'jogador1_vitoria'),
(199, 1, 17, 18, NULL),
(200, 1, 16, 14, NULL),
(201, 1, 15, NULL, 'jogador1_vitoria'),
(202, 1, 17, 18, NULL),
(203, 1, 16, 14, NULL),
(204, 1, 15, NULL, 'jogador1_vitoria'),
(205, 1, 17, 18, NULL),
(206, 1, 16, 14, NULL),
(207, 1, 15, NULL, 'jogador1_vitoria'),
(208, 1, 17, 18, NULL),
(209, 1, 16, 14, NULL),
(210, 1, 15, NULL, 'jogador1_vitoria'),
(211, 1, 17, 18, NULL),
(212, 1, 16, 14, NULL),
(213, 1, 15, NULL, 'jogador1_vitoria'),
(214, 1, 17, 18, NULL),
(215, 1, 16, 14, NULL),
(216, 43, 15, NULL, 'jogador1_2x1'),
(217, 43, 17, 18, 'jogador2_2x0'),
(218, 43, 16, 14, 'jogador2_2x0'),
(219, 44, 15, NULL, 'jogador1_2x0'),
(220, 44, 17, 18, 'jogador1_2x1'),
(221, 44, 16, 14, 'jogador2_2x1'),
(222, 45, 15, NULL, 'jogador1_2x1'),
(223, 45, 17, 18, 'jogador1_2x1'),
(224, 45, 16, 14, 'jogador2_2x1'),
(225, 46, 15, NULL, 'jogador1_vitoria'),
(226, 46, 17, 18, 'jogador1_2x0'),
(227, 46, 16, 14, 'jogador1_2x1'),
(228, 47, 15, NULL, 'jogador1_vitoria'),
(229, 47, 17, 18, 'jogador2_2x0'),
(230, 47, 16, 14, 'empate'),
(231, 48, 15, NULL, 'jogador1_vitoria'),
(232, 48, 16, 14, 'jogador2_2x0'),
(233, 48, 17, 18, 'jogador2_2x0'),
(234, 49, 18, NULL, 'jogador1_vitoria'),
(235, 49, 15, 16, 'jogador2_2x0'),
(236, 49, 17, 14, 'jogador2_2x1'),
(237, 50, 17, NULL, 'jogador1_vitoria'),
(238, 50, 18, 16, 'jogador1_2x1'),
(239, 50, 14, 15, 'jogador1_2x1'),
(240, 51, 16, NULL, 'jogador1_vitoria'),
(241, 51, 17, 18, 'jogador2_2x1'),
(242, 51, 14, 15, 'jogador1_2x0'),
(243, 53, 17, 16, 'jogador1_vitoria'),
(244, 53, 18, 15, 'jogador2_vitoria'),
(245, 54, 17, 18, 'jogador1_vitoria'),
(246, 54, 16, 15, 'jogador2_vitoria'),
(247, 55, 17, 18, 'jogador2_vitoria'),
(248, 55, 16, 14, 'jogador1_vitoria'),
(249, 56, 18, 16, 'jogador1_vitoria'),
(250, 56, 17, 14, 'jogador1_vitoria'),
(251, 57, 17, NULL, 'jogador1_vitoria'),
(252, 57, 15, 16, 'jogador1_vitoria'),
(253, 57, 14, 18, 'empate'),
(254, 58, 15, NULL, 'jogador1_vitoria'),
(255, 58, 17, 18, 'jogador1_vitoria'),
(256, 58, 14, 16, 'jogador2_vitoria'),
(257, 59, 16, NULL, 'jogador1_vitoria'),
(258, 59, 15, 17, 'jogador1_vitoria'),
(259, 59, 18, 14, 'jogador2_vitoria'),
(260, 60, 15, NULL, 'jogador1_vitoria'),
(261, 60, 17, 16, 'jogador1_2x0'),
(262, 60, 18, 14, 'jogador2_2x1'),
(263, 61, 17, NULL, 'jogador1_vitoria'),
(264, 61, 14, 15, 'jogador1_2x1'),
(265, 61, 18, 16, 'jogador2_2x1'),
(266, 62, 14, NULL, 'jogador1_vitoria'),
(267, 62, 17, 15, 'jogador2_2x0'),
(268, 62, 16, 18, 'jogador2_2x0'),
(269, 63, 2, 4, 'jogador1_2x0'),
(270, 63, 10, 12, 'jogador1_2x1'),
(271, 64, 2, 10, 'jogador2_2x0'),
(272, 64, 12, 4, 'empate'),
(273, 65, 13, NULL, 'jogador1_vitoria'),
(274, 65, 10, 1, 'jogador1_2x0'),
(275, 65, 2, 5, 'jogador1_2x1'),
(276, 65, 12, 4, 'jogador1_2x0'),
(277, 66, 10, NULL, 'jogador1_vitoria'),
(278, 66, 12, 2, 'jogador2_2x0'),
(279, 66, 13, 5, 'jogador2_2x0'),
(280, 66, 1, 4, 'jogador2_2x1'),
(281, 67, 2, NULL, 'jogador1_vitoria'),
(282, 67, 10, 5, 'jogador1_2x0'),
(283, 67, 12, 13, 'jogador2_2x0'),
(284, 67, 4, 1, 'jogador2_2x1'),
(285, 68, 13, NULL, 'jogador1_vitoria'),
(286, 68, 10, 5, 'jogador1_2x0'),
(287, 68, 19, 14, 'jogador2_2x0'),
(288, 68, 21, 4, 'jogador1_2x1'),
(289, 68, 12, 1, 'jogador2_2x0'),
(290, 68, 2, 20, 'jogador1_2x1'),
(291, 69, 10, NULL, 'jogador1_vitoria'),
(292, 69, 1, 14, 'jogador2_2x0'),
(293, 69, 2, 21, 'jogador1_2x1'),
(294, 69, 13, 4, 'jogador2_2x1'),
(295, 69, 20, 12, 'jogador1_2x0'),
(296, 69, 5, 19, 'empate'),
(297, 70, 14, NULL, 'jogador1_vitoria'),
(298, 70, 10, 2, 'jogador1_2x0'),
(299, 70, 20, 21, 'jogador1_2x1'),
(300, 70, 1, 4, 'jogador1_2x1'),
(301, 70, 13, 19, 'jogador2_2x0'),
(302, 70, 5, 12, 'jogador2_2x1'),
(303, 71, 20, NULL, 'jogador1_vitoria'),
(304, 71, 14, 10, 'jogador1_2x0'),
(305, 71, 1, 2, 'jogador1_2x1'),
(306, 71, 21, 19, 'jogador2_2x0'),
(307, 71, 4, 13, 'empate'),
(308, 71, 5, 12, 'empate');

-- --------------------------------------------------------

--
-- Estrutura para tabela `torneio_rodadas`
--

CREATE TABLE `torneio_rodadas` (
  `id_rodada` int(11) NOT NULL,
  `id_torneio` int(11) NOT NULL,
  `numero_rodada` int(11) NOT NULL,
  `tempo_restante` int(11) DEFAULT NULL,
  `status` enum('nao_iniciada','em_andamento','finalizada') DEFAULT 'nao_iniciada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `torneio_rodadas`
--

INSERT INTO `torneio_rodadas` (`id_rodada`, `id_torneio`, `numero_rodada`, `tempo_restante`, `status`) VALUES
(1, 5, 1, NULL, 'finalizada'),
(2, 5, 2, NULL, 'finalizada'),
(3, 5, 3, NULL, 'finalizada'),
(4, 5, 4, NULL, 'finalizada'),
(5, 5, 5, NULL, 'nao_iniciada'),
(6, 5, 6, NULL, 'nao_iniciada'),
(7, 5, 7, NULL, 'nao_iniciada'),
(8, 5, 8, NULL, 'nao_iniciada'),
(9, 5, 9, NULL, 'finalizada'),
(10, 5, 10, NULL, 'nao_iniciada'),
(11, 5, 11, NULL, 'nao_iniciada'),
(12, 6, 1, NULL, 'finalizada'),
(13, 6, 2, NULL, 'finalizada'),
(14, 6, 3, NULL, 'finalizada'),
(15, 6, 4, NULL, 'finalizada'),
(16, 6, 5, NULL, 'nao_iniciada'),
(17, 6, 6, NULL, 'nao_iniciada'),
(18, 6, 7, NULL, 'nao_iniciada'),
(19, 6, 8, NULL, 'nao_iniciada'),
(20, 6, 9, NULL, 'nao_iniciada'),
(21, 6, 10, NULL, 'nao_iniciada'),
(22, 6, 11, NULL, 'nao_iniciada'),
(23, 6, 12, NULL, 'nao_iniciada'),
(24, 6, 13, NULL, 'nao_iniciada'),
(25, 6, 14, NULL, 'nao_iniciada'),
(26, 1, 1, NULL, 'nao_iniciada'),
(27, 6, 15, NULL, 'nao_iniciada'),
(28, 7, 1, NULL, 'finalizada'),
(29, 7, 2, NULL, 'finalizada'),
(30, 7, 3, NULL, 'finalizada'),
(31, 8, 1, NULL, 'nao_iniciada'),
(32, 9, 1, NULL, 'finalizada'),
(33, 9, 2, NULL, 'finalizada'),
(34, 9, 3, NULL, 'finalizada'),
(35, 10, 1, NULL, 'finalizada'),
(36, 10, 2, NULL, 'finalizada'),
(37, 10, 3, NULL, 'finalizada'),
(38, 11, 1, NULL, 'finalizada'),
(39, 11, 2, NULL, 'finalizada'),
(40, 12, 1, NULL, 'finalizada'),
(41, 12, 2, NULL, 'finalizada'),
(42, 12, 3, NULL, 'finalizada'),
(43, 16, 1, NULL, 'finalizada'),
(44, 16, 2, NULL, 'finalizada'),
(45, 16, 3, NULL, 'finalizada'),
(46, 18, 1, NULL, 'finalizada'),
(47, 18, 2, NULL, 'finalizada'),
(48, 18, 3, NULL, 'finalizada'),
(49, 19, 1, NULL, 'finalizada'),
(50, 19, 2, NULL, 'finalizada'),
(51, 19, 3, NULL, 'finalizada'),
(52, 20, 1, NULL, 'em_andamento'),
(53, 21, 1, NULL, 'finalizada'),
(54, 21, 2, NULL, 'finalizada'),
(55, 22, 1, NULL, 'finalizada'),
(56, 22, 2, NULL, 'finalizada'),
(57, 23, 1, NULL, 'finalizada'),
(58, 23, 2, NULL, 'finalizada'),
(59, 23, 3, NULL, 'finalizada'),
(60, 13, 1, NULL, 'finalizada'),
(61, 13, 2, NULL, 'finalizada'),
(62, 13, 3, NULL, 'finalizada'),
(63, 24, 1, NULL, 'finalizada'),
(64, 24, 2, NULL, 'finalizada'),
(65, 25, 1, NULL, 'finalizada'),
(66, 25, 2, NULL, 'finalizada'),
(67, 25, 3, NULL, 'finalizada'),
(68, 26, 1, NULL, 'finalizada'),
(69, 26, 2, NULL, 'finalizada'),
(70, 26, 3, NULL, 'finalizada'),
(71, 26, 4, NULL, 'finalizada');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_loja`
--

CREATE TABLE `usuarios_loja` (
  `id_usuario` int(11) NOT NULL,
  `id_loja` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('atendente','gerente') NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios_loja`
--

INSERT INTO `usuarios_loja` (`id_usuario`, `id_loja`, `nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
(6, 1, 'Carlos Atendente', 'carlos@lojaalpha.com', '$2y$10$Tob7KC76Ni7H5lojJaCIPeYaLAgbh6QYG9B8upkkUmrW9Ia7.rv0y', 'atendente', 1),
(7, 1, 'Ana Gerente', 'ana@lojaalpha.com', '$2y$10$Tob7KC76Ni7H5lojJaCIPeYaLAgbh6QYG9B8upkkUmrW9Ia7.rv0y', 'gerente', 1),
(8, 2, 'Carlos Atendente', 'carlos@lojabeta.com', '$2y$10$Tob7KC76Ni7H5lojJaCIPeYaLAgbh6QYG9B8upkkUmrW9Ia7.rv0y', 'atendente', 1),
(9, 2, 'Ana Gerente', 'ana@lojabeta.com', '$2y$10$Tob7KC76Ni7H5lojJaCIPeYaLAgbh6QYG9B8upkkUmrW9Ia7.rv0y', 'gerente', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_lojas`
--
ALTER TABLE `admin_lojas`
  ADD PRIMARY KEY (`id_admin`);

--
-- Índices de tabela `cardgames`
--
ALTER TABLE `cardgames`
  ADD PRIMARY KEY (`id_cardgame`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `telefone` (`telefone`);

--
-- Índices de tabela `clientes_cardgames`
--
ALTER TABLE `clientes_cardgames`
  ADD PRIMARY KEY (`id_cliente`,`id_cardgame`),
  ADD KEY `id_cardgame` (`id_cardgame`);

--
-- Índices de tabela `clientes_lojas`
--
ALTER TABLE `clientes_lojas`
  ADD PRIMARY KEY (`id_cliente`,`id_loja`),
  ADD KEY `id_loja` (`id_loja`);

--
-- Índices de tabela `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_loja` (`id_loja`);

--
-- Índices de tabela `estoque_movimentacoes`
--
ALTER TABLE `estoque_movimentacoes`
  ADD PRIMARY KEY (`id_mov`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id_fornecedor`);

--
-- Índices de tabela `logs_pedidos`
--
ALTER TABLE `logs_pedidos`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `lojas`
--
ALTER TABLE `lojas`
  ADD PRIMARY KEY (`id_loja`);

--
-- Índices de tabela `notas_fiscais`
--
ALTER TABLE `notas_fiscais`
  ADD PRIMARY KEY (`id_nf`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_loja` (`id_loja`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `pedido_pagamento`
--
ALTER TABLE `pedido_pagamento`
  ADD PRIMARY KEY (`id_pedido`,`id_pagamento`),
  ADD KEY `id_pagamento` (`id_pagamento`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_loja` (`id_loja`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  ADD PRIMARY KEY (`id_pagamento`);

--
-- Índices de tabela `torneios`
--
ALTER TABLE `torneios`
  ADD PRIMARY KEY (`id_torneio`),
  ADD KEY `id_loja` (`id_loja`),
  ADD KEY `id_cardgame` (`id_cardgame`);

--
-- Índices de tabela `torneio_participantes`
--
ALTER TABLE `torneio_participantes`
  ADD PRIMARY KEY (`id_torneio`,`id_cliente`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `torneio_partidas`
--
ALTER TABLE `torneio_partidas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_rodada` (`id_rodada`),
  ADD KEY `id_jogador1` (`id_jogador1`),
  ADD KEY `id_jogador2` (`id_jogador2`);

--
-- Índices de tabela `torneio_rodadas`
--
ALTER TABLE `torneio_rodadas`
  ADD PRIMARY KEY (`id_rodada`),
  ADD KEY `id_torneio` (`id_torneio`);

--
-- Índices de tabela `usuarios_loja`
--
ALTER TABLE `usuarios_loja`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_loja` (`id_loja`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_lojas`
--
ALTER TABLE `admin_lojas`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cardgames`
--
ALTER TABLE `cardgames`
  MODIFY `id_cardgame` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estoque_movimentacoes`
--
ALTER TABLE `estoque_movimentacoes`
  MODIFY `id_mov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `logs_pedidos`
--
ALTER TABLE `logs_pedidos`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `lojas`
--
ALTER TABLE `lojas`
  MODIFY `id_loja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `notas_fiscais`
--
ALTER TABLE `notas_fiscais`
  MODIFY `id_nf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT de tabela `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1291;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `tipos_pagamento`
--
ALTER TABLE `tipos_pagamento`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `torneios`
--
ALTER TABLE `torneios`
  MODIFY `id_torneio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `torneio_partidas`
--
ALTER TABLE `torneio_partidas`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT de tabela `torneio_rodadas`
--
ALTER TABLE `torneio_rodadas`
  MODIFY `id_rodada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de tabela `usuarios_loja`
--
ALTER TABLE `usuarios_loja`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `clientes_cardgames`
--
ALTER TABLE `clientes_cardgames`
  ADD CONSTRAINT `clientes_cardgames_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `clientes_cardgames_ibfk_2` FOREIGN KEY (`id_cardgame`) REFERENCES `cardgames` (`id_cardgame`);

--
-- Restrições para tabelas `clientes_lojas`
--
ALTER TABLE `clientes_lojas`
  ADD CONSTRAINT `clientes_lojas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `clientes_lojas_ibfk_2` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`);

--
-- Restrições para tabelas `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`);

--
-- Restrições para tabelas `estoque_movimentacoes`
--
ALTER TABLE `estoque_movimentacoes`
  ADD CONSTRAINT `estoque_movimentacoes_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Restrições para tabelas `logs_pedidos`
--
ALTER TABLE `logs_pedidos`
  ADD CONSTRAINT `logs_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Restrições para tabelas `notas_fiscais`
--
ALTER TABLE `notas_fiscais`
  ADD CONSTRAINT `notas_fiscais_ibfk_1` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id_fornecedor`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Restrições para tabelas `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  ADD CONSTRAINT `pedidos_itens_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `pedidos_itens_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Restrições para tabelas `pedido_pagamento`
--
ALTER TABLE `pedido_pagamento`
  ADD CONSTRAINT `pedido_pagamento_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `pedido_pagamento_ibfk_2` FOREIGN KEY (`id_pagamento`) REFERENCES `tipos_pagamento` (`id_pagamento`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`),
  ADD CONSTRAINT `produtos_ibfk_2` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id_fornecedor`);

--
-- Restrições para tabelas `torneios`
--
ALTER TABLE `torneios`
  ADD CONSTRAINT `torneios_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`),
  ADD CONSTRAINT `torneios_ibfk_2` FOREIGN KEY (`id_cardgame`) REFERENCES `cardgames` (`id_cardgame`);

--
-- Restrições para tabelas `torneio_participantes`
--
ALTER TABLE `torneio_participantes`
  ADD CONSTRAINT `torneio_participantes_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `torneios` (`id_torneio`),
  ADD CONSTRAINT `torneio_participantes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Restrições para tabelas `torneio_partidas`
--
ALTER TABLE `torneio_partidas`
  ADD CONSTRAINT `torneio_partidas_ibfk_1` FOREIGN KEY (`id_rodada`) REFERENCES `torneio_rodadas` (`id_rodada`),
  ADD CONSTRAINT `torneio_partidas_ibfk_2` FOREIGN KEY (`id_jogador1`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `torneio_partidas_ibfk_3` FOREIGN KEY (`id_jogador2`) REFERENCES `clientes` (`id_cliente`);

--
-- Restrições para tabelas `torneio_rodadas`
--
ALTER TABLE `torneio_rodadas`
  ADD CONSTRAINT `torneio_rodadas_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `torneios` (`id_torneio`);

--
-- Restrições para tabelas `usuarios_loja`
--
ALTER TABLE `usuarios_loja`
  ADD CONSTRAINT `usuarios_loja_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
