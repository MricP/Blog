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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/global.css">
    <title>Blog</title>
</head>
<body>
    <Header>
    <nav>
        <ul>
            <li><a href="./page.php">SportDebate</a></li>
            <li><a href="./page.php">Profiter des articles</a></li>
            <li><a href="./creationArticle.php">Créer un article</a></li>
            
                <?php if(isset($_SESSION['currentUser'])){ ?>
                    <li><a href='./auth.php?logout=1' class='lienSeConnecter'>Se déconnecter</a></li>
                <?php } else { ?>
                    <li><a href='./auth.php' class='lienSeConnecter'>Se connecter</a></li>
                <?php } ?>
            
            
            
        </ul>
    </nav>
    </Header>
