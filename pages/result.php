<?php
  //header('Content-Type: text/html; charset=utf-8');
  require('../inc/ConnexionBase.php');
  require('../inc/Function.php');
  $idGroupe = $_GET['idGroupe'];
  $connexion = getConnection();
  $groupeonly = getGroupe($connexion);
  $classement = getclassement($connexion, $idGroupe);
  $detailsscoregroupe = getdetailsscoregroupe($connexion, $idGroupe);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../world-cup.png" type="image/x-icon">
    <title>Coupe du monde</title>
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="../index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4 title">Qatar 2022</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">Match</a></li>
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">About</a></li>
            </ul>
        </header>
        
        <div class="section-header">
            <!-- <span>Result for GROUP A</span> -->
            <h2>Result for <?= $classement[0]['nomGroupe']?></h2>
        </div>

        <!-- liste classement -->
        <div class="d-flex justify-content-center align-items-center text-center">
            <table class="section-body">
                <tr>
                    <th>Team</th>
                    <th>Points</th>
                </tr>
                <?php foreach($classement as $ligneclassement) { ?>
                    <tr>
                      <td><?= $ligneclassement['nomEquipe']?></td>
                      <td><?= $ligneclassement['points']?></td>  
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- details -->
        <?php
            for ($i=0; $i < count($detailsscoregroupe); $i=$i+2) 
            { 
            ?>
            <div class="d-flex row text-center justify-content-center py-3 mb-5 align-items-center">
            
                <div class="col-md-1 team"><?php echo $detailsscoregroupe[$i]['nomEquipe']; ?> </div>
                <div class="col-md-1 score <?php echo getwinorloose($detailsscoregroupe[$i]['val'], $detailsscoregroupe[$i+1]['val']); ?>"><?php echo $detailsscoregroupe[$i]['val']; ?> </div>
                <div class="col-md-1 separator">-</div>
                <div class="col-md-1 score <?php echo getwinorloose($detailsscoregroupe[$i+1]['val'], $detailsscoregroupe[$i]['val']); ?>"><?php echo $detailsscoregroupe[$i+1]['val']; ?></div>
                <div class="col-md-1 team"><?php echo $detailsscoregroupe[$i+1]['nomEquipe']; ?> </div>
            </div>
        <?php
            }
        ?>
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md mb-0 text-muted">&copy; 2022 ITU ETU1821 - ETU1919 - ETU1381</p>
            <ul class="nav col-md-4 mb-0 justify-content-end">
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Match</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
            </ul>
        </footer>
    </div>
</body>
</html>