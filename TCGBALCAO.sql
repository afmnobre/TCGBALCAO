-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/02/2026 às 05:00
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
(3, 'Yu-Gi-Oh!', 'YuGOh.webp', NULL),
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
(10, 'Adilson Ferreira Martins', '119628511513', 'lnfm1987@gmail.com', NULL, '2026-02-13 23:11:53'),
(11, '0 - Balcão', '111111111111', 'teste@teste.com.br', NULL, '2026-02-15 20:11:42');

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
(1, 4),
(2, 2),
(4, 3),
(5, 2),
(9, 1),
(10, 1),
(10, 3),
(10, 5),
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
(11, 11);

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
(11, 2, 'ativo', '2026-02-17 00:23:32');

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
(1, 'Player\'s Stop Tcg', '11111111000101', 'Rua A, 100', '#FF0000', 'logo.jpg', 'favicon.png', '2026-02-13 02:11:01', 'C001'),
(2, 'Neowalkers Geek Store', '22222222000102', 'Rua B, 200', '#540a15', 'logo.jpg', 'logo.ico', '2026-02-16 21:27:16', 'C002');

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
(106, 2, 11, '2026-02-17', 10.00, '', 1, '2026-02-17 00:26:08');

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
(236, 32, 1, 2, 5.00),
(237, 32, 2, 1, 8.00),
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
(391, 106, 15, 1, 8.50);

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
(1, 1, 'Produto 10 Reais', '🃏', 10.00, 10.00, 1, 12, 10, 3, 1, 1),
(2, 1, 'Produto 10 Reais', '🎴', 10.00, 10.00, 1, 13, 10, 9, 1, 1),
(3, 1, 'Produto 10 Reais', '📦', 10.00, 10.00, 0, 0, 0, 8, 3, 1),
(4, 1, 'Produto 10 Reais', '🧩', 10.00, 10.00, 1, 7, 5, 1, 4, 1),
(5, 1, 'Produto 10 Reais', '📥', 10.00, 10.00, 1, 9, 5, 2, 5, 1),
(6, 1, 'Produto 10 Reais', '🏆', 10.00, 10.00, 0, 10, 10, 6, 1, 1),
(7, 1, 'Produto 10 Reais', '🍺', 10.00, 10.00, 1, 30, 10, 5, 4, 1),
(8, 1, 'Produto 10 Reais', '🍬', 10.00, 10.00, 1, 99, 50, 4, 2, 1),
(9, 1, 'Produto 10 Reais', '💧', 10.00, 10.00, 1, 20, 0, 7, 2, 1),
(15, 2, 'Hamburgão', '🍔', 8.50, 4.00, 0, 35, 0, 0, 1, 1);

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
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_loja` (`id_loja`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

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
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de tabela `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=396;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`),
  ADD CONSTRAINT `produtos_ibfk_2` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id_fornecedor`);

--
-- Restrições para tabelas `usuarios_loja`
--
ALTER TABLE `usuarios_loja`
  ADD CONSTRAINT `usuarios_loja_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `lojas` (`id_loja`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
