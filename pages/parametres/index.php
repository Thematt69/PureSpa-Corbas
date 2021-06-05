<?php

session_start();

include('../../scripts/import/index.php');

if (isset($_SESSION['mail'])) {
    //ne rien faire
} else {
    header('Location: https://spa.matthieudevilliers.fr');
}

if (isset($_POST['mail_actuel'])) {
    if ($_SESSION['mail'] == htmlspecialchars($_POST['mail_actuel'])) {

        $req = $bdd->prepare('UPDATE dev_spa_compte SET mail = :mail_nouveau WHERE mail = :mail_actuel');

        $req->execute(array(
            'mail_actuel' => $_SESSION['mail'],
            'mail_nouveau' => htmlspecialchars($_POST['mail_nouveau'])
        ));

        $_SESSION['mail'] = htmlspecialchars($_POST['mail_nouveau']);

        $req->closeCursor();

        // Destinataires
        $to = $_SESSION['mail'] . ',' . htmlspecialchars($_POST['mail_actuel']);

        // Sujet
        $subject = 'Changement d\'adresse mail - PureSpa Corbas';

        // Message
        $contenu = '
            <html>
                <body>
                    <h4>Votre adresse mail d\'être modifié</h4>
                    <br>
                    <p>Bonjour,</p>
                    <p>Votre adresse mail vient d\'être modifié depuis votre profil.</p>
                    <p>Si vous n\'êtes pas à l\'origine de cette modification, répondez directement à ce mail.</p>
                    <br>
                    <p>Le PureSpa Corbas vous remercie pour votre visite et reste à votre disposition.</p>
                </body>
            </html>
            ';

        // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'Reply-To: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
        $headers[] = 'From: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
        $headers[] = 'Bcc: Matthieu Devilliers <devilliers.matthieu@gmail.com>';
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Envoi du mail
        mail($to, $subject, $contenu, implode("\r\n", $headers));

        header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
    }
} elseif (isset($_POST['mdp_actuel'])) {
    if (password_verify($_POST['mdp_actuel'], $_SESSION['mdp'])) {

        $req1 = $bdd->prepare('UPDATE dev_spa_compte SET mdp = :mdp_nouveau WHERE mdp = :mdp_actuel');

        $req1->execute(array(
            'mdp_actuel' => $_SESSION['mdp'],
            'mdp_nouveau' => password_hash(htmlspecialchars($_POST['mdp_nouveau']), PASSWORD_DEFAULT)
        ));

        $_SESSION['mdp'] = password_hash(htmlspecialchars($_POST['mdp_nouveau']), PASSWORD_DEFAULT);

        $req1->closeCursor();

        // Sujet
        $subject = 'Changement de mot de passe - PureSpa Corbas';

        // Message
        $contenu = '
            <html>
                <body>
                    <h4>Votre mot de passe vient d\'être modifié</h4>
                    <br>
                    <p>Bonjour,</p>
                    <p>Votre mot de pase vient d\'être modifié depuis votre profil.</p>
                    <p>Si vous n\'êtes pas à l\'origine de cette modification, modifiez-le au plus vite sur votre <a href="https://spa.matthieudevilliers.fr/pages/profil/">compte</a>.</p>
                    <p>Si le problème persiste, répondez directement à ce mail.</p>
                    <br>
                    <p>Le PureSpa Corbas vous remercie pour votre visite et reste à votre disposition.</p>
                </body>
            </html>
            ';

        // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'Reply-To: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
        $headers[] = 'From: Matthieu Devilliers <webmaster@matthieudevilliers.fr>';
        $headers[] = 'Bcc: Matthieu Devilliers <devilliers.matthieu@gmail.com>';
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Envoi du mail
        mail($_SESSION['mail'], $subject, $contenu, implode("\r\n", $headers));

        header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Paramètres</title>

    <!-- Import -->
    <?php include('../../widgets/import/index.php'); ?>

    <!-- Affichage preview image -->
    <script>
        function handleFiles(files) {
            var imageType = /^image\//;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!imageType.test(file.type)) {
                    alert("veuillez sélectionner une image");
                } else {
                    if (i == 0) {
                        preview.innerHTML = '';
                    }
                    var img = document.createElement("img");
                    img.classList.add("obj");
                    img.file = file;
                    img.width = 200;
                    img.height = 200;
                    preview.appendChild(img);
                    var reader = new FileReader();
                    reader.onload = (function(aImg) {
                        return function(e) {
                            aImg.src = e.target.result;
                        };
                    })(img);
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
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
                        if ($_SESSION['mail'] == "devilliers.matthieu@gmail.com") {
                        ?>
                            <!-- Modifier ma photo -->
                            <div class="media">
                                <div class="media-body">
                                    <form class="form" method="POST" action="">
                                        <h2 class="mt-0 mb-1">Modifier ma photo</h2>
                                        <br><br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupFileAddon01">Image</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="images" accept="image/*" onchange="handleFiles(files)" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required>
                                                <label class="custom-file-label" for="inputGroupFile01">Choisisez une image</label>
                                            </div>
                                        </div>
                                        <br>
                                        <button class="btn btn-primary" type="submit" id="login-button">Enregistrer</button>
                                    </form>
                                </div>
                                <span id="preview" style="width:200; height:200; padding-left: 20px;">
                                </span>
                            </div>
                            <hr><br>
                        <?php
                        }
                        ?>
                        <!-- Modifier mon mail -->
                        <form class="form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="mt-0 mb-1">Modifier mon mail</h2>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon">Adresse mail actuel</span>
                                        </div>
                                        <input type="email" name="mail_actuel" class="form-control" aria-describedby="basic-addon" required>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Nouvelle adresse mail</span>
                                        </div>
                                        <input type="email" name="mail_nouveau" class="form-control" aria-describedby="basic-addon1" required>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                        <hr><br>
                        <!-- Modifier mon mot de passe -->
                        <form class="form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="mt-0 mb-1">Modifier mon mot de passe</h2>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Mot de passe actuel</span>
                                        </div>
                                        <input type="password" name="mdp_actuel" class="form-control" required>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Nouveau mot de passe</span>
                                        </div>
                                        <input type="password" name="mdp_nouveau" class="form-control" required>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                        <hr><br>
                        <!-- Modifier lien météo -->
                        <?php

                        if (isset($_POST['cp'])) {
                            $route = 'location/cities';
                            $request = 'search=' . $_POST['cp'];


                            $data = @file_get_contents($baseUrl . $route . '?token=' . $token . '&' . $request);

                            if (strpos($http_response_header[0], "200")) {
                                if ($data !== false) {
                                    $locationCities = json_decode($data);
                                }
                            } else {
                                echo getError($http_response_header[0]);
                            }
                        } elseif (isset($_POST['insee'])) {
                            // Je met à jour le code insee en BDD
                            $sql1 = 'UPDATE dev_spa_compte 
                                SET insee = ? 
                                WHERE mail = ?';
                            $response1 = $bdd->prepare($sql1);
                            $response1->execute(array($_POST['insee'], $_SESSION['mail']));
                            // Je met à jour le code insee en variable de session
                            $_SESSION['insee'] = $_POST['insee'];
                            $response1->closeCursor();
                            // Je supprime les variables de session lié au stockage de la méteo
                            unset($_SESSION['forecastDailyDay']);
                            unset($_SESSION['forecastNextHours']);
                            unset($_SESSION['forecastDaily']);
                            echo ('<div class="alert alert-warning" role="alert">La modification a bien été prise en compte, merci de changer de page avant de regarder la météo.</div>');
                        }

                        if (isset($locationCities)) {
                            $count = count($locationCities->cities);
                            echo ('<form action="" method="post">');
                            if ($count == 0) {
                                // Si aucune ville n'est trouver, je redirige
                                header('Location: https://spa.matthieudevilliers.fr/pages/parametres/');
                            } elseif ($count == 1) {
                                echo ('<h2 class="text-center">Nous avons trouvez ' . $count . ' ville.</h2>
                                    <h4 class="text-center">Merci de choisir la ville ci-dessous :</h4>');
                            } else {
                                echo ('<h2 class="text-center">Nous avons trouvez ' . $count . ' villes.</h2>
                                    <h4 class="text-center">Merci de choisir une des villes ci-dessous :</h4>');
                            }
                            echo ('<div class="btn-group-vertical d-flex justify-content-center" role="group" aria-label="Basic outlined example">');
                            foreach ($locationCities->cities as $city) {
                                echo ('<button type="submit" name="insee" value="' . $city->insee . '" class="btn btn-outline-primary">' . $city->name . '</button>');
                            }
                            echo ('</div></form>');
                        } else {
                        ?>
                            <form class="form" method="POST" action="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="mt-0 mb-1">Modifier la localisation de ma météo</h2>
                                        <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Code postal</span>
                                            </div>
                                            <input type="number" name="cp" class="form-control" required>
                                        </div>
                                        <br>
                                        <button class="btn btn-primary" type="submit">Rechercher</button>
                                    </div>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

</body>

</html>