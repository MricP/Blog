<?php

$db = [
    "host" => "iutdoua-web.univ-lyon1.fr",
    "name" => "p2310243",
    "username" =>"p2310243",
    "password" => "727423",
    "tables" => [
        "user",
        "article",
        "category",
        "article_categories",
        "comment"
    ],

];

function getConnection() {
    $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connexion;
}

/* USERS */

function selectUser($idUser) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][0]." WHERE id_user=:idUser";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':idUser', $idUser);
        $stmt->execute();
        $users = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $users;
}

/* ARTICLES */

function selectArticles($maxNbArticles) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][1]." LIMIT :maxNbArticles";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':maxNbArticles', $maxNbArticles);
        $stmt->execute();
        $articles = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $articles;
}

function selectArticle($idarticle) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][1]." WHERE id_article=:idarticle";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':idarticle', $idarticle);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $article;
}

/* CATEGORIES */

function selectAllCategories() {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][2];
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $categories;
}

/* ARTICLES-CATEGORIES*/

function selectCategories($idarticle) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][2]." AS c JOIN ".$GLOBALS['db']['tables'][3]." AS ac ON c.id_category=ac.id_category WHERE id_article=:idarticle";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':idarticle', $idarticle);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $categories;
}

/* COMMENTAIRES */

function createComment() {
    try {
        $connexion = getConnection();

        if (!(isset($_POST['currentUser']))) {
            $sql = "INSERT INTO ".$GLOBALS['db']['tables'][4]."(id_user, id_article, description) VALUES (:id_user, :id_article, :description)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_user', $_SESSION['currentUser']);
            $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
            $stmt->bindParam(':description', $_SESSION['commentText']);
            $stmt->execute();
        } else {
            header("Location: ./login.php");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function selectComments($idarticle) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][4]." WHERE id_article=:idarticle";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':idarticle', $idarticle);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $comments;
}

function deleteComment() {
    try {
        $connexion = getConnection();

        if (isset($_SESSION['currentUser'])) {
            $sql = "DELETE FROM ".$GLOBALS['db']['tables'][4]." WHERE id_comment=:id_comment AND id_user=:user";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_comment', $_POST['id_comment']);
            $stmt->bindParam(':user', $_SESSION['currentUser']);
            $stmt->execute();
        } else {
            echo "Pas de currentUser";
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}
