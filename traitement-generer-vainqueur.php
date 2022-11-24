<?php
require('inc/ConnexionBase.php');
require('inc/Function.php');
$connexion = getConnection();

genererhuitiemeandscore($connexion);
genererquatriemeandscore($connexion);
genererdemiandscore($connexion);
genererfinaleandscore($connexion);
generertroisiemeandscore($connexion);

header("Location: pages/elimination.php");

?>