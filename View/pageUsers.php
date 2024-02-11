<?php require 'inc/header.php';?>

<?php
session_start();
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