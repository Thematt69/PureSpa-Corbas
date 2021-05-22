<?php

if (!$_SERVER['HTTPS']) {
    header('Location: https://spa.matthieudevilliers.fr' . $_SERVER['PHP_SELF'] . '');
}

include($_SERVER['DOCUMENT_ROOT'] . '/spa.matthieudevilliers.fr/access/bdd.php');

function envoi_mail($to)
{

    // Sujet
    $subject = 'Rappel Abonnement - PureSpa Corbas';

    // Message
    $contenu = '
        <html>
            <body>
                <h4>Votre abonnement arrive bientôt à terme !</h4>
                <br>
                <p>Bonjour,</p>
                <p>Votre abonnement se termine dans 2 jours, il serait peut-être temps de le renouveler.</p>
                <p>Si vous souhaitez le renouveler, cliquez <a href="https://spa.matthieudevilliers.fr/pages/profil/">ici</a>.</p>
                <p>Si vous ne souhaitez pas continuer, veuillez ne pas donner suite à ce mail.</p>
                <br>
                <p>Le PureSpa Corbas vous remercie pour votre visite et reste à votre disposition.</p>
            </body>
        </html>
        ';

    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'Reply-To: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
    $headers[] = 'From: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
    $headers[] = 'Bcc: Matthieu Devilliers <devilliers.matthieu@gmail.com>';
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    // Envoi du mail
    mail($to, $subject, $contenu, implode("\r\n", $headers));
}

$req = $bdd->query('SELECT id,mail,date_debut,date_expiration,abonnement FROM dev_spa_achats WHERE statut!="Expirée"');

while ($donnees = $req->fetch()) {
    $date = strtotime($donnees['date_debut']);
    $expiration = strtotime($donnees['date_expiration']);

    if (time() > $expiration) { //aujourd'hui > date d'expiration 
        $statut = "Expirée";
    } else if (time() > $expiration - 172800 && $donnees['abonnement'] == 1) { //aujourd'hui > date d'expiration - 2 jours
        $statut = "Averti par mail";
        if ($donnees['statut'] != "Averti par mail") {
            envoi_mail($donnees['mail']);
        }
    } else if (time() > $date && time() < $expiration) { //aujourd'hui > date Et aujourd'hui < date d'expiration
        $statut = "En cours";
    } else if (time() < $date) { //aujourd'hui < date
        $statut = "Programmée";
    } else {
        $statut = "Inconnu";
    }

    $req1 = $bdd->prepare('UPDATE dev_spa_achats SET statut = :statut WHERE id = :id');

    $req1->execute(array(
        'statut' => $statut,
        'id' => $donnees['id']
    ));

    $req1->closeCursor();
}

$req->closeCursor();
