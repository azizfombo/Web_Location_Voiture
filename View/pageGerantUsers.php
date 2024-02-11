<?php 
require 'inc/header.php';
require '../database.php';
require_once '../index.php';
verifierDroitsAcces('pageGerantUsers');

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
            <h2>Utilisateurs</h2>
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
                <th scope="col">Nom</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Poste</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i<count($noms);$i++){
                echo'
                <tr>
                    <th scope="row">'.$noms[$i].'</th>
                    <td>'.$telephones[$i].'</td>
                    <td>'.$postes[$i].'</td>
                    <td>
                    <button type="button" class="btn btn-success" onclick="editUser('.$id[$i].')">
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