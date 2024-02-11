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

<div class="container mt-5">
        <header>
            <h1>Rent a Car</h1>
        </header>

        <?php 
            if($_SESSION['poste']=='CAISSE'){
                require_once 'inc/navbarCaisse.php';
            }else if($_SESSION['poste']=='GERANT'){
                require_once 'inc/navbarGerant.php';
            }
        
        ?>
        <section class="mt-4">
            <h2>MON PROFIL</h2>
        </section>
        <div class="row">
            <div class="col">
                <div class="card" style="width: 18rem;">
                <?php
                echo'<img class="card-img-top" style="height: 250px; object-fit:cover;" src="data:image/jpeg;base64,'.base64_encode($photoUser[0]).'" alt="Card image cap">';
                ?>
                    <div class="card-body">
                        <p class="card-text"><?php echo $_SESSION['Nomuser'] ?></p>
                        <p class="card-text"><?php echo $_SESSION['poste'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <form>
                    <div class="form-group">
                        <label for="nom">NOM</label>
                        <input type="text" class="form-control" id="nom"  placeholder="Enter Name" value="<?php echo $_SESSION['Nomuser'] ?>">    
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse Mail</label>
                        <input type="email" class="form-control" id="email"  placeholder="Enter email" value="<?php echo $_SESSION['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de Passe</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter email" value="<?php echo $_SESSION['password'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="tel" class="form-control" id="telephone" placeholder="Enter email" value="<?php echo $_SESSION['telephone'] ?>">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>
            </div>
        </div>
</div>        
<?php require 'inc/footer.php';?>