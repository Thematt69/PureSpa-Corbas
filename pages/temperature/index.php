<?php

session_start();

include('../../../access/bdd.php');

include('../../scripts/verif/index.php');

function difference($dif)
{
    if ($dif == 0) {
        $icon = "fas fa-angle-right";
    } elseif ($dif >= 5) {
        $icon = "fas fa-angle-double-up";
    } elseif ($dif > 0.01 && $dif < 4.99) {
        $icon = "fas fa-angle-up";
    } elseif ($dif < -0.01 && $dif > -4.99) {
        $icon = "fas fa-angle-down";
    } else {
        $icon = "fas fa-angle-double-down";
    }

    if ($dif >= 10 || $dif <= -10) {
        $icon = $icon . "\"></i>&nbsp;<i class=\"fas fa-exclamation-triangle";
    }
    return $icon;
}

if (isset($_POST['temp_int_spa'])) {
    if (isset($_POST['date'])) {
        $date = htmlspecialchars($_POST['date']) . " " . htmlspecialchars($_POST['heure']) . ":00";
        $date = strtotime($date);
    } else {
        $date = strtotime(time());
    }

    $req1 = $bdd->prepare('SELECT * FROM `dev_spa_temperature` WHERE horodateur < :horodateur ORDER BY horodateur DESC LIMIT 1');

    $req1->execute(array(
        'horodateur' => date("Y-m-d H:i:s", $date)
    ));

    $donnees1 = $req1->fetch();

    $evol_temp_int_spa = difference(htmlspecialchars($_POST['temp_int_spa']) - $donnees1['temp_int_spa']);

    $evol_temp_ext_spa = difference(htmlspecialchars($_POST['temp_ext_spa']) - $donnees1['temp_ext_spa']);

    $evol_temp_ext_terrase = difference(htmlspecialchars($_POST['temp_ext_terrase']) - $donnees1['temp_ext_terrase']);

    $req1->closeCursor();

    $req = $bdd->prepare('INSERT INTO dev_spa_temperature(horodateur, temp_int_spa, evol_temp_int_spa, temp_ext_spa, evol_temp_ext_spa, temp_ext_terrase, evol_temp_ext_terrase, meteo, vent)
                        VALUES(:horodateur, :temp_int_spa, :evol_temp_int_spa, :temp_ext_spa, :evol_temp_ext_spa, :temp_ext_terrase, :evol_temp_ext_terrase, :meteo, :vent)');

    $req->execute(array(
        'horodateur' => date("Y-m-d H:i:s", $date),
        'temp_int_spa' => htmlspecialchars($_POST['temp_int_spa']),
        'evol_temp_int_spa' => $evol_temp_int_spa,
        'temp_ext_spa' => htmlspecialchars($_POST['temp_ext_spa']),
        'evol_temp_ext_spa' => $evol_temp_ext_spa,
        'temp_ext_terrase' => htmlspecialchars($_POST['temp_ext_terrase']),
        'evol_temp_ext_terrase' => $evol_temp_ext_terrase,
        'meteo' => htmlspecialchars($_POST['meteo']),
        'vent' => htmlspecialchars($_POST['vent'])
    ));

    $req->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Enregistrement des températures</title>

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
                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/temperature/">
                            <h2 class="text-center">Enregistrement des températures - Extérieur</h2>
                            <br>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="input1">Eau</label>
                                    <input name="temp_int_spa" type="number" class="form-control" step="0.1" id="input1" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="input2">Spa</label>
                                    <input name="temp_ext_spa" type="number" class="form-control" step="0.1" id="input2" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="input3">Terrase</label>
                                    <input name="temp_ext_terrase" type="number" class="form-control" step="0.1" id="input3" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleFormControlSelect1">Météo</label>
                                    <select name="meteo" class="form-control" id="exampleFormControlSelect1" required>
                                        <option value="fas fa-sun">Soleil</option>
                                        <option value="fas fa-moon">Lune</option>
                                        <option value="fas fa-cloud-sun">Nuage Soleil</option>
                                        <option value="fas fa-cloud-moon">Nuage Lune</option>
                                        <option value="fas fa-cloud-sun-rain">Nuage Soleil Pluie</option>
                                        <option value="fas fa-cloud-moon-rain">Nuage Lune Pluie</option>
                                        <option value="fas fa-cloud">Nuage</option>
                                        <option value="fas fa-cloud-rain">Nuage Pluie</option>
                                        <option value="fas fa-cloud-showers-heavy">Nuage Fortes Averses</option>
                                        <option value="fas fa-cloud-meatball">Nuage Grêle</option>
                                        <option value="fas fa-poo-storm">Nuage Orage</option>
                                        <option value="fas fa-smog">Nuage Brouillard</option>
                                    </select>
                                </div>
                                <div class="form-group form-check col-md-2">
                                    <label for="exampleFormControlSelect1">Vent</label>
                                    <select name="vent" class="form-control" id="exampleFormControlSelect1" required>
                                        <option value="far fa-times-circle">Non</option>
                                        <option value='fas fa-wind'>Oui</option>
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