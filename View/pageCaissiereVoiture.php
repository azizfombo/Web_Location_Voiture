<?php require 'inc/header.php'; ?>

<?php
$immat=[];
$marque=[];
$info=[];
$kilometrage=[];
$prixlocation=[];
$dispo = [];
$photos = [];


require '../database.php';
    try{
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM vehicules") ;
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($res){
        foreach($res as $resultat){
            array_push($immat,$resultat['immat']);
            array_push($marque,$resultat['marque']);
            array_push($info,$resultat['info']);
            array_push($kilometrage,$resultat['kilometrage']);
            array_push($dispo,$resultat['dispo']);
            array_push($prixlocation,$resultat['prixlocation']);
            array_push($photos,$resultat['photos']);
        }
    }

    $connection = Database::disconnect();
}catch (PDOException $e) {
  echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
?>

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
<body>
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
            <?php
            // Boucle pour afficher les cartes
            for ($i = 0; $i < count($immat); $i++) {
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                      <?php
                        echo'<img class="card-img-top" src="data:image/jpg;base64,'.$photos[$i].'" alt="Card image cap">';
                      ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $marque[$i] . '  (' . $immat[$i] . ')'; ?></h5>
                            <p class="card-text"><?php echo $info[$i] ?></p>
                            <div class="row">
                                <div class="col-4 mb-2 text-center">
                                    <?php if ($dispo[$i] == 'OUI') : ?>
                                        <a href="#" class="btn btn-secondary disabled d-flex align-items-center justify-content-center">Sold Out</a>
                                    <?php else : ?>
                                        <a href="#" class="btn btn-success d-flex align-items-center justify-content-center">Sell</a>
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
      </div> 
</body>

<?php require 'inc/footer.php'; ?>
