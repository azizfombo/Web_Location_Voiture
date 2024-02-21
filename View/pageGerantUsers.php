<div class="container-fluid" style="background-image:url('images/voiture8.webp');background-repeat: no-repeat; background-size: cover; ">

<?php 
require 'inc/header.php';
require '../database.php';
require_once '../index.php';
verifierDroitsAcces('pageGerantUsers.php');

$postes = [];
$noms = [];
$telephones = [];
$id = [];
try{
  $connection = Database::connect();
  $stmt = $connection->prepare('select iduser,nomuser,telephone,poste from user');
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($result){
    foreach($result as $res){
      array_push($id,$res['iduser']);
      array_push($noms,$res['nomuser']);
      array_push($telephones,$res['telephone']);
      array_push($postes,$res['poste']);
    }
  }
  $connection = Database::disconnect();
}catch (PDOException $e) {
  echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
?>


<div class="container">
        <header class="fas fa-car fa-3x me-3 pt-5" style="color: #709085;">
          <h1>Rent & Drive</h1>  
        </header>

        <?php require_once 'inc/navbarGerant.php';?>

        <section class="mt-4">
        <h2 style="color: #709085; font-size: 30px; font-family: 'Courier New', Courier, monospace; text-shadow: 2px 2px #709085;">UTILISATEURS</h2>
        </section>
        
        <!-- Modal pour la suppression -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer cet utilisateur ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal pour le changement de statut -->
<div class="modal fade" id="statut" tabindex="-1" aria-labelledby="statutLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statutLabel">Changement de poste</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Sélectionnez le nouveau poste :</p>
        <select id="selectStatut" class="form-select">
          <option value="CAISSE">CAISSE</option>
          <option value="GERANT">GERANT</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="confirmStatut">Modifier</button>
      </div>
    </div>
  </div>
</div>


<div class="row mt-4">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" style="color:white;">Nom</th>
                <th scope="col" style="color:white;" >Téléphone</th>
                <th scope="col" style="color:white;">Poste</th>
                <th scope="col" style="color:white;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i<count($noms);$i++){
                echo'
                <tr>
                    <th scope="row" style="color:white;">'.$noms[$i].'</th>
                    <td style="color:white;">'.$telephones[$i].'</td>
                    <td style="color:white;">'.$postes[$i].'</td>
                    <td>
                    <button type="button" style="color:white;" class="btn btn-success" onclick="editUser('.$id[$i].')">
                    <i class="fas fa-pencil-alt"></i>
                </button>                
                        <button type="button" class=" btn btn-danger" aria-label="Close" onclick="confirmDelete('.$id[$i].')">
                        <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

        <script>
            function editUser(index) {
        var confirmationModal = new bootstrap.Modal(document.getElementById('statut'));
        confirmationModal.show();
        document.getElementById('confirmStatut').addEventListener('click', function() {
            var selectedStatut = document.getElementById('selectStatut').value; // Valeur sélectionnée dans le select
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Traitement/changementStatut.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        window.location.reload(); // Actualiser la page après la modification réussie
                    } else {
                        alert("Une erreur s'est produite lors du changement de statut.");
                    }
                }
            };
            xhr.send("index=" + index + "&statut=" + selectedStatut);
        });
    }
    function confirmDelete(index) {
        console.log(index);
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Traitement/suppressionUser.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        window.location.reload();
                    } else {
                        alert("Une erreur s'est produite lors de la suppression de l'utilisateur.");
                    }
                }
            };
            xhr.send("index=" + index);
        });
    }
</script>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterUtilisateurModal">
  Ajouter un utilisateur
</button>

<div class="modal fade" id="ajouterUtilisateurModal" tabindex="-1" aria-labelledby="ajouterUtilisateurModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterUtilisateurModalLabel">Ajouter un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success" role="alert">Utilisateur ajouté avec succès!</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">Une erreur s\'est produite lors de l\'ajout de l\'utilisateur.</div>';
        }
        ?>
        <form action="../Traitement/ajoutUser.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control" id="telephone" name="telephone" required>
    </div>
    <div class="mb-3">
    <label for="poste" class="form-label">Poste</label>
    <select id="poste" name="poste" class="form-select">
        <option value="CAISSE">CAISSE</option>
        <option value="GERANT">GERANT</option>
    </select>
</div>

    <div class="mb-3">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png, image/gif" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

      </div>
    </div>
  </div>
</div>

    </div>


<?php require 'inc/footer.php' ?>
  
      </div>