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
        $stat['libele'] = "Resy";
        $stat['point'] = 0;
    }
    elseif ($scorequipe > $scoreadva) 
    {
        $stat['libele'] = "Nandresy";
        $stat['point'] = 3;
    }
    elseif ($scorequipe == $scoreadva) 
    {
        $stat['libele'] = "Sahala";
        $stat['point'] = 1;
    }
    return $stat;
}

function generateallmatchesandscore($connection)
{   
    //generation 48 matchs
    $compteurrencontre = 0;
    for ($i=0; $i < 7; $i++) { 
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
    var_dump($sqlclassement);

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
    $sqlclassement = "select * from rencontre join score on score.idRencontre = rencontre.idRencontre join equipe on equipe.idEquipe = score.idEquipe join groupe on groupe.idGroupe = equipe.idGroupe where equipe.idGroupe = '%s' order by rencontre.idRencontre;";
    $sqlclassement = sprintf($sqlclassement, $idGroupe);
    var_dump($sqlclassement);

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
?>