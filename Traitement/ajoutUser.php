<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $poste = $_POST['poste'];

    $conn = Database::connect();
    $stmt = $conn->prepare("INSERT INTO user (nomuser, email, telephone, poste) VALUES (:nom, :email, :telephone, :poste)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':poste', $poste);
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
