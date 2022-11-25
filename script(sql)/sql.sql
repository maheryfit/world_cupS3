--CREATE USER coupedumonde@localhost IDENTIFIED BY 'coupedumonde';  
--CREATE DATABASE IF NOT EXISTS coupedumonde;
--GRANT ALL PRIVILEGES ON coupedumonde.* to coupedumonde@localhost;
--use coupedumonde;

-- drop table Stat;
-- drop table Score;
-- drop table Rencontre;
-- drop table scoretroisieme;
-- drop table Troisieme;
-- drop table ScoreQuatrieme;
-- drop table scoreFinale;
-- drop table Finale;
-- drop table scoredemi;
-- drop table Demi;
-- drop table scorehuitieme;
-- drop table huitieme;
-- drop table Quatrieme;
-- drop table Equipe;
-- drop table Groupe;

-- truncate table Groupe;
-- truncate table Equipe;
-- truncate table Rencontre;
-- truncate table Score;
-- truncate table Stat;
-- truncate table Huitieme;


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

create or replace view v_classement as select groupe.nomGroupe, groupe.idGroupe, equipe.nomEquipe, stat.idEquipe, sum(pointCdm) as points from stat join equipe on equipe.idEquipe = stat.idEquipe join groupe on groupe.idGroupe = equipe.idGroupe group by stat.idEquipe;

select * from rencontre join score on score.idRencontre = rencontre.idRencontre join equipe on equipe.idEquipe = score.idEquipe join groupe on groupe.idGroupe = equipe.idGroupe where equipe.idGroupe = 1 order by rencontre.idRencontre;

CREATE TABLE IF NOT EXISTS Huitieme (
  idHuitieme int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idHuitieme)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Scorehuitieme (
  idScorehuitieme int NOT NULL AUTO_INCREMENT,
  idHuitieme int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idHuitieme) REFERENCES Huitieme(idHuitieme),
  PRIMARY KEY (idScorehuitieme)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Quatrieme (
  idQuatrieme int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idQuatrieme)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Scorequatrieme (
  idScorequatrieme int NOT NULL AUTO_INCREMENT,
  idQuatrieme int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idQuatrieme) REFERENCES Quatrieme(idQuatrieme),
  PRIMARY KEY (idScorequatrieme)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Demi (
  idDemi int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idDemi)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS ScoreDemi (
  idScoredemi int NOT NULL AUTO_INCREMENT,
  idDemi int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idDemi) REFERENCES Demi(idDemi),
  PRIMARY KEY (idScoredemi)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Finale (
  idFinale int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idFinale)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS ScoreFinale (
  idScorefinale int NOT NULL AUTO_INCREMENT,
  idFinale int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idFinale) REFERENCES Finale(idFinale),
  PRIMARY KEY (idScorefinale)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Troisieme (
  idTroisieme int NOT NULL AUTO_INCREMENT,
  idEquipe1 int NOT NULL, 
  idEquipe2 int NOT NULL, 
  dateRencontre Date, 
  FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
  PRIMARY KEY (idTroisieme)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS ScoreTroisieme (
  idScorefinale int NOT NULL AUTO_INCREMENT,
  idTroisieme int NOT NULL, 
  idEquipe int NOT NULL, 
  val int,
  FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
  FOREIGN KEY (idTroisieme) REFERENCES Troisieme(idTroisieme),
  PRIMARY KEY (idScorefinale)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- CREATE TABLE IF NOT EXISTS Stathuitieme (
--   idStathuitieme int NOT NULL AUTO_INCREMENT,
--   idHuitieme int NOT NULL, 
--   idEquipe int NOT NULL, 
--   libele varchar(20),
--   pointCdm int,
--   FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
--   FOREIGN KEY (idHuitieme) REFERENCES Huitieme(idHuitieme),
--   PRIMARY KEY (idStathuitieme)
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

