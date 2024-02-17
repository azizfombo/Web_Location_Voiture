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
      <th scope="col">E-mail</th>
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
      <th scope="row"><?php echo $cniclient[$i] ?></th>
      <td><?php echo $nomclient[$i] ?></td>
      <td><?php echo $telclient[$i] ?></td>
      <td>emmanuel@3il.fr</td>
      <td><?php echo $typelocation[$i] ?></td>
      <td><?php echo $datedebut[$i] ?></td>
      <td><?php echo $duree[$i] ?></td>
      <td><?php echo $datefin[$i] ?></td>
      <td>
      <button type="button" class=" btn btn-danger" aria-label="Close" onclick="confirmDelete('.$cniclient[$i].')">
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

<?php require 'inc/footer.php' ?>