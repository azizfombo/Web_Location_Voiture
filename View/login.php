<?php
require 'inc/header.php';
require '../database.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    try{
        $connection = Database::connect();
        $stmt = $connection->prepare('select * from user where email =:email and password=:password ');
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':password',$password);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          $_SESSION['iduser']=$result['iduser'];
          $_SESSION['Nomuser']=$result['Nomuser'];
          $_SESSION['email']=$result['email'];
          $_SESSION['password']=$result['password'];
          $_SESSION['poste']=$result['poste'];
          $_SESSION['telephone']=$result['telephone'];
          $_SESSION['photo']=$result['photo'];
          if($result['poste'] == 'CAISSE'){
            header("Location: pageUsers.php");
          }else if($result['poste'] == 'GERANT'){
            header("Location: pageUsers.php");
          }
      } else {
        echo '<div class="alert alert-danger" role="alert">Identifiants incorrects. Veuillez réessayer.</div>';
      }
        
      $stmt->closeCursor();
      $connection = Database::disconnect();
    }catch (PDOException $e) {
      echo '<div class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
    }
  } 
?>


<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-4 text-black" style="background-color:#CD7F32">

        <div class="px-5 ms-xl-4">
          <i class="fas fa-car fa-4x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
          <span class="h1 fw-bold mb-0">Rent & Drive</span>
        </div>

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

          <form method ="POST" style="width: 23rem;">

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

            <div class="form-outline mb-4">
              <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="xxxxx@hotmail.fr" required>
              <label class="form-label" for="email">Email address</label>
            </div>

            <div class="form-outline mb-4">
              <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="******" required>
              <label class="form-label" for="password">Password</label>
            </div>

            <div class="pt-1 mb-4">
              <button class="btn btn-info btn-lg btn-block" type="submit">Login</button>
            </div>

            <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Mot de passe oublié ?</a></p>

          </form>

        </div>

      </div>
      <div class="col-sm-8 px-0 d-none d-sm-block">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="images/voiture5.webp" class="d-block w-100 vh-100" alt="Login image" style="object-fit: cover; object-position: left;">
            </div>
            <div class="carousel-item">
              <img src="images/voiture6.webp" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
              <img src="images/voiture7.webp" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
              <img src="images/voiture8.webp" class="d-block w-100" alt="...">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php require 'inc/footer.php' ?>
