<div class="commentaire-box">
    <p class="commentaire-author"><?php echo selectUser($comments[strval($i)][$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']])[$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']] ?></p>
    <p class="commentaire-text"><?php echo $comments[strval($i)]["text"] ?></p>
    <form class="form-del-commentaire" action="../redirect/commentDeleted.php" method="POST">
        <?php if (isConnected()) { 
                if($_SESSION["currentUser"] == $comments[strval($i)][$GLOBALS['db']['tables']['COMMENTS']['fields']['AUTHOR']]
                    || selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") {?>
            <button type="submit" class="del-commentaire-submit-button" name="id_comment" value="<?php echo $comments[strval($i)][$GLOBALS['db']['tables']['COMMENTS']['fields']['ID']]?>">Supprimer le commentaire</button>
        <?php }}?>
    </form>
</div>