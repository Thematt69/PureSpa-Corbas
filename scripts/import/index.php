<?php

if (!$_SERVER['HTTPS']) {
    header('Location: https://spa.matthieudevilliers.fr' . $_SERVER['PHP_SELF'] . '');
}

include($_SERVER['DOCUMENT_ROOT'] . '/spa.matthieudevilliers.fr/access/bdd.php');
