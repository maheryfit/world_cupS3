<?php
require('inc/ConnexionBase.php');
require('inc/Function.php');
$connexion = getConnection();

reinitialisergagnantcoupe($connexion);
header("Location: index.php");

?>