<?php

session_start();

include('scripts/verif/index.php');

if (isset($_SESSION['mail'])) {
    header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
}

if ($_GET['mail']) {
    $sqlGoogle = 'SELECT *
                FROM dev_spa_compte
                WHERE mail = ?';

    $req = $bdd->prepare($sqlGoogle);
    $req->execute(array($_GET['mail']));

    $donneeGoogle = $req->fetch();

    if (isset($donneeGoogle['mail'])) {
        $_SESSION['nom'] = $donneeGoogle['nom'];
        $_SESSION['prenom'] = $donneeGoogle['prenom'];
        $_SESSION['mail'] = $donneeGoogle['mail'];
        $_SESSION['mdp'] = $donneeGoogle['mdp'];
        $_SESSION['insee'] = $donneeGoogle['insee'];
        $_SESSION['token'] = $donneeGoogle['token'];
        echo ('coucou');

        header('Location: https://spa.matthieudevilliers.fr/pages/profil/');
    } else {
        echo ('rien trouvé');
    }

    $req->closeCursor();
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <title>PureSpa - Inscription - Connexion</title>

    <!-- Import -->
    <?php include('widgets/import/index.php'); ?>
</head>

<body>

    <?php include('widgets/navbar/index.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <br>
                <div class="card">
                    <div class="card-body">

                        <h2 class="text-center">Inscription</h2>

                        <br>

                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/profil/">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput3">Nom</label>
                                        <input name="nom" type="text" class="form-control" id="exampleFormControlInput3" placeholder="Nom" required>
                                        <label for="exampleFormControlInput4">Prénom</label>
                                        <input name="prenom" type="text" class="form-control" id="exampleFormControlInput4" placeholder="Prénom" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Adresse mail</label>
                                        <input name="mail" type="email" class="form-control" id="exampleFormControlInput1" placeholder="nom.prenom@example.com" required>
                                        <label for="exampleFormControlInput2">Mot de passe</label>
                                        <input name="mdp" type="password" class="form-control" id="exampleFormControlInput2" placeholder="Mot de passe" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="submit">Inscription</button>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <br>
                <div class="card">
                    <div class="card-body">

                        <h2 class="text-center">Connexion</h2>

                        <br>

                        <form class="form" method="POST" action="https://spa.matthieudevilliers.fr/pages/profil/">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput5">Adresse mail</label>
                                        <input name="mail" type="email" class="form-control" id="exampleFormControlInput5" placeholder="nom.prenom@example.com" required>
                                        <label for="exampleFormControlInput6">Mot de passe</label>
                                        <input name="mdp" type="password" class="form-control" id="exampleFormControlInput6" placeholder="Mot de passe" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="submit">
                                        Connexion
                                    </button>
                                    <a href="mailto:webmaster.matthieudevilliers.fr">
                                        Mot de passe oublié ?
                                    </a>
                                </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <br>
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Connexion avec Google</h2>

                        <br>

                        <div class="d-flex justify-content-center">
                            <div class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
                        </div>
                        <script>
                            function onSignIn(googleUser) {
                                var profile = googleUser.getBasicProfile();
                                // console.log('Full Name: ' + profile.getName());
                                // console.log('Given Name: ' + profile.getGivenName());
                                // console.log('Family Name: ' + profile.getFamilyName());
                                // console.log("Image URL: " + profile.getImageUrl());
                                // console.log("Email: " + profile.getEmail());
                                getMail(profile.getEmail());

                                var id_token = googleUser.getAuthResponse().id_token;
                                // console.log("ID Token: " + id_token);
                                // getToken(id_token);

                            }

                            function getMail(mail) {
                                if (window.location.search === '') {
                                    document.location.href = "https://spa.matthieudevilliers.fr/?mail=" + mail;
                                }
                            }

                            // function getToken(token) {
                            //     if (window.location.search === '') {
                            //         document.location.href = "https://spa.matthieudevilliers.fr/?token=" + token;
                            //     }
                            // }
                        </script>

                        <br>

                    </div>
                </div>
            </div>
            <br>
        </div>
    </div> -->

</body>

</html>