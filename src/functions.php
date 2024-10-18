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
        "paragraphs",
        "comment"
    ],

];

/* USERS */

function selectUser($idUser) {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

/* PARAGRAPHES */

function selectParagraphs($idarticle) {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][4]." WHERE id_article=:idarticle";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':idarticle', $idarticle);
        $stmt->execute();
        $paragraphs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $paragraphs;
}

/* COMMENTAIRES */

function createComment() {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!(isset($_POST['currentUser']))) {
            $sql = "INSERT INTO ".$GLOBALS['db']['tables'][5]."(id_creator, id_article, texte) VALUES (:id_creator, :id_article, :texte)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_creator', $_SESSION['currentUser']);
            $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
            $stmt->bindParam(':texte', $_SESSION['commentText']);
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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][5]." WHERE id_article=:idarticle";
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
