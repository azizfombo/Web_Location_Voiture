<?php 
session_start();
require '../database.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //recuperer les infos du formulaires 
    $index = $_POST['index'];
    $Nomuser = $_POST['Nomuser'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $poste = $_POST['poste'];
    $password = $_POST['password'];
    $photo = file_get_contents($_FILES['photo']['tmp_name']);

    $conn = Database::connect();
    $stmt = $conn->prepare("UPDATE user SET Nomuser = :nom, email = :email, password = :password, telephone = :telephone, poste = :poste, photo = :photo WHERE iduser = $_SESSION['iduser']");
    $stmt->bindParam(':iduser',$index)
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':poste', $poste);
    $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: ../View/pageUsers.php?success=1");
        exit();
    } else {
        header("Location: ../View/pageUsers.php?error=1");
        exit();
    }

}
?>