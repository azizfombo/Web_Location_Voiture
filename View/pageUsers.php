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
                        <p class="card-text"><?php echo $posteUser[0] ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">NOM</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="<?php $nomUser[0] ?>">
                        
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Adresse Mail</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mot de Passe</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Telephone</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Poste</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>
            </div>
        </div>
</div>        
<?php require 'inc/footer.php';?>