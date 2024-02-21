<?php require 'inc/header.php';?>

<?php
require_once '../index.php';
verifierDroitsAcces('pageUsers.php');
$idUser=[];
$nomUser=[];
$emailUser=[];
$pwdUser=[];
$posteUser=[];
$telUser=[];
$photoUser=[];

require '../database.php';
try{
    $connexion = Database::connect();
    $stmt = $connexion-> prepare("SELECT * FROM user");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($res){
        foreach($res as $resultat){
            array_push($idUser,$resultat['iduser']);
            array_push($nomUser,$resultat['Nomuser']);
            array_push($emailUser,$resultat['email']);
            array_push($pwdUser,$resultat['password']);
            array_push($posteUser,$resultat['poste']);
            array_push($telUser,$resultat['telephone']);
            array_push($photoUser,$resultat['photo']);
        }
    }
    $connect = Database::disconnect();
}catch(PDOException $e){
    echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
   
?>

<div class="container-fluid" style="background-image:url('images/voiture8.webp');background-repeat: no-repeat; background-size: cover; ">
        <header class="fas fa-car fa-3x me-3 pt-5" style="color: #709085;">
        <h1>Rent & Drive</h1>  
        </header>

        <?php 
            if($_SESSION['poste']=='CAISSE'){
                require_once 'inc/navbarCaisse.php';
            }else if($_SESSION['poste']=='GERANT'){
                require_once 'inc/navbarGerant.php';
            }
        ?>
        <section class="mt-4">
        <h2 style="color: #709085; font-size: 30px; font-family: 'Courier New', Courier, monospace; text-shadow: 2px 2px #709085;">MON PROFIL</h2>
            <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success" role="alert">Les données de l\'Utilisateur ont été modifiées avec succès!</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">Une erreur s\'est produite lors de la modification des informations de l\'utilisateur.</div>';
        }
        ?>
        </section>
        <div class="row">
            <div class="col">
                <div class="card" style="width: 18rem;">
                <?php
                echo'<img class="card-img-top" style="height: 250px; object-fit:cover; border-radius: 20px;" src="data:image/jpeg;base64,'.base64_encode($_SESSION['photo']).'" alt="Card image cap">';
                ?>
                    <div class="card-body">
                        <p class="card-text"><?php echo $_SESSION['Nomuser'] ?></p>
                        <p class="card-text"><?php echo $_SESSION['poste'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <form action="../Traitement/modifierMesInfos.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nom">NOM</label>
                        <input type="text" class="form-control" id="nom"  name="Nomuser" value="<?php echo $_SESSION['Nomuser'] ?>">    
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse Mail</label>
                        <input type="email" class="form-control" id="email"  name="email" value="<?php echo $_SESSION['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de Passe</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $_SESSION['password'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $_SESSION['telephone'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png, image/gif">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" style="background-color: #709085; border-color: #709085; color: white;">Modifier</button>
                </form>
            </div>
        </div>
        <br>    
</div>   
<br>

<?php require 'inc/footer.php';?>
