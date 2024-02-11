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
            header("Location: pageCaissiere.php");
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


<div class="container mt-5">
    <form method="POST">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            <small class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php require 'inc/footer.php' ?>
