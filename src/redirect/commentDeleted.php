<?php 
    session_start();

    require "../utils/functions.php";

    deleteComment();

    header("Location: ../pages/article.php?id=".$_SESSION['lastArticle']);
    exit();
?>
