<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $poste = $_POST['poste'];
    $password = $_POST['password'];

    $photo = file_get_contents($_FILES['photo']['tmp_name']);

    $conn = Database::connect();
    $stmt = $conn->prepare("INSERT INTO user (nomuser, email, password , telephone, poste, photo) VALUES (:nom, :email,:password, :telephone, :poste,:photo)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':poste', $poste);
    $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        header("Location: ../View/pageGerantUsers.php?success=1");
        exit();
    } else {
        header("Location: ../View/pageGerantUsers.php?error=1");
        exit();
    }
}
?>
