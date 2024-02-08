<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['index'])) {
        $index = $_POST['index'];
        $poste = $_POST['statut'];
        require '../database.php';

        try {
            $connection = Database::connect();

            $stmt = $connection->prepare('UPDATE FROM user WHERE iduser = :id AND poste=:poste');
            $stmt->bindParam(':id', $index);
            $stmt->bindParam(':poste', $poste);
            $stmt->execute();
            http_response_code(200);
            exit(); 
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Erreur lors de la modification de l'utilisateur: " . $e->getMessage();
            exit();
        }
    } else {
        http_response_code(400);
        exit();
    }
} else {
    http_response_code(405);
    echo "Méthode non autorisée";
    exit();
}
?>
