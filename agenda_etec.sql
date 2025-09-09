CREATE DATABASE agenda_etec;
USE agenda_etec;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `salas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_sala` varchar(255) NOT NULL,
  `capacidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sala` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_reserva` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'confirmada',
  PRIMARY KEY (`id`),
  KEY `id_sala` (`id_sala`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id`),
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Prof. Ana', 'ana.prof@etec.sp.gov.br', '12345', 'professor'),
('Aluno João', 'joao.aluno@etec.sp.gov.br', '12345', 'aluno');

INSERT INTO salas (nome_sala, capacidade) VALUES
('Laboratório de Informática 1', 32),
('Laboratório de Informática 2', 36),
('Oficina de Eletrônica', 20),
('Auditório', 120);

INSERT INTO reservas (id_sala, id_usuario, data_reserva, hora_inicio, hora_fim, status)
SELECT s.id, u.id, CURDATE(), '19:00:00', '20:30:00', 'confirmada'
FROM salas s, usuarios u
WHERE s.nome_sala='Auditório' AND u.email='ana.prof@etec.sp.gov.br'
LIMIT 1;