<?php

session_start();

include('../import/index.php');

if (isset($_POST['date'])) {
    $date = htmlspecialchars($_POST['date']) . " " . htmlspecialchars($_POST['heure']) . ":00";
    $date = strtotime($date);
} else {
    $date = strtotime(time());
}

if (isset($_POST['abonnement'])) {

    $reponse = $bdd->prepare('SELECT * FROM dev_spa_prix WHERE nom = ?');
    $reponse->execute(array(htmlspecialchars($_POST['abonnement'])));

    $donnees = $reponse->fetch();

    $expiration = $date + $donnees['duree'];

    if ($_POST['argent']) {
        $paiement = 1;
    } else {
        $paiement = 0;
    }

    $req = $bdd->prepare('INSERT INTO dev_spa_achats(mail, type, date_debut, date_expiration, prix, abonnement, paiement)
                                        VALUES(:mail, :type, :date_debut, :date_expiration, :prix, :abonnement, :paiement)');

    $req->execute(array(
        'mail' => $_SESSION['mail'],
        'type' => htmlspecialchars($_POST['abonnement']),
        'date_debut' => date("Y-m-d H:i:s", $date),
        'date_expiration' => date("Y-m-d H:i:s", $expiration),
        'prix' => $donnees['prix'],
        'abonnement' => $donnees['abonnement'],
        'paiement' => $paiement
    ));

    $req->closeCursor();

    header('Location: https://spa.matthieudevilliers.fr/pages/profil/');

    $reponse->closeCursor();
} else {
    header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
}
