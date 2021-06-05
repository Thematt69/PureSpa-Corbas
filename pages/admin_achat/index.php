<?php

session_start();

include('../../scripts/import/index.php');

if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com") {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

$sqlAchat = "SELECT SUM(prix) As total FROM dev_spa_achats WHERE paiement != 0";
$sql1Achat = "SELECT * FROM dev_spa_achats ORDER BY date_expiration DESC";

if (isset($_POST['compte'])) {
    if ($_POST['compte'] == "all") {
        $sqlAchat = "SELECT SUM(prix) As total FROM dev_spa_achats WHERE paiement != 0";
        $sql1Achat = "SELECT * FROM dev_spa_achats ORDER BY date_expiration DESC";
    } else {
        $sqlAchat = "SELECT SUM(prix) As total FROM dev_spa_achats WHERE paiement != 0 AND mail = '" . htmlspecialchars($_POST['compte']) . "'";
        $sql1Achat = "SELECT * FROM dev_spa_achats WHERE mail = '" . htmlspecialchars($_POST['compte']) . "' ORDER BY date_expiration DESC";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Panel Admin</title>

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

                        <?php
                        $req2 = $bdd->query($sqlAchat);

                        $donnees2 = $req2->fetch();

                        ?>
                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/admin_achat/">
                            <div class="row">
                                <div class="form-group col-md-7">
                                    <h2>Historique des achats - Total : <?php echo round($donnees2['total'], 2); ?> €</h2>
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="compte" class="form-control" aria-label="Paramètre de filtrage">
                                        <option value="all"> -- Tous les comptes -- </option>
                                        <?php

                                        $reponse = $bdd->query('SELECT * FROM dev_spa_compte ORDER BY nom');

                                        while ($donnees = $reponse->fetch()) {
                                        ?>
                                            <option value="<?php echo $donnees['mail']; ?>"><?php echo $donnees['nom']; ?> <?php echo $donnees['prenom']; ?> - <?php echo $donnees['mail']; ?></option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                        <?php

                        $req2->closeCursor();

                        ?>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col-md">Mail</th>
                                        <th scope="col-md">A partir du</th>
                                        <th scope="col-md">Jusqu'au</th>
                                        <th scope="col-md">Type d'achat</th>
                                        <th scope="col-md">Statut</th>
                                        <th scope="col-md">Paiement</th>
                                        <th scope="col-md">Prix</th>
                                        <th scope="col-md">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $req1 = $bdd->query($sql1Achat);

                                    while ($donnees1 = $req1->fetch()) {
                                        if ($donnees1['paiement'] == 1) {
                                    ?>
                                            <tr>
                                                <td><?php echo $donnees1['mail']; ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>Payée</td>
                                                <td><?php echo $donnees1['prix']; ?> €</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" id="<?php echo $donnees1['id'] ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="<?php echo $donnees1['id'] ?>">
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/modification/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Modifier</button>
                                                            </form>
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/suppression/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Supprimer</button>
                                                            </form>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        } else if ($donnees1['paiement'] == 2) {
                                        ?>
                                            <tr class="text-danger">
                                                <td><?php echo $donnees1['mail']; ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>Dépensée</td>
                                                <td><?php echo $donnees1['prix']; ?> €</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button id="<?php echo $donnees1['id'] ?>" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="<?php echo $donnees1['id'] ?>">
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/modification/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Modifier</button>
                                                            </form>
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/suppression/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Supprimer</button>
                                                            </form>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        } else {
                                        ?>
                                            <tr class="text-danger">
                                                <td><?php echo $donnees1['mail']; ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_debut'])); ?></td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($donnees1['date_expiration'])); ?></td>
                                                <td><?php echo $donnees1['type']; ?></td>
                                                <td><?php echo $donnees1['statut']; ?></td>
                                                <td>A payer</td>
                                                <td><?php echo $donnees1['prix']; ?> €</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button id="<?php echo $donnees1['id'] ?>" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="<?php echo $donnees1['id'] ?>">
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/paiement/">
                                                                <button class="btn btn-danger dropdown-item" name="mail" value="<?php echo $donnees1['mail']; ?>" type="submit">Payer</button>
                                                            </form>
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/modification/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Modifier</button>
                                                            </form>
                                                            <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/suppression/">
                                                                <button class="btn btn-danger dropdown-item" name="id" value="<?php echo $donnees1['id']; ?>" type="submit">Supprimer</button>
                                                            </form>
                                                        </ul>
                                                    </div>
                                                </td>
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