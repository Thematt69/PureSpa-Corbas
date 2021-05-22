<?php

session_start();

include('../../../access/bdd.php');

include('../../scripts/verif/index.php');

if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com" || $_SESSION['mail'] == 'test@matthieudevilliers.fr') {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

if (isset($_POST['identification'])) {

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

    $req = $bdd->prepare('UPDATE dev_spa_achats SET type = :type, date_debut = :date_debut, date_expiration = :date_expiration, statut = :statut, prix = :prix, abonnement = :abonnement WHERE id = :id');

    $req->execute(array(
        'type' => htmlspecialchars($_POST['abonnement']),
        'date_debut' => date("Y-m-d H:i:s", $date),
        'date_expiration' => date("Y-m-d H:i:s", $expiration),
        'statut' => $statut,
        'prix' => $_POST['prix'],
        'abonnement' => $donnees['abonnement'],
        'id' => htmlspecialchars($_POST['identification'])
    ));

    $req->closeCursor();

    $reponse->closeCursor();

    header('Location: https://spa.matthieudevilliers.fr/pages/admin_achat/');
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Votre compte</title>

    <!-- Import -->
    <?php include('../../widgets/import/index.php'); ?>
</head>

<body>

    <?php include('../../widgets/navbar/index.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <br>
                <div class="card">
                    <div class="card-body">

                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/modification">

                            <?php

                            $req1 = $bdd->prepare('SELECT * FROM dev_spa_achats WHERE id = ?');
                            $req1->execute(array($_POST['id']));

                            $donnees1 = $req1->fetch();

                            ?>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Type d'achat (Actuellement : <?php echo $donnees1['type']; ?> - <?php echo $donnees1['prix']; ?>€)</label>
                                        <select name="abonnement" class="form-control" id="exampleFormControlSelect1">
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
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Prix">Prix</label>
                                        <input name="prix" type="number" step="0.01" value="<?php echo $donnees1['prix']; ?>" class="form-control" id="Prix" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Date1">A partir du</label>
                                        <input name="date" type="date" value="<?php echo date("Y-m-d", strtotime($donnees1['date_debut'])); ?>" class="form-control" id="Date1" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Heure1">A partir du</label>
                                        <input name="heure" type="time" value="<?php echo date("H:i", strtotime($donnees1['date_debut'])); ?>" class="form-control" id="Heure1" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" name="identification" value="<?php echo $donnees1['id']; ?>" type="submit">Valider</button>
                                    <a class="btn btn-danger" href="https://spa.matthieudevilliers.fr/pages/admin_achat/" role="button">Annuler</a>
                                </div>

                            </div>

                            <?php

                            $req1->closeCursor();

                            ?>

                        </form>

                    </div>
                </div>

                <br>
            </div>
        </div>
    </div>

</body>

</html>