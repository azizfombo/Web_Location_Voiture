<?php
require_once('../tcpdf/tcpdf.php');
try {
    if(isset($_POST['cni'], $_POST['nomclient'], $_POST['telephone'], $_POST['date_debut'], $_POST['duree'], $_POST['panier'], $_POST['prix'])){
        $cni = $_POST['cni'];
        $nomclient = $_POST['nomclient'];
        $telephone = $_POST['telephone'];
        $date_debut = $_POST['date_debut'];
        $duree = $_POST['duree'];
        $panier = json_decode($_POST['panier']);
        $prix = json_decode($_POST['prix']);

        require_once '../database.php'; // Vérifiez le chemin vers votre fichier de connexion à la base de données

        $conn = Database::connect();

        $stmt = $conn->prepare("INSERT INTO clients (cniclient, nomclient, telclient, typelocation, datedebut, duree, datefin) VALUES (?, ?, ?, ?, ?, ?,?)");


        $typelocation = ($duree > 10) ? 'LLD' : 'LCD';

        $stmt->bindParam(1, $cni);
        $stmt->bindParam(2, $nomclient);
        $stmt->bindParam(3, $telephone);
        $stmt->bindParam(4, $typelocation);
        $stmt->bindParam(5, $date_debut);
        $stmt->bindParam(6, $duree);
        $stmt->bindParam(7, $date_debut);
        //$stmt->bindParam(8, $duree);
        $stmt->execute();

        foreach ($panier as $immatriculation) {
            $stmt = $conn->prepare("INSERT INTO panier (cniclient, immat) VALUES (?, ?)");
            $stmt->bindValue(1, $cni);
            $stmt->bindValue(2, $immatriculation);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE vehicules SET dispo='NON' WHERE immat = ?");
            $stmt->bindValue(1, $immatriculation);
            $stmt->execute();
        }
        
        foreach ($prix as $p) {
            $stmt_check = $conn->prepare("SELECT dateRecette FROM stats WHERE dateRecette = ?");
            $stmt_check->bindValue(1, $date_debut);
            $stmt_check->execute();
            $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
            if ($row) {
                $stmt_update = $conn->prepare("UPDATE stats SET Recette = Recette + ? WHERE dateRecette = ?");
                $stmt_update->bindValue(1, $duree * $p);
                $stmt_update->bindValue(2, $date_debut);
                $stmt_update->execute();
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO stats (dateRecette, Recette) VALUES (?, ?)");
                $stmt_insert->bindValue(1, $date_debut);
                $stmt_insert->bindValue(2, $duree * $p);
                $stmt_insert->execute();
            }
        }
        
        
        

        echo "Clients ajoutés avec succès.";
    } else {
        echo "Toutes les données nécessaires n'ont pas été envoyées.";
    }
    // Création d'un nouveau document PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Ensemble des informations du document
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre nom');
    $pdf->SetTitle('Facture de location de voiture');
    $pdf->SetSubject('Facture de location de voiture');
    $pdf->SetKeywords('Facture, location, voiture');

    // Ajouter une page
    $pdf->AddPage();

    // Ajouter le contenu du PDF
    $content = '<h1>Facture de location de voiture</h1>';
    $content .= '<p><strong>Informations du client :</strong></p>';
    $content .= '<p>CNI : ' . $cni . '</p>';
    $content .= '<p>Nom du client : ' . $nomclient . '</p>';
    $content .= '<p>Téléphone : ' . $telephone . '</p>';
    $content .= '<p>Date de début : ' . $date_debut . '</p>';
    $content .= '<p>Durée : ' . $duree . ' jours</p>';
    $content .= '<p><strong>Éléments du panier :</strong></p>';
    foreach ($panier as $immat) {
        // Récupérer les détails de chaque voiture et les ajouter à la facture
        // (vous devez adapter ce code en fonction de votre modèle de données)
        $content .= '<p>Voiture : ' . $immat . '</p>';
    }

    // Ajouter le contenu au document
    $pdf->writeHTML($content, true, false, true, false, '');

    // Nom du fichier PDF
    $file_name = 'facture_location_' . date('YmdHis') . '.pdf';
 // Chemin complet vers le répertoire pdf
 $pdf_dir = __DIR__ . '/../pdf/';

 // Vérifier si le répertoire pdf existe, sinon le créer
 if (!is_dir($pdf_dir)) {
     mkdir($pdf_dir, 0755, true);
 }

 // Chemin complet vers le fichier PDF
 $file_path = $pdf_dir . $file_name;

 // Sauvegarder le document PDF sur le serveur
 $pdf->Output($file_path, 'F');

    // Envoyer le PDF par e-mail
    $to = 'emmanuelpondi07@gmail.com';
    $subject = 'Facture de location de voiture';
    $message = 'Veuillez trouver en pièce jointe la facture de location de voiture.';
    $headers = 'From: fomboaziz@yahoo.com' . "\r\n" .
               'Reply-To: fomboaziz@yahoo.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // Pièce jointe
    $file_path = '../pdf/' . $file_name;

    // Envoyer l'e-mail avec le PDF en pièce jointe
    if (file_exists($file_path)) {
        // Ajoutez le fichier en tant que pièce jointe
        $attachment = chunk_split(base64_encode(file_get_contents($file_path)));

        // En-têtes pour l'e-mail
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: multipart/mixed; boundary="mixedboundary"';
        $body = "--mixedboundary\r\n";
        $body .= "Content-Type: multipart/alternative; boundary=\"alternativeboundary\"\r\n\r\n";
        $body .= "--alternativeboundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n";
        $body .= "This is a multi-part message in MIME format.\r\n";
        $body .= "--alternativeboundary\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n\r\n";
        $body .= "--alternativeboundary--\r\n";
        $body .= "--mixedboundary\r\n";
        $body .= "Content-Type: application/pdf; name=\"" . $file_name . "\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment\r\n\r\n";
        $body .= $attachment . "\r\n\r\n";
        $body .= "--mixedboundary--";

        // Envoi de l'e-mail
        mail($to, $subject, $body, $headers);

        // Supprimer le fichier PDF après l'envoi par e-mail
        //unlink($file_path);
    } else {
        echo 'Le fichier PDF n\'existe pas.';
    }

} catch (PDOException $e) {
    echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
}catch (Exception $ex) {
    echo 'Erreur lors de la génération du PDF : ' . $ex->getMessage();
}
?>
