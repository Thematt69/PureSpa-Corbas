<?php

session_start();

include('../../scripts/import/index.php');

if ($_SESSION['mail'] == 'test@matthieudevilliers.fr') {
    header('Location: https://spa.matthieudevilliers.fr');
}

function envoi_mail_abo($to, $abo)
{

    // Sujet
    $subject = 'Rappel Paiement - PureSpa Corbas';

    // Message
    $contenu = '
        <html>
            <body>
                <h3>Vous avez effectué un achat en attente de paiement.</h3>
                <br>
                <p>Bonjour,</p>
                <p>Le service/abonnement "' . $abo . '" est en attente de paiement.</p>
                <p>Vous devez le régler au plus vite via votre espace client sur notre site.</p>
                <p>Vous pouvez vous y rendre en cliquant <a href="https://spa.matthieudevilliers.fr/pages/profil/">ici</a>.</p>
                <br>
                <p>Le PureSpa Corbas vous remercie pour votre achat.</p>
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

if (isset($_POST['abonnement'])) {

    $reponse = $bdd->prepare('SELECT * FROM dev_spa_prix WHERE nom = ?');
    $reponse->execute(array(htmlspecialchars($_POST['abonnement'])));

    $donnees = $reponse->fetch();

    if (isset($_POST['date'])) {
        $date = htmlspecialchars($_POST['date']) . " " . htmlspecialchars($_POST['heure']) . ":00";
        $date = strtotime($date);
    } else {
        $date = strtotime(time());
    }

    $expiration = $date + $donnees['duree'];

    $req = $bdd->prepare('INSERT INTO dev_spa_achats(mail, type, date_debut, date_expiration, prix, abonnement, paiement)
                                        VALUES(:mail, :type, :date_debut, :date_expiration, :prix, :abonnement, :paiement)');

    $req->execute(array(
        'mail' => htmlspecialchars($_POST['compte']),
        'type' => htmlspecialchars($_POST['abonnement']),
        'date_debut' => date("Y-m-d H:i:s", $date),
        'date_expiration' => date("Y-m-d H:i:s", $expiration),
        'prix' => $donnees['prix'],
        'abonnement' => $donnees['abonnement'],
        'paiement' => htmlspecialchars($_POST['paiement'])
    ));

    $req->closeCursor();

    if (htmlspecialchars($_POST['paiement']) == 0) {
        envoi_mail_abo(htmlspecialchars($_POST['compte']), htmlspecialchars($_POST['abonnement']));
    }
    $reponse->closeCursor();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Enregistrement des achats</title>

    <!-- Import -->
    <?php include('../../widgets/import/index.php'); ?>
</head>

<body>

    <?php include('../../widgets/navbar/index.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <br>
                <!-- Enregistrement des dettes -->
                <div class="card">
                    <div class="card-body">
                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/admin/">
                            <h2 class="text-center">Enregistrement des achats</h2>
                            <br>
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="exampleFormControlSelect1">Type d'achat</label>
                                    <select name="abonnement" class="form-control" id="exampleFormControlSelect1" required>
                                        <?php

                                        $reponse = $bdd->query('SELECT * FROM dev_spa_prix');

                                        while ($donnees = $reponse->fetch()) {
                                        ?>
                                            <option value="<?php echo $donnees['nom']; ?>"><?php echo $donnees['nom']; ?> - <?php echo $donnees['prix']; ?>€</option>
                                        <?php
                                        }

                                        $reponse->closeCursor();

                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="exampleFormControlInput3">Date</label>
                                    <input name="date" type="date" class="form-control" id="exampleFormControlInput3" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="exampleFormControlInput4">Heure</label>
                                    <input name="heure" type="time" class="form-control" id="exampleFormControlInput4" required>
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="exampleFormControlSelect1">Utilisateur</label>
                                    <select name="compte" class="form-control" id="exampleFormControlSelect1" required>
                                        <?php

                                        $reponse = $bdd->query('SELECT * FROM dev_spa_compte ORDER BY nom');

                                        while ($donnees = $reponse->fetch()) {
                                        ?>
                                            <option value="<?php echo $donnees['mail']; ?>"><?php echo $donnees['nom']; ?> <?php echo $donnees['prenom']; ?> - <?php echo $donnees['mail']; ?></option>
                                        <?php
                                        }

                                        $reponse->closeCursor();

                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleFormControlSelect1">Paiement</label>
                                    <select name="paiement" class="form-control" id="exampleFormControlSelect1" required>
                                        <option value="0">A payer</option>
                                        <option value="1">Payée</option>
                                        <option value="2">Dépensée</option>
                                    </select>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>

                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

</body>

</html>