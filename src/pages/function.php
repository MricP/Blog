<?php

$db = [
    "host" => "localhost",
    "name" => "blog",
    "tables" => [
        "user",
        "article",
        "category",
        "article_categories",
        "comment"
    ],

];

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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables'][1]." ORDER BY id_article DESC LIMIT 1";
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

function createArticle() {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($_SESSION['currentUser'])) {
            if(!empty($_SESSION['article-title']) && !empty($_SESSION['article-content']) && !empty($_SESSION['categories'])){
                $sql = "INSERT INTO ".$GLOBALS['db']['tables'][1]."(title, description, id_author) VALUES (:title, :description, :id_author)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':title', $_SESSION['article-title']);
                $stmt->bindParam(':description', $_SESSION['article-content']);
                $stmt->bindParam(':id_author', $_SESSION['currentUser']['id_user']);
                $stmt->execute();
            
                $lastArticle = selectLastArticle();
                $id_last_article = $lastArticle['id_article'];
                $allCategories = selectAllCategories();

                for ($i=1; $i <= sizeof($_SESSION['categories']); $i++){

                    foreach ($_SESSION['categories'] as $categoryName) {
                        $id_category = null;
                        foreach ($allCategories as $category) {
                            if ($category['name_category'] === $categoryName) {
                                $id_category = $category['id_category'];
                                break;
                            }
                    }

                    if ($id_category !== null) {
                        $checkSql = "SELECT COUNT(*) FROM ".$GLOBALS['db']['tables'][3]." WHERE id_article = :id_article AND id_category = :id_category";
                        $checkStmt = $connexion->prepare($checkSql);
                        $checkStmt->bindValue(':id_article', $id_last_article);
                        $checkStmt->bindValue(':id_category', $id_category);
                        $checkStmt->execute();
                        $exists = $checkStmt->fetchColumn();
                
                        if ($exists == 0) {
                            $sql = "INSERT INTO ".$GLOBALS['db']['tables'][3]."(id_article, id_category) VALUES (:id_article, :id_category)";
                            $stmt = $connexion->prepare($sql);
                            $stmt->bindValue(':id_article', $id_last_article);
                            $stmt->bindValue(':id_category', $id_category);
                            $stmt->execute();
                        }
                    }
                }
            }
        } else {
            header("Location: ./auth.php");
            exit();
        }}
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function isCategoryInDB($category,&$errorMessage) {
    try {
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) FROM category WHERE name_category = :category";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $errorMessage = "La catégorie sélectionnée n'existe pas.";
            return false;
        }
        return true;

    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

