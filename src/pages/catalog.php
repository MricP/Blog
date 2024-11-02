<?php
    require_once('../utils/functions.php');
    require_once('../includes/header.php');

    $nombreArticles = 3;
    /*
    ---------- GESTION DU FORMULAIRE DE FILTRE PAR UTILISATEUR ------------------------------------------------------------
    */

    function isIDValid() {
        return isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 0;
    }

    function existFilterUser() {
        return isset($_GET['filter_user']) && !empty($_GET['filter_user']);
    }

    function existExactPseudo() {
        return isset($_GET['exact_pseudo']) && !empty($_GET['exact_pseudo']);
    }
        
    if (isIDValid()) {
        if (existFilterUser()) {
            if (existExactPseudo()) {
                $articles = selectArticlesOfPageByPseudoExact((int) $_GET['id'], $nombreArticles, $_GET['filter_user']);
                $totalArticles = selectTotalArticlesByPseudoExact($_GET['filter_user']);
            } else {
                $articles = selectArticlesOfPageByPseudo((int) $_GET['id'], $nombreArticles, $_GET['filter_user']);
                $totalArticles = selectTotalArticlesByPseudo($_GET['filter_user']);
            }
        } else {
            $articles = selectArticlesOfPage((int) $_GET['id'], $nombreArticles);
            $totalArticles = selectTotalArticles();
        }
        if (empty($articles)) {
            die("<div class=\"error-container\"><p class=\"error-message-article\">Aucun article</p><a href=\"./catalog.php?id=1\"><div class=\"error-redirect\">Revenir à l'accueil</div></a></div>");
        }
    } else {
        die("<div class=\"error-container\"><p class=\"error-message-article\">URL invalide</p><a href='./catalog.php?id=1'><div class='error-redirect'>Revenir à l'accueil</div></a></div>");
    }
        
    $minIndexArticle = ((int) $_GET['id']-1) * $nombreArticles + 1;
    $maxIndexArticle = ((int) $_GET['id']-1) * $nombreArticles + sizeof($articles);
?>
    <main>
        <h1 class="article-list-title">Page <?php echo $_GET['id']?> - Articles de <?php echo $minIndexArticle ?> à <?php echo $maxIndexArticle?></h1>
        <form class="filter-form" method="GET">
            <label for="filter_user">Filtrer par utilisateur
                <input type="text" name="filter_user">
            </label>
            <label for="exactPseudo">Pseudo exact ?
                <input type="checkbox" name="exact_pseudo">
            </label>
            <input type="hidden" name="id" value="1">
        </form>
        <div class="article-list-container">
            <?php 
            for ($i = 0; $i < $nombreArticles; $i++) {
                require "../components/article-box.php";
            }
            ?> 
        </div>
        <div class="page-switch-container">
            <?php if ((int) $_GET['id'] > 1) { ?>
                <a href="catalog.php?id=<?php echo (int) $_GET['id']-1 ?><?php echo (existFilterUser() ? "&filter_user=".$_GET['filter_user'] : "") ?><?php echo (existExactPseudo() ? "&exact_pseudo=".$_GET['exact_pseudo'] : "") ?>">
                    <span class="page-switch-text">Précédent</span>
                </a>
            <?php } ?>
            <?php if ($totalArticles > ((int) $_GET['id']*$nombreArticles)) { ?>
                <a href="catalog.php?id=<?php echo (int) $_GET['id'] +1 ?><?php echo (existFilterUser() ? "&filter_user=".$_GET['filter_user'] : "") ?><?php echo (existExactPseudo() ? "&exact_pseudo=".$_GET['exact_pseudo'] : "") ?>">
                    <span class="page-switch-text">Suivant</span>
                </a>
            <?php } ?>
        </div>
    
<?php
    require_once("../includes/footer.php");
?>