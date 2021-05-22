<?php

session_start();

include('../../../access/bdd.php');

include('../../scripts/verif/index.php');

if (isset($_POST['nom'])) {

    $reponse = $bdd->prepare('SELECT * FROM dev_spa_compte WHERE nom = ?');
    $reponse->execute(array(htmlspecialchars($_POST['nom'])));

    while ($donnees = $reponse->fetch()) {
        if (password_verify($_POST['mdp'], $donnees['mdp'])) {

            $_SESSION['nom'] = $donnees['nom'];
            $_SESSION['prenom'] = $donnees['prenom'];
            $_SESSION['mail'] = $donnees['mail'];
            $_SESSION['mdp'] = $donnees['mdp'];
            $_SESSION['insee'] = $donnees['insee'];
            // if (!isset($_SESSION['temp']))
            //     $_SESSION['temp'] = "connexion";

            header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
        } else {
            header('Location: https://spa.matthieudevilliers.fr');
        }
    }

    $reponse->closeCursor();

    $req = $bdd->prepare('INSERT INTO dev_spa_compte(nom, prenom, mail, mdp) VALUES(:nom, :prenom, :mail, :mdp)');

    $req->execute(array(
        'nom' => htmlspecialchars($_POST['nom']),
        'prenom' => htmlspecialchars($_POST['prenom']),
        'mail' => htmlspecialchars($_POST['mail']),
        'mdp' => htmlspecialchars(password_hash($_POST['mdp'], PASSWORD_DEFAULT))
    ));

    $_SESSION['nom'] = htmlspecialchars($_POST['nom']);
    $_SESSION['prenom'] = htmlspecialchars($_POST['prenom']);
    $_SESSION['mail'] = htmlspecialchars($_POST['mail']);
    $_SESSION['mdp'] = password_hash(htmlspecialchars($_POST['mdp']), PASSWORD_DEFAULT);
    $_SESSION['insee'] = 69273;
    // if (!isset($_SESSION['temp']))
    //     $_SESSION['temp'] = "inscription";

    $req->closeCursor();

    header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
} elseif (isset($_POST['mail'])) {

    $reponse = $bdd->prepare('SELECT * FROM dev_spa_compte WHERE mail = ?');
    $reponse->execute(array(htmlspecialchars($_POST['mail'])));

    while ($donnees = $reponse->fetch()) {
        if (password_verify($_POST['mdp'], $donnees['mdp'])) {

            $_SESSION['nom'] = $donnees['nom'];
            $_SESSION['prenom'] = $donnees['prenom'];
            $_SESSION['mail'] = $donnees['mail'];
            $_SESSION['mdp'] = $donnees['mdp'];
            $_SESSION['insee'] = $donnees['insee'];
            // if (!isset($_SESSION['temp']))
            //     $_SESSION['temp'] = "connexion";

            header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
        } else {
            header('Location: https://spa.matthieudevilliers.fr');
        }
    }

    $reponse->closeCursor();

    header('Location: https://spa.matthieudevilliers.fr');
} elseif (isset($_SESSION['mail'])) {
    // Ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

$reponse1 = $bdd->prepare('SELECT `type`,date_expiration FROM dev_spa_achats WHERE mail = ? AND (statut = "En cours" OR statut = "Averti par mail") AND (abonnement = 1 OR abonnement = 2) ORDER BY date_debut DESC LIMIT 1');
$reponse1->execute(array($_SESSION['mail']));

$donnees1 = $reponse1->fetch();

if (isset($donnees1['type'])) {
    $abo = "Dernier abonnement : " . $donnees1['type'] . " (expire le : " . date("d/m/Y \à H:i", strtotime($donnees1['date_expiration'])) . " )";
} else {
    $abo = "Vous n'avez pas d'abonnement en cours.";
}

$reponse1->closeCursor();


$reponse2 = $bdd->prepare('SELECT `type` FROM dev_spa_achats WHERE mail = ? ORDER BY date_debut DESC LIMIT 1');
$reponse2->execute(array($_SESSION['mail']));

$donnees2 = $reponse2->fetch();

if (isset($donnees2['type'])) {
    $achat = "Dernier achat effectué : " . $donnees2['type'];
} else {
    $achat = "Vous n'avez jamais fait d'achats.";
}

$reponse2->closeCursor();


$reponse3 = $bdd->prepare('SELECT SUM(prix) As total FROM dev_spa_achats WHERE mail = ? AND paiement != 0 ORDER BY date_debut DESC LIMIT 1');
$reponse3->execute(array($_SESSION['mail']));

$donnees3 = $reponse3->fetch();

$total_achats = "Total des dépenses : " . round($donnees3['total'], 2) . " €";

$reponse3->closeCursor();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Mon profil</title>

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
                        <div class="media">
                            <div class="media-body">
                                <h2 class="mt-0 mb-1">
                                    <span style="font-size: 2.1rem;">
                                        <?php echo $_SESSION['nom']; ?>
                                    </span>
                                    <?php echo $_SESSION['prenom']; ?>
                                </h2>
                                <br>
                                <?php echo $abo; ?>
                                <br><br>
                                <?php echo $achat; ?>
                                <br><br>
                                <?php echo $total_achats; ?>
                            </div>
                            <img id="imageProfil" src="https://spa.matthieudevilliers.fr/images/SpaLogo.webp" alt="Image du profil" width="200" height="200">
                        </div>
                    </div>
                </div>

                <br>

                <div class="card">
                    <div class="card-body">

                        <?php
                        $req2 = $bdd->prepare('SELECT SUM(prix) As total FROM dev_spa_achats WHERE mail = ? AND paiement != 0');
                        $req2->execute(array($_SESSION['mail']));

                        $donnees2 = $req2->fetch();

                        ?>
                        <h2>Historique des achats - Total : <?php echo round($donnees2['total'], 2); ?> €</h2>
                        <?php

                        $req2->closeCursor();

                        ?>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col-md">A partir du</th>
                                        <th scope="col-md">Jusqu'au</th>
                                        <th scope="col-md">Type d'achat</th>
                                        <th scope="col-md">Statut</th>
                                        <th scope="col-md">Paiement</th>
                                        <th scope="col-md">Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $req1 = $bdd->prepare('SELECT * FROM dev_spa_achats WHERE mail = ? ORDER BY date_expiration DESC');
                                    $req1->execute(array($_SESSION['mail']));

                                    while ($donnees1 = $req1->fetch()) {
                                        if ($donnees1['paiement'] == 1) {
                                    ?>
                                            <tr>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>Payée</td>
                                                <td><?php echo $donnees1['prix']; ?> €</td>
                                            </tr>
                                        <?php
                                        } else if ($donnees1['paiement'] == 2) {
                                        ?>
                                            <tr class="text-danger">
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>Dépensée</td>
                                                <td><?php echo $donnees1['prix']; ?> €</td>
                                            </tr>
                                        <?php
                                        } else {
                                        ?>
                                            <tr class="text-danger">
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>
                                                    <form class="form" method="POST" action="../paiement/">
                                                        <button class="btn btn-danger" name="mail" value="<?php echo $_SESSION['mail']; ?>" type="submit">A Payer</button>
                                                    </form>
                                                </td>
                                                <td"><?php echo $donnees1['prix']; ?> €</td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    $req1->closeCursor();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

</body>

</html>