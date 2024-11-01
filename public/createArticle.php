<!DOCTYPE html>
<html>
    <head>
        <title>Sport Live</title>
        <link rel="stylesheet" href="./app.css" />
        <?php
        session_start();

        require "../src/functions.php";
        require "../src/components/navbar.php";

        $categories = selectAllCategories();

        /*
        ---------- GESTION DU FORMULAIRE DE CREATION D'ARTICLE ------------------------------------------------------------
        */
        

        ?>
    </head>

    
    <input da
    <main class="main-create-article-container">
        <h1 class="form-create-article-title">Créez votre Article !</h1>
        <form class="form-create-article" method="POST">
            <label for="article-title" class="form-create-article-label">Titre de l'article</label>
            <input class="form-create-article-input" name="article-title" type="text">
            <label for="article-content" class="form-create-article-label">Contenu de l'article</label>
            <textarea class="form-create-article-textarea" name="article-content" type="text"></textarea>
            <button type="submit" class="form-create-article-submit-button">Valider
                <?php (isConnected() ? "Valider" : "Se connecter") ?>
            </button>
        </form>
    </main>
    <datalist id='categories-list'>
        <?php 
        foreach($categories as $category) { 
            $nomCat = $category[$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']];
        ?>
            <option value='<?php echo $category[$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']] ?>'></option>
        <?php } ?>
    </datalist>
    </body>
</html>
<?php /*<img class='deleteCross' src='../assets/croix.svg' alt='croix supprimer'> */ ?>