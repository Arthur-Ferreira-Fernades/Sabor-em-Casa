-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/10/2024 às 00:46
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sabor-em-casa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `receita_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `receita_id`, `created_at`) VALUES
(10, 7, 16, '2024-10-21 22:27:45'),
(12, 7, 17, '2024-10-21 22:28:24'),
(13, 7, 26, '2024-10-21 22:29:11'),
(14, 7, 19, '2024-10-21 22:29:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL,
  `Id_receita` int(11) DEFAULT NULL,
  `ingrediente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ingredientes`
--

INSERT INTO `ingredientes` (`id`, `Id_receita`, `ingrediente`) VALUES
(59, 16, '1 baguete de pão italiano'),
(60, 16, '4 tomates maduros'),
(61, 16, '1/2 xícara de folhas de manjericão'),
(62, 16, '1/4 de xícara de azeite de oliva'),
(63, 16, 'Sal e pimenta a gosto'),
(64, 17, '500g de peito de frango cortado em cubos'),
(65, 17, '1 cebola picada'),
(66, 17, '2 dentes de alho picados'),
(67, 17, '2 colheres de sopa de curry em pó'),
(68, 17, '1 lata de leite de coco (400ml)'),
(69, 17, 'Sal e pimenta a gosto'),
(70, 18, '1 lata de leite condensado (395g)'),
(71, 18, '2 latas de leite (use a lata do leite condensado como medida)'),
(72, 18, '3 ovos'),
(73, 18, '1 xícara de açúcar (para caramelizar)'),
(74, 18, '1 colher de chá de essência de baunilha'),
(75, 19, '1 limão cortado em pedaços'),
(76, 19, '2 colheres de sopa de açúcar'),
(77, 19, '50ml de cachaça'),
(78, 19, 'Gelo a gosto'),
(79, 20, '2 abacates maduros'),
(80, 20, '1/2 cebola picada'),
(81, 20, '1 tomate picado'),
(82, 20, 'Suco de 1 limão'),
(83, 20, '1/4 de xícara de coentro picado'),
(84, 20, 'Sal a gosto'),
(85, 21, '250g de spaghetti'),
(86, 21, '300g de carne moída'),
(87, 21, '1 cebola picada'),
(88, 21, '2 dentes de alho picados'),
(89, 21, '1 lata de molho de tomate (340g)'),
(90, 21, 'Sal e pimenta a gosto'),
(91, 22, '200g de biscoitos de maisena'),
(92, 22, '100g de manteiga derretida'),
(93, 22, '1 lata de leite condensado (395g)'),
(94, 22, '1/2 xícara de suco de limão'),
(95, 22, '3 claras'),
(96, 22, '1/2 xícara de açúcar'),
(97, 23, '200ml de leite de coco'),
(98, 23, '100ml de cachaça'),
(99, 23, '3 colheres de sopa de açúcar'),
(100, 23, 'Gelo a gosto'),
(101, 24, '1 pacote de massa folhada'),
(102, 24, '3 ovos'),
(103, 24, '1/2 xícara de creme de leite'),
(104, 24, '1 xícara de queijo ralado'),
(105, 24, '1/2 xícara de presunto picado'),
(106, 24, 'Sal e pimenta a gosto'),
(114, 25, '1 xícara de arroz arbóreo'),
(115, 25, '200g de cogumelos (shiitake ou paris)'),
(116, 25, '1 cebola picada'),
(117, 25, '4 xícaras de caldo de galinha'),
(118, 25, '1/2 xícara de queijo parmesão ralado'),
(119, 25, '2 colheres de sopa de manteiga'),
(120, 25, 'Sal e pimenta a gosto'),
(121, 26, '200g de chocolate meio amargo'),
(122, 26, '3 ovos'),
(123, 26, '1/2 xícara de açúcar'),
(124, 26, '1/2 xícara de creme de leite'),
(125, 27, '1/2 abacaxi picado'),
(126, 27, '1 litro de água'),
(127, 27, '1/4 de xícara de folhas de hortelã'),
(128, 27, '2 colheres de sopa de açúcar (opcional)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `receitas`
--

CREATE TABLE `receitas` (
  `Id_receita` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `Criador` int(11) DEFAULT NULL,
  `congelado` int(2) DEFAULT 0,
  `preparo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `receitas`
--

INSERT INTO `receitas` (`Id_receita`, `nome`, `categoria`, `imagem`, `Criador`, `congelado`, `preparo`) VALUES
(16, 'Bruschetta de Tomate', 'entradas', 'ReceitaImagens/67148de254f99-348040-original.jpg', 4, 0, ' Torre o pão, misture os tomates picados com manjericão, azeite, sal e pimenta. Coloque a mistura sobre o pão torrado.'),
(17, 'Frango ao Curry', 'principal', 'ReceitaImagens/67148ed68216d-bk-5378-frango-ao-curry-com-maca.webp', 4, 0, 'Refogue cebola e alho, adicione o frango e cozinhe até dourar. Misture o curry e o leite de coco, cozinhe até o frango ficar macio.'),
(18, 'Pudim de Leite Condensado', 'sobremesa', 'ReceitaImagens/67148f6b45ad0-download (3).jpg', 4, 0, 'Bata todos os ingredientes no liquidificador, caramelize uma forma com açúcar e asse em banho-maria até firmar.'),
(19, 'Caipirinha', 'bebida', 'ReceitaImagens/67148fdc90edc-download (4).jpg', 4, 0, 'Corte o limão em pedaços, macere com açúcar, adicione a cachaça e gelo.\r\n'),
(20, 'Guacamole', 'entradas', 'ReceitaImagens/6714918eb6d6c-download.jpg', 5, 0, 'Amasse o abacate e misture com cebola, tomate e coentro picados. Adicione suco de limão e sal a gosto.'),
(21, 'Spaghetti à Bolonhesa', 'principal', 'ReceitaImagens/67149208ed1f6-VNTJ_JAN_BLOG-optimized.webp', 5, 0, ' Cozinhe o spaghetti. Refogue cebola e alho, adicione carne moída até dourar. Misture o molho de tomate e sirva sobre o spaghetti.'),
(22, 'Torta de Limão', 'sobremesa', 'ReceitaImagens/6714926e220df-download (5).jpg', 5, 0, 'Triture os biscoitos e misture com manteiga, forre uma forma. Misture leite condensado com limão, coloque sobre a base e bata as claras com açúcar. Asse até dourar.'),
(23, 'Batida de Coco', 'bebida', 'ReceitaImagens/671492cc0a741-228-bk-9355-batidadecoco.webp', 5, 0, 'Misture todos os ingredientes no liquidificador e bata até ficar homogêneo.'),
(24, 'Mini Quiches', 'entradas', 'ReceitaImagens/6714935b59831-download (1).jpg', 6, 0, 'Forre forminhas com a massa, bata os ovos com creme de leite, adicione o queijo e presunto picados. Despeje sobre a massa e asse até dourar.'),
(25, 'Risoto de Funghi', 'principal', 'ReceitaImagens/671493f5954d3-risoto-de-funghi-secchi.avif', 6, 0, 'Refogue cebola e cogumelos, adicione o arroz e misture. Vá adicionando caldo aos poucos, até o arroz cozinhar. Finalize com parmesão e manteiga.'),
(26, 'Mousse de Chocolate', 'sobremesa', 'ReceitaImagens/6714949845206-download (6).jpg', 6, 0, 'Derreta o chocolate, bata as claras em neve, misture com o chocolate e o creme de leite. Refrigere até firmar.'),
(27, 'Suco de Abacaxi com Hortelã', 'bebida', 'ReceitaImagens/671494f49084b-usuario-3367-f11ec1c24d2ba676e942674aa0b51296.jpg', 6, 0, 'Bata o abacaxi com água e açúcar. Coe e sirva com folhas de hortelã.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `UsuId` int(11) NOT NULL,
  `UsuNome` varchar(255) DEFAULT NULL,
  `UsuEmail` varchar(255) DEFAULT NULL,
  `UsuTelefone` varchar(255) DEFAULT NULL,
  `UsuSenha` varchar(255) DEFAULT NULL,
  `UsuImagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`UsuId`, `UsuNome`, `UsuEmail`, `UsuTelefone`, `UsuSenha`, `UsuImagem`) VALUES
(4, 'Arthur Ferreira Fernandes', 'arthur@123.com', '11986599562', '123', 'arthur.enc'),
(5, 'Sarah Alves Moya Ferreira', 'sarah@123.com', '11998844335', '123', 'padrao.png'),
(6, 'Eduarda Ferreira Fernandes', 'eduarda@123.com', '11999999999', '123', 'padrao.png'),
(7, 'sarah ferreira', 'sarahalmoya@gmail.com', '11998844335', 'arthur', 'padrao.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`receita_id`),
  ADD KEY `receita_id` (`receita_id`);

--
-- Índices de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receita_id` (`Id_receita`);

--
-- Índices de tabela `receitas`
--
ALTER TABLE `receitas`
  ADD PRIMARY KEY (`Id_receita`),
  ADD KEY `fk_usuario` (`Criador`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`UsuId`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de tabela `receitas`
--
ALTER TABLE `receitas`
  MODIFY `Id_receita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `UsuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`UsuId`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`Id_receita`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `ingredientes_ibfk_1` FOREIGN KEY (`Id_receita`) REFERENCES `receitas` (`Id_receita`) ON DELETE CASCADE;

--
-- Restrições para tabelas `receitas`
--
ALTER TABLE `receitas`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`Criador`) REFERENCES `usuarios` (`UsuId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
