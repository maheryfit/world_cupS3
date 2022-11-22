<?php 

function getGroupe ($connection) {
    //echo $bdd;
    $requete = $connection->query("SELECT * from groupe");
    $requete->setFetchMode(PDO::FETCH_OBJ);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();
    return $val;
}

function getequipesofgroupe($connection, $idgroupe)
{
    $sql = "SELECT * from groupe natural join equipe where idgroupe = '%s'";
    $sql = sprintf($sql, $idgroupe);

    $requete = $connection->query($sql);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();
    return $val;
}

function getstatequipeinamatch($scorequipe, $scoreadva)
{
    $stat = array();
    if ($scorequipe < $scoreadva) 
    {
        $stat['libele'] = "win";
        $stat['point'] = 0;
    }
    elseif ($scorequipe > $scoreadva) 
    {
        $stat['libele'] = "loose";
        $stat['point'] = 3;
    }
    elseif ($scorequipe == $scoreadva) 
    {
        $stat['libele'] = "draw";
        $stat['point'] = 1;
    }
    return $stat;
}

function getwinorloose($scorequipe, $scoreadva)
{
    if ($scorequipe < $scoreadva) 
    {
        return "loose";
    }
    elseif ($scorequipe > $scoreadva) 
    {
        return "win";
    }
    elseif ($scorequipe == $scoreadva) 
    {
        return "draw";
    }
}

function generateallmatchesandscore($connection)
{   
    //generation 48 matchs
    $compteurrencontre = 0;
    for ($i=0; $i < 8; $i++) { 
        $equipes = getequipesofgroupe($connection, $i+1); //1, 2, 3, 4
        $compteurarrangement = count($equipes); //4
        $min = 0;   
        for ($j=0; $j < count($equipes); $j++)
        {
            for ($k=2+ $min + ($compteurarrangement*$i); $k <= ($compteurarrangement*($i+1)); $k++) { 
                $idEquipe1 = $equipes[$j]['idEquipe']; //id
                $idEquipe2 = $k;    //compteur

                try {       
                    $sqlrencontre = "INSERT INTO Rencontre (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
                    $stmt = $connection->prepare($sqlrencontre);
                    $stmt->execute([$idEquipe1, $idEquipe2]);
                }
                catch(Exception $e) {       
                        echo 'Erreur : '.$e->getMessage().'<br />';        
                        echo 'N° : '.$e->getCode(); 
                }
                
                $compteurrencontre = $compteurrencontre + 1;
                // Score equipe 1
                $sqlscoreequipe1 = "INSERT INTO Score (idRencontre, idEquipe, val) VALUES (?,?,?)";
                $idRencontre = $compteurrencontre;
                $idEquipe = $idEquipe1;
                $val1 = rand(0, 7);
                $stmt = $connection->prepare($sqlscoreequipe1);
                $stmt->execute([$idRencontre, $idEquipe, $val1]);

                // Score equipe 2
                $sqlscoreequipe2 = "INSERT INTO Score (idRencontre, idEquipe, val) VALUES (?,?,?)";
                $idRencontre = $compteurrencontre;
                $idEquipe = $idEquipe2;
                $val2 = rand(0, 7);
                $stmt = $connection->prepare($sqlscoreequipe2);
                $stmt->execute([$idRencontre, $idEquipe, $val2]);
                
                //Stat equipe 1
                $sqlstatequipe1 = "INSERT INTO Stat (idRencontre, idEquipe, libele, pointCdm) VALUES (?,?,?,?)";
                $idRencontre = $compteurrencontre;
                $idEquipe = $idEquipe1;
                $statequipe1 = getstatequipeinamatch($val1, $val2);
                $pointCdm = $statequipe1['point'];
                $libele = $statequipe1['libele'];
                $stmt = $connection->prepare($sqlstatequipe1);
                $stmt->execute([$idRencontre, $idEquipe, $libele, $pointCdm]);

                //Stat equipe 2
                $sqlstatequipe2 = "INSERT INTO Stat (idRencontre, idEquipe, libele, pointCdm) VALUES (?,?,?,?)";
                $idRencontre = $compteurrencontre;
                $idEquipe = $idEquipe2;
                $statequipe2 = getstatequipeinamatch($val2, $val1);
                $pointCdm = $statequipe2['point'];
                $libele = $statequipe2['libele'];
                $stmt = $connection->prepare($sqlstatequipe2);
                $stmt->execute([$idRencontre, $idEquipe, $libele, $pointCdm]);
            }
            $min = $min + 1;
        } 
        echo "Matchs du groupe " . $i . "inserés";
    }
}

function getclassement ($connection, $idGroupe) {
    //echo $bdd;
    $sqlclassement = "SELECT * from v_classement where idGroupe = '%s' order by points desc";
    $sqlclassement = sprintf($sqlclassement, $idGroupe);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();
    return $val;
}

function getdetailsscoregroupe ($connection, $idGroupe) {
    //echo $bdd;
    $sqlclassement = "select * from rencontre join score on score.idRencontre = rencontre.idRencontre join equipe on equipe.idEquipe = score.idEquipe join groupe on groupe.idGroupe = equipe.idGroupe where equipe.idGroupe = '%s' order by rencontre.idRencontre";
    $sqlclassement = sprintf($sqlclassement, $idGroupe);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();
    return $val;
}

function reinitialiser($connection)
{

    $statementsdrop = [
        'drop table Stat;',
        'drop table Score;',
        'drop table Rencontre;'
    ];
    foreach ($statementsdrop as $statementsuppression) {
        $connection->exec($statementsuppression);
    }

    $statementscreate = [
        'CREATE TABLE IF NOT EXISTS Rencontre (
            idRencontre int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idRencontre)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
        'CREATE TABLE IF NOT EXISTS Score (
            idScore int NOT NULL AUTO_INCREMENT,
            idRencontre int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idRencontre) REFERENCES Rencontre(idRencontre),
            PRIMARY KEY (idScore)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
        'CREATE TABLE IF NOT EXISTS Stat (
            idStat int NOT NULL AUTO_INCREMENT,
            idRencontre int NOT NULL, 
            idEquipe int NOT NULL, 
            libele varchar(20),
            pointCdm int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idRencontre) REFERENCES Rencontre(idRencontre),
            PRIMARY KEY (idStat)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;'
        ];

    foreach ($statementscreate as $statementcreation) {
        $connection->exec($statementcreation);
    }
    
}
?>