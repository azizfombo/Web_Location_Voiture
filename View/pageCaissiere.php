<div class="container-fluid" style="background-image:url('images/voiture8.webp');background-repeat: no-repeat; background-size: cover; ">

<header class="fas fa-car fa-3x me-3 pt-5" style="color: #709085;">
        <h1>Rent & Drive</h1>  
        </header>
<?php 
require_once '../index.php';
verifierDroitsAcces('pageCaissiereVoiture.php');
require 'inc/header.php';
require '../database.php';

$cniclient=[];
$nomclient=[];
$telclient=[];
$typelocation=[];
$datedebut=[];
$duree = [];
$datefin = [];

try {
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM clients");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($res) {
        foreach ($res as $resultat) {
            array_push($cniclient, $resultat['cniclient']);
            array_push($nomclient, $resultat['nomclient']);
            array_push($telclient, $resultat['telclient']);
            array_push($typelocation, $resultat['typelocation']);
            array_push($datedebut, $resultat['datedebut']);
            array_push($duree, $resultat['duree']);
            array_push($datefin, $resultat['datefin']);
        }
    }

    $connection = Database::disconnect();
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
?>

<div class="container">
<?php
if ($_SESSION['poste'] == 'CAISSE') {
    require_once 'inc/navbarCaisse.php';
} else if ($_SESSION['poste'] == 'GERANT') {
    require_once 'inc/navbarGerant.php';
}
?>

<div class="row">
<table class="table">
  <thead>
    <tr>
      <th scope="col">CNI</th>
      <th scope="col">Nom</th>
      <th scope="col">Téléphone</th>
      <th scope="col">Type_Location</th>
      <th scope="col">Date_Debut</th>
      <th scope="col">Duree</th>
      <th scope="col">Date_Fin</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  
  <tbody>
  <?php
    for ($i = 0; $i < count($cniclient); $i++) {
  ?>
    <tr>
      <th scope="row" style="color:white;"><?php echo $cniclient[$i] ?></th>
      <td style="color:white;"><?php echo $nomclient[$i] ?></td>
      <td style="color:white;"><?php echo $telclient[$i] ?></td>
      <td style="color:white;"><?php echo $typelocation[$i] ?></td>
      <td style="color:white;"><?php echo $datedebut[$i] ?></td>
      <td style="color:white;"><?php echo $duree[$i] ?></td>
      <td style="color:white;"><?php echo $datefin[$i] ?></td>
      <td>
      <button type="button" class=" btn btn-danger" aria-label="Close" onclick="confirmDelete(<?php echo $cniclient[$i] ?>)">
        <i class="fas fa-trash-alt"></i>
      </button>
      </td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>    
</div>

</div>
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer ce clients ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
      </div>
    </div>
  </div>
</div>
<script>
  function confirmDelete(index) {
        console.log(index);
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Traitement/suppressionClient.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        window.location.reload();
                    } else {
                        alert("Une erreur s'est produite lors de la suppression du client.");
                    }
                }
            };
            xhr.send("index=" + index);
        });
    }
</script>

<?php require 'inc/footer.php' ?>
</div>