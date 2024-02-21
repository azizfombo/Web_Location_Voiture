<div class="container-fluid" style="background-image:url('images/voiture8.webp');background-repeat: no-repeat; background-size: cover; ">

        <header class="fas fa-car fa-3x me-3 pt-5" style="color: #709085;">
        <h1>Rent & Drive</h1>  
        </header>
<?php
require 'inc/header.php';
require_once '../index.php';
verifierDroitsAcces('pageCaissiereVoiture.php');
$immat=[];
$marque=[];
$info=[];
$kilometrage=[];
$prixlocation=[];
$dispo = [];
$photos = [];


require '../database.php';
try {
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM vehicules");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($res) {
        foreach ($res as $resultat) {
            array_push($immat, $resultat['immat']);
            array_push($marque, $resultat['marque']);
            array_push($info, $resultat['info']);
            array_push($kilometrage, $resultat['kilometrage']);
            array_push($dispo, $resultat['dispo']);
            array_push($prixlocation, $resultat['prixlocation']);
            array_push($photos, $resultat['photos']);
        }
    }

    $connection = Database::disconnect();
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
?>

<?php
if ($_SESSION['poste'] == 'CAISSE') {
    require_once 'inc/navbarCaisse.php';
} else if ($_SESSION['poste'] == 'GERANT') {
    require_once 'inc/navbarGerant.php';
}
?>

    <div class="container">
        <div class="row d-flex justify-content-between">
            <div class="col-auto">
            <h2 style="color: #709085; font-size: 30px; font-family: 'Courier New', Courier, monospace; text-shadow: 2px 2px #709085;">NOS VEHICULES</h2>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#panierModal">
                    <i class="fas fa-shopping-cart fa-2x"></i>Panier
                </button>
            </div>
        </div>
        <br>
        <div class="row">
        <?php if ($_SESSION['poste'] == 'GERANT') : ?>
            <?php

            ///////  GERANT
            // Boucle pour afficher les cartes
            for ($i = 0; $i < count($immat); $i++) {
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <?php
                            echo '<img class="card-img-top" style="height: 250px; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($photos[$i]) . '" alt="Card image cap">';
                            ?>

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                <h5 class="card-title"><?php echo $marque[$i] . '  (' . $immat[$i] . ')'; ?></h5>
                                </div>
                                <div class="col-3">
                                <h6 class="card-title"><?php echo $prixlocation[$i].'€/J'; ?></h6>
                                </div>
                            </div>
                            <p class="card-text"><?php echo $info[$i] ?></p>
                            <div class="row">
                                <div class="col-4 mb-2 text-center">
                                    <?php if ($dispo[$i] == 'OUI') : ?>
                                        <a href="#" id="sell_<?php echo $immat[$i]; ?>" class="btn btn-success d-flex align-items-center justify-content-center">Sell</a>
                                    <?php else : ?>
                                        <a href="#" id="sell_<?php echo $immat[$i]; ?>" class="btn btn-secondary disabled d-flex align-items-center justify-content-center">Sold Out</a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-4 mb-2 text-center">
                                    <a href="#" class="btn btn-danger d-flex align-items-center justify-content-center" onclick="confirmDelete(<?php echo $immat[$i] ?>)">Delete</a>
                                </div>
                                <div class="col-4 mb-2 text-center ">
                                    <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center">Update</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
        }
        ?>
        <?php endif; ?>
        <?php if ($_SESSION['poste'] == 'CAISSE') : ?>
            <?php

            ///////  CAISSE
            // Boucle pour afficher les cartes
            for ($i = 0; $i < count($immat); $i++) {
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <?php
                            echo '<img class="card-img-top" style="height: 250px; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($photos[$i]) . '" alt="Card image cap">';
                            ?>

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                <h5 class="card-title"><?php echo $marque[$i] . '  (' . $immat[$i] . ')'; ?></h5>
                                </div>
                                <div class="col-3">
                                <h6 class="card-title"><?php echo $prixlocation[$i].'€/J'; ?></h6>
                                </div>
                            </div>
                            <p class="card-text"><?php echo $info[$i] ?></p>
                            <div class="row">
                                <div class="col-12 mb-2 text-center">
                                    <?php if ($dispo[$i] == 'OUI') : ?>
                                        <a href="#" id="sell_<?php echo $immat[$i]; ?>" class="btn btn-success d-flex align-items-center justify-content-center">Sell</a>
                                    <?php else : ?>
                                        <a href="#" id="sell_<?php echo $immat[$i]; ?>" class="btn btn-secondary disabled d-flex align-items-center justify-content-center">Sold Out</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
        }
        ?>

        <?php endif; ?>



      </div>
      <br>
    <?php if ($_SESSION['poste'] == 'GERANT') : ?>
      <div class="col-2 mb-2 text-center ">
        <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#ajouterVehiculeModal">Ajouter un Véhicule</a>
      </div> 
      <?php endif; ?>
      <div class="modal fade" id="ajouterVehiculeModal" tabindex="-1" aria-labelledby="ajouterVehiculeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajouterVehiculeModalLabel">Ajouter un vehicule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success" role="alert">Véhicule ajouté avec succès!</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">Une erreur s\'est produite lors de l\'ajout de l\'utilisateur.</div>';
        }
        ?>
        <form action="../Traitement/ajouterVehicule.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="marque" class="form-label">Nom De La Marque</label>
        <input type="text" class="form-control" id="marque" name="marque" required>
    </div>
    <div class="mb-3">
        <label for="immat" class="form-label">Entrer l'Immatriculation</label>
        <input type="text" class="form-control" id="immat" name="immat" required>
    </div>
    <div class="mb-3">
        <label for="info" class="form-label">Détails</label>
        <input type="text" class="form-control" id="info" name="info" required>
    </div>
    <div class="mb-3">
        <label for="prixlocation" class="form-label">Prix de location à la journée</label>
        <input type="text" class="form-control" id="prixlocation" name="prixlocation" required>
    </div>
    <div class="mb-3">
    <label for="dispo" class="form-label">Disponibilité</label>
    <select id="dispo" name="dispo" class="form-select">
        <option value="CAISSE">OUI</option>
        <option value="GERANT">NON</option>
    </select>
</div>

    <div class="mb-3">
        <label for="photos" class="form-label">Photos</label>
        <input type="file" class="form-control" id="photos" name="photos" accept="image/jpeg, image/png, image/gif" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

      </div>
    </div>
  </div>
</div>

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
        Êtes-vous sûr de vouloir supprimer ce vehicule ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
      </div>
    </div>
  </div>
</div>



        <!-- Modal pour les informations du panier -->
        <div class="modal fade" id="panierModal" tabindex="-1" role="dialog" aria-labelledby="panierModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="panierModalLabel">Informations du panier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulaire pour les informations du client -->
                        <form id="panierForm">
                            <div class="form-group">
                                <label for="cni">CNI</label>
                                <input type="text" class="form-control" id="cni" name="cni" required>
                            </div>
                            <div class="form-group">
                                <label for="nomclient">Nom du client</label>
                                <input type="text" class="form-control" id="nomclient" name="nomclient" required>
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                            </div>
                            <div class="form-group">
                                <label for="date_debut">Date de début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="date_fin">Duree</label>
                                <input type="number" class="form-control" id="duree" name="duree" required>
                            </div>
                            <h3> Liste des véhicules dans le panier</h3>
                            <ul id="listePanier"></ul>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="payer()">Payer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>

<script>
    var panier = [];
    var prix = [];
    function addToCart(immat) {
        panier.push(immat);
    }
    function addPrice(price){
      prix.push(price);
    }

    <?php for ($i = 0; $i < count($immat); $i++) : ?>
        document.getElementById('sell_<?php echo $immat[$i]; ?>').addEventListener('click', function() {

            addToCart('<?php echo $immat[$i]; ?>');
            addPrice('<?php echo $prixlocation[$i]; ?>')
            this.innerText = "Sold Out";
            this.classList.add("disabled");
            updatePanier(); 
            console.log(panier[1]);
        });
    <?php endfor; ?>

function updatePanier() {
    var listeElements = document.getElementById("listePanier");
    listeElements.innerHTML = ""; 

    for (var i = 0; i < panier.length; i++) {
        var nouvelElementLi = document.createElement("li");
        nouvelElementLi.textContent = panier[i] + "    -     " + prix[i] + " €";
        var boutonSupprimer = document.createElement("button");
        boutonSupprimer.textContent = "Supprimer";
        boutonSupprimer.setAttribute("data-index", i);

        boutonSupprimer.addEventListener("click", function (event) {
            var index = event.target.getAttribute("data-index"); 
            <?php for ($j = 0; $j < count($immat); $j++) : ?>
                if (panier[index] == '<?php echo $immat[$j]; ?>') {
                var bouton = document.getElementById("sell_<?php echo $immat[$j]; ?>");
                bouton.innerText = "Sell";
                bouton.classList.remove("disabled");
                }
            <?php endfor; ?>
            listeElements.removeChild(listeElements.childNodes[index]); 
            panier.splice(index, 1); 
            prix.splice(index, 1);
            updatePanier(); 
        });

        nouvelElementLi.appendChild(boutonSupprimer);
        listeElements.appendChild(nouvelElementLi);
    }
}

    function payer() {
    var cni = document.getElementById("cni").value;
    var nomclient = document.getElementById("nomclient").value;
    var telephone = document.getElementById("telephone").value;
    var date_debut = document.getElementById("date_debut").value;
    var duree = document.getElementById("duree").value;

    if (panier && prix && panier.length > 0 && prix.length > 0) {
        var data = new URLSearchParams();
        data.append('cni', cni);
        data.append('nomclient', nomclient);
        data.append('telephone', telephone);
        data.append('date_debut', date_debut);
        data.append('duree', duree);
        data.append('panier', JSON.stringify(panier));
        data.append('prix', JSON.stringify(prix));

        fetch('../Traitement/insererClient.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            $('#panierModal').modal('hide');
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
    } else {
        console.error('Panier ou prix non défini ou vide.');
    }
}




function confirmDelete(index) {
        console.log(index);
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Traitement/suppressionVehicule.php", true);
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

<?php require 'inc/footer.php'; ?>

</div>