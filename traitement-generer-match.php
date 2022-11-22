<?php
require('inc/ConnexionBase.php');
require('inc/Function.php');
$connexion = getConnection();

generateallmatchesandscore($connexion);
header("Location: index.php");

?>