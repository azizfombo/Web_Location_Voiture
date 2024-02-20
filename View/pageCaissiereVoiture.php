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
                <h1>Liste des voitures</h1>
            </div>
            <div class="col-auto">
                <form class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
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
                                    <a href="#" class="btn btn-danger d-flex align-items-center justify-content-center">Delete</a>
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
        <!-- Bouton "Panier" -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#panierModal">
            Panier
        </button>

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
                        <form id="panierForm1">
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="payer()">Payer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <form id="panierForm">
                            <div class="form-group">
                                <label for="cni">CNI</label>
                                <input type="text" class="form-control" id="cni" name="cni">
                            </div>
                            <div class="form-group">
                                <label for="nomclient">Nom du client</label>
                                <input type="text" class="form-control" id="nomclient" name="nomclient">
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone">
                            </div>
                            <div class="form-group">
                                <label for="date_debut">Date de début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut">
                            </div>
                            <div class="form-group">
                                <label for="date_fin">Duree</label>
                                <input type="number" class="form-control" id="duree" name="duree">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="payer()">Payer</button>

                            <ul id="listePanier"></ul>

                        </form>


    <!-- Assurez-vous d'inclure jQuery avant votre script -->
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
            // Modifier le texte du bouton pour "Sold Out"
            this.innerText = "Sold Out";
            // Désactiver le bouton
            this.classList.add("disabled");
            // Empêcher les clics ultérieurs
            //this.removeEventListener('click');
            console.log(panier[1]);
        });
    <?php endfor; ?>


    function payer() {
    var cni = document.getElementById("cni").value;
    var nomclient = document.getElementById("nomclient").value;
    var telephone = document.getElementById("telephone").value;
    var date_debut = document.getElementById("date_debut").value;
    var duree = document.getElementById("duree").value;

    // Vérifier si panier et prix sont définis et non vides
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
            // Fermer la modal après l'insertion
            $('#panierModal').modal('hide');
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
    } else {
        console.error('Panier ou prix non défini ou vide.');
    }
}

var listeElements = document.getElementById("listePanier");

for (var i = 0; i < panier.length; i++) {
    var nouvelElementLi = document.createElement("li");
    nouvelElementLi.textContent = panier[i]+ "    -     "+ prix[i]+" €";
    var boutonSupprimer = document.createElement("button");
    boutonSupprimer.textContent = "Supprimer";
    boutonSupprimer.setAttribute("data-index", i);

    /// Evenement sur Bouton supprimer
    boutonSupprimer.addEventListener("click", function(event) {
        var index = event.target.getAttribute("data-index"); // Récupérer l'index de l'élément
        listeElements.removeChild(listeElements.childNodes[index]); // Supprimer l'élément li du DOM
        panier.splice(index, 1);
        prix.splice(index, 1);

        // on rend le bouton cliquable et sell
        <?php for ($j = 0; $j < count($immat); $j++) : ?>
        if (panier[index] == '<?php echo $immat[$j]; ?>') {
            var bouton = document.getElementById("sell_<?php echo $immat[$j]; ?>");
            bouton.innerText = "Sell";
            bouton.disabled = false;
        }
    <?php endfor; ?>
    });
    nouvelElement.appendChild(boutonSupprimer);
    listeElements.appendChild(nouvelElementLi);
}


</script>

<?php require 'inc/footer.php'; ?>