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

function getfirstandsecondofgroup($connection, $idGroupe)
{
    $sqlclassement = "SELECT * from v_classement where idGroupe = '%s' order by points desc limit 2";
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

function genererhuitiemeandscore($connection)
{
    $compteurrencontre = 0;
    $participantshuitiemes = array();
    //section A
    for ($i=1; $i <= 8; $i=$i+2) { 
        $participantshuitiemes[] = getfirstandsecondofgroup($connection, $i)[0];
        $participantshuitiemes[] = getfirstandsecondofgroup($connection, $i+1)[1];
    }

    //section B
    for ($i=1; $i <= 8; $i=$i+2) { 
        $participantshuitiemes[] = getfirstandsecondofgroup($connection, $i+1)[0];
        $participantshuitiemes[] = getfirstandsecondofgroup($connection, $i)[1];
    }

    for ($i=0; $i < 16; $i=$i+2) 
    { 
        try {       
            $sqlhuitieme = "INSERT INTO Huitieme (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
            $stmt = $connection->prepare($sqlhuitieme);
            $stmt->execute([$participantshuitiemes[$i]['idEquipe'], $participantshuitiemes[$i+1]['idEquipe']]);
        }
        catch(Exception $e) {       
                echo 'Erreur : '.$e->getMessage().'<br />';        
                echo 'N° : '.$e->getCode(); 
        }

        $compteurrencontre = $compteurrencontre + 1;
        // Score equipe 1
        try
        {
            $sqlscoreequipe1 = "INSERT INTO Scorehuitieme (idHuitieme, idEquipe, val) VALUES (?,?,?)";
            $idHuitieme = $compteurrencontre;
            $idEquipe = $participantshuitiemes[$i]['idEquipe'];
            $val1 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe1);
            $stmt->execute([$idHuitieme, $idEquipe, $val1]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }

        // Score equipe 2
        try
        {
            $sqlscoreequipe2 = "INSERT INTO Scorehuitieme (idHuitieme, idEquipe, val) VALUES (?,?,?)";
            $idHuitieme = $compteurrencontre;
            $idEquipe = $participantshuitiemes[$i+1]['idEquipe'];
            $val2 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe2);
            $stmt->execute([$idHuitieme, $idEquipe, $val2]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }
    }
   
}

function getgagnantshuitieme($connection)
{
    $sqlclassement = "SELECT * from scorehuitieme join equipe on scorehuitieme.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    $gagnantshuitieme = array();
    for ($i=0; $i < count($val); $i=$i+2) 
    { 
        if ($val[$i]['val'] < $val[$i+1]['val']) 
        {
            $gagnantshuitieme[] = $val[$i+1];
        }
        elseif ($val[$i]['val'] > $val[$i+1]['val']) 
        {
            $gagnantshuitieme[] = $val[$i];
        }
        elseif ($val[$i]['val'] == $val[$i+1]['val']) 
        {
            $gagnantshuitieme[] = $val[$i];
        }
    }
    return $gagnantshuitieme;
}

function genererquatriemeandscore($connection)
{
    $compteurrencontre = 0;
    //section A
    
    $participantsquatrieme = getgagnantshuitieme($connection);

    for ($i=0; $i < 8; $i=$i+2) 
    { 
        try {       
            $sqlquatrieme = "INSERT INTO Quatrieme (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
            $stmt = $connection->prepare($sqlquatrieme);
            $stmt->execute([$participantsquatrieme[$i]['idEquipe'], $participantsquatrieme[$i+1]['idEquipe']]);
        }
        catch(Exception $e) {       
                echo 'Erreur : '.$e->getMessage().'<br />';        
                echo 'N° : '.$e->getCode(); 
        }

        $compteurrencontre = $compteurrencontre + 1;
        // Score equipe 1
        try
        {
            $sqlscoreequipe1 = "INSERT INTO Scorequatrieme (idQuatrieme, idEquipe, val) VALUES (?,?,?)";
            $idQuatrieme = $compteurrencontre;
            $idEquipe = $participantsquatrieme[$i]['idEquipe'];
            $val1 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe1);
            $stmt->execute([$idQuatrieme, $idEquipe, $val1]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }

        // Score equipe 2
        try
        {
            $sqlscoreequipe2 = "INSERT INTO Scorequatrieme (idQuatrieme, idEquipe, val) VALUES (?,?,?)";
            $idQuatrieme = $compteurrencontre;
            $idEquipe = $participantsquatrieme[$i+1]['idEquipe'];
            $val2 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe2);
            $stmt->execute([$idQuatrieme, $idEquipe, $val2]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }
    }

}

function getgagnantsquatrieme($connection)
{
    $sqlclassement = "SELECT * from scorequatrieme join equipe on scorequatrieme.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    $gagnantsquatrieme = array();
    for ($i=0; $i < count($val); $i=$i+2) 
    { 
        if ($val[$i]['val'] < $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i+1];
        }
        elseif ($val[$i]['val'] > $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i];
        }
        elseif ($val[$i]['val'] == $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i];
        }
    }
    return $gagnantsquatrieme;
}

function genererdemiandscore($connection)
{
    $compteurrencontre = 0;
    //section A
    
    $participantsdemi = getgagnantsquatrieme($connection);

    for ($i=0; $i < 4; $i=$i+2) 
    { 
        try {       
            $sqldemi = "INSERT INTO Demi (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
            $stmt = $connection->prepare($sqldemi);
            $stmt->execute([$participantsdemi[$i]['idEquipe'], $participantsdemi[$i+1]['idEquipe']]);
        }
        catch(Exception $e) {       
                echo 'Erreur : '.$e->getMessage().'<br />';        
                echo 'N° : '.$e->getCode(); 
        }

        $compteurrencontre = $compteurrencontre + 1;
        // Score equipe 1
        try
        {
            $sqlscoreequipe1 = "INSERT INTO Scoredemi (idDemi, idEquipe, val) VALUES (?,?,?)";
            $idDemi = $compteurrencontre;
            $idEquipe = $participantsdemi[$i]['idEquipe'];
            $val1 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe1);
            $stmt->execute([$idDemi, $idEquipe, $val1]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }

        // Score equipe 2
        try
        {
            $sqlscoreequipe2 = "INSERT INTO Scoredemi (idDemi, idEquipe, val) VALUES (?,?,?)";
            $idDemi = $compteurrencontre;
            $idEquipe = $participantsdemi[$i+1]['idEquipe'];
            $val2 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe2);
            $stmt->execute([$idDemi, $idEquipe, $val2]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }
    }

}

function getgagnantsdemi($connection)
{
    $sqlclassement = "SELECT * from scoredemi join equipe on scoredemi.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    $gagnantsquatrieme = array();
    for ($i=0; $i < count($val); $i=$i+2) 
    { 
        if ($val[$i]['val'] < $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i+1];
        }
        elseif ($val[$i]['val'] > $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i];
        }
        elseif ($val[$i]['val'] == $val[$i+1]['val']) 
        {
            $gagnantsquatrieme[] = $val[$i];
        }
    }
    return $gagnantsquatrieme;
}

function genererfinaleandscore($connection)
{
    $compteurrencontre = 0;
    //section A
    
    $participantsfinale = getgagnantsdemi($connection);

    for ($i=0; $i < 2; $i=$i+2) 
    { 
        try {       
            $sqlfinale = "INSERT INTO Finale (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
            $stmt = $connection->prepare($sqlfinale);
            $stmt->execute([$participantsfinale[$i]['idEquipe'], $participantsfinale[$i+1]['idEquipe']]);
        }
        catch(Exception $e) {       
                echo 'Erreur : '.$e->getMessage().'<br />';        
                echo 'N° : '.$e->getCode(); 
        }

        $compteurrencontre = $compteurrencontre + 1;
        // Score equipe 1
        try
        {
            $sqlscoreequipe1 = "INSERT INTO Scorefinale (idFinale, idEquipe, val) VALUES (?,?,?)";
            $idFinale = $compteurrencontre;
            $idEquipe = $participantsfinale[$i]['idEquipe'];
            $val1 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe1);
            $stmt->execute([$idFinale, $idEquipe, $val1]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }

        // Score equipe 2
        try
        {
            $sqlscoreequipe2 = "INSERT INTO Scorefinale (idFinale, idEquipe, val) VALUES (?,?,?)";
            $idFinale = $compteurrencontre;
            $idEquipe = $participantsfinale[$i+1]['idEquipe'];
            $val2 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe2);
            $stmt->execute([$idFinale, $idEquipe, $val2]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }
    }

}

function getgagnantfinale($connection)
{
    $sqlclassement = "SELECT * from scorefinale join equipe on scorefinale.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    $resultatfinale = array();
    for ($i=0; $i < count($val); $i++) 
    { 
        if ($val[$i]['val'] < $val[$i+1]['val']) 
        {
            $resultatfinale[] = $val[$i+1];
        }
        elseif ($val[$i]['val'] > $val[$i+1]['val']) 
        {
            $resultatfinale[] = $val[$i];
        }
        elseif ($val[$i]['val'] == $val[$i+1]['val']) 
        {
            $resultatfinale[] = $val[$i];
        }
    }
    return $resultatfinale;
}

function getperdantsdemi($connection)
{
    $sqlclassement = "SELECT * from scoredemi join equipe on scoredemi.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    $perdantsdemi = array();
    for ($i=0; $i < count($val); $i=$i+2) 
    { 
        if ($val[$i]['val'] < $val[$i+1]['val']) 
        {
            $perdantsdemi[] = $val[$i];
        }
        elseif ($val[$i]['val'] > $val[$i+1]['val']) 
        {
            $perdantsdemi[] = $val[$i+1];
        }
        elseif ($val[$i]['val'] == $val[$i+1]['val']) 
        {
            $perdantsdemi[] = $val[$i];
        }
    }
    return $perdantsdemi;
}


function generertroisiemeandscore($connection)
{
    $compteurrencontre = 0;
    //section A
    
    $participantsfinale = getperdantsdemi($connection);

    for ($i=0; $i < 2; $i=$i+2) 
    { 
        try {       
            $sqlfinale = "INSERT INTO Troisieme (idEquipe1, idEquipe2, dateRencontre) VALUES (?,?,curDate())";
            $stmt = $connection->prepare($sqlfinale);
            $stmt->execute([$participantsfinale[$i]['idEquipe'], $participantsfinale[$i+1]['idEquipe']]);
        }
        catch(Exception $e) {       
                echo 'Erreur : '.$e->getMessage().'<br />';        
                echo 'N° : '.$e->getCode(); 
        }

        $compteurrencontre = $compteurrencontre + 1;
        // Score equipe 1
        try
        {
            $sqlscoreequipe1 = "INSERT INTO Scoretroisieme (idTroisieme, idEquipe, val) VALUES (?,?,?)";
            $idTroisieme = $compteurrencontre;
            $idEquipe = $participantsfinale[$i]['idEquipe'];
            $val1 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe1);
            $stmt->execute([$idTroisieme, $idEquipe, $val1]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }

        // Score equipe 2
        try
        {
            $sqlscoreequipe2 = "INSERT INTO Scorefinale (idTroisieme, idEquipe, val) VALUES (?,?,?)";
            $idTroisieme = $compteurrencontre;
            $idEquipe = $participantsfinale[$i+1]['idEquipe'];
            $val2 = rand(0, 7);
            $stmt = $connection->prepare($sqlscoreequipe2);
            $stmt->execute([$idTroisieme, $idEquipe, $val2]);
        }
        catch(Exception $e)
        {
            echo 'Erreur : '.$e->getMessage().'<br />';        
            echo 'N° : '.$e->getCode();
        }
    }

}


function getdeuxiemeplace($connection)
{
    
}


function gettroisiemeplace($connection)
{
    $sqlclassement = "SELECT * from scoretroisieme join equipe on scoretroisieme.idEquipe = equipe.idEquipe";
    $sqlclassement = sprintf($sqlclassement);
    //var_dump($sqlclassement);

    $requete = $connection->query($sqlclassement);
    $requete->setFetchMode(PDO::FETCH_ASSOC);
    $val = array();
    while($donne = $requete->fetch()){ 
        //echo $donne->categorie;
        $val[] = $donne;
    }
    $requete->closeCursor();

    // $resultatfinale = array();
    // for ($i=0; $i < count($val); $i++) 
    // { 
    //     if ($val[$i]['val'] < $val[$i+1]['val']) 
    //     {
    //         $resultatfinale[] = $val[$i+1];
    //     }
    //     elseif ($val[$i]['val'] > $val[$i+1]['val']) 
    //     {
    //         $resultatfinale[] = $val[$i];
    //     }
    //     elseif ($val[$i]['val'] == $val[$i+1]['val']) 
    //     {
    //         $resultatfinale[] = $val[$i];
    //     }
    // }

    $resultatfinale = $val;
    return $resultatfinale;
}

function generergagnantworldcup($connection)
{
    genererhuitiemeandscore($connection);
    genererquatriemeandscore($connection);
    genererdemiandscore($connection);
    genererfinaleandscore($connection);
    generertroisiemeandscore($connection);


}
function getnomequipefromid($connection, $id)
{
    //echo $bdd;
    $sqlclassement = "SELECT nomEquipe from equipe where idEquipe = '%s'";
    $sqlclassement = sprintf($sqlclassement, $id);
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

function getparticipantshuitieme($connection)
{
    //echo $bdd;
    $sqlclassement = "SELECT * from huitieme";
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

function reinitialisergagnantcoupe($connection)
{

    $statementsdrop = [
         'drop table scoretroisieme',
 'drop table Troisieme',
 'drop table ScoreQuatrieme',
 'drop table scoreFinale',
 'drop table Finale',
 'drop table scoredemi',
 'drop table Demi',
 'drop table scorehuitieme',
 'drop table huitieme'
    ];
    foreach ($statementsdrop as $statementsuppression) {
        $connection->exec($statementsuppression);
    }

    $statementscreate = [
        'CREATE TABLE IF NOT EXISTS Huitieme (
            idHuitieme int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idHuitieme)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Scorehuitieme (
            idScorehuitieme int NOT NULL AUTO_INCREMENT,
            idHuitieme int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idHuitieme) REFERENCES Huitieme(idHuitieme),
            PRIMARY KEY (idScorehuitieme)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Quatrieme (
            idQuatrieme int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idQuatrieme)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Scorequatrieme (
            idScorequatrieme int NOT NULL AUTO_INCREMENT,
            idQuatrieme int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idQuatrieme) REFERENCES Quatrieme(idQuatrieme),
            PRIMARY KEY (idScorequatrieme)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Demi (
            idDemi int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idDemi)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS ScoreDemi (
            idScoredemi int NOT NULL AUTO_INCREMENT,
            idDemi int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idDemi) REFERENCES Demi(idDemi),
            PRIMARY KEY (idScoredemi)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Finale (
            idFinale int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idFinale)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS ScoreFinale (
            idScorefinale int NOT NULL AUTO_INCREMENT,
            idFinale int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idFinale) REFERENCES Finale(idFinale),
            PRIMARY KEY (idScorefinale)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS Troisieme (
            idTroisieme int NOT NULL AUTO_INCREMENT,
            idEquipe1 int NOT NULL, 
            idEquipe2 int NOT NULL, 
            dateRencontre Date, 
            FOREIGN KEY (idEquipe1) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idEquipe2) REFERENCES Equipe(idEquipe),
            PRIMARY KEY (idTroisieme)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',
          
          'CREATE TABLE IF NOT EXISTS ScoreTroisieme (
            idScorefinale int NOT NULL AUTO_INCREMENT,
            idTroisieme int NOT NULL, 
            idEquipe int NOT NULL, 
            val int,
            FOREIGN KEY (idEquipe) REFERENCES Equipe(idEquipe),
            FOREIGN KEY (idTroisieme) REFERENCES Troisieme(idTroisieme),
            PRIMARY KEY (idScorefinale)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;'
        ];

    foreach ($statementscreate as $statementcreation) {
        $connection->exec($statementcreation);
    }
    
}


?>