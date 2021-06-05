<?php

session_start();

include('../../scripts/import/index.php');

if (isset($_SESSION['mail'])) {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Faire un achat</title>

    <!-- Import -->
    <?php include('../../widgets/import/index.php'); ?>
</head>

<body>

    <?php include('../../widgets/navbar/index.php'); ?>

    <style type="text/css">
        i.info span {
            display: none;
            /* On masque l'infobulle. */
        }

        i.info:hover {
            background: none;
            /* Correction d'un bug d'Internet Explorer. */
            z-index: 500;
            /* On définit une valeur pour l'ordre d'affichage. */
        }

        i.info:hover span {
            display: inline;
            /* On affiche l'infobulle. */
            position: absolute;
            left: 240px;
            bottom: 10px;

            padding: 6px;
            background: white;
            border: 1px solid;
            line-height: 175%;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <br>
                <!-- Faire un achat -->
                <div class="card">
                    <div class="card-body">
                        <h2>Faire un achat</h2>
                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/scripts/achats/">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Type d'achat</label>
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Date">Date</label>
                                        <input name="date" type="date" class="form-control" id="Date" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Heure">Heure</label>
                                        <input name="heure" type="time" class="form-control" id="Heure" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-check">
                                        <input name="cgu" type="checkbox" class="form-check-input" id="CGU" required>
                                        <label class="form-check-label" for="CGU">J'accepte les <a href="https://spa.matthieudevilliers.fr/pages/cgu/" target="_blank">Conditions Générales d'Utilisation</a></label>
                                    </div>
                                    <div class="form-group form-check">
                                        <input name="argent" type="checkbox" class="form-check-input" id="Argent" required>
                                        <label class="form-check-label" for="Argent">J'atteste avoir mis le montant dans la tirelire prévu à cette effet</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Valider</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <?php
                $date = date("Y-m-d H:i:s", time() - 604800);

                $response = $bdd->prepare('SELECT id FROM `dev_spa_temperature` WHERE horodateur > ? ORDER BY horodateur ASC LIMIT 1');
                $response->execute(array($date));

                $donnee = $response->fetch();

                if ($donnee != null) {
                ?>
                    <div class="card" id="temp">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h2>Température entre <?php echo date("d/m/Y", time() - 604800) . " et " . date("d/m/Y", time()); ?></h2>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-primary" href="https://spa.matthieudevilliers.fr/pages/temperature/" role="button">Ajouter une température</a>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                $req1 = $bdd->prepare('SELECT * FROM `dev_spa_temperature` ORDER BY horodateur DESC LIMIT 1');
                                $req1->execute(array());

                                $donnees1 = $req1->fetch();
                                ?>
                                <div class="col-md-3">
                                    <p>Météo : <i class="<?php echo $donnees1['meteo']; ?>"></i></p>
                                </div>
                                <div class="col-md-3">
                                    <p>Vent : <i class="<?php echo $donnees1['vent']; ?>"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Eau du Spa : <?php echo $donnees1['temp_int_spa']; ?>°C <i class="<?php echo $donnees1['evol_temp_int_spa']; ?>"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Extérieur : <?php echo $donnees1['temp_ext_spa']; ?>°C <i class="<?php echo $donnees1['evol_temp_ext_spa']; ?>"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Terrasse : <?php echo $donnees1['temp_ext_terrase']; ?>°C <i class="<?php echo $donnees1['evol_temp_ext_terrase']; ?>"></i></p>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <script src="https://spa.matthieudevilliers.fr/Chart/Chart.min.js"></script>
                                    <script src="https://spa.matthieudevilliers.fr/Chart/Chart.bundle.min.js"></script>
                                    <script src="https://spa.matthieudevilliers.fr/Chart/utils.js"></script>

                                    <?php
                                    $months = '[';
                                    $eau = '[';
                                    $spa = '[';
                                    $terrase = '[';
                                    $date = date("Y-m-d H:i:s", time() - 604800);

                                    $req2 = $bdd->prepare('SELECT * FROM `dev_spa_temperature` WHERE horodateur > ? ORDER BY horodateur DESC');
                                    $req2->execute(array($date));

                                    while ($donnees2 = $req2->fetch()) {
                                        $months = $months . "'" . date("d/m/Y H:i", strtotime($donnees2['horodateur'])) . "'" . ',';
                                        $eau = $eau . $donnees2['temp_int_spa'] . ',';
                                        $spa = $spa . $donnees2['temp_ext_spa'] . ',';
                                        $terrase = $terrase . $donnees2['temp_ext_terrase'] . ',';
                                    }

                                    $months = substr($months, 0, -1) . ']';
                                    $eau = substr($eau, 0, -1) . ']';
                                    $spa = substr($spa, 0, -1) . ']';
                                    $terrase = substr($terrase, 0, -1) . ']';

                                    $req2->closeCursor();
                                    ?>

                                    <canvas id="canvas"></canvas>

                                    <script>
                                        var MONTHS = <?php echo $months; ?>;
                                        var config = {
                                            type: 'line',
                                            data: {
                                                labels: <?php echo $months; ?>,
                                                datasets: [{
                                                    label: 'Eau du spa',
                                                    backgroundColor: window.chartColors.blue,
                                                    borderColor: window.chartColors.blue,
                                                    data: <?php echo $eau; ?>,
                                                    fill: false,
                                                }, {
                                                    label: 'Extérieur Spa',
                                                    fill: false,
                                                    backgroundColor: window.chartColors.red,
                                                    borderColor: window.chartColors.red,
                                                    data: <?php echo $spa; ?>,
                                                }, {
                                                    label: 'Extérieur Terrase',
                                                    fill: false,
                                                    backgroundColor: window.chartColors.green,
                                                    borderColor: window.chartColors.green,
                                                    data: <?php echo $terrase; ?>,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                tooltips: {
                                                    mode: 'index',
                                                    intersect: false,
                                                },
                                                hover: {
                                                    mode: 'nearest',
                                                    intersect: true
                                                },
                                                scales: {
                                                    xAxes: [{
                                                        display: true,
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: 'Date & Heure'
                                                        }
                                                    }],
                                                    yAxes: [{
                                                        display: true,
                                                        scaleLabel: {
                                                            display: true,
                                                            labelString: 'Température (°C)'
                                                        }
                                                    }]
                                                }
                                            }
                                        };

                                        window.onload = function() {
                                            var ctx = document.getElementById('canvas').getContext('2d');
                                            window.myLine = new Chart(ctx, config);
                                        };
                                    </script>
                                </div>
                                <!-- Date et légende -->
                                <div class="col-md-12">
                                    <p>
                                        <small>
                                            MAJ : <?php echo date("d/m/Y H:i", strtotime($donnees1['horodateur'])); ?> - Légende :

                                            <i class="fas fa-question-circle info">
                                                <span>
                                                    <i class="fas fa-angle-up"></i> : Augmentation de température
                                                    <br>
                                                    <i class="fas fa-angle-double-up"></i> : Augmentation forte de température
                                                    <br>
                                                    <i class="fas fa-angle-right"></i> : Température stable
                                                    <br>
                                                    <i class="fas fa-angle-down"></i> : Diminution de température
                                                    <br>
                                                    <i class="fas fa-angle-double-down"></i> : Diminution forte de température
                                                    <br>
                                                    <i class="fas fa-exclamation-triangle"></i> : Changement anormal de température
                                                </span>
                                            </i>
                                        </small>
                                    </p>
                                </div>
                                <?php
                                $req1->closeCursor();
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                $response->closeCursor();
                ?>
                <br>
            </div>
        </div>
    </div>

</body>

</html>