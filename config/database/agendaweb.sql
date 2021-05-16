-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Maio-2021 às 02:25
-- Versão do servidor: 10.4.18-MariaDB
-- versão do PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agendaweb`
--
CREATE DATABASE IF NOT EXISTS `agendaweb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `agendaweb`;

DELIMITER $$
--
-- Procedimentos
--
DROP PROCEDURE IF EXISTS `eventoDelete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eventoDelete` (`p_id_evento` INT)  BEGIN
IF((p_id_evento > 0) && (p_id_evento !=''))
THEN 
DELETE FROM eventos WHERE id_evento=p_id_evento;
ELSE
SELECT 'Os dados devem ser informados para cadastro'
AS Msg;
END IF;
END$$

DROP PROCEDURE IF EXISTS `eventoInsert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eventoInsert` (`p_id_usuario` INT, `p_nome_evento` VARCHAR(45), `p_desc_evento` VARCHAR(128), `p_color` VARCHAR(12), `p_inicio_evento` DATETIME, `p_final_evento` DATETIME)  BEGIN
IF((p_id_usuario !='') && (p_nome_evento !='') && (p_desc_evento !='') && (p_color !='') && (p_inicio_evento !='') && (p_final_evento !='')) 
THEN
INSERT INTO `eventos`(`id_usuario`, `nome_evento`, `desc_evento`, `color`, `inicio_evento`, `final_evento`)
VALUES
(p_id_usuario,p_nome_evento,p_desc_evento,p_color,p_inicio_evento,p_final_evento);
ELSE
SELECT 'NOME e CPF devem ser fornecidos para o cadastro!'
AS Msg;
END IF;
END$$

DROP PROCEDURE IF EXISTS `eventoUpdate`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eventoUpdate` (`p_id_evento` INT, `p_id_usuario` INT, `p_nome_evento` VARCHAR(45), `p_desc_evento` VARCHAR(128), `p_color` VARCHAR(12), `p_inicio_evento` DATETIME, `p_final_evento` DATETIME)  BEGIN
IF((p_id_evento > 0 ) && (p_id_evento != '') && (p_id_usuario !='') && (p_nome_evento !='') && (p_desc_evento !='') && (p_color !='') && (p_inicio_evento !='') && (p_final_evento !='')) 
THEN 
UPDATE `eventos` SET `id_evento`=p_id_evento ,`id_usuario`=p_id_usuario ,`nome_evento`=p_nome_evento ,`desc_evento`=p_desc_evento ,`color`=p_color ,`inicio_evento`=p_inicio_evento ,`final_evento`=p_final_evento
WHERE id_evento = p_id_evento;
ELSE
SELECT 'Os dados devem ser informados para cadastro'
AS Msg;
END IF;
END$$

DROP PROCEDURE IF EXISTS `usuarioDelete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usuarioDelete` (`p_id_usuario` INT)  BEGIN
IF((p_id_usuario > 0) && (p_id_usuario !=''))
THEN 
DELETE FROM usuario WHERE id_usuario=p_id_usuario;
ELSE
SELECT 'Os dados devem ser informados para cadastro'
AS Msg;
END IF;
END$$

DROP PROCEDURE IF EXISTS `usuarioInsert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usuarioInsert` (`p_nome_usuario` VARCHAR(45), `p_email_usuario` VARCHAR(45), `p_senha_usuario` VARCHAR(255), `p_data_nasc` DATE, `p_data_cad` DATE, `p_foto_usuario` VARCHAR(220))  BEGIN
IF((p_nome_usuario !='') && (p_email_usuario !='') && (p_senha_usuario !='') && (p_data_nasc !='') && (p_data_cad !='') && (p_foto_usuario !=''))
THEN 
INSERT INTO usuario
(nome_usuario, email_usuario, senha_usuario, data_nasc, data_cadastro,foto_usuario)
VALUES
(p_nome_usuario,p_email_usuario,p_senha_usuario,p_data_nasc,p_data_cad,p_foto_usuario);
ELSE
SELECT 'Os dados devem ser informados para cadastro'
AS Msg;
END IF;
END$$

DROP PROCEDURE IF EXISTS `usuarioUpdate`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usuarioUpdate` (`p_id_usuario` INT, `p_nome_usuario` VARCHAR(45), `p_email_usuario` VARCHAR(45), `p_senha_usuario` VARCHAR(255), `p_data_nasc` DATE, `p_foto_usuario` VARCHAR(220))  BEGIN
IF((p_id_usuario > 0 ) && (p_id_usuario != '' ) && (p_nome_usuario !='') && (p_email_usuario !='') && (p_senha_usuario !='') && (p_data_nasc !='') && (p_foto_usuario !=''))
THEN 
UPDATE usuario set email_usuario=p_email_usuario, nome_usuario = p_nome_usuario, senha_usuario = p_senha_usuario, data_nasc=p_data_nasc, foto_usuario=p_foto_usuario
WHERE id_usuario = p_id_usuario;
ELSE
SELECT 'Os dados devem ser informados para cadastro'
AS Msg;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contatos`
--

DROP TABLE IF EXISTS `contatos`;
CREATE TABLE `contatos` (
  `id_contato` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome_contato` varchar(45) NOT NULL,
  `email_contato` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nome_evento` varchar(45) DEFAULT NULL,
  `desc_evento` varchar(128) DEFAULT NULL,
  `color` varchar(12) NOT NULL,
  `inicio_evento` datetime NOT NULL,
  `final_evento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventos_shared`
--

DROP TABLE IF EXISTS `eventos_shared`;
CREATE TABLE `eventos_shared` (
  `id_compartilhado` int(11) NOT NULL,
  `id_usuario_solicitante` int(11) NOT NULL,
  `id_evento_compartilhado` int(11) NOT NULL,
  `id_usuario_solicitado` int(11) NOT NULL,
  `permissao` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(45) DEFAULT NULL,
  `email_usuario` varchar(45) DEFAULT NULL,
  `senha_usuario` varchar(255) DEFAULT NULL,
  `data_nasc` date NOT NULL,
  `data_cadastro` date NOT NULL,
  `foto_usuario` varchar(220) NOT NULL,
  `wallpaper_usuario` varchar(10) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id_contato`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Índices para tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_usuario_fk` (`id_usuario`);

--
-- Índices para tabela `eventos_shared`
--
ALTER TABLE `eventos_shared`
  ADD PRIMARY KEY (`id_compartilhado`),
  ADD KEY `id_usuario_solicitante_fk` (`id_usuario_solicitante`),
  ADD KEY `id_evento_solicitante_fk` (`id_evento_compartilhado`),
  ADD KEY `id_usuario_solicitado` (`id_usuario_solicitado`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id_contato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventos_shared`
--
ALTER TABLE `eventos_shared`
  MODIFY `id_compartilhado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `contatos`
--
ALTER TABLE `contatos`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Limitadores para a tabela `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `id_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Limitadores para a tabela `eventos_shared`
--
ALTER TABLE `eventos_shared`
  ADD CONSTRAINT `id_evento_solicitante_fk` FOREIGN KEY (`id_evento_compartilhado`) REFERENCES `eventos` (`id_evento`),
  ADD CONSTRAINT `id_usuario_solicitado` FOREIGN KEY (`id_usuario_solicitado`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `id_usuario_solicitante_fk` FOREIGN KEY (`id_usuario_solicitante`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
