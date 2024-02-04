<?php 
require 'inc/header.php';
require '../database.php';
$postes = [];
$noms = [];
$telephones = [];
try{
  $connection = Database::connect();
  $stmt = $connection->prepare('select nomuser,telephone,poste from user');
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($result){
    foreach($result as $res){
      array_push($noms,$res['nomuser']);
      array_push($telephones,$res['telephone']);
      array_push($postes,$res['poste']);
    }
  }
}catch (PDOException $e) {
  echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rz0+5P5y1z5PNSq3jpEkL+CpRBE5Q0qr4GLVzLUuIuLOq54dbM4iNA4l6NGW64dd" crossorigin="anonymous">
    <title>Ma Page</title>
</head>

<body>
    <div class="container mt-5">
        <header>
            <h1>Mon Site Web</h1>
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
            <h2>Présentation</h2>
            <p>Bienvenue sur notre site web. Découvrez ce que nous avons à offrir.</p>
        </section>

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
                        <td><button type="button" class="close btn-danger" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <section class="mt-4">
            <h2>Ajouter un utilisateur</h2>
            <form action="../Traitement/ajoutUser.php" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" required>
                </div>
                <div class="mb-3">
                <label for="poste" class="form-label">Poste</label>
                  <input type="text" list="poste" id="poste" name="poste"/>
                  <datalist id="poste">
                      <option>GERANT</option>
                      <option>CAISSE</option>
                  </datalist>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </section>
    </div>


<?php require 'inc/footer.php' ?>