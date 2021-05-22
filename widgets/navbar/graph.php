<?php

// Pour l'affiche de graphique, j'utilise le script Chart.js (https://www.chartjs.org/)

/* Graph NextHours */

$data = $_SESSION['forecastNextHours'];

$hoursNextHours = '[';
$tempNextHours = '[';
$probaNextHours = '[';
$pluieNextHours = '[';
$ventNextHours = '[';
$rafaleNextHours = '[';

// Je parcours mes données et remplis mes variables
foreach ($data->forecast as $forecast) {
    $dateNextHours = new \DateTime($forecast->datetime);
    $hoursNextHours .= "'" . $dateNextHours->format('H\h') . "',";
    $tempNextHours .= $forecast->temp2m . ',';
    $probaNextHours .= $forecast->probarain . ',';
    $pluieNextHours .= $forecast->rr10 . ',';
    $ventNextHours .= $forecast->wind10m . ',';
    $rafaleNextHours .= $forecast->gust10m . ',';
}

$hoursNextHours = substr($hoursNextHours, 0, -1) . ']';
$tempNextHours = substr($tempNextHours, 0, -1) . ']';
$probaNextHours = substr($probaNextHours, 0, -1) . ']';
$pluieNextHours = substr($pluieNextHours, 0, -1) . ']';
$ventNextHours = substr($ventNextHours, 0, -1) . ']';
$rafaleNextHours = substr($rafaleNextHours, 0, -1) . ']';

/* Graph NextDaily */

$data = $_SESSION['forecastDaily'];

$dateDaily = '[';
$tminDaily = '[';
$tmaxDaily = '[';
$probaDaily = '[';
$pluieDaily = '[';
$ventDaily = '[';
$rafaleDaily = '[';

// Je parcours mes données et remplis mes variables
foreach ($data->forecast as $forecast) {
    $dateNextHours = new \DateTime($forecast->datetime);
    $dateDaily .= "'" . $dateNextHours->format('d/m') . "',";
    $tminDaily .= $forecast->tmin . ',';
    $tmaxDaily .= $forecast->tmax . ',';
    $probaDaily .= $forecast->probarain . ',';
    $pluieDaily .= $forecast->rr10 . ',';
    $ventDaily .= $forecast->wind10m . ',';
    $rafaleDaily .= $forecast->gust10m . ',';
}

$dateDaily = substr($dateDaily, 0, -1) . ']';
$tminDaily = substr($tminDaily, 0, -1) . ']';
$tmaxDaily = substr($tmaxDaily, 0, -1) . ']';
$probaDaily = substr($probaDaily, 0, -1) . ']';
$pluieDaily = substr($pluieDaily, 0, -1) . ']';
$ventDaily = substr($ventDaily, 0, -1) . ']';
$rafaleDaily = substr($rafaleDaily, 0, -1) . ']';

?>
<script>
    // configuration du graphique NextHours
    var configNextHours = {
        type: 'line',
        data: {
            labels: <?php echo $hoursNextHours; ?>,
            datasets: [{
                label: 'Température (°C)',
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: <?php echo $tempNextHours; ?>,
                fill: false,
            }, {
                label: 'Probabilité de pluie',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: <?php echo $probaNextHours; ?>,
                fill: false,
            }, {
                label: 'Précipitation (mm)',
                backgroundColor: window.chartColors.green,
                borderColor: window.chartColors.green,
                data: <?php echo $pluieNextHours; ?>,
                fill: false,
            }, {
                label: 'Vent moyen (km/h)',
                backgroundColor: window.chartColors.yellow,
                borderColor: window.chartColors.yellow,
                data: <?php echo $ventNextHours; ?>,
                fill: false,
            }, {
                label: 'Rafales (km/h)',
                backgroundColor: window.chartColors.purple,
                borderColor: window.chartColors.purple,
                data: <?php echo $rafaleNextHours; ?>,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Prévisions horaires pour les 12 prochaines heures'
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
                        labelString: 'Heure'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                    }
                }]
            }
        }
    };

    // configuration du graphique Daily
    var configDaily = {
        type: 'line',
        data: {
            labels: <?php echo $dateDaily; ?>,
            datasets: [{
                label: 'Température minimal (°C)',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: <?php echo $tminDaily; ?>,
                fill: false,
            }, {
                label: 'Température maximal (°C)',
                backgroundColor: 'rgb(255, 159, 64)',
                borderColor: 'rgb(255, 159, 64)',
                data: <?php echo $tmaxDaily; ?>,
                fill: false,
            }, {
                label: 'Probabilité de pluie',
                backgroundColor: 'rgb(54, 200, 54)',
                borderColor: 'rgb(54, 200, 54)',
                data: <?php echo $probaDaily; ?>,
                fill: false,
            }, {
                label: 'Précipitation (mm)',
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgb(54, 162, 235)',
                data: <?php echo $pluieDaily; ?>,
                fill: false,
            }, {
                label: 'Vent moyen (km/h)',
                backgroundColor: 'rgb(153, 102, 255)',
                borderColor: 'rgb(153, 102, 255)',
                data: <?php echo $ventDaily; ?>,
                fill: false,
            }, {
                label: 'Rafales (km/h)',
                backgroundColor: 'rgb(201, 203, 207)',
                borderColor: 'rgb(201, 203, 207)',
                data: <?php echo $rafaleDaily; ?>,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Prévisions journalières sur les 14 prochains jours'
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
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                    }
                }]
            }
        }
    };

    // Affichage des graphiques quand la fenètre est chargée
    window.onload = function() {
        var ctxDaily = document.getElementById('forecastDaily').getContext("2d");
        var ctxNextHours = document.getElementById('forecastNextHours').getContext("2d");
        window.myChartDaily = new Chart(ctxDaily, configDaily);
        window.myChartNextHours = new Chart(ctxNextHours, configNextHours);
    };
</script>