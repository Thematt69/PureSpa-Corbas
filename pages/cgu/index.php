<?php

session_start();

include('../../scripts/import/index.php');

$reponse = $bdd->query('SELECT * FROM dev_spa_prix');

while ($donnees = $reponse->fetch()) {
    switch ($donnees['nom']) {
        case 'ABONNEMENT 2 HEURES':
            $two_hours_prix = $donnees['prix'];
            break;

        case 'ABONNEMENT JOURNALIER':
            $journalier_prix = $donnees['prix'];
            break;

        case 'ABONNEMENT HEBDOMADAIRE':
            $hebdomadaire_prix = $donnees['prix'];
            break;

        case 'ABONNEMENT MENSUEL':
            $mensuel_prix = $donnees['prix'];
            break;

        case 'SERVICE DE BOISSONS':
            $boissons_prix = $donnees['prix'];
            break;

        case 'PRÊT DE TONGS':
            $tongs_prix = $donnees['prix'];
            break;

        case 'PRÊT DE SERVIETTE':
            $serviette_prix = $donnees['prix'];
            break;

        case 'DÉGRADATION':
            $degradation_prix = $donnees['prix'];
            break;

        case 'SUPPLÉMENT NOCTURNE':
            $supp_nocturne_prix = $donnees['prix'];
            break;
    }
}

$reponse->closeCursor();

?>

<html lang="fr">

<head>
    <title>PureSpa - Conditions Générales d'Utilisation</title>

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

                        <h2>Conditions Générales d'Utilisation</h2>

                        <br>

                        <h4>1. Tarification</h4>
                        <br>

                        <p>
                            Les tarifs ci-dessous sont susceptibles de varier, seuls les prix affichés sur le site font foi.
                            <br>
                            Les services ne sont pas remboursables ni échangeables.
                            <br>
                            Les abonnements peuvent être déplacés sur demande auprès du service client, mais ne sont pas remboursables.
                            <br>
                            L'administrateur se réserve le droit de modifier ou ajouter de lui-même vos achats.
                            <br>
                            Il est possible d'avoir des achats en attente de payement, il est recommandé de les payer au plus vite.
                        </p>

                        <br>

                        <h5>1.1. Abonnement</h5>
                        <br>

                        <p>
                            Quatre types d'abonnement sont disponibles : Nocturne, Journalier, Hebdomadaire ou Mensuel.
                            <br>
                            <li>L'abonnement 2 heures est disponible pendant 2h à l'heure de début, il coûte <?php echo $two_hours_prix; ?> €. Toutes heures supplémentaires commencée sera dû, par tranche de 2h.
                                <br>
                            <li>L'abonnement Journalier est effectif pendant 24 h à la date de début et coûte <?php echo $journalier_prix; ?> €.
                                <br>
                            <li>L'abonnement Hebdomadaire a une durée de 7 jours à compter de la date d’effet et coûte <?php echo $hebdomadaire_prix; ?> €, un rappel par mail est fait 2 jours avant la fin de l'abonnement.
                                <br>
                            <li>L'abonnement Mensuel est effectif pendant environ 30,4 jours (durée moyenne d'un mois) et coûte <?php echo $mensuel_prix; ?> €, un rappel est effectué 2 jours avant la date d’échéance.
                        </p>

                        <br>

                        <h5>1.2. Services supplémentaires</h5>
                        <br>

                        <p>
                            Des services supplémentaires peuvent améliorer le plaisir d'utilisation du PureSpa – Corbas.
                            <br>
                            L'utilisation des bulles est active pendant une durée de 30 minutes, arrêt effectué automatiquement par le PureSpa, elle est inclus dans tous les abonnements.
                            <br>
                            Ce service peut générer une mousse à la surface de l'eau, elle n'est pas nocive, un produit de traitement anti-mousse peut être appliqué 1 h avant la baignade.
                            <br>
                            Le service de boissons, permet à un utilisateur du PureSpa - Corbas de se faire servir une boisson de son choix pendant sa baignade.
                            <br>
                            Ses boissons seront sans alcool, dans la limite des stocks et en dehors des heures de fonctionnement des bulles.
                            <br>
                            Ce service est valable pour une journée et pour une boisson par personne au tarif de <?php echo $boissons_prix; ?> €.
                            <br>
                            Il inclut la mise à disposition d'un repose-verre spécialement conçu pour votre confort.
                            <!-- <br>
                            L'usage nocturne du PureSpa - Corbas comprend la lumière et les différents services nocturnes au prix de <?php echo $supp_nocturne_prix; ?> €, elle est inclus dans les abonnements Nocturne, Hebdomadaire et Mensuel. -->
                            <br>
                            PureSpa - Corbas met à disposition en prêt des équipements indispensables à la baignade, des tongs ou une serviette. Ce service est facturé, respectivement, <?php echo $tongs_prix; ?> € et <?php echo $serviette_prix; ?> €.
                            <br>
                            Enfin, si une dégradation est constatée sur le PureSpa, elle sera facturée <?php echo $degradation_prix; ?> €, le prix peut augmenter proportionellement au dommage.
                        </p>

                        <br>

                        <h4>2. Fonctionnement</h4>
                        <br>

                        <p>
                            Le PureSpa - Corbas est régulièrement contrôlé, ses tests peuvent être consultés sur demande.
                            <br>
                            La température est souvent mise à jour et disponible sur le site internet, voir <a href="http://spa.matthieudevilliers.fr/pages/achat/">"Faire un achat"</a>.
                            <br>
                            La température de l'eau peut varier en fonction de la température extérieure, de la météo ou de l'usage du spa.
                            <br>
                            En moyenne, le spa augmente de 2 °C par heure, en étant fermé ; lors de son utilisation, la température est régulée, mais une baisse de température peut être remarquée, si la température extérieure est basse et/ou lors de l'utilisation des bulles.
                            <br>
                            Le PureSpa - Corbas n'est pas disponible pendant la saison hivernale et son usage est interdit si la température extérieure est inférieure ou égale à 4 °C.
                            <br>
                            La température de l'eau ne peut également pas être supérieure à 40 °C et il est recommandé de rester entre 32 °C et 34 °C selon le climat.
                            <br>
                            Il est strictement interdit de consommer de l'alcool ou de la nourriture pendant l’utilisation du PureSpa.
                            <br>
                            Il est aussi interdit lors d’orage ou de chute de grêle.
                        </p>

                        <br>

                        <h4>3. Espace Client</h4>
                        <br>

                        <p>
                            Les utilisateurs du PureSpa - Corbas disposent d'un espace client personnalisé.
                            <br>
                            Cet espace est sécurisé par un mot de passe, défini à l'inscription. Il peut être modifié depuis l'espace client.
                            <br>
                            Dans cet espace, vous pouvez effectuer des achats, visualisée ou encore régler vos impayées.
                            <br>
                            La possession d'un compte client implique d'avoir accepté les Conditions Générales d'Utilisation.
                        </p>

                        <br>

                        <h4>4. Service Client et Maintenance</h4>
                        <br>

                        <p>
                            Si vous rencontrer une difficulté ou avez une suggestion, merci de nous contacter par <a href="mailto:webmaster@matthieudevilliers.fr">mail</a>.
                            <br>
                            Des maintenances peuvent survenir, nous sommes désolée si l'accès au site peut être ralenti ou temporairement inaccessibles.
                            <br>
                            Enfin le PureSpa - Corbas se réserve le droit de modification et de suppression sur les profils, achats ou autres informations disponibles sur le site.
                            <br>
                            Sur demande par <a href="mailto:webmaster@matthieudevilliers.fr">mail</a>, le client peut recevoir gratuitement l'entièreté des informations personnelles le concernant.
                        </p>

                        <br>

                        <h4>5. RGPD</h4>
                        <br>

                        <p>
                            Cette partie est en cours de réalisation, veuillez-nous contacter si besoin.
                        </p>

                        <br>

                        <p>
                            <small>
                                Mise à jour le 22/10/2020
                            </small>
                        </p>

                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

</body>

</html>