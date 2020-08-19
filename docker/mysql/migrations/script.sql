CREATE DATABASE IF NOT EXISTS secture;

CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, team_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, position VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

INSERT INTO `team` (name) VALUES
('Athletic Club'),
('Atlético de Madrid'),
('FC Barcelona'),
('Real Betis Balompié'),
('RC Celta de Vigo'),
('RC Deportivo de La Coruña'),
('SD Éibar'),
('RCD Espanyol de Barcelona'),
('Getafe CF'),
('Granada CF'),
('UD Las Palmas'),
('Levante UD'),
('Málaga CF'),
('Rayo Vallecano de Madrid'),
('Real Madrid CF'),
('Real Sociedad de Fútbol'),
('Sevilla FC'),
('Real Sporting de Gijón'),
('Valencia CF'),
('Villarreal CF');