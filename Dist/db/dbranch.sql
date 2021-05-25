-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 25/05/2021 às 00h43min
-- Versão do Servidor: 5.5.20
-- Versão do PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `dbranch`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_resp`
--

CREATE TABLE IF NOT EXISTS `tb_resp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Carimbo de data/hora` datetime NOT NULL,
  `Endereço de e-mail` varchar(255) NOT NULL,
  `Posto/Graduação` varchar(255) NOT NULL,
  `Nome de guerra` varchar(255) NOT NULL,
  `Organização Militar` varchar(255) NOT NULL,
  `Segunda-feira` varchar(255) NOT NULL,
  `Terça-feira` varchar(255) NOT NULL,
  `Quarta-feira` varchar(255) NOT NULL,
  `Quinta-feira` varchar(255) NOT NULL,
  `Sexta-feira` varchar(255) NOT NULL,
  `Sábado` varchar(255) NOT NULL,
  `Domingo` varchar(255) NOT NULL,
  `registro_db` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_users`
--

CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nvl_acess` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tb_users`
--

INSERT INTO `tb_users` (`id`, `username`, `password`, `nvl_acess`) VALUES
(1, 'ten.decol', 'af41bd17b2c59a934ab74615df8d5060', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
