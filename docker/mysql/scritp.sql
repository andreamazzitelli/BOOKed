CREATE DATABASE PRENOTAZIONI;
USE PRENOTAZIONI;

CREATE TABLE Libri (
    ID int PRIMARY KEY AUTO_INCREMENT,
    NomeLibro varchar(255) NOT NULL,
    AutoreLibro varchar(255) NOT NULL,
    INDEX (NomeLibro),
    INDEX (AutoreLibro)

);

CREATE TABLE Prenotazioni (
    ID varchar(255) PRIMARY KEY,
    Email varchar(255) NOT NULL,
    DataRitiro DATE NOT NULL,
    NomeLuogoRitiro varchar(255) NOT NULL,
    IndirizzoRitiro varchar(255) NOT NULL,
    NomeLibro varchar(255) NOt NULL,
    AutoreLibro varchar(255) NOT NULL,
    Nome varchar(255) DEFAULT ' ',
    Cognome varchar(255) DEFAULT ' ',
    FOREIGN KEY (NomeLibro) REFERENCES Libri(NomeLibro),
    FOREIGN KEY (AutoreLibro) REFERENCES Libri(AutoreLibro)

);

CREATE TABLE Users(
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    username varchar(100),
    pass varchar(128)
);


GRANT ALL ON PRENOTAZIONI.* TO 'user'@'%';
FLUSH PRIVILEGES;
ALTER USER 'user'@'%' IDENTIFIED WITH mysql_native_password BY 'password';
FLUSH PRIVILEGES;

INSERT INTO Users(username, pass) VALUES ('admin', '591d731183b86e5cfd7b851133fcc033c530bdc56d612ad006aae239dfd3530324cbd4761a9a1e97dc854d257df8997c1792256f3d54fd4949c8489ae65df952');

INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('Divina Commedia', 'Dante ');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('Iliade', 'Omero');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('Odissea', 'Omero');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('Eneide', 'Virgilio');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('La Fattoria degli Animali', 'Orwell');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('1984', 'Orwell');
INSERT INTO Libri(NomeLibro, AutoreLibro) VALUES ('Decameron', 'Boccaccio');
