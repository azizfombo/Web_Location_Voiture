<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['marque'], $_POST['immat'], $_POST['info'], $_POST['dispo'])){
    // Récupérer les données du formulaire
    $marque = $_POST['marque'];
    $immat = $_POST['immat'];
    $info = $_POST['info'];
    $dispo = $_POST['dispo'];
    $prixlocation = $_POST['prixlocation'];

    if (isset($_FILES['photos']['tmp_name']) && !empty($_FILES['photos']['tmp_name'])) {
        $photos = file_get_contents($_FILES['photos']['tmp_name']);
    } else {
        $photos = NULL;
    }

    $conn = Database::connect();
    $stmt = $conn->prepare("INSERT INTO vehicules (marque, immat, info , dispo, prixlocation, photos) VALUES (:marque, :immat,:info, :dispo, :prixlocation,:photos)");
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':immat', $immat);
    $stmt->bindParam(':info', $info);
    $stmt->bindParam(':dispo', $dispo);
    $stmt->bindParam(':photos', $photos, PDO::PARAM_LOB);
    $stmt->bindParam(':prixlocation', $prixlocation);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        header("Location: ../View/pageCaissiereVoiture.php?success=1");
        exit();
    } else {
        header("Location: ../View/pageCaissiereVoiture.php?error=1");
        exit();
    }
}
}
?>