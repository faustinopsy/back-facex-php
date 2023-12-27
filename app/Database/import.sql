CREATE DATABASE `pontozero`;
USE `pontozero`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(145) DEFAULT NULL,
  `registro` varchar(45) DEFAULT NULL,
  `email` varchar(245) DEFAULT NULL,
  `senha` text,
  PRIMARY KEY (`id`)
);
CREATE TABLE `presencas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `data_hora` datetime NOT NULL,
  `tipo` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `presencas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
);
 CREATE TABLE `faces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idusers` varchar(45) DEFAULT NULL,
  `faces` mediumtext,
  PRIMARY KEY (`id`)
);

