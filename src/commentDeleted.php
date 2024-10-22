<?php 
session_start();

require "./functions.php";

deleteComment();

header("Location: ../article.php?id=".$_SESSION["lastArticle"]);
