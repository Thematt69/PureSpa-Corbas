<?php

session_start();

include('../../../access/bdd.php');

include('../../scripts/verif/index.php');

if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com" || $_SESSION['mail'] == 'test@matthieudevilliers.fr') {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

if (isset($_POST['abonnement'])) {
    $reponse = $bdd->prepare('DELETE FROM `dev_spa_achats` WHERE id = ?');
    $reponse->execute(array(htmlspecialchars($_POST['abonnement'])));

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

                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/suppression/">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Type d'achat</label>
                                        <select name="abonnement" class="form-control" id="exampleFormControlSelect1">
                                            <?php

                                            $reponse = $bdd->prepare('SELECT * FROM dev_spa_achats WHERE id = ?');
                                            $reponse->execute(array(htmlspecialchars($_POST['id'])));

                                            while ($donnees = $reponse->fetch()) {
                                            ?>
                                                <option value="<?php echo $donnees['id']; ?>"><?php echo $donnees['type']; ?> - <?php echo $donnees['prix']; ?>€ - <?php echo date("d/m/Y H:i", strtotime($donnees['date_debut'])) . " au " . date("d/m/Y H:i", strtotime($donnees['date_expiration'])); ?> - <?php echo $donnees['statut']; ?> - <?php echo $donnees['mail']; ?></option>
                                            <?php
                                            }

                                            $reponse->closeCursor();

                                            ?>
                                        </select>
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