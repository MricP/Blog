<?php
    session_start();

    if(!isset($_SESSION['currentUser'])) {
        $_SESSION['currentUser'] = NULL;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Blog</title>
</head>
<body>
    <Header>
        <nav>COUCOU</nav>
    </Header>
