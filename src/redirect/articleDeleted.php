<?php 
    session_start();

    require "../utils/functions.php";

    deleteArticle();

    header("Location: ../pages/catalog.php?id=1");
    exit();
?>
