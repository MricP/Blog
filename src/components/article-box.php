<?php 
if (isset($articles[strval($i)])) {
$categories = selectCategories($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']]);
?>
<a href="./article.php?id=<?php echo $articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']]?>">
    <div class="article-box">
        <h2 class="article-box-title"><?php echo $articles[strval($i)]["title"]?></h2>
        <div class="article-box-lead">
            <p class="article-box-creator"><?php echo selectUser($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']])[$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']]?></p>
            <p class="article-box-date">Posté le <?php echo formatDate($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['DATE']])?></p>
        </div>
        <p class="article-box-description"><?php echo substr(nl2br(htmlspecialchars($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['TEXT']])), 0, 400)."...";?></p>
        <p class="article-box-interactions"><?php echo selectNumberOfLikes($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']])?> Like(s) - <?php echo selectNumberOfComments($articles[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']])?> Commentaire(s)</p>
        <div class="pastilles-container">
            <?php 
                for ($i_category = 0; $i_category < sizeof($categories); $i_category++) {
                    echo '<div class="pastille-category"><span>'.$categories[strval($i_category)][$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']].'</span></div>';
                }
            ?>
        </div>
    </div>
</a>
<?php }?>