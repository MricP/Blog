<div class="commentaire-box">
    <p class="commentaire-text"><?php echo $comments[strval($i)]["description"] ?></p>
    <p class="commentaire-creator"><?php echo selectUser($comments[strval($i)]["id_user"])["pseudo"] ?></p>
    <form class="form-del-commentaire" action="./src/commentDeleted.php" method="POST">
        <?php if ($_SESSION["currentUser"] == $comments[strval($i)]["id_user"]) {?>
            <button type="submit" class="del-commentaire-submit-button" name="id_comment" value="<?php echo $comments[strval($i)]["id_comment"]?>">Supprimer le commentaire</button>
        <?php }?>
    </form>
</div>