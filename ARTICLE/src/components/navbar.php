<div class="navbar-container">
    <a href="./page.php?id=1" class="navbar-option">Sport Live</a>
    <?php if(isConnected()) { ?>
        <div class="connected-options-container">
            <?php if($_SERVER['PHP_SELF'] !== "/createArticle.php") { ?>
                <a href="./createArticle.php" class="navbar-option">Créer un article</a>
            <?php } ?>
            <a href="./profil.php" class="navbar-option">Mon profil</a>
            <?php if(selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") { ?>
                <a href="./admin.php" class="navbar-option">Administration</a>
            <?php } ?>
            <a href="./redirect/logout.php" class="navbar-option">Se déconnecter</a>
        </div>
    <?php } ?>
    
    <?php if(!isConnected() && $_SERVER['PHP_SELF'] !== "/auth.php") { ?>
        <a href="./auth.php" class="navbar-option">Se connecter</a>
    <?php } ?>
</div>
<div class="section-separator"></div>