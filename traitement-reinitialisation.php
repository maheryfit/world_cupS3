<?php
require('inc/ConnexionBase.php');
require('inc/Function.php');
$connexion = getConnection();

reinitialiser($connexion);
header("Location: index.php");

?>