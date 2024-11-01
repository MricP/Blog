<?php 
session_start();

require "./functions.php";

if (isCreated($_POST["authEmail"])) {
    connectUser($_POST["authEmail"], $_POST["authPassword"]);
    header("Location: ../article.php?id=".$_SESSION['lastArticle']);
} else {
    if (empty(FoundUser($_POST["authEmail"]))) {
        createUser($_POST["authEmail"], $_POST["authPassword"]);
    }
}


