<?php 
session_start();

require "../../src/functions.php";

deleteComment();

header("Location: ../article.php?id=".$_SESSION['lastArticle']);
exit();
