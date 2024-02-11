<?php


session_start();

function verifierDroitsAcces($pageAutorisee) {
    if(!isset($_SESSION['poste'])) {
        header('Location: login.php');
        exit();
    }

    $pagesAutorisees = [
        'GERANT' => ['pageGerantStats.php', 'pageGerantUsers.php', 'pageUsers.php'],
        'CAISSE' => ['pageCaissiereVoiture.php', 'pageUsers.php']
    ];

    $poste = $_SESSION['poste'];
    if(!in_array($pageAutorisee, $pagesAutorisees[$poste])) {
        header('Location: login.php');
        exit();
    }
}
?>
