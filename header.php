<?php 
    session_start();
    if(!isset($_SESSION['categories'])){
        $_SESSION['categories'] = [];
    }
    $_SESSION['article-title'] = "";
    $_SESSION['article-content'] = "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation Article</title>
    <link rel="stylesheet" href="index.css"/>
</head>
<body>

<nav>
    <ul>
        <li>
            SportDebate
        </li>
        <li>
            <a href="#">Profiter des articles</a>
        </li>
        <li>
            <a href="#">Créer un article</a>
        </li>
        <li>
            <a href="#"  class="lienSeConnecter"> Se Connecter</a>
        </li>
    </ul>
</nav>