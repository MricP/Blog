<!DOCTYPE html>
<html>
    <head>
        <title>Sport News</title>
        <link rel="stylesheet" href="./app.css" />
        <link href="/your-path-to-fontawesome/css/solid.css" rel="stylesheet" />
        <?php
        session_start();

        require "./src/functions.php";
        $_SESSION["currentUser"] = 2;
        echo $_SESSION["currentUser"];
        
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            if ($_GET['id'] >= 0) {
                $_SESSION["lastArticle"] = (int) $_GET['id'];;
            } else {
                die("Numéro d'article invalide");
            }
            
        } else {
            die("Article non trouvé.");
        }

        if(isset($_POST['commentText']) && !empty($_POST['commentText'])) {
            if (isset($_SESSION['commentText'])) {
                unset($_SESSION['commentText']);
            } else {
                $_SESSION['commentText'] = $_POST['commentText'];
            }
            unset($_POST['commentText']);
        }
        
        if (isset($_SESSION['currentUser'])) {
            if (isset($_SESSION['commentText'])) {
                createComment();
            }
        }

        /* Affichage des données de l'article*/
        $article = selectArticle($_SESSION["lastArticle"]);
        $creator = selectUser($article["id_author"]);
        $categories = selectCategories($_SESSION["lastArticle"]);
        $comments = selectComments($_SESSION["lastArticle"]);
        ?>
    </head>
    <body>
        <div class="navbar-container">
            <a href="./article.php?id=0" class="navbar-option">Sport Live</a>
            <a href="./login.php" class="navbar-option">Se connecter</a>
        </div>
        <div class="section-separator"></div>
        <div class="article-container">
            <h1><?php echo $article['title'] ; ?></h1>
            <div class="pastilles-container">
                <?php 
                    for ($i = 0; $i < sizeof($categories); $i++) {
                        echo '<div class="pastille-category"><span>'.$categories[strval($i)]["texte"].'</span></div>';
                    }
                ?>
            </div>
            
            <div class="article-paragraphes-container">
                <?php 
                    echo '<p class="article-paragraphe">'.$article["description"].'</p>';
                ?>
            </div>
            <p class="article-creator"><?php echo $creator["pseudo"]; ?></p><i class="fa-solid fa-xmark"></i>
        </div>
        <div class="section-separator"></div>
        <div class="commentaires-container">
            <h2>Commentaires</h2>
            <form class="form-new-commentaire" method="POST">
                <label class="new-commentaire-label" for="commentText">Votre commentaire</label>
                <textarea class="new-commentaire-text" name="commentText"></textarea>
                
                <button type="submit" class="new-commentaire-submit-button"><?php echo (empty($_SESSION["currentUser"]) ? "Se connecter" : "Envoyer le commentaire"); ?></button>
            </form>
            <?php 
                for ($i = 0; $i < sizeof($comments); $i++) {
                    require "comment-box.php";
                }
            ?>
            <?php 
            
            ?>
        </div>
        <div class="section-separator"></div>
        <div class="page-switch-container">
            <span class="page-switch-text">Précédent</span>
            <div class="page-circle-buttons-container">
            <span class="page-switch-text">Suivant</span>
        </div>
    </body>
</html>