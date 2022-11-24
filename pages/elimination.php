<?php
  //header('Content-Type: text/html; charset=utf-8');
  require('../inc/ConnexionBase.php');
  require('../inc/Function.php');
  $connexion = getConnection();
  $groupeonly = getGroupe($connexion);
  $participantshuitieme = getparticipantshuitieme($connexion);
  $participantsquatrieme = getgagnantshuitieme($connexion);
  $participantsdemi = getgagnantsquatrieme($connexion);
  $participantsfinale = getgagnantsdemi($connexion);
  $troisieme = gettroisiemeplace($connexion);
  
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
        <header class="d-flex flex-wrap justify-content py-3 mb-4 border-bottom">
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
            <h2>Elimination</h2>
          </div>
        <!-- <div class="section-body">
            <a href="#"><button class="btn btn-primary">Generer Vainqueur</button></a>
            <a href="#"><button class="btn btn-danger">Reinitialiser Vainqueur</button></a>
          </div> -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="d-flex justify-content-around align-items-start flex-row flex-grow-1">
                <!-- Huitieme -->
                <div class="huitieme m-auto">
                <?php 
                    for ($i=0; $i < 4; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo getnomequipefromid($connexion, $participantshuitieme[$i]['idEquipe1'])[0]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                
                </div>
                
                <!-- Quart de finale -->
                <div class="quart m-auto">
                <?php 
                    for ($i=0; $i < 2; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo $participantsquatrieme[$i]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                </div>

                <!-- Demi-final -->
                <div class="demi-final m-auto">
                <?php 
                    for ($i=0; $i < 1; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo $participantsdemi[$i]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                </div>

                <!-- Last square -->
                <div class="last-square m-auto">
                    <!-- Finale -->
                    <div class="final m-auto">
                        <div class="team GROUPE-D"><?php echo $participantsfinale[0]['nomEquipe']; ?></div>
                    </div>
                    
                    <!-- 3e-place -->
                    <div class="third-place m-auto">
                        <div class="team GROUPE-C"><?php echo $troisieme[0]['nomEquipe']; ?></div>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-around align-items-end flex-row-reverse flex-grow-1">
                 <!-- Huitieme -->
                <div class="huitieme m-auto">
                <?php 
                    for ($i=4; $i < 8; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo getnomequipefromid($connexion, $participantshuitieme[$i]['idEquipe2'])[0]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                </div>
                
                <!-- Quart de finale -->
                <div class="quart m-auto">
                <?php 
                    for ($i=2; $i < 4; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo $participantsquatrieme[$i]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                </div>

                <!-- Demi-final -->
                <div class="demi-final m-auto">
                <?php 
                    for ($i=1; $i < 2; $i++) 
                    { 
                ?>
                    <div class="team GROUPE-A"><?php echo $participantsdemi[$i]['nomEquipe']; ?></div>
                <?php
                    }
                ?>
                </div>

                <!-- Last square -->
                <div class="last-square m-auto">
                    <!-- Finale -->
                    <div class="final m-auto">
                        <div class="team GROUPE-D"><?php echo $participantsfinale[1]['nomEquipe']; ?></div>
                    </div>
                    
                    <!-- 3e-place -->
                    <div class="third-place m-auto">
                        <div class="team GROUPE-C"><?php echo $troisieme[0]['nomEquipe']; ?></div>
                    </div>
                </div>
            </div>    
        </div>
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