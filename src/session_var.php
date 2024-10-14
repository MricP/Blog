<?php
    session_start();

    if(!isset($_SESSION['utilisateurConnected'])) {
        $_SESSION['utilisateurConnected'] = NULL;
    }
?>
