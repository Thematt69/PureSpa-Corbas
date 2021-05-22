<?php

session_start();

include('../../../access/bdd.php');

include('../../scripts/verif/index.php');

if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com" || $_SESSION['mail'] == 'test@matthieudevilliers.fr') {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-9">
                                <h2>Historique des températures</h2>
                            </div>
                        </div>

                        <div class="graph">
                            <p><small><i class="fas fa-exclamation-triangle"></i> : Changement très important de température (+10)</small></p>

                            <script src="https://spa.matthieudevilliers.fr/Chart/Chart.min.js"></script>
                            <script src="https://spa.matthieudevilliers.fr/Chart/Chart.bundle.min.js"></script>
                            <script src="https://spa.matthieudevilliers.fr/Chart/utils.js"></script>

                            <?php
                            $months1 = '[';
                            $eau1 = '[';
                            $spa1 = '[';
                            $terrase1 = '[';

                            $req2 = $bdd->prepare('SELECT horodateur,temp_int_spa,temp_ext_spa,temp_ext_terrase FROM `dev_spa_temperature` ORDER BY horodateur DESC LIMIT 30');
                            $req2->execute(array());

                            while ($donnees2 = $req2->fetch()) {
                                $months1 = $months1 . "'" . date("d/m/Y H:i", strtotime($donnees2['horodateur'])) . "'" . ',';
                                $eau1 = $eau1 . $donnees2['temp_int_spa'] . ',';
                                $spa1 = $spa1 . $donnees2['temp_ext_spa'] . ',';
                                $terrase1 = $terrase1 . $donnees2['temp_ext_terrase'] . ',';
                            }

                            $months1 = substr($months1, 0, -1) . ']';
                            $eau1 = substr($eau1, 0, -1) . ']';
                            $spa1 = substr($spa1, 0, -1) . ']';
                            $terrase1 = substr($terrase1, 0, -1) . ']';

                            $req2->closeCursor();
                            ?>
                            <script>
                                var config1 = {
                                    type: 'line',
                                    data: {
                                        labels: <?php echo $months1; ?>,
                                        datasets: [{
                                            label: 'Eau du spa',
                                            backgroundColor: window.chartColors.blue,
                                            borderColor: window.chartColors.blue,
                                            data: <?php echo $eau1; ?>,
                                            fill: false,
                                        }, {
                                            label: 'Extérieur Spa',
                                            fill: false,
                                            backgroundColor: window.chartColors.red,
                                            borderColor: window.chartColors.red,
                                            data: <?php echo $spa1; ?>,
                                        }, {
                                            label: 'Extérieur Terrase',
                                            fill: false,
                                            backgroundColor: window.chartColors.green,
                                            borderColor: window.chartColors.green,
                                            data: <?php echo $terrase1; ?>,
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        title: {
                                            display: true,
                                            text: 'Historique des températures - Extérieur'
                                        },
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
                                    var ctx1 = document.getElementById('canvas1').getContext('2d');
                                    window.myLine1 = new Chart(ctx1, config1);
                                };
                            </script>

                            <canvas id="canvas1"></canvas>

                            <!-- Date et légende -->
                            <div class="col-md-12">
                                <p>
                                    <small>
                                        MAJ : <?php echo substr($months2, 2, 16); ?> - Légende :

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
                        </div>

                        <br>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col-md">Date</th>
                                        <th scope="col-md">Eau</th>
                                        <th scope="col-md">Spa</th>
                                        <th scope="col-md">Terrase</th>
                                        <th scope="col-md">Météo</th>
                                        <th scope="col-md">Vent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $req3 = $bdd->prepare('SELECT horodateur,temp_int_spa,evol_temp_int_spa,temp_ext_spa,evol_temp_ext_spa,temp_ext_terrase,evol_temp_ext_terrase,meteo,vent FROM `dev_spa_temperature` ORDER BY horodateur DESC');
                                    $req3->execute(array());

                                    while ($donnees3 = $req3->fetch()) {
                                    ?>
                                        <tr>
                                            <td><?php echo date("d/m/Y H:i", strtotime($donnees3['horodateur'])); ?></td>
                                            <td><?php echo $donnees3['temp_int_spa']; ?>&nbsp;<i class="<?php echo $donnees3['evol_temp_int_spa']; ?>"></i></td>
                                            <td><?php echo $donnees3['temp_ext_spa']; ?>&nbsp;<i class="<?php echo $donnees3['evol_temp_ext_spa']; ?>"></i></td>
                                            <td><?php echo $donnees3['temp_ext_terrase']; ?>&nbsp;<i class="<?php echo $donnees3['evol_temp_ext_terrase']; ?>"></i></td>
                                            <td><i class="<?php echo $donnees3['meteo']; ?>"></i></td>
                                            <td><i class="<?php echo $donnees3['vent']; ?>"></i></td>
                                        </tr>
                                    <?php
                                    }
                                    $req3->closeCursor();
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