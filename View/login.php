<?php
require 'inc/header.php';
require '../database.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    try{
        $connection = Database::connect();
        $stmt = $connection->prepare('SELECT * FROM user WHERE email = :email');
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          echo $result['password'];
          if (password_verify($password, $result['password'])) {
              $_SESSION['iduser']=$result['iduser'];
              $_SESSION['Nomuser']=$result['Nomuser'];
              $_SESSION['email']=$result['email'];
              $_SESSION['password']=$result['password'];
              $_SESSION['poste']=$result['poste'];
              $_SESSION['telephone']=$result['telephone'];
              $_SESSION['photo']=$result['photo'];
              if($result['poste'] == 'CAISSE'){
                header("Location: pageUsers.php");
                exit();
              }else if($result['poste'] == 'GERANT'){
                header("Location: pageUsers.php");
                exit();
              }
          }else {
            echo '<div class="alert alert-danger" role="alert">Mot de passe incorrect. Veuillez réessayer.</div>';
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
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php require 'inc/footer.php' ?>
