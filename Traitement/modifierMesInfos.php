<?php 
session_start();
require '../database.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //recuperer les infos du formulaires 
    $Nomuser = $_POST['Nomuser'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $conn = Database::connect();
    if(isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name'])) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
        $stmt = $conn->prepare("UPDATE user SET Nomuser = :nom, email = :email, password = :password, telephone = :telephone, photo = :photo WHERE iduser = :iduser");
        $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
    }else{
        $stmt = $conn->prepare("UPDATE user SET Nomuser = :nom, email = :email, password = :password, telephone = :telephone WHERE iduser = :iduser");
    }
    
    $stmt->bindParam(':iduser',$_SESSION['iduser']);
    $stmt->bindParam(':nom', $Nomuser);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: ../View/pageUsers.php?success=1");
          $_SESSION['Nomuser']=$Nomuser;
          $_SESSION['email']=$email;
          $_SESSION['password']=$password;
          $_SESSION['telephone']=$telephone;
          if(isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name'])) {
            $_SESSION['photo']=$photo;
          }
        exit();
    } else {
        header("Location: ../View/pageUsers.php?error=1");
        exit();
    }

}
?>