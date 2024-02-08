<?php 
require 'inc/header.php';
require '../database.php';

$recetteMoisPrecedent=0;
$recetteAnneePrecedent=0;
$recetteJourPrecedent = 0;
$jourPrecedent="";
$moisPrecedent ="";
$AnneePrecedent="";
try{
    $connection = Database::connect();
    //Mois précédent
    $stmt = $connection->prepare("SELECT SUM(recette) AS sommeRecette
FROM stats
WHERE dateRecette >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01')
  AND dateRecette < DATE_FORMAT(NOW(), '%Y-%m-01')
");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
      $recetteMoisPrecedent = $result['sommeRecette'];
    }

    //Année Précédente
    $stmt = $connection->prepare("SELECT SUM(recette) AS sommeRecette
    FROM stats
    WHERE dateRecette >= DATE_FORMAT(NOW() - INTERVAL 1 YEAR, '%Y-01-01')
      AND dateRecette < DATE_FORMAT(NOW(), '%Y-01-01')    
");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
        $recetteAnneePrecedent = $result['sommeRecette'];
    }
    //Jour Précédent
    $stmt = $connection->prepare("SELECT SUM(recette) AS sommeRecette
    FROM stats
    WHERE dateRecette = CURDATE() - INTERVAL 1 DAY");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
        $recetteJourPrecedent = $result['sommeRecette'];
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
        $recetteAnneePrecedent = $result['sommeRecette'];
    }
    //Les périodes
    $stmt = $connection->prepare("SELECT 
    YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)) AS AnneePrecedente,
    DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%M') AS MoisPrecedent,
    DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) AS JourPrecedent
  ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
            $AnneePrecedent = $result['AnneePrecedente'];
            $moisPrecedent = $result['MoisPrecedent'];
            $jourPrecedent = $result['JourPrecedent'];
    }
    $connection = Database::disconnect();
  }catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
  }


?>
<div class="container mt-5">
        <header>
            <h1>Rent a Car</h1>
        </header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                </ul>
            </div>
        </nav>

        <section class="mt-4">
            <h2>Statistiques (en Euros €)</h2>
        </section>
        <div class="row justify-content-center">
        <div class="col-md-6">
            <canvas id="diagramme"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Données pour le diagramme circulaire
        var data = {
            <?php
            echo "labels: ['".$AnneePrecedent."', '".$moisPrecedent."', '".$jourPrecedent."'],
            datasets: [{";
                echo"data: [".$recetteAnneePrecedent.", ".$recetteMoisPrecedent.",".$recetteJourPrecedent."],";
                echo"backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]";
            ?>
        };

        var options = {
            responsive: true,
        };

        var root = document.getElementById('diagramme').getContext('2d');
        var diagrame = new Chart(root, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
</script>


        <section class="mt-4">
            <h2>Recherche</h2>
        </section>
            <form action="POST">
                <div class="col-md-6">
                    <label for="datedebut" class="form-label">Date de début</label>
                    <input type="date" id="datedebut" class="form-control"  max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                    <label for="datefin" class="form-label">Date de fin</label>
                    <input type="date" id="datefin" class="form-control "  max="<?php echo date('Y-m-d'); ?>">
                </div>
            </form>



  <h1 id="nombreDynamique" class="display-4">0</h1>
  <button onclick="submitFormAndAnimate();">Rechercher</button>
<script>
  var dureeAnimation = 1000; // en millisecondes

// Fonction pour soumettre le formulaire et démarrer l'animation
function submitFormAndAnimate() {
var input1Value = document.getElementById("datedebut").value;
var input2Value = document.getElementById("datefin").value;

var formData = new FormData();
formData.append('datedebut', input1Value);
formData.append('datefin', input2Value);

var options = {
  method: 'POST',
  body: formData
};

fetch('rechercherStats.php', options)
  .then(response => response.text())
  .then(data => {
    var nombreCible = parseInt(data);
    
    animerComptage(nombreCible); // Démarrer l'animation
  })
  .catch(error => console.error('Erreur:', error));

}

function animerComptage(nombreCible) {
  var nombreActuel = 0;
  var increment = nombreCible / (dureeAnimation / 50); // 50 étapes par seconde

  var interval = setInterval(function() {
    nombreActuel += increment;
    if (nombreActuel >= nombreCible) {
      nombreActuel = nombreCible;
      clearInterval(interval); // Arrêtez l'interval une fois que le nombre cible est atteint
    }
    document.getElementById('nombreDynamique').innerText = Math.round(nombreActuel);
  }, 50); 
}


</script>




</div>



<?php require 'inc/footer.php' ?>