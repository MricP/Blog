<?php 
session_start();

require "../../src/functions.php";

deleteArticle();

header("Location: ../page.php?id=1");
exit();
