<?php

$baseUrl = 'https://api.meteo-concept.com/api/';
$token = 'f7da012d1771189e3b1bf3bebe727b0900752db29e85c93f1244b34195bd8441';

/**
 * Variable constante pour identifier la météo avec le code de l'API
 */
const WEATHER = [
    0 => "Soleil",
    1 => "Peu nuageux",
    2 => "Ciel voilé",
    3 => "Nuageux",
    4 => "Très nuageux",
    5 => "Couvert",
    6 => "Brouillard",
    7 => "Brouillard givrant",
    10 => "Pluie faible",
    11 => "Pluie modérée",
    12 => "Pluie forte",
    13 => "Pluie faible verglaçante",
    14 => "Pluie modérée verglaçante",
    15 => "Pluie forte verglaçante",
    16 => "Bruine",
    20 => "Neige faible",
    21 => "Neige modérée",
    22 => "Neige forte",
    30 => "Pluie et neige mêlées faibles",
    31 => "Pluie et neige mêlées modérées",
    32 => "Pluie et neige mêlées fortes",
    40 => "Averses de pluie locales et faibles",
    41 => "Averses de pluie locales",
    42 => "Averses locales et fortes",
    43 => "Averses de pluie faibles",
    44 => "Averses de pluie",
    45 => "Averses de pluie fortes",
    46 => "Averses de pluie faibles et fréquentes",
    47 => "Averses de pluie fréquentes",
    48 => "Averses de pluie fortes et fréquentes",
    60 => "Averses de neige localisées et faibles",
    61 => "Averses de neige localisées",
    62 => "Averses de neige localisées et fortes",
    63 => "Averses de neige faibles",
    64 => "Averses de neige",
    65 => "Averses de neige fortes",
    66 => "Averses de neige faibles et fréquentes",
    67 => "Averses de neige fréquentes",
    68 => "Averses de neige fortes et fréquentes",
    70 => "Averses de pluie et neige mêlées localisées et faibles",
    71 => "Averses de pluie et neige mêlées localisées",
    72 => "Averses de pluie et neige mêlées localisées et fortes",
    73 => "Averses de pluie et neige mêlées faibles",
    74 => "Averses de pluie et neige mêlées",
    75 => "Averses de pluie et neige mêlées fortes",
    76 => "Averses de pluie et neige mêlées faibles et nombreuses",
    77 => "Averses de pluie et neige mêlées fréquentes",
    78 => "Averses de pluie et neige mêlées fortes et fréquentes",
    100 => "Orages faibles et locaux",
    101 => "Orages locaux",
    102 => "Orages fort et locaux",
    103 => "Orages faibles",
    104 => "Orages",
    105 => "Orages forts",
    106 => "Orages faibles et fréquents",
    107 => "Orages fréquents",
    108 => "Orages forts et fréquents",
    120 => "Orages faibles et locaux de neige ou grésil",
    121 => "Orages locaux de neige ou grésil",
    122 => "Orages locaux de neige ou grésil",
    123 => "Orages faibles de neige ou grésil",
    124 => "Orages de neige ou grésil",
    125 => "Orages de neige ou grésil",
    126 => "Orages faibles et fréquents de neige ou grésil",
    127 => "Orages fréquents de neige ou grésil",
    128 => "Orages fréquents de neige ou grésil",
    130 => "Orages faibles et locaux de pluie et neige mêlées ou grésil",
    131 => "Orages locaux de pluie et neige mêlées ou grésil",
    132 => "Orages fort et locaux de pluie et neige mêlées ou grésil",
    133 => "Orages faibles de pluie et neige mêlées ou grésil",
    134 => "Orages de pluie et neige mêlées ou grésil",
    135 => "Orages forts de pluie et neige mêlées ou grésil",
    136 => "Orages faibles et fréquents de pluie et neige mêlées ou grésil",
    137 => "Orages fréquents de pluie et neige mêlées ou grésil",
    138 => "Orages forts et fréquents de pluie et neige mêlées ou grésil",
    140 => "Pluies orageuses",
    141 => "Pluie et neige mêlées à caractère orageux",
    142 => "Neige à caractère orageux",
    210 => "Pluie faible intermittente",
    211 => "Pluie modérée intermittente",
    212 => "Pluie forte intermittente",
    220 => "Neige faible intermittente",
    221 => "Neige modérée intermittente",
    222 => "Neige forte intermittente",
    230 => "Pluie et neige mêlées",
    231 => "Pluie et neige mêlées",
    232 => "Pluie et neige mêlées",
    235 => "Averses de grêle",
];

/**
 * Permet d'identifer l'erreur en fonction du code d'entête
 */
function getError($codeError)
{
    $e = str_split(substr($codeError, 9), 3);
    switch ($e[0]) {
        case '400':
            return 'Paramètre manquant, ou valeur incorrecte';
            break;
        case '401':
            return 'Authentification nécessaire (token absent ou invalide)';
            break;
        case '403':
            return 'Action non autorisée (URL non autorisée avec votre abonnement)';
            break;
        case '404':
            return 'Page inaccessible (URL inconnue)';
            break;
        case '500':
            return 'Erreur interne au serveur, contactez-nous';
            break;
        case '503':
            return 'L\'API est momentanément indisponible, réessayez dans quelques minute';
            break;

        default:
            return 'Erreur inconnue';
            break;
    }
}

// Si la varaible de session n'existe pas, j'excute la requête
if (!isset($_SESSION['forecastDailyDay'])) {
    $route = 'forecast/daily';
    $day = '/0';
    $request = 'insee=' . $_SESSION['insee'];

    $data = @file_get_contents($baseUrl . $route . $day . '?token=' . $token . '&' . $request);

    // Si l'entête contient '200' sinon je retourne un message d'erreur
    if (strpos($http_response_header[0], "200")) {
        if ($data !== false) {
            $forecastDailyDay = json_decode($data);

            // Je stocke la donnée en session pour éviter la redondance de rêquete (je suis limité à 500 par jour)
            $_SESSION['forecastDailyDay'] = $forecastDailyDay;
        }
    } else {
        echo getError($http_response_header[0]);
    }
}

// Si la varaible de session n'existe pas, j'excute la requête
if (!isset($_SESSION['forecastNextHours'])) {
    $route = 'forecast/nextHours';
    $request = 'insee=' . $_SESSION['insee'] . '&hourly=true';

    $data = @file_get_contents($baseUrl . $route . '?token=' . $token . '&' . $request);

    // Si l'entête contient '200' sinon je retourne un message d'erreur
    if (strpos($http_response_header[0], "200")) {
        if ($data !== false) {
            $forecastNextHours = json_decode($data);

            $_SESSION['forecastNextHours'] = $forecastNextHours;
        }
    } else {
        echo getError($http_response_header[0]);
    }
}

// Si la varaible de session n'existe pas, j'excute la requête
if (!isset($_SESSION['forecastDaily'])) {
    $route = 'forecast/daily';
    $request = 'insee=' . $_SESSION['insee'];

    $data = @file_get_contents($baseUrl . $route . '?token=' . $token . '&' . $request);

    // Si l'entête contient '200' sinon je retourne un message d'erreur
    if (strpos($http_response_header[0], "200")) {
        if ($data !== false) {
            $forecastDaily = json_decode($data);

            $_SESSION['forecastDaily'] = $forecastDaily;
        }
    } else {
        echo getError($http_response_header[0]);
    }
}

include_once('graph.php');

?>

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="https://spa.matthieudevilliers.fr">
            <img src="https://spa.matthieudevilliers.fr/images/SpaLogo.webp" alt="Logo du site" width="30" height="30" class="d-inline-block align-top" loading="lazy">
            &nbsp;PureSpa - Corbas
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <?php

                // Je récupère l'historique des températures sur les 7 derniers jours
                // Si il n'y a aucune données, je n'affiche rien

                $date = date("Y-m-d H:i:s", time() - 604800);

                $sql = 'SELECT * FROM `dev_spa_temperature` WHERE horodateur > ? ORDER BY horodateur ASC LIMIT 1';
                $response = $bdd->prepare($sql);
                $response->execute(array($date));

                $donnee = $response->fetch();

                if ($donnee != null) {
                ?>
                    <li class="nav-item d-flex align-items-center">
                        <?php
                        $req = $bdd->query('SELECT * FROM `dev_spa_temperature` ORDER BY horodateur DESC LIMIT 1');

                        $donnees = $req->fetch();
                        ?>
                        <i class="text-white <?php echo $donnees['meteo']; ?>"></i>
                        &nbsp;
                        <span class="text-success"><?php echo $donnees['temp_ext_spa']; ?>°C <i class="<?php echo $donnees1['evol_temp_ext_spa']; ?>"></i></span>
                        &nbsp;<strong>-</strong>&nbsp;
                        <span class="text-info"><?php echo $donnees['temp_int_spa']; ?>°C <i class="<?php echo $donnees1['evol_temp_int_spa']; ?>"></i></span>
                        <?php

                        $req->closeCursor();
                        ?>
                    </li>
                <?php
                }

                $response->closeCursor();

                if (isset($_SESSION['mail'])) {
                ?>
                    <li class="nav-item d-flex align-items-center">
                        <!-- Ouvre le Modal -->
                        <span class="nav-link d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalMeteo">
                            Info Météo
                        </span>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center" href="https://spa.matthieudevilliers.fr/pages/achat/">Faire un achat</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span style="vertical-align: sub;"><?php echo $_SESSION['prenom'] ?>&nbsp;<?php echo $_SESSION['nom'] ?>&nbsp;</span>
                            <img src="https://spa.matthieudevilliers.fr/images/SpaLogo.webp" alt="Logo de l'utilisateur" class="d-inline-block align-top" loading="lazy" width="30" height="30">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                            if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com" || $_SESSION['mail'] == 'test@matthieudevilliers.fr') {
                            ?>
                                <p class="dropdown-item h5">Panel Admin</p>
                                <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/temperature/">Enregistrement des températures</a>
                                <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/admin/">Enregistrement des achats</a>
                                <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/admin_achat/">Historique des achats</a>
                                <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/admin_temp/">Historique des températures</a>
                                <div class="dropdown-divider"></div>
                            <?php
                            }
                            ?>
                            <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/profil/">Mon profil</a>
                            <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/parametres/">Paramètres</a>
                            <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/pages/cgu/">CGU</a>
                            <a class="dropdown-item" href="https://spa.matthieudevilliers.fr/scripts/deconnexion/">Déconnexion</a>
                        </div>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center" href="https://spa.matthieudevilliers.fr">Inscription & Connexion</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center" href="https://spa.matthieudevilliers.fr/pages/cgu/">Conditions Générales d'Utilisation</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="modalMeteo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h2>Prévisions journalière</h2>
                            <p>
                                <?php

                                $data = $_SESSION['forecastDailyDay'];

                                // Converti la donnée reçu par l'API en DateTime
                                $dateForecastDailyDay = new \DateTime($data->update);

                                echo ("Aujourd'hui à {$data->city->name}, il fera une température comprise entre {$data->forecast->tmin}°C et {$data->forecast->tmax}°C.<br>
                                    La probabilité de pluie est de {$data->forecast->probarain} % avec environ {$data->forecast->rr10}mm de pluie (maximum {$data->forecast->rr1}mm).<br>
                                    Le vent soufflera en moyenne à {$data->forecast->wind10m} km/h avec des rafales à {$data->forecast->gust10m} km/h.<br>
                                    La météo sera : " . WEATHER[$data->forecast->weather] . "<br>
                                    <small>Mise à jour : {$dateForecastDailyDay->format('H:i d/m/Y')}</small>");
                                ?>
                            </p>
                            <hr>
                            <h2>Prévisions horaires pour les 12 prochaines heures</h2>
                            <canvas id="forecastNextHours"></canvas>
                            <!-- Ce message s'affiche uniquement via un @media -->
                            <p class="alertGraph">Les graphiques ne sont pas disponible dans la version mobile et tablettes.</p>
                            <p>
                                <?php
                                $data = $_SESSION['forecastNextHours'];

                                // Converti la donnée reçu par l'API en DateTime
                                $dateNextHours = new \DateTime($data->update);
                                echo ("<small>Mise à jour : {$dateNextHours->format('H:i d/m/Y')}</small>");
                                ?>
                            </p>
                            <h2>Prévisions journalières sur les 14 prochains jours</h2>
                            <canvas id="forecastDaily"></canvas>
                            <!-- Ce message s'affiche uniquement via un @media -->
                            <p class="alertGraph">Les graphiques ne sont pas disponible dans la version mobile et tablettes.</p>
                            <p>
                                <?php
                                $data = $_SESSION['forecastDaily'];

                                // Converti la donnée reçu par l'API en DateTime
                                $dateForecastDaily = new \DateTime($data->update);
                                echo ("<small>Mise à jour : {$dateForecastDaily->format('H:i d/m/Y')}</small>");
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>