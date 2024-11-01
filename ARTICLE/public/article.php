<!DOCTYPE html>
<html>
    <head>
        <title>Sport Live</title>
        <link rel="stylesheet" href="./app.css" />
        <?php
        session_start();
        //$_SESSION["currentUser"] = 2;
        require "../src/functions.php";
        require "../src/components/navbar.php";
        
        
        // Vérifier si l'article existe
        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 0) {
            $_SESSION['lastArticle'] = (int) $_GET['id'];
            $article = selectArticle($_SESSION['lastArticle']);
            if (empty($article)) {
                die("<div class=\"error-container\"><p class=\"error-message\">Numéro d'article invalide</p><a href=\"./page.php?id=1\"><div class=\"error-redirect\">Revenir à l'accueil</div></a></div>");
            }
        } else {
            die("<div class=\"error-container\"><p>URL invalide</p><a href=\"./page.php?id=1\">Revenir à l'accueil</a></div>");
        }

        /*
        ---------- GESTION DU FORMULAIRE DES COMMENTAIRES ------------------------------------------------------------
        */

        function existComment() {
            return isset($_POST['commentText']) && !empty($_POST['commentText']);
        }

        function existDraftComment() {
            return isset($_SESSION['commentText']) && !empty($_SESSION['commentText']);
        }
        
        if (isConnected()) {
            if (existDraftComment()) {
                // Quand l'utilisateur est connecté et est redirigé vers le dernier article où il a essayé de commenter 
                $commentText = $_SESSION['commentText'];
                // On pré-remplit le textarea du formulaire de création de commentaire avec son commentaire pour qu'il puisse le modifier.
                unset($_SESSION['commentText']);
            }
            if (existComment()) {
                /*
                L'utilisateur connecté souhaite créer un nouveau commentaire.
                On utilise uniquement la méthode POST sans créer de variable de session.
                */
                createComment($_POST["commentText"]);
            }
        } else {
        // Si l'utilisateur n'est pas connecté
            if(existComment()) {
                // On sauvegarde son commentaire dans une variable de session
                $_SESSION['commentText'] = $_POST['commentText'];
                // Puis on le redirige vers la page d'authentification
                header("Location: auth.php");
                exit();
            }
        }

        /* Affichage des données de l'article*/
        $creator = selectUser($article["id_author"]);
        $categories = selectCategories($_SESSION['lastArticle']);
        $comments = selectComments($_SESSION['lastArticle']);
        $previousArticle = selectPreviousArticle($_SESSION['lastArticle']);
        $nextArticle = selectNextArticle($_SESSION['lastArticle']);
        ?>
    </head>
    <body>
        <div class="article-container">
            <h1><?php echo $article['title'] ; ?></h1>
            <div class="pastilles-container">
                <?php 
                    for ($i = 0; $i < sizeof($categories); $i++) {
                        echo '<div class="pastille-category"><span>'.$categories[strval($i)][$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']].'</span></div>';
                    }
                ?>
            </div>
            <p class="article-text"><?php echo $article[$GLOBALS['db']['tables']['ARTICLES']['fields']['TEXT']]?></p>
            <p class="article-creator"><?php echo $creator[$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']]; ?></p>
            <p class="article-date">
                Crée le <?php echo formatDate($article[$GLOBALS['db']['tables']['ARTICLES']['fields']['DATE']]); ?>
            </p>
        </div>
        <form class="form-del-article" action="./redirect/articleDeleted.php" method="POST">
            <?php if (isset($_SESSION["currentUser"]) && $_SESSION["currentUser"] == $article[$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']]) {?>
                <button type="submit" class="del-article-submit-button">Supprimer l'article</button>
            <?php }?>
        </form>
        <div class="section-separator"></div>
        <div class="commentaires-container">
            <h2>Commentaires</h2>
            <form class="form-new-commentaire" method="POST">
                <label class="new-commentaire-label" for="commentText">Votre commentaire</label>
                <textarea class="new-commentaire-text" name="commentText"><?php echo (isset($commentText) && !empty($commentText) ? $commentText : "" ) ?></textarea>
                
                <button type="submit" class="new-commentaire-submit-button"><?php echo (empty($_SESSION["currentUser"]) ? "Se connecter" : "Envoyer le commentaire"); ?></button>
            </form>
            <?php 
                for ($i = 0; $i < sizeof($comments); $i++) {
                    require "../src/components/comment-box.php";
                }
            ?>
            <?php 
            
            ?>
        </div>
        <div class="section-separator"></div>
        <div class="page-switch-container">
            <?php if (!empty($previousArticle)) { ?>
                <a href="article.php?id=<?php echo $previousArticle[$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']] ?>">
                    <span class="page-switch-text">Précédent</span>
                </a>
            <?php } ?>
            <?php if (!empty($nextArticle)) { ?>
                <a href="article.php?id=<?php echo $nextArticle[$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']] ?>">
                    <span class="page-switch-text">Suivant</span>
                </a>
            <?php } ?>
        </div>
    </body>
</html>