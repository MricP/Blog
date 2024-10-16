<!DOCTYPE html>
<html>
<head>
        <title>Sport News</title>
        <link rel="stylesheet" href="./app.css">
        <?php 
        $nombreArticles = 5;
        $nombreBoutonsPage = 3;
        ?>
    </head>
    <body>
        <h1>Page 1 - Articles de 1 à <?php echo $nombreArticles; ?></h1>
        <div class="article-list-container">
            <?php 
            for ($i = 0; $i < $nombreArticles; $i++) {
                require "article-box.php";
            }
            ?> 
        </div>
        <div class="page-switch-container">
            <span class="page-switch-text">Précédent</span>
            <div class="page-circle-buttons-container">
            <?php 
                for ($i = 1; $i <= $nombreBoutonsPage; $i++) {
                    echo '<div class="page-circle-button">'.$i.'</div>';
                }
                ?>
            </div>
            <span class="page-switch-text">Suivant</span>
        </div>
    </body>
</html>