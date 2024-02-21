<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['index'])) {
        $index = $_POST['index'];

        require '../database.php';

        try {
            $connection = Database::connect();          
            
            $stmt = $connection->prepare('DELETE FROM panier WHERE cniclient = ?');
            $stmt->bindParam(1, $index);
            $stmt->execute();
            http_response_code(200);

            $stmt = $connection->prepare('DELETE FROM clients WHERE cniclient = ?');
            $stmt->bindParam(1, $index);
            $stmt->execute();
            http_response_code(200);
            exit(); 
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Erreur lors de la suppression de l'utilisateur: " . $e->getMessage();
            exit();
        }
    } else {
        http_response_code(400);
        echo "Index de l'utilisateur à supprimer manquant";
        exit();
    }
} else {
    http_response_code(405);
    echo "Méthode non autorisée";
    exit();
}
?>
