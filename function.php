<?php

$db = [
    //"host" => "iutdoua-web.univ-lyon1.fr",
    "host" => "localhost",
    "name" => "blog",
    //"username" =>"p2300496",
    //"password" => "718858",
    "tables" => [
        "user",
        "article",
        "category",
        "article_categories",
        //"paragraphs",
        "comment"
    ],

];

function selectAllCategories() {
    try {
        //$connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion = new PDO('mysql:host='.$GLOBALS['db']['host'].';dbname='.$GLOBALS['db']['name'], 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM category";
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



function selectLastArticle() {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][1]." ORDER BY id_article ASC LIMIT 1";
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $article;
}


function createArticle($id_last_article) {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!(isset($_SESSION['currentUser']))) {
            if(!empty($_SESSION['titreArticle']) && !empty($_SESSION['contentArticle']) && !empty($_SESSION['currentUser']) && !empty($_SESSION['categories'])){
                $sql = "INSERT INTO ".$GLOBALS['db']['tables'][1]."(title, description, id_author) VALUES (:title, :description, :id_author)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':title', $_SESSION['titreArticle']);
                $stmt->bindParam(':description', $_SESSION['contentArticle']);
                $stmt->bindParam(':id_author', $_SESSION['currentUser']);
                $stmt->execute();

                $id_last_article = selectLastArticle();
                for ($i=0; $i < sizeof($_SESSION['categories']); $i++){
                    $sql = "INSERT INTO ".$GLOBALS['db']['tables'][3]."(id_article, id_category) VALUES (:id_article, :id_category)";
                    $stmt = $connexion->prepare($sql);
                    $stmt->bindParam(':id_article', $id_last_article);
                    $stmt->bindParam(':description', $_SESSION['categories'][$i]);
                    $stmt->execute();
                }
            }
        } else {
            header("Location: ./login.php");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

