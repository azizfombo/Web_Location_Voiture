<?php
try {
    if(isset($_POST['cni'], $_POST['nomclient'], $_POST['telephone'], $_POST['date_debut'], $_POST['duree'], $_POST['panier'], $_POST['prix'])){
        $cni = $_POST['cni'];
        $nomclient = $_POST['nomclient'];
        $telephone = $_POST['telephone'];
        $date_debut = $_POST['date_debut'];
        $duree = $_POST['duree'];
        $panier = $_POST['panier'];
        $prix = $_POST['prix'];

        require_once '../database.php'; // Vérifiez le chemin vers votre fichier de connexion à la base de données

        $conn = Database::connect();

        $stmt = $conn->prepare("INSERT INTO clients (cniclient, nomclient, telclient, typelocation, datedebut, duree, datefin) VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(?, INTERVAL ? DAY))");

        $typelocation = ($duree > 10) ? 'LLD' : 'LCD';

        $stmt->bindParam(1, $cni);
        $stmt->bindParam(2, $nomclient);
        $stmt->bindParam(3, $telephone);
        $stmt->bindParam(4, $typelocation);
        $stmt->bindParam(5, $date_debut);
        $stmt->bindParam(6, $duree);
        $stmt->bindParam(7, $date_debut);
        $stmt->bindParam(8, $duree);
        $stmt->execute();

        foreach ($panier as $immatriculation) {
            $stmt = $conn->prepare("INSERT INTO panier (cniclient, immat) VALUES (?, ?)");
            $stmt->bindParam(1, $cni);
            $stmt->bindParam(2, $immatriculation);
            $stmt->execute();
        }

        foreach ($prix as $p) {
            $stmt = $conn->prepare("INSERT INTO stats (dateRecette, Recette) VALUES (?, ?) ON DUPLICATE KEY UPDATE Recette = Recette + ?");
            $stmt->bindParam(1, $date_debut);
            $stmt->bindParam(2, $duree * $p);
            $stmt->bindParam(3, $duree * $p);
            $stmt->execute();
        }

        echo "Clients ajoutés avec succès.";
    } else {
        echo "Toutes les données nécessaires n'ont pas été envoyées.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
}
?>
