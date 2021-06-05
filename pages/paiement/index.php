<?php

session_start();

include('../../scripts/import/index.php');

if (isset($_POST['abonnement'])) {
    $_POST['abonnement']; //ID

    if ($_POST['argent']) {
        $paiement = 1;
    }

    $req = $bdd->prepare('UPDATE dev_spa_achats SET paiement = :paiement WHERE id = :cible');

    $req->execute(array(
        'paiement' => $paiement,
        'cible' => htmlspecialchars($_POST['abonnement']),
    ));

    $req->closeCursor();

    header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
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

                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/paiement/">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Type d'achat</label>
                                        <select name="abonnement" class="form-control" id="exampleFormControlSelect1">
                                            <?php

                                            $reponse = $bdd->prepare('SELECT * FROM dev_spa_achats WHERE mail = ? AND paiement=0 ORDER BY date_expiration DESC');
                                            $reponse->execute(array($_POST['mail']));

                                            while ($donnees = $reponse->fetch()) {
                                            ?>
                                                <option value="<?php echo $donnees['id']; ?>"><?php echo $donnees['type']; ?> - <?php echo $donnees['prix']; ?>€ - <?php echo date("d/m/Y H:i", strtotime($donnees['date_debut'])) . " au " . date("d/m/Y H:i", strtotime($donnees['date_expiration'])); ?> - <?php echo $donnees['statut']; ?> - <?php echo $donnees['mail']; ?></option>
                                            <?php
                                            }

                                            $reponse->closeCursor();

                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group form-check">
                                        <input name="cgu" type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                        <label class="form-check-label" for="exampleCheck1">J'accepte les <a href="pages/cgu">Conditions Générales d'Utilisation</a></label>
                                    </div>
                                    <div class="form-group form-check">
                                        <input name="argent" type="checkbox" class="form-check-input" id="exampleCheck2" required>
                                        <label class="form-check-label" for="exampleCheck2">J'atteste avoir mis le montant dans la tirelire prévu à cette effet</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Valider</button>
                                    <a class="btn btn-danger" href="https://spa.matthieudevilliers.fr/pages/admin_achat/" role="button">Annuler</a>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>

                <br>
            </div>
        </div>
    </div>

</body>

</html>