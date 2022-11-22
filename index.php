<?php
  //header('Content-Type: text/html; charset=utf-8');
  require('inc/ConnexionBase.php');
  require('inc/Function.php');
  $connexion = getConnection();
  $groupeonly = getGroupe($connexion);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="shortcut icon" href="world-cup.png" type="image/x-icon">
    <title>Coupe de monde</title>
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content py-3 mb-4 border-bottom">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4 title">Qatar 2022</span>
                
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">Match</a></li>
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">About</a></li>
            </ul>
        </header>
        <section id="service" class="services pt-0">
            <div class="container" data-aos="fade-up">
      
              <div class="section-header">
                <!-- <span>Stage Group</span> -->
                <h2>Stage Group</h2>
              </div>
              <div class="section-body">
                <a href="traitement-generer-match.php"><button>Generer matchs</button></a>
              </div>
      
              <div class="row gy-4">
                <?php
                  foreach ($groupeonly as $groupe) {
                ?>
                  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card">
                      <ul class="list-group list-group-flush">
                          <h3><a href="./pages/result.php?idGroupe=<?php echo $groupe->idGroupe;?>" class="stretched-link"><?php echo $groupe->nomGroupe; ?> </a></h3>
                          <?php 
                            $equipes = getequipesofgroupe($connexion, $groupe->idGroupe);
                            foreach ($equipes as $equipe) {
                          ?>
                            <li class="list-group-item"><?php echo $equipe['nomEquipe']; ?></li>
                          <?php
                            }
                          ?>
                      </ul>
                    </div>
                  </div><!-- End Card Item -->
                <?php
                  }
                ?>
              </div>
            </div>
          </section>
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