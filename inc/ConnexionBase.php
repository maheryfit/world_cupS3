<?php
        // $servername = "localhost";
        // $username = "username";
        // $password = "password";

        // try {
        // $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
        // // set the PDO error mode to exception
        // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
        // } catch(PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
        // }
?>

<?php 
        function getConnection() {
                $PARAM_hote='localhost'; // le chemin vers le serveur
                $PARAM_port='3306';
                $PARAM_nom_bd='coupedumonde'; // le nom de votre base de données
                $PARAM_utilisateur='coupedumonde'; // nom d'utilisateur pour se connecter
                $PARAM_mot_passe='coupedumonde'; // mot de passe de l'utilisateur pour se connecter
                try {       
                        $connexion = new PDO('mysql:host='.$PARAM_hote.';port = '. $PARAM_port .';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe); 
                        return $connexion;
                }
                catch(Exception $e) {       
                        echo 'Erreur : '.$e->getMessage().'<br />';        
                        echo 'N° : '.$e->getCode(); 
                }

        }
?>