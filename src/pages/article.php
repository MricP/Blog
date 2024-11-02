<?php
    require_once('../utils/functions.php');
    require_once('../includes/header.php');
    
        
    // Vérifier si l'article existe
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 0) {
        $_SESSION['lastArticle'] = (int) $_GET['id'];
        $article = selectArticle($_SESSION['lastArticle']);
        if (empty($article)) {
            die("<div class=\"error-container\"><p class=\"error-message-article\">Numéro d'article invalide</p><a href=\"./catalog.php?id=1\"><div class=\"error-redirect\">Revenir à l'accueil</div></a></div>");
        }
    } else {
        die("<div class=\"error-container\"><p>URL invalide</p><a href=\"./catalog.php?id=1\">Revenir à l'accueil</a></div>");
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
            echo "ici";
            /*
            L'utilisateur connecté souhaite créer un nouveau commentaire.
            On utilise uniquement la méthode POST sans créer de variable de session.
            */
            createComment($_POST["commentText"]);

            // Pour éviter que le commentaire soit reposté si je raffraichit la page
            header("Location: ./article.php?id=".$_GET['id']); 
            exit();
        }
    } else {
    // Si l'utilisateur n'est pas connecté
        if(existComment()) {
            // On sauvegarde son commentaire dans une variable de session
            $_SESSION['commentText'] = $_POST['commentText'];
            // Puis on le redirige vers la page d'authentification
            header("Location: ./auth.php?from=article.php?id=".$_GET['id']);
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
    <main class="article-main">
        <div class="article-container">
            <div>
                <h1><?php echo $article['title'] ; ?></h1>
                <form class="form-del-article" action="../redirect/articleDeleted.php" method="POST">
                    <?php if (isConnected()) {
                            if($_SESSION["currentUser"] == $article[$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']]
                                || selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") {?>
                        <button type="submit" class="del-article-submit-button">Supprimer l'article</button>
                    <?php }}?>
                </form>
            </div>
            <div class="pastilles-container">
                <?php 
                    for ($i = 0; $i < sizeof($categories); $i++) {
                        echo '<div class="pastille-category"><span>'.$categories[strval($i)][$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']].'</span></div>';
                    }
                ?>
            </div>
            <!--nl2br(htmlspecialchars()) => permet de prendre en compte les retours à la ligne 
                tout en empechant l'execution de code HTML ou JS présent dans le texte -->
            <p class="article-text"><?php echo nl2br(htmlspecialchars($article[$GLOBALS['db']['tables']['ARTICLES']['fields']['TEXT']]))?></p>
            <p class="article-creator"><?php echo $creator[$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']]; ?></p>
            <p class="article-date">
                Posté le <?php echo formatDate($article[$GLOBALS['db']['tables']['ARTICLES']['fields']['DATE']]); ?>
            </p>
        </div>
        <div class="section-separator"></div>
        <div class="commentaires-container">
            <form class="form-new-commentaire" method="POST">
                <label class="new-commentaire-label" for="commentText"><h2>Poster un commentaire</h2></label>
                <textarea id="textarea" class="new-commentaire-text" oninput="updateCharacterCount(1000)" maxlength='1000' name="commentText"><?php echo (isset($commentText) && !empty($commentText) ? $commentText : "" ) ?></textarea>
                <div id="characterCount"></div>
                <button type="submit" class="new-commentaire-submit-button"><?php echo (empty($_SESSION["currentUser"]) ? "Se connecter" : "Envoyer le commentaire"); ?></button>
            </form>
            <div class="section-separator"></div>
            <h2>Commentaires</h2>
            <?php 
                for ($i = 0; $i < sizeof($comments); $i++) {
                    require "../components/comment-box.php";
                }
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
    </main>
    <script>
    // Appel de la fonction pour mettre à jour le compteur au chargement de la page
        window.onload = function() {
            updateCharacterCount(1000); // Met à jour le compteur dès le chargement
        };
    </script>
<?php
    require_once('../includes/footer.php');
?>