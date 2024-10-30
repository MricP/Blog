<?php
    session_start();

    if(!isset($_SESSION['currentUser'])) {
        $_SESSION['currentUser'] = NULL;
    }
    $_SESSION['article-title'] = "";
    $_SESSION['article-content'] = "";
    if(!isset($_SESSION['categories'])){
        $_SESSION['categories'] = [];
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Blog</title>
</head>
<body>
    <Header>
    <nav>
        <ul>
            <li>
                SportDebate
            </li>
            <li>
                <a href="./page.php">Profiter des articles</a>
            </li>
            <li>
                <a href="./creationArticle.php">Créer un article</a>
            </li>
            <li>
                <a href="./auth.php"  class="lienSeConnecter"> Se Connecter</a>
            </li>
        </ul>
    </nav>
    </Header>
