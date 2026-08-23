-- Criação da base de dados e uso
CREATE DATABASE IF NOT EXISTS `pphp` DEFAULT CHARACTER SET utf8mb4;
USE `pphp`;

-- Tabela: utilizadores
DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE `utilizadores` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `apelido` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(9) NOT NULL,
  `palavra_passe` VARCHAR(255) NOT NULL,
  `tipo` VARCHAR(15) NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `utilizadores` (`id`, `nome`, `apelido`, `email`, `telefone`, `palavra_passe`, `tipo`) VALUES
(1, 'Francisco', 'Silva', 'user@admin.com', '999888333', '$2y$10$06CTBLZ70JUAe9clGBqwbOoc8s/IyLcHgCbZbub7wkrJZCJOt8l.y', 'admin');

-- Tabela: consultas
DROP TABLE IF EXISTS `consultas`;
CREATE TABLE `consultas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_utilizador` INT NOT NULL,
  `data` DATE NOT NULL,
  `conteudo` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilizador_idx` (`id_utilizador`),
  CONSTRAINT `fk_consultas_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `consultas` (`id`, `id_utilizador`, `data`, `conteudo`) VALUES
(1, 1, '2025-07-15', 'teste'),
(2, 1, '2025-07-26', 'Consulta alterando a data'),
(3, 1, '2025-07-31', 'Consulta sem alterar data.'),
(4, 1, '2025-01-12', 'teste 4');

-- Tabela: noticias
DROP TABLE IF EXISTS `noticias`;
CREATE TABLE `noticias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `conteudo` TEXT NOT NULL,
  `data` DATE NOT NULL,
  `autor` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `autor_idx` (`autor`),
  CONSTRAINT `fk_noticias_autor` FOREIGN KEY (`autor`) REFERENCES `utilizadores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `noticias` (`id`, `titulo`, `conteudo`, `data`, `autor`) VALUES
(3, 'HTML6 e CSS5 explicados', 'Como inauguramos em 2025, o lançamento antecipado de HTML6E a CSS5Inflamou a emoção e a curiosidade entre os desenvolvedores web em todo o mundo. Essas atualizações de ponta prometem elevar os padrões de desenvolvimento web, oferecendo funcionalidades aprimoradas, melhor acessibilidade e ferramentas robustas que atendem às necessidades de aplicativos da Web modernos.', '2025-07-27', 1),
(4, 'JavaScript', 'O termo "JavaScript novo" pode referir-se a diversas novidades na linguagem JavaScript. Isso inclui a evolução do padrão ECMAScript (como o ES6/ES2015 e versões mais recentes), novos runtimes como Deno e Bun, e novas funcionalidades propostas para o futuro da linguagem.', '2025-07-27', 1);

-- Tabela: projetos
DROP TABLE IF EXISTS `projetos`;
CREATE TABLE `projetos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` TEXT NOT NULL,
  `tecnologias` TEXT NOT NULL,
  `imagem` VARCHAR(255) NOT NULL,
  `tempo` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `projetos` (`id`, `nome`, `descricao`, `tecnologias`, `imagem`, `tempo`) VALUES
(6, 'Site da Banda Weezer', 'Site da Banda Weezer.', 'HTML e CSS', '1753571157.png', 1),
(7, 'Site da Banda Weezer', 'Site da Banda Weezer.', 'HTML, CSS, Bootstrap', '1753571124.png', 1),
(8, 'Site para Caso Prático JS', 'Site para Caso Prático JS.', 'HTML, CSS e JS.', '1753571194.png', 1),
(9, 'Projeto Final', 'Projeto Final do Curso de Web Development da Master D.', 'HTML, CSS, JS, PHP e SQL', '1753571264.png', 1);
