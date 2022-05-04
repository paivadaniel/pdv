-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Maio-2022 às 17:11
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pdv`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa`
--

CREATE TABLE `caixa` (
  `id` int(11) NOT NULL,
  `data_ab` date NOT NULL,
  `hora_ab` time NOT NULL,
  `valor_ab` decimal(8,2) NOT NULL,
  `gerente_ab` int(11) NOT NULL,
  `data_fec` date DEFAULT NULL,
  `hora_fec` time DEFAULT NULL,
  `valor_fec` decimal(8,2) DEFAULT NULL,
  `valor_vendido` decimal(8,2) DEFAULT NULL,
  `valor_quebra` decimal(8,2) DEFAULT NULL,
  `gerente_fec` int(11) DEFAULT NULL,
  `caixa` int(11) NOT NULL,
  `operador` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `caixa`
--

INSERT INTO `caixa` (`id`, `data_ab`, `hora_ab`, `valor_ab`, `gerente_ab`, `data_fec`, `hora_fec`, `valor_fec`, `valor_vendido`, `valor_quebra`, `gerente_fec`, `caixa`, `operador`, `status`) VALUES
(8, '2022-04-18', '18:27:59', '900.00', 11, '2022-05-01', '00:36:59', '1200.00', '279.96', '20.04', 11, 3, 3, 'Fechado'),
(9, '2022-05-01', '00:33:27', '1000.00', 2, '2022-05-01', '00:34:58', '1300.00', '200.00', '100.00', 11, 1, 6, 'Fechado'),
(10, '2022-05-01', '01:29:32', '100.00', 11, '2022-05-01', '01:35:41', '210.00', '120.00', '-10.00', 2, 7, 3, 'Fechado'),
(11, '2022-05-01', '01:37:01', '100.00', 2, '2022-05-02', '12:38:59', '1122.86', '1022.86', '0.00', 2, 4, 3, 'Fechado'),
(13, '2022-05-02', '15:40:28', '100.00', 2, NULL, NULL, NULL, NULL, NULL, NULL, 2, 3, 'Aberto');

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixas`
--

CREATE TABLE `caixas` (
  `id` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `caixas`
--

INSERT INTO `caixas` (`id`, `nome`) VALUES
(1, 'Caixa 01'),
(2, 'Caixa 02'),
(3, 'Caixa 03'),
(4, 'Caixa 04'),
(5, 'Caixa 05'),
(7, 'Caixa 06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `foto` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `foto`) VALUES
(12, 'Alimentício', '21-03-2022-22-55-50-pintinho-amarelinho.jpg'),
(13, 'rwrwrw', '02-03-2022-18-37-52-curso-de-aplicativo-ecommerce-react-native.jpeg'),
(18, 'Laços de pano', '21-03-2022-22-58-48-curso-de-aplicativo-de-tarefas-com-react.jpeg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `usuario` int(11) NOT NULL,
  `fornecedor` int(11) NOT NULL,
  `pago` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `compras`
--

INSERT INTO `compras` (`id`, `total`, `data`, `usuario`, `fornecedor`, `pago`) VALUES
(1, '40.00', '2022-04-11', 2, 1, 'Sim'),
(2, '15.00', '2022-04-11', 2, 1, 'Sim'),
(3, '80.00', '2022-04-22', 2, 3, 'Sim'),
(4, '4000.00', '2022-04-28', 3, 1, 'Não'),
(5, '3500.00', '2022-04-28', 2, 1, 'Não'),
(6, '25272.00', '2022-04-28', 2, 1, 'Não'),
(7, '93.00', '2022-05-03', 2, 1, 'Não');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contas_pagar`
--

CREATE TABLE `contas_pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `usuario` int(11) NOT NULL,
  `pago` varchar(5) NOT NULL,
  `data` date NOT NULL,
  `vencimento` date NOT NULL,
  `arquivo` varchar(150) DEFAULT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contas_pagar`
--

INSERT INTO `contas_pagar` (`id`, `descricao`, `valor`, `usuario`, `pago`, `data`, `vencimento`, `arquivo`, `id_compra`) VALUES
(1, 'Conta de luz', '50.00', 12, 'Sim', '2022-04-11', '2022-04-11', '11-04-2022-09-44-14-144fb3e2-9225-486a-8e5d-8de16fff3564.pdf', 0),
(2, 'Conta de água', '100.00', 12, 'Não', '2022-04-11', '2022-04-18', '11-04-2022-09-46-31-2020-11_125,74.pdf', 0),
(3, 'Compra de Produtos', '40.00', 12, 'Sim', '2022-04-11', '0000-00-00', 'sem-foto.jpg', 1),
(4, 'Compra de Produtos', '15.00', 12, 'Sim', '2022-04-11', '2022-04-11', 'sem-foto.jpg', 2),
(5, 'Nova conta', '42.00', 12, 'Não', '2022-04-11', '2022-04-30', 'sem-foto.jpg', 0),
(6, 'Nova conta 02', '90.00', 12, 'Não', '2022-04-11', '2022-05-30', 'sem-foto.jpg', 0),
(7, 'fafafsfs', '242.00', 12, 'Não', '2022-04-11', '2022-05-15', 'sem-foto.jpg', 0),
(8, 'eeetetete', '256.00', 12, 'Não', '2022-04-11', '2022-04-15', 'sem-foto.jpg', 0),
(9, 'Conta vencida no dia 10', '120.00', 12, 'Sim', '2022-04-11', '2022-04-10', 'sem-foto.jpg', 0),
(10, 'Conta a pagar Hoje', '900.00', 12, 'Sim', '2022-04-11', '2022-04-11', 'sem-foto.jpg', 0),
(11, 'fsfsfsfs', '42.00', 12, 'Não', '2022-04-11', '2022-04-10', 'sem-foto.jpg', 0),
(12, 'czgdgdgdgd', '45.00', 12, 'Sim', '2022-04-12', '2022-04-12', 'sem-foto.jpg', 0),
(13, 'e664747575', '64.00', 12, 'Não', '2022-04-12', '2022-04-04', 'sem-foto.jpg', 0),
(14, 'conta a pagar 01 dia 12', '100.00', 12, 'Sim', '2022-04-12', '2022-04-12', 'sem-foto.jpg', 0),
(15, 'Compra de Produtos', '80.00', 12, 'Sim', '2022-04-22', '2022-04-22', 'sem-foto.jpg', 3),
(16, 'Compra de Produtos', '4000.00', 3, 'Não', '2022-04-28', '2022-04-28', 'sem-foto.jpg', 4),
(17, 'Compra de Produtos', '3500.00', 2, 'Não', '2022-04-28', '2022-04-28', 'sem-foto.jpg', 5),
(18, 'Compra de Produtos', '25272.00', 2, 'Não', '2022-04-28', '2022-04-28', 'sem-foto.jpg', 6),
(19, 'Compra de Produtos', '93.00', 2, 'Não', '2022-05-03', '2022-05-03', 'sem-foto.jpg', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contas_receber`
--

CREATE TABLE `contas_receber` (
  `id` int(11) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `usuario` int(5) NOT NULL,
  `pago` varchar(5) NOT NULL,
  `data` date NOT NULL,
  `vencimento` date NOT NULL,
  `arquivo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contas_receber`
--

INSERT INTO `contas_receber` (`id`, `descricao`, `valor`, `usuario`, `pago`, `data`, `vencimento`, `arquivo`) VALUES
(1, 'Marcelo Medeiros', '430.00', 12, 'Não', '2022-04-11', '2022-04-16', 'sem-foto.jpg'),
(2, 'Conta a Receber 01', '100.00', 12, 'Sim', '2022-04-11', '2022-04-30', '11-04-2022-18-28-24-5285cadf-df60-4a21-bf57-173c0abd6292.pdf'),
(3, 'Conta Receber Vencida', '543.00', 12, 'Sim', '2022-04-11', '2022-04-01', 'sem-foto.jpg'),
(4, 'daaadadadaggrt43', '1500.00', 12, 'Sim', '2022-04-11', '2022-04-16', 'sem-foto.jpg'),
(5, '52535353', '42.00', 12, 'Não', '2022-04-12', '2022-04-08', 'sem-foto.jpg'),
(6, 'fsfs3etete', '425.00', 12, 'Não', '2022-04-12', '2022-04-12', 'sem-foto.jpg'),
(7, 'conta a receber 01 dia 12', '500.00', 12, 'Sim', '2022-04-12', '2022-04-12', 'sem-foto.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `forma_pgtos`
--

CREATE TABLE `forma_pgtos` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `forma_pgtos`
--

INSERT INTO `forma_pgtos` (`id`, `codigo`, `nome`) VALUES
(2, 2, 'cartão de crédito'),
(3, 3, 'cartão de débito'),
(5, 1, 'dinheiro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `tipo_pessoa` varchar(20) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `endereco` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `tipo_pessoa`, `cpf`, `email`, `telefone`, `endereco`) VALUES
(1, 'Rubaldo do Doce', 'Física', '53535353535353', 'rubaldodoces@yahoo.com', '(34) 41414-1414', 'Rua do Chocolate, 12, Vila Prestígio, Santa Toddy'),
(3, 'Xing Ling', 'Jurídica', '313131313131', 'jjxxxxx@gmail.com', '(11) 11111-1122', 'Rua Long Dong, 35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_venda`
--

CREATE TABLE `itens_venda` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `valor_unitario` decimal(8,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_total_item` decimal(8,2) NOT NULL,
  `usuario` int(11) NOT NULL,
  `venda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `itens_venda`
--

INSERT INTO `itens_venda` (`id`, `produto`, `valor_unitario`, `quantidade`, `valor_total_item`, `usuario`, `venda`) VALUES
(124, 2, '29.99', 1, '29.99', 3, 7),
(125, 2, '29.99', 5, '149.95', 3, 7),
(129, 2, '29.99', 1, '29.99', 3, 10),
(130, 5, '40.00', 1, '40.00', 3, 10),
(131, 2, '29.99', 1, '29.99', 3, 11),
(134, 2, '29.99', 1, '29.99', 3, 12),
(135, 5, '40.00', 1, '40.00', 3, 12),
(142, 2, '29.99', 1, '29.99', 3, 13),
(143, 5, '40.00', 1, '40.00', 3, 13),
(144, 5, '40.00', 5, '200.00', 3, 13),
(145, 2, '29.99', 5, '149.95', 3, 13),
(146, 2, '29.99', 2, '59.98', 3, 13),
(148, 2, '29.99', 1, '29.99', 3, 14),
(149, 5, '40.00', 1, '40.00', 3, 14),
(150, 5, '40.00', 3, '120.00', 3, 14),
(151, 2, '29.99', 3, '89.97', 3, 14),
(152, 5, '40.00', 5, '200.00', 6, 15),
(153, 5, '40.00', 3, '120.00', 3, 16),
(155, 2, '29.99', 3, '89.97', 3, 17),
(156, 5, '40.00', 4, '160.00', 3, 17),
(157, 2, '29.99', 1, '29.99', 3, 18),
(158, 2, '29.99', 1, '29.99', 3, 19),
(159, 5, '40.00', 1, '40.00', 3, 20),
(160, 2, '29.99', 1, '29.99', 3, 20),
(161, 2, '29.99', 1, '29.99', 3, 22),
(162, 2, '29.99', 1, '29.99', 3, 23),
(163, 2, '29.99', 1, '29.99', 3, 24),
(164, 2, '29.99', 1, '29.99', 3, 25),
(165, 5, '40.00', 3, '120.00', 3, 25),
(166, 2, '29.99', 1, '29.99', 3, 26),
(167, 5, '40.00', 3, '120.00', 3, 26),
(172, 2, '29.99', 1, '29.99', 3, 0),
(173, 5, '40.00', 3, '120.00', 3, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacoes`
--

CREATE TABLE `movimentacoes` (
  `id` int(11) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `usuario` int(11) NOT NULL,
  `data` date NOT NULL,
  `id_mov` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `movimentacoes`
--

INSERT INTO `movimentacoes` (`id`, `tipo`, `descricao`, `valor`, `usuario`, `data`, `id_mov`) VALUES
(1, 'Saída', 'Conta de luz', '50.00', 12, '2022-04-11', 1),
(2, 'Saída', 'Compra de Produtos', '15.00', 12, '2022-04-11', 4),
(3, 'Entrada', 'Conta a Receber 01', '100.00', 12, '2022-04-11', 2),
(4, 'Saída', 'Conta vencida no dia 10', '120.00', 12, '2022-04-11', 9),
(5, 'Saída', 'Compra de Produtos', '40.00', 12, '2022-04-11', 3),
(6, 'Saída', 'Conta a pagar Hoje', '900.00', 12, '2022-04-11', 10),
(7, 'Entrada', 'Conta Receber Vencida', '543.00', 12, '2022-04-11', 3),
(8, 'Entrada', 'daaadadadaggrt43', '1500.00', 12, '2022-04-11', 4),
(9, 'Saída', 'conta a pagar 01 dia 12', '100.00', 12, '2022-04-12', 14),
(10, 'Saída', 'czgdgdgdgd', '45.00', 12, '2022-04-12', 12),
(11, 'Entrada', 'conta a receber 01 dia 12', '500.00', 12, '2022-04-12', 7),
(12, 'Saída', 'Compra de Produtos', '80.00', 12, '2022-04-22', 15);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `estoque` int(11) NOT NULL,
  `valor_compra` decimal(8,2) NOT NULL,
  `valor_venda` decimal(8,2) NOT NULL,
  `fornecedor` int(11) NOT NULL,
  `categoria` int(11) NOT NULL,
  `foto` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `codigo`, `nome`, `descricao`, `estoque`, `valor_compra`, `valor_venda`, `fornecedor`, `categoria`, `foto`) VALUES
(2, '123', 'teste', '  ffsfsfs  ', 9, '31.00', '29.99', 1, 12, '09-03-2022-16-15-16-curso-de-aplicativo-de-tarefas-com-react.jpeg'),
(5, '321', 'teste 02', '  novo teste', 15, '10.00', '40.00', 1, 18, 'sem-foto.jpg'),
(6, '1234598793189', 'Coca Cola 350ml', '   coca cola lata 350ml', 20, '4.00', '3.90', 3, 12, '22-04-2022-23-22-18-coca-cola-lata-350ml-min.png'),
(7, '7898934925093', 'novo teste 01', '  novo teste 01 descrição', 324, '78.00', '5.00', 1, 12, 'sem-foto.jpg'),
(8, '898934925093', 'novo teste 02', '  novo teste 02', 350, '10.00', '10.00', 1, 13, 'sem-foto.jpg'),
(9, '6891271925093', 'novo teste 03', '  novo teste 03 descrição', 50, '80.00', '50.00', 1, 18, 'sem-foto.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `nivel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `cpf`, `senha`, `nivel`) VALUES
(1, 'Iarley Johnson', 'iarley@gmail.com', '123.456.789-01', '123', 'Tesoureiro'),
(2, 'Administrador Nerd', 'admin@gmailnovo.com', '000.000.000-00', '123', 'Admin'),
(3, 'Paloma Freitas', 'palomita@hotmail.com', '442.334.541.-01', '123', 'Operador'),
(5, 'Carlitos Tevez', 'tevez@gmail.com', '131.313.313-00', '123', 'Tesoureiro'),
(6, 'Dado Dolabela', 'dolabela@hotmail.com', '314.242,242-00', '123', 'Operador'),
(11, 'Marcio Petroleiro', 'marciopetroleiro@gmail.com', '042.429.429-88', '123', 'Admin'),
(12, 'Tesoureiro Teste', 'tesoureiro@hotmail.com', '123', '123', 'Tesoureiro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `operador` int(11) NOT NULL,
  `valor_recebido` decimal(8,2) NOT NULL,
  `desconto` varchar(30) NOT NULL,
  `troco` decimal(8,2) NOT NULL,
  `forma_pgto` int(11) NOT NULL,
  `abertura` int(11) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `valor`, `data`, `hora`, `operador`, `valor_recebido`, `desconto`, `troco`, `forma_pgto`, `abertura`, `status`) VALUES
(14, '279.96', '2022-04-30', '23:54:23', 3, '279.96', '0.00', '0.00', 1, 8, 'Concluída'),
(15, '200.00', '2022-05-01', '00:34:07', 6, '200.00', '0.00', '0.00', 2, 9, 'Concluída'),
(16, '120.00', '2022-05-01', '01:29:46', 3, '120.00', '0.00', '0.00', 3, 10, 'Concluída'),
(17, '249.97', '2022-05-02', '00:22:53', 3, '249.97', '0.00', '0.00', 2, 11, 'Concluída'),
(18, '29.99', '2022-05-02', '00:24:15', 3, '29.99', '0.00', '0.00', 3, 11, 'Concluída'),
(19, '29.99', '2022-05-02', '00:26:50', 3, '29.99', '0.00', '0.00', 3, 11, 'Concluída'),
(20, '69.99', '2022-05-02', '00:28:08', 3, '69.99', '0.00', '0.00', 2, 11, 'Concluída'),
(21, '69.99', '2022-05-02', '00:28:19', 3, '69.99', '0.00', '0.00', 2, 11, 'Concluída'),
(22, '29.99', '2022-05-02', '00:30:49', 3, '29.99', '0.00', '0.00', 2, 11, 'Concluída'),
(23, '29.99', '2022-05-02', '00:32:59', 3, '29.99', '0.00', '0.00', 2, 11, 'Concluída'),
(24, '29.99', '2022-05-02', '00:35:54', 3, '29.99', '0.00', '0.00', 2, 11, 'Concluída'),
(25, '134.99', '2022-05-02', '12:16:27', 3, '150.00', '10%', '15.01', 2, 11, 'Concluída'),
(26, '134.99', '2022-05-02', '12:18:57', 3, '150.00', '10%', '15.01', 2, 11, 'Concluída'),
(27, '62.99', '2022-05-02', '12:19:43', 3, '70.00', '10%', '7.01', 2, 11, 'Cancelada'),
(28, '149.99', '2022-05-02', '12:37:16', 3, '149.99', '0.00', '0.00', 2, 11, 'Cancelada');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `caixas`
--
ALTER TABLE `caixas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `forma_pgtos`
--
ALTER TABLE `forma_pgtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `caixas`
--
ALTER TABLE `caixas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `forma_pgtos`
--
ALTER TABLE `forma_pgtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
