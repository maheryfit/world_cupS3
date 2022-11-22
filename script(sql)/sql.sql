--CREATE USER coupedumonde@localhost IDENTIFIED BY 'coupedumonde';  
--CREATE DATABASE IF NOT EXISTS coupedumonde;
--GRANT ALL PRIVILEGES ON coupedumonde.* to coupedumonde@localhost;
--use coupedumonde;

-- drop table Stat;
-- drop table Score;
-- drop table Rencontre;
-- drop table Equipe;
-- drop table Groupe;

CREATE TABLE IF NOT EXISTS Groupe (
  idGroupe int NOT NULL AUTO_INCREMENT, 
  nomGroupe varchar(40) NOT NULL,
  PRIMARY KEY (idGroupe)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS Equipe (
  idEquipe int NOT NULL AUTO_INCREMENT,
  idGroupe int NOT NULL,
  nomEquipe varchar(40) NOT NULL,
  PRIMARY KEY (idEquipe),
  FOREIGN KEY (idGroupe) REFERENCES Groupe(idGroupe)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS Rencontre (
  idRencontre int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idRencontre)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Score (
  idScore int NOT NULL AUTO_INCREMENT,
  idRencontre int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idRencontre) REFERENCES Rencontre(idRencontre),
  PRIMARY KEY (idScore)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Stat (
  idStat int NOT NULL AUTO_INCREMENT,
  idRencontre int NOT NULL, 
  idEquipe int NOT NULL, 
  libele varchar(20),
  pointCdm int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idRencontre) REFERENCES Rencontre(idRencontre),
  PRIMARY KEY (idStat)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Groupe VALUES('', 'GROUPE A');
INSERT INTO Groupe VALUES('', 'GROUPE B');
INSERT INTO Groupe VALUES('', 'GROUPE C');
INSERT INTO Groupe VALUES('', 'GROUPE D');
INSERT INTO Groupe VALUES('', 'GROUPE E');
INSERT INTO Groupe VALUES('', 'GROUPE F');
INSERT INTO Groupe VALUES('', 'GROUPE G');
INSERT INTO Groupe VALUES('', 'GROUPE H');

INSERT INTO Equipe VALUES('',1,'Qatar');
INSERT INTO Equipe VALUES('',1,'Equateur');
INSERT INTO Equipe VALUES('',1,'Senegal');
INSERT INTO Equipe VALUES('',1,'Pays-bas');
INSERT INTO Equipe VALUES('',2,'Angleterre');
INSERT INTO Equipe VALUES('',2,'Iran');
INSERT INTO Equipe VALUES('',2,'USA');
INSERT INTO Equipe VALUES('',2,'Pays de Galles');
INSERT INTO Equipe VALUES('',3,'Argentine');
INSERT INTO Equipe VALUES('',3,'Arabie Saoudite');
INSERT INTO Equipe VALUES('',3,'Mexique');
INSERT INTO Equipe VALUES('',3,'Pologne');
INSERT INTO Equipe VALUES('',4,'France');
INSERT INTO Equipe VALUES('',4,'Australie');
INSERT INTO Equipe VALUES('',4,'Danemark');
INSERT INTO Equipe VALUES('',4,'Tunisie');
INSERT INTO Equipe VALUES('',5,'Espagne');
INSERT INTO Equipe VALUES('',5,'Costa Rica');
INSERT INTO Equipe VALUES('',5,'Allemagne');
INSERT INTO Equipe VALUES('',5,'Japon');
INSERT INTO Equipe VALUES('',6,'Belgique');
INSERT INTO Equipe VALUES('',6,'Canada');
INSERT INTO Equipe VALUES('',6,'Maroc');
INSERT INTO Equipe VALUES('',6,'Croatie');
INSERT INTO Equipe VALUES('',7,'Bresil');
INSERT INTO Equipe VALUES('',7,'Serbie');
INSERT INTO Equipe VALUES('',7,'Suisse');
INSERT INTO Equipe VALUES('',7,'Cameroon');
INSERT INTO Equipe VALUES('',8,'Portugal');
INSERT INTO Equipe VALUES('',8,'Ghana');
INSERT INTO Equipe VALUES('',8,'Uruguay');
INSERT INTO Equipe VALUES('',8,'Coree du Sud');
