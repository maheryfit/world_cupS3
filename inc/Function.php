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

function getGroupeEquipe ($connection) {
    //echo $bdd;
    $requete = $connection->query("SELECT * from groupe natural join equipe");
    $requete->setFetchMode(PDO::FETCH_OBJ);
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
    for ($i=0; $i < 7; $i++) { 
        $equipes = getequipesofgroupe($connection, $i+1); //1, 2, 3, 4
        $compteurarrangement = count($equipes); //4
        $min = 0;
        $compteurrencontre = 0;

        for ($j=0; $j < count($equipes); $j++)
        {
            for ($k=2+ $min + ($compteurarrangement*$i); $k <= ($compteurarrangement*($i+1)); $k++) { 
                $idEquipe1 = $equipes[$j]['idEquipe'];
                $idEquipe2 = $k;

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

?>