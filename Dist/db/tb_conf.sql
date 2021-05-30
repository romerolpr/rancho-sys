-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 30/05/2021 às 16h30min
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
-- Estrutura da tabela `tb_conf`
--

CREATE TABLE IF NOT EXISTS `tb_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_id` varchar(255) NOT NULL,
  `data_json` varchar(25000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=153 ;

--
-- Extraindo dados da tabela `tb_conf`
--

INSERT INTO `tb_conf` (`id`, `hash_id`, `data_json`) VALUES
(1, 'f6b96e6d730e8f146976b40cb86e8692', '{"hash":"f6b96e6d730e8f146976b40cb86e8692","0":{"timestamp":1622319379,"values":[]}}'),
(2, 'f5ff5a61946476be68668f63bc43162f', '{"hash":"f5ff5a61946476be68668f63bc43162f","0":{"timestamp":1622319410637,"values":["Almo\\u00e7o;25\\/05\\/2021","Almo\\u00e7o;24\\/05\\/2021"]}}'),
(3, '2dd2e543b120292a1cc6cff79d97ec49', '{"hash":"2dd2e543b120292a1cc6cff79d97ec49","0":{"timestamp":1622339010619,"values":["Almo\\u00e7o;26\\/05\\/2021","Almo\\u00e7o;24\\/05\\/2021"]}}'),
(4, '460fe7989054c963cf894aa62763d17f', '{"hash":"460fe7989054c963cf894aa62763d17f","0":{"timestamp":1622319379,"values":[]}}'),
(5, '0c73befe4827cad5967cfe51579190d6', '{"hash":"0c73befe4827cad5967cfe51579190d6","0":{"timestamp":1622319379,"values":[]}}'),
(6, '63bad736cf6fac7866f77e639e199aa4', '{"hash":"63bad736cf6fac7866f77e639e199aa4","0":{"timestamp":1622319379,"values":[]}}'),
(7, 'a6c39fc24fed232c6e2d762e783af94d', '{"hash":"a6c39fc24fed232c6e2d762e783af94d","0":{"timestamp":1622319379,"values":[]}}'),
(8, '138e79089f409b01fb635839b9ab5150', '{"hash":"138e79089f409b01fb635839b9ab5150","0":{"timestamp":1622319379,"values":[]}}'),
(9, '29939042784d22e36df764564c103cc5', '{"hash":"29939042784d22e36df764564c103cc5","0":{"timestamp":1622319379,"values":[]}}'),
(10, '633570a034ae058bdc19ecb14a569366', '{"hash":"633570a034ae058bdc19ecb14a569366","0":{"timestamp":1622319379,"values":[]}}'),
(11, '1a55e9a1eb7a6a30f50e3cbeaa5fe294', '{"hash":"1a55e9a1eb7a6a30f50e3cbeaa5fe294","0":{"timestamp":1622319379,"values":[]}}'),
(12, '9abbc8fb9b52aebc02e6d8d8a257387f', '{"hash":"9abbc8fb9b52aebc02e6d8d8a257387f","0":{"timestamp":1622319379,"values":[]}}'),
(13, '4c9f77a8b4e72f2dcd5b55f349fb903a', '{"hash":"4c9f77a8b4e72f2dcd5b55f349fb903a","0":{"timestamp":1622319379,"values":[]}}'),
(14, '6ffaaaabdfd79a818204542fbf5957a0', '{"hash":"6ffaaaabdfd79a818204542fbf5957a0","0":{"timestamp":1622319379,"values":[]}}'),
(15, '476a4e1387964c66f8a9e9ffbd7f3fc8', '{"hash":"476a4e1387964c66f8a9e9ffbd7f3fc8","0":{"timestamp":1622319379,"values":[]}}'),
(16, 'b8c3b7fcf484e4aa8a199dc9aab4f235', '{"hash":"b8c3b7fcf484e4aa8a199dc9aab4f235","0":{"timestamp":1622319379,"values":[]}}'),
(17, '8e6b7e281e76b35db65f6cee41d5bbcc', '{"hash":"8e6b7e281e76b35db65f6cee41d5bbcc","0":{"timestamp":1622319379,"values":[]}}'),
(18, 'ccbed7579ca8bd6667001bdec4c3daf8', '{"hash":"ccbed7579ca8bd6667001bdec4c3daf8","0":{"timestamp":1622319379,"values":[]}}'),
(19, '568548bd0767f844f9304cb14cc7b20c', '{"hash":"568548bd0767f844f9304cb14cc7b20c","0":{"timestamp":1622319379,"values":[]}}'),
(20, '9779d00105ba37aad0462b9f83796b0f', '{"hash":"9779d00105ba37aad0462b9f83796b0f","0":{"timestamp":1622319379,"values":[]}}'),
(21, '4070022e4119b372ebef4f637b824475', '{"hash":"4070022e4119b372ebef4f637b824475","0":{"timestamp":1622319379,"values":[]}}'),
(22, 'ac85ca44a3b983aa7d424ad3fef4f2ce', '{"hash":"ac85ca44a3b983aa7d424ad3fef4f2ce","0":{"timestamp":1622319379,"values":[]}}'),
(23, '93375f83c0806e0c9f8d6c62cd1c12e2', '{"hash":"93375f83c0806e0c9f8d6c62cd1c12e2","0":{"timestamp":1622319379,"values":[]}}'),
(24, 'e45ee46de8774b4dd4e1cbf9aecf1b08', '{"hash":"e45ee46de8774b4dd4e1cbf9aecf1b08","0":{"timestamp":1622319379,"values":[]}}'),
(25, '45803510bed94fd77c3ee0ee4acccf58', '{"hash":"45803510bed94fd77c3ee0ee4acccf58","0":{"timestamp":1622319379,"values":[]}}'),
(26, '254037aa4149e6723d9062bc5d83eb5b', '{"hash":"254037aa4149e6723d9062bc5d83eb5b","0":{"timestamp":1622319379,"values":[]}}'),
(27, 'dfb1b44da392cad1fc8860a93927a1f9', '{"hash":"dfb1b44da392cad1fc8860a93927a1f9","0":{"timestamp":1622319379,"values":[]}}'),
(28, '571857eed094b2d25706801614c55df4', '{"hash":"571857eed094b2d25706801614c55df4","0":{"timestamp":1622319379,"values":[]}}'),
(29, '8c45cbeb46edbd63b4204f9108111e49', '{"hash":"8c45cbeb46edbd63b4204f9108111e49","0":{"timestamp":1622319379,"values":[]}}'),
(30, '465bc260ec4e8a2913c5e13c1dd58b95', '{"hash":"465bc260ec4e8a2913c5e13c1dd58b95","0":{"timestamp":1622319379,"values":[]}}'),
(31, '743a13955cb0fbdc7a7a5a9cf944ca14', '{"hash":"743a13955cb0fbdc7a7a5a9cf944ca14","0":{"timestamp":1622319379,"values":[]}}'),
(32, '74248343d8689456f90fc3880a875151', '{"hash":"74248343d8689456f90fc3880a875151","0":{"timestamp":1622319379,"values":[]}}'),
(33, '554940bdca28c86b53ceaaa2bce47338', '{"hash":"554940bdca28c86b53ceaaa2bce47338","0":{"timestamp":1622319379,"values":[]}}'),
(34, '5632780cf59726329ac5c99ed9cb9eec', '{"hash":"5632780cf59726329ac5c99ed9cb9eec","0":{"timestamp":1622319379,"values":[]}}'),
(35, '59c19f37c072ebc6dfc6a4dfbeab399f', '{"hash":"59c19f37c072ebc6dfc6a4dfbeab399f","0":{"timestamp":1622319379,"values":[]}}'),
(36, '7f656066a5ebe199a8f3a8cde09d92b3', '{"hash":"7f656066a5ebe199a8f3a8cde09d92b3","0":{"timestamp":1622319379,"values":[]}}'),
(37, '426c2bf32f2b19de795f0e971869a164', '{"hash":"426c2bf32f2b19de795f0e971869a164","0":{"timestamp":1622319379,"values":[]}}'),
(38, '012b71803df91a8bebea0c1d7fb43208', '{"hash":"012b71803df91a8bebea0c1d7fb43208","0":{"timestamp":1622319379,"values":[]}}'),
(39, '2bbe648f08a8df83ff1c726f7ba9ee43', '{"hash":"2bbe648f08a8df83ff1c726f7ba9ee43","0":{"timestamp":1622319379,"values":[]}}'),
(40, 'f0fffe05abe8480c8d8753218fd96ed3', '{"hash":"f0fffe05abe8480c8d8753218fd96ed3","0":{"timestamp":1622319379,"values":[]}}'),
(41, 'ca002f29760376da08f72e64e9f8a656', '{"hash":"ca002f29760376da08f72e64e9f8a656","0":{"timestamp":1622319379,"values":[]}}'),
(42, '1bbd9408383108db8c3c0abc4ab12cac', '{"hash":"1bbd9408383108db8c3c0abc4ab12cac","0":{"timestamp":1622319379,"values":[]}}'),
(43, 'aa3b59201cff4d2cefbd6552e14be111', '{"hash":"aa3b59201cff4d2cefbd6552e14be111","0":{"timestamp":1622319379,"values":[]}}'),
(44, '8bb06feaf5a332393001c7a38480a0ff', '{"hash":"8bb06feaf5a332393001c7a38480a0ff","0":{"timestamp":1622319379,"values":[]}}'),
(45, '43db7545280746217ac157392f76f10b', '{"hash":"43db7545280746217ac157392f76f10b","0":{"timestamp":1622319379,"values":[]}}'),
(46, 'e4235b7126af89242d21a613cf08df2b', '{"hash":"e4235b7126af89242d21a613cf08df2b","0":{"timestamp":1622319379,"values":[]}}'),
(47, 'c4d1e82764ad00b8845eccde7c60f768', '{"hash":"c4d1e82764ad00b8845eccde7c60f768","0":{"timestamp":1622319379,"values":[]}}'),
(48, '0211d531a0b1bce8c645bd904a245161', '{"hash":"0211d531a0b1bce8c645bd904a245161","0":{"timestamp":1622319379,"values":[]}}'),
(49, '3fd86574d369e6f881781d70874684e8', '{"hash":"3fd86574d369e6f881781d70874684e8","0":{"timestamp":1622319379,"values":[]}}'),
(50, 'b2e4b329c5ec7a77009b568786e3945a', '{"hash":"b2e4b329c5ec7a77009b568786e3945a","0":{"timestamp":1622319379,"values":[]}}'),
(51, '212ae3c0fc020e28be7a1d5333cccecb', '{"hash":"212ae3c0fc020e28be7a1d5333cccecb","0":{"timestamp":1622319379,"values":[]}}'),
(52, '3ad0ada203f5961a86a2e2f34fdad602', '{"hash":"3ad0ada203f5961a86a2e2f34fdad602","0":{"timestamp":1622319379,"values":[]}}'),
(53, 'eff6bd8406892636e8478e808a50dd2c', '{"hash":"eff6bd8406892636e8478e808a50dd2c","0":{"timestamp":1622319379,"values":[]}}'),
(54, 'b4e26287a02bdf7209535e8edb26de79', '{"hash":"b4e26287a02bdf7209535e8edb26de79","0":{"timestamp":1622319379,"values":[]}}'),
(55, 'df6dc7011656b2eee1ec117533f10f4e', '{"hash":"df6dc7011656b2eee1ec117533f10f4e","0":{"timestamp":1622319379,"values":[]}}'),
(56, '46e0340e5b1f378104854a7e218308c1', '{"hash":"46e0340e5b1f378104854a7e218308c1","0":{"timestamp":1622319379,"values":[]}}'),
(57, '0e653639db1e6f47d171dbbe16aebecf', '{"hash":"0e653639db1e6f47d171dbbe16aebecf","0":{"timestamp":1622319379,"values":[]}}'),
(58, 'a2ecca292da4ffbebe5edf5f4614616f', '{"hash":"a2ecca292da4ffbebe5edf5f4614616f","0":{"timestamp":1622319379,"values":[]}}'),
(59, '39eb72d7ee56fcb4ed940877f837523a', '{"hash":"39eb72d7ee56fcb4ed940877f837523a","0":{"timestamp":1622319379,"values":[]}}'),
(60, '7cb635b342e7009dd777760abb5eb71c', '{"hash":"7cb635b342e7009dd777760abb5eb71c","0":{"timestamp":1622319379,"values":[]}}'),
(61, '8a94504ea21be079d13a0f23bbb960c7', '{"hash":"8a94504ea21be079d13a0f23bbb960c7","0":{"timestamp":1622340696770,"values":["Jantar;30\\/05\\/2021","Almo\\u00e7o;30\\/05\\/2021","Jantar;29\\/05\\/2021","Almo\\u00e7o;29\\/05\\/2021","Jantar;28\\/05\\/2021","Almo\\u00e7o;28\\/05\\/2021","Jantar;27\\/05\\/2021","Almo\\u00e7o;27\\/05\\/2021","Jantar;26\\/05\\/2021","Almo\\u00e7o;26\\/05\\/2021","Almo\\u00e7o;25\\/05\\/2021","Jantar;24\\/05\\/2021","Almo\\u00e7o;24\\/05\\/2021"]}}'),
(62, '3a64aa80ad71908262524f59bd1c2a0d', '{"hash":"3a64aa80ad71908262524f59bd1c2a0d","0":{"timestamp":1622319379,"values":[]}}'),
(63, 'ab50a0320b40d5dcbf152fbd9f1fefa8', '{"hash":"ab50a0320b40d5dcbf152fbd9f1fefa8","0":{"timestamp":1622319379,"values":[]}}'),
(64, '72c4dae6d6a7a886f4fad988af78075b', '{"hash":"72c4dae6d6a7a886f4fad988af78075b","0":{"timestamp":1622343712712,"values":["Jantar;30\\/05\\/2021","Almo\\u00e7o;30\\/05\\/2021"]}}'),
(65, '24fcf63756a2ac1aa6bb9f982d304c13', '{"hash":"24fcf63756a2ac1aa6bb9f982d304c13","0":{"timestamp":1622319379,"values":[]}}'),
(66, '9c66008ef15f09f46165ad1ef9f3a466', '{"hash":"9c66008ef15f09f46165ad1ef9f3a466","0":{"timestamp":1622319379,"values":[]}}'),
(67, 'd52124dde0608cf96da16f6343b22877', '{"hash":"d52124dde0608cf96da16f6343b22877","0":{"timestamp":1622319379,"values":[]}}'),
(68, '0ae57fee866ce968e3db58abf1135c8f', '{"hash":"0ae57fee866ce968e3db58abf1135c8f","0":{"timestamp":1622319379,"values":[]}}'),
(69, '39ccd48544a921c9aee1a7090af89be2', '{"hash":"39ccd48544a921c9aee1a7090af89be2","0":{"timestamp":1622319379,"values":[]}}'),
(70, 'cbe36c4fa68666ee676a0ba3639925dd', '{"hash":"cbe36c4fa68666ee676a0ba3639925dd","0":{"timestamp":1622319379,"values":[]}}'),
(71, '354ed4d05dc4416712e365c3b741ea39', '{"hash":"354ed4d05dc4416712e365c3b741ea39","0":{"timestamp":1622319379,"values":[]}}'),
(72, '8872cb91f917a432168d7b7bb7006b2b', '{"hash":"8872cb91f917a432168d7b7bb7006b2b","0":{"timestamp":1622319379,"values":[]}}'),
(73, 'b3ed6a13cc28b7fbd45e7146bf189581', '{"hash":"b3ed6a13cc28b7fbd45e7146bf189581","0":{"timestamp":1622319379,"values":[]}}'),
(74, '62a876ac09184d0ce7c4427e361501ad', '{"hash":"62a876ac09184d0ce7c4427e361501ad","0":{"timestamp":1622319379,"values":[]}}'),
(75, 'ae47523933158077d194962e9bdb30f0', '{"hash":"ae47523933158077d194962e9bdb30f0","0":{"timestamp":1622319379,"values":[]}}'),
(76, '81875ba42b0973843a2c93d8b65ee6d3', '{"hash":"81875ba42b0973843a2c93d8b65ee6d3","0":{"timestamp":1622319379,"values":[]}}'),
(77, '905651bc650e24b9ff1a8eab6ef99446', '{"hash":"905651bc650e24b9ff1a8eab6ef99446","0":{"timestamp":1622319379,"values":[]}}'),
(78, '0f3f2ecc1c16f87ac373a89bbce9cf11', '{"hash":"0f3f2ecc1c16f87ac373a89bbce9cf11","0":{"timestamp":1622319379,"values":[]}}'),
(79, 'b0cebee8106ed73e014bd2b4a4fef30c', '{"hash":"b0cebee8106ed73e014bd2b4a4fef30c","0":{"timestamp":1622343695320,"values":["Jantar;30\\/05\\/2021"]}}'),
(80, 'c90b51b036bd13be232627ab585ffb75', '{"hash":"c90b51b036bd13be232627ab585ffb75","0":{"timestamp":1622319379,"values":[]}}'),
(81, 'bb536911d277805100d3a74d11f39293', '{"hash":"bb536911d277805100d3a74d11f39293","0":{"timestamp":1622319379,"values":[]}}'),
(82, '36a89b5d89445a58747189de73aa3622', '{"hash":"36a89b5d89445a58747189de73aa3622","0":{"timestamp":1622319379,"values":[]}}'),
(83, '1a44594d6abcc4827e4fb7c30b668db7', '{"hash":"1a44594d6abcc4827e4fb7c30b668db7","0":{"timestamp":1622319379,"values":[]}}'),
(84, 'eb789c0125e3155688e6e196a993c966', '{"hash":"eb789c0125e3155688e6e196a993c966","0":{"timestamp":1622319379,"values":[]}}'),
(85, '6ffaaaabdfd79a818204542fbf5957a0', '{"hash":"6ffaaaabdfd79a818204542fbf5957a0","0":{"timestamp":1622319379,"values":[]}}'),
(86, '496bd176404c29f161dc89a68daae27b', '{"hash":"496bd176404c29f161dc89a68daae27b","0":{"timestamp":1622319379,"values":[]}}'),
(87, '743a13955cb0fbdc7a7a5a9cf944ca14', '{"hash":"743a13955cb0fbdc7a7a5a9cf944ca14","0":{"timestamp":1622319379,"values":[]}}'),
(88, 'a6e7f1ae29c0de6da5e0eb955b5a75e0', '{"hash":"a6e7f1ae29c0de6da5e0eb955b5a75e0","0":{"timestamp":1622319379,"values":[]}}'),
(89, '1074478ae0af050ed069b30eec036f9f', '{"hash":"1074478ae0af050ed069b30eec036f9f","0":{"timestamp":1622319379,"values":[]}}'),
(90, '528e2624646c5374b1b3b3d9bceff32e', '{"hash":"528e2624646c5374b1b3b3d9bceff32e","0":{"timestamp":1622319379,"values":[]}}'),
(91, '66e1b9af8878952222eb2977a61c9b38', '{"hash":"66e1b9af8878952222eb2977a61c9b38","0":{"timestamp":1622319379,"values":[]}}'),
(92, '13008ca1e4e28d993be1087390bc65b1', '{"hash":"13008ca1e4e28d993be1087390bc65b1","0":{"timestamp":1622319379,"values":[]}}'),
(93, 'f82314659d0e852eba7dc622e5586c44', '{"hash":"f82314659d0e852eba7dc622e5586c44","0":{"timestamp":1622319379,"values":[]}}'),
(94, '7652d0c0982c6da6c379a63d20290f2d', '{"hash":"7652d0c0982c6da6c379a63d20290f2d","0":{"timestamp":1622319379,"values":[]}}'),
(95, '7ba6a581f5d44c6281cad2c2e936e3b5', '{"hash":"7ba6a581f5d44c6281cad2c2e936e3b5","0":{"timestamp":1622319379,"values":[]}}'),
(96, '9a1a30e79c2f62021835284645d73b62', '{"hash":"9a1a30e79c2f62021835284645d73b62","0":{"timestamp":1622319379,"values":[]}}'),
(97, 'c4f5d7f424dcb96fdb3875b985af2328', '{"hash":"c4f5d7f424dcb96fdb3875b985af2328","0":{"timestamp":1622319379,"values":[]}}'),
(98, '2cfcc16392105a2c30d0d8e9bbb35a94', '{"hash":"2cfcc16392105a2c30d0d8e9bbb35a94","0":{"timestamp":1622319379,"values":[]}}'),
(99, '3faf65afb258d3f82f6f74ecfc72590b', '{"hash":"3faf65afb258d3f82f6f74ecfc72590b","0":{"timestamp":1622319379,"values":[]}}'),
(100, 'fd47c623fab0ec3de103e3d6f83f18e8', '{"hash":"fd47c623fab0ec3de103e3d6f83f18e8","0":{"timestamp":1622319379,"values":[]}}'),
(101, 'eff4c82b17974cef70fcf3376f8ea02f', '{"hash":"eff4c82b17974cef70fcf3376f8ea02f","0":{"timestamp":1622319379,"values":[]}}'),
(102, '1f40ba03b1108752eedccd2cf3ad4627', '{"hash":"1f40ba03b1108752eedccd2cf3ad4627","0":{"timestamp":1622343554824,"values":["Almo\\u00e7o;29\\/05\\/2021"]}}'),
(103, 'cb58f7aecbb3823a52bd6847bce3fd1c', '{"hash":"cb58f7aecbb3823a52bd6847bce3fd1c","0":{"timestamp":1622319379,"values":[]}}'),
(104, '3d708ba7a7cba51e3d4f8b293f754704', '{"hash":"3d708ba7a7cba51e3d4f8b293f754704","0":{"timestamp":1622319379,"values":[]}}'),
(105, '25c7591670a8a25ba39a4539dfe23f56', '{"hash":"25c7591670a8a25ba39a4539dfe23f56","0":{"timestamp":1622319379,"values":[]}}'),
(106, 'a10c5cc105090790ee69a03d98845250', '{"hash":"a10c5cc105090790ee69a03d98845250","0":{"timestamp":1622319379,"values":[]}}'),
(107, 'b677ed0f9edfb2dd1b1df3c57f19e83d', '{"hash":"b677ed0f9edfb2dd1b1df3c57f19e83d","0":{"timestamp":1622319379,"values":[]}}'),
(108, '84892ebf340f7785bc67b0c9e755223b', '{"hash":"84892ebf340f7785bc67b0c9e755223b","0":{"timestamp":1622319379,"values":[]}}'),
(109, '7d45076dab86bb5ff35df60a2c9a75e6', '{"hash":"7d45076dab86bb5ff35df60a2c9a75e6","0":{"timestamp":1622319379,"values":[]}}'),
(110, '212ae3c0fc020e28be7a1d5333cccecb', '{"hash":"212ae3c0fc020e28be7a1d5333cccecb","0":{"timestamp":1622319379,"values":[]}}'),
(111, 'd0964224048cc0e9a42eff081f0a0755', '{"hash":"d0964224048cc0e9a42eff081f0a0755","0":{"timestamp":1622319379,"values":[]}}'),
(112, 'cdb0560166e912ac3e5cea15c157e739', '{"hash":"cdb0560166e912ac3e5cea15c157e739","0":{"timestamp":1622319379,"values":[]}}'),
(113, '978b7e8139a3d14bbab9f9f16379a735', '{"hash":"978b7e8139a3d14bbab9f9f16379a735","0":{"timestamp":1622319379,"values":[]}}'),
(114, 'f929542ba703fe45d4b0728b11a8cf6e', '{"hash":"f929542ba703fe45d4b0728b11a8cf6e","0":{"timestamp":1622319379,"values":[]}}'),
(115, '1cf2e09d2faaa59af2cbab8f27ad8902', '{"hash":"1cf2e09d2faaa59af2cbab8f27ad8902","0":{"timestamp":1622319379,"values":[]}}'),
(116, 'b38ac58c82eeab8d074ad8d5733c70a2', '{"hash":"b38ac58c82eeab8d074ad8d5733c70a2","0":{"timestamp":1622319379,"values":[]}}'),
(117, 'bf8b695cffd6632c6ca27a8dfca315f0', '{"hash":"bf8b695cffd6632c6ca27a8dfca315f0","0":{"timestamp":1622319379,"values":[]}}'),
(118, 'b9e0b11f1e9feb8757ce70caef8b4c49', '{"hash":"b9e0b11f1e9feb8757ce70caef8b4c49","0":{"timestamp":1622343720853,"values":["Caf\\u00e9;30\\/05\\/2021"]}}'),
(119, '2312bffa5536e8cf0bb8534e1a6d3aaf', '{"hash":"2312bffa5536e8cf0bb8534e1a6d3aaf","0":{"timestamp":1622319379,"values":[]}}'),
(120, '2169cf45add184cfd9f7644235715cdb', '{"hash":"2169cf45add184cfd9f7644235715cdb","0":{"timestamp":1622343719999,"values":["Jantar;30\\/05\\/2021","Caf\\u00e9;30\\/05\\/2021"]}}'),
(121, '359d25e3817a1f18d4c84f03706e2b8e', '{"hash":"359d25e3817a1f18d4c84f03706e2b8e","0":{"timestamp":1622319379,"values":[]}}'),
(122, '6094d9427dd7a635c0e77b68656a7a57', '{"hash":"6094d9427dd7a635c0e77b68656a7a57","0":{"timestamp":1622319379,"values":[]}}'),
(123, 'cd59eb772995df7e1164ccd6e63e80f6', '{"hash":"cd59eb772995df7e1164ccd6e63e80f6","0":{"timestamp":1622319379,"values":[]}}'),
(124, '14395ba7d93d1b6f5f01539985d57b37', '{"hash":"14395ba7d93d1b6f5f01539985d57b37","0":{"timestamp":1622319379,"values":[]}}'),
(125, '03d0274d01e6b8617be0710a0db47110', '{"hash":"03d0274d01e6b8617be0710a0db47110","0":{"timestamp":1622319379,"values":[]}}'),
(126, 'cbd96ebd4f4da238c5f28645c2a1dd52', '{"hash":"cbd96ebd4f4da238c5f28645c2a1dd52","0":{"timestamp":1622319379,"values":[]}}'),
(127, 'bdf7df327b89cc433079c3b7f786e06a', '{"hash":"bdf7df327b89cc433079c3b7f786e06a","0":{"timestamp":1622319379,"values":[]}}'),
(128, 'ebd2b0f56a9da27de4c6fcd9a17de063', '{"hash":"ebd2b0f56a9da27de4c6fcd9a17de063","0":{"timestamp":1622319379,"values":[]}}'),
(129, 'a3c88acbab5720fddbbce3af7591e9b5', '{"hash":"a3c88acbab5720fddbbce3af7591e9b5","0":{"timestamp":1622319379,"values":[]}}'),
(130, '086163ddfb2d9eb1cacded5b163324e9', '{"hash":"086163ddfb2d9eb1cacded5b163324e9","0":{"timestamp":1622319379,"values":[]}}'),
(131, '165637cf665847990d0619a9de5e81b4', '{"hash":"165637cf665847990d0619a9de5e81b4","0":{"timestamp":1622319379,"values":[]}}'),
(132, 'a2ecca292da4ffbebe5edf5f4614616f', '{"hash":"a2ecca292da4ffbebe5edf5f4614616f","0":{"timestamp":1622319379,"values":[]}}'),
(133, '37eda42b023ca84ffbd97dac64535175', '{"hash":"37eda42b023ca84ffbd97dac64535175","0":{"timestamp":1622319379,"values":[]}}'),
(134, '1d556f1fd61091c93ab9bd288ef1658e', '{"hash":"1d556f1fd61091c93ab9bd288ef1658e","0":{"timestamp":1622319379,"values":[]}}'),
(135, 'd56ec37edc23db54ab984d4f4f07d207', '{"hash":"d56ec37edc23db54ab984d4f4f07d207","0":{"timestamp":1622319379,"values":[]}}'),
(136, 'd1f80360de26a18bd9d86e2523f96d80', '{"hash":"d1f80360de26a18bd9d86e2523f96d80","0":{"timestamp":1622319379,"values":[]}}'),
(137, '8745647c81d39af92db12e13bdd7d5ea', '{"hash":"8745647c81d39af92db12e13bdd7d5ea","0":{"timestamp":1622319379,"values":[]}}'),
(138, '860870ab72107b4e0b5316445d2e6e50', '{"hash":"860870ab72107b4e0b5316445d2e6e50","0":{"timestamp":1622319379,"values":[]}}'),
(139, 'ac68babf562e72285dfd25a094f253af', '{"hash":"ac68babf562e72285dfd25a094f253af","0":{"timestamp":1622319379,"values":[]}}'),
(140, '33e7d1877669934e595fbb17e4df8da9', '{"hash":"33e7d1877669934e595fbb17e4df8da9","0":{"timestamp":1622319379,"values":[]}}'),
(141, 'c1e1c72bbbf343fb2fbe99487b88ac05', '{"hash":"c1e1c72bbbf343fb2fbe99487b88ac05","0":{"timestamp":1622319379,"values":[]}}'),
(142, '53e2801b1709bfdb05b166460e0936ab', '{"hash":"53e2801b1709bfdb05b166460e0936ab","0":{"timestamp":1622319379,"values":[]}}'),
(143, '9a748767a2c87c665654b57007d10bee', '{"hash":"9a748767a2c87c665654b57007d10bee","0":{"timestamp":1622319379,"values":[]}}'),
(144, 'eb9b14b7c027e4d0625a788811fd6043', '{"hash":"eb9b14b7c027e4d0625a788811fd6043","0":{"timestamp":1622319379,"values":[]}}'),
(145, '21732ce4c12e3801300ada97caa9f320', '{"hash":"21732ce4c12e3801300ada97caa9f320","0":{"timestamp":1622319379,"values":[]}}'),
(146, '81cf9439993fdd185b309af252ac1ab6', '{"hash":"81cf9439993fdd185b309af252ac1ab6","0":{"timestamp":1622319379,"values":[]}}'),
(147, '2f51948f5ab5e77c50c69d1e64ac7d0c', '{"hash":"2f51948f5ab5e77c50c69d1e64ac7d0c","0":{"timestamp":1622319379,"values":[]}}'),
(148, 'f400e110d292102914d475b1769c6bb2', '{"hash":"f400e110d292102914d475b1769c6bb2","0":{"timestamp":1622319379,"values":[]}}'),
(149, '44ecc452a502248c0bdd72018bc769a4', '{"hash":"44ecc452a502248c0bdd72018bc769a4","0":{"timestamp":1622319379,"values":[]}}'),
(150, '52f7f5bdbd8ab945d706d4473e51678c', '{"hash":"52f7f5bdbd8ab945d706d4473e51678c","0":{"timestamp":1622319379,"values":[]}}'),
(151, 'f229a2e47e05fdb47bf9b43fb164c4cd', '{"hash":"f229a2e47e05fdb47bf9b43fb164c4cd","0":{"timestamp":1622319379,"values":[]}}'),
(152, 'a34e962e10470c04eedda939e9e012d8', '{"hash":"a34e962e10470c04eedda939e9e012d8","0":{"timestamp":1622319379,"values":[]}}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
