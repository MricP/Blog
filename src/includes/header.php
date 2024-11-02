<?php
    session_start();

    // if(!isset($_SESSION['currentUser'])) {
    //     $_SESSION['currentUser'] = NULL;
    // }
    // $_SESSION['article-title'] = "";
    // $_SESSION['article-content'] = "";
    // if(!isset($_SESSION['categories'])){
    //     $_SESSION['categories'] = [];
    // }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>"> <!-- CONSTANTE VERS LE CSS -->
    <title>Sport</title>
</head>
<body>
    <Header>
        <nav>
            <ul>
                <li><a href="../pages/catalog.php?id=1">SportDebate</a></li>
                <li><a href="../pages/createArticle.php">Créer un article</a></li>
                <?php 
                    if(isConnected()){ 
                        if(selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") {
                            echo "<li><a href='../pages/admin.php'>Menu admin</a></li>";
                        }
                        echo "<li><a href='../pages/auth.php?logout=1' class='lienSeConnecter'>Se déconnecter</a></li>";
                    } else {
                        echo "<li><a href='../pages/auth.php' class='lienSeConnecter'>Se connecter</a></li>";
                    }
                ?>
            </ul>
        </nav>
    </Header>
