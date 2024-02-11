<?php 
require_once 'inc/header.php';
require_once '../database.php';
require_once '../index.php';
verifierDroitsAcces('pageGerantStats');

$mois = [];
$recette = [];

$recetteMoisPrecedent=0;
$recetteAnneePrecedent=0;
$recetteJourPrecedent = 0;
$jourPrecedent="";
$moisPrecedent ="";
$AnneePrecedent="";
try{
    $connection = Database::connect();
    $stmt = $connection->prepare("SELECT 
    MONTHNAME(dateRecette) AS Mois,
    SUM(recette) AS Recette
FROM 
    stats
WHERE 
    dateRecette >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
    AND dateRecette <= CURDATE()
GROUP BY 
    YEAR(dateRecette), MONTH(dateRecette)
ORDER BY 
    YEAR(dateRecette) ASC, MONTH(dateRecette) ASC;

");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result){
        foreach($result as $res){
            array_push($mois,$res['Mois']);
            array_push($recette,$res['Recette']);
        }
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
        <?php
$labels = [];
$data = [];
$background = [];
$tabBackground = ['#FF6384', '#36A2EB', '#FFCE56', '#32CD32', '#8A2BE2'];

for ($i = 0; $i < count($mois); $i++) {
    array_push($labels, $mois[$i]);
    array_push($data, $recette[$i]);
    array_push($background, $tabBackground[$i]);
}

$chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'data' => $data,
            'backgroundColor' => $background
        ]
    ]
];
?>

var data = <?php echo json_encode($chartData); ?>;


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
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col">
                    <label for="datedebut" class="form-label">Date de début</label>
                    <input type="date" id="datedebut" class="form-control" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col">
                    <label for="datefin" class="form-label">Date de fin</label>
                    <input type="date" id="datefin" class="form-control" max="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
        </div>
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
    if (!isNaN(nombreCible)) {
        animerComptage(nombreCible); 
    } else {
        animerComptage(0);
    }

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