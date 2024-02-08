<?php
require '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datedebut = $_POST['datedebut'];
    $datefin = $_POST['datefin'];
    
    $connection = Database::connect();
    $stmt = $connection->prepare("SELECT SUM(recette) AS somme FROM stats WHERE dateRecette BETWEEN :datedebut AND :datefin");
    $stmt->bindParam(':datedebut', $datedebut);
    $stmt->bindParam(':datefin', $datefin);
    $stmt->execute();
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo $resultat['somme'];
}
?>
