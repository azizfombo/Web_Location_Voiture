<?php
require_once('../tcpdf/tcpdf.php');
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    for($i=0;$i<count($panier);$i++) {
        $content .= '<p>Voiture : ' . $panier[$i] . '   -   '.$prix[$i].' €</p>';
    }

    $pdf->writeHTML($content, true, false, true, false, '');

    $file_name = 'facture_location_' . date('YmdHis') . '.pdf';

    $pdf_dir = __DIR__ . '/../pdf/';


    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0755, true);
    }

    $file_path = $pdf_dir . $file_name;

    // Sauvegarder le document PDF sur le serveur
    $pdf->Output($file_path, 'F');

    // Envoyer le PDF par e-mail
    $to = 'fomboaziz@yahoo.com';
    $subject = 'Facture de location de voiture';
    $message = 'Veuillez trouver en pièce jointe la facture de location de voiture.';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'karim.bachirou.bk@gmail.com'; // Votre adresse e-mail Gmail
    $mail->Password = 'Arthemix237'; // Votre mot de passe Gmail
    $mail->setFrom('karim.bachirou.bk@gmail.com', 'Boyka Karim');
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Pièce jointe
    $file_path = '../pdf/' . $file_name;
    $mail->addAttachment($file_path, $file_name);

    if ($mail->send()) {
        echo 'E-mail envoyé avec succès !';
    } else {
        echo 'Erreur lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo;
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
} catch (Exception $ex) {
    echo 'Erreur lors de la génération du PDF : ' . $ex->getMessage();
}
?>
