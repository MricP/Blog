<?php

/* 
-------------    PRECONFIGURATIONS    -------------------------------------------------------------------------------------------------------------

NE PAS MODIFIER LE NOM D'UN CHAMP/TABLE DIRECTEMENT DANS LES REQUÊTES SQL OU DANS LES FICHIERS PHP DANS /public !

Des variables globales sont déjà intégrées dans les pages PHP et les requêtes SQL permettant de gérer à un seul endroit la 
modification du nom des tables ou des champs.

-> Modifier le nom de la table ou du champ dans les préconfigurations

--------------------------------
Utiliser les alias dans les requêtes SQL ou les pages PHP pour utiliser les préconfigurations :

Pour accéder au nom d'une table :
$GLOBALS['db']['tables']['ALIAS_TABLE']['name']


Pour accéder au nom d'un champ d'une table :
$GLOBALS['db']['tables']['ALIAS_TABLE']['fields']['ALIAS_CHAMP]
*/

$db = [
    "host" => "localhost",
    "name" => "blog",
    "username" =>"root",
    "password" => "",
    "tables" => [
        /* Pour modifier le nom des tables,
        NE PAS MODIFIER LE NOM EN MAJUSCULES (Il sert uniquement d'alias pour renseigner les tables)
        Modifier UNIQUEMENT le nom de l'attribut "name"

        Pareil pour les attributs des tables, 
        NE PAS MODIFIER L'ALIAS DES ATTRIBUTS EN MAJUSCULES
        Modifier le nom en minuscule.
        */
        "USERS" => [
            "name" => "user",
            "fields" => [
                "ID" => "id_user",
                "PSEUDO" => "pseudo",
                "EMAIL" => "email",
                "PASSWORD" => "password",
                "TYPE_USER" => "userType"
            ]
        ],
        "ARTICLES" => [
            "name" => "article",
            "fields" => [
                "ID" => "id_article",
                "TITLE" => "title",
                "AUTHOR" => "id_author",
                "DATE" => "creation_date",
                "TEXT" => "text"
            ]
        ], 
        "CATEGORIES" => [
            "name" => "category",
            "fields" => [
                "ID" => "id_category",
                "NAME" => "name_category",
            ]
        ], 
        "ARTICLES_CATEGORIES" => [
            "name" => "article_categories",
            "fields" => [
                "ARTICLE" => "id_article",
                "CATEGORY" => "id_category",
            ]
        ],  
        "COMMENTS" => [
            "name" => "comment",
            "fields" => [
                "ID" => "id_comment",
                "AUTHOR" => "id_author",
                "ARTICLE" => "id_article",
                "TEXT" => "text",
            ]
        ],  
        "LIKES" => [
            "name" => "article_likes",
            "fields" => [
                "ARTICLE" => "id_article",
                "AUTHOR" => "id_user",
            ]
        ],   
    ],

];

/* 
-------------    GENERAL    -------------------------------------------------------------------------------------------------------------
*/

function getConnection() {
    $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", $GLOBALS['db']['username'], $GLOBALS['db']['password']);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connexion;
}

function isConnected() {
    return isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser']);
}

function formatDate($date) {
    $date_formatee = date("d/m/Y", strtotime($date)); // Date au format DD-MM-YYYY
    echo $date_formatee;
}


/* 
-------------    CREATION ARTICLE    -------------------------------------------------------------------------------------------------------------
*/

function isConsentCheckBoxChecked() {
    return isset($_POST['consent']);
}

/* 
-------------    AUTHENTIFICATION    -------------------------------------------------------------------------------------------------------------
*/

function connectUser(&$errorMessage) {
    try {
        $connexion = getConnection();
        // Vérification si le pseudo existe déjà
        $user = selectUserByEmail($_SESSION['email']);
        if(empty($user)) { //Créer un compte
            $_SESSION['displayPseudo']=true;
            if(isPseudoCompleted()){
                //L'insertion s'est bien passé, on redirige
                if(insertUser($errorMessage)) {
                    $_SESSION['currentUser'] = selectUserByEmail($_SESSION['email'])['id_user'];
                    unset($_SESSION['email']);
                    unset($_SESSION['password']);
                    unset($_SESSION['pseudo']);
                    unset($_SESSION['displayPseudo']);
                    unset($_SESSION['lastActivity']);
                    return true;
                }
            }
        } else { //Se connecter
            if($user['password'] == hashPassword($_SESSION['password'])) {
                $_SESSION['currentUser'] = $user['id_user'];
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                unset($_SESSION['pseudo']);
                unset($_SESSION['displayPseudo']);
                unset($_SESSION['lastActivity']);
                return true;
            } else {
                $errorMessage = "Mot de passe incorrect.";
            }
        }
        return false;
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function insertUser(&$errorMessage) {
    try {
        $connexion = getConnection();

        if(!empty(selectUserByPseudo($_SESSION['pseudo']))) {
            $errorMessage = "Ce pseudo est déjà emprunté.";
            return false;
        } else {
            // Insérer le nouvel utilisateur
            $sql = "INSERT INTO ".$GLOBALS['db']['tables']['USERS']['name']."(".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].", ".$GLOBALS['db']['tables']['USERS']['fields']['EMAIL'].", ".$GLOBALS['db']['tables']['USERS']['fields']['PASSWORD'].") VALUES (:pseudo, :email, :password)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
            $stmt->bindParam(':password', hashPassword($_SESSION['password']));
            $stmt->bindParam(':email', $_SESSION['email']);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function hashPassword($password) {
    // sha256 permet d'avoir le même hashage pour des chaînes identiques
    return hash('sha256', $password);
}

function areIdentifiersCompleted() {
    //Vérifie si l'email et le password ont bien été renseigné
    return isset($_SESSION['email'],$_SESSION['password']);
}

function isPseudoCompleted(){
    //Vérifie si le pseudo a bien été renseigné
    return isset($_SESSION['pseudo']);
}

function areIdentifiersVerified(&$errorMessage) {
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Couvre la connexion et la création
        $errorMessage = "L'adresse email n'a pas un format valide.";
        return false;
    } elseif(strlen($password) < 6 || strlen($password)>30) { //Couvre la connexion et la création
        $errorMessage = "Votre mot de passe doit comporter 6 à 15 caractères.";
        return false;
    } elseif(isPseudoCompleted()){
        //Si on est dans la phase de création (qu'un pseudo est entré)
        if(strlen($_SESSION['pseudo'])<4 || strlen($_SESSION['pseudo'])>64) { 
            $errorMessage = "Votre pseudo doit comporter 4 à 15 caractères.";
            return false;
        }
    }
    return true;
}

/* 
-------------    USERS    -------------------------------------------------------------------------------------------------------------
*/

function selectUser($id_user) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['USERS']['name']." WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['ID']."=:id_user";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $user;
}

function selectUserByEmail($email) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['USERS']['name']." WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['EMAIL']."=:email";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $user;
}

function selectUserByPseudo($pseudo) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['USERS']['name']." WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']."=:pseudo";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $user;
}

/* 
-------------    ARTICLES    -------------------------------------------------------------------------------------------------------------
*/

function selectArticles($maxNbArticles) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." LIMIT :maxNbArticles";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':maxNbArticles', $maxNbArticles, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $articles;
}

function selectArticle($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']."=:id_article";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $article;
}

function selectPreviousArticle($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']."<:id_article ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." DESC LIMIT 1";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $article;
}

function selectNextArticle($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID'].">:id_article ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." ASC LIMIT 1";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $article;
}

function selectArticlesOfPage($id_page, $nb_articles) {
    try {
        $connexion = getConnection();
        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." ASC LIMIT :nb_articles OFFSET :saut";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':nb_articles', $nb_articles, PDO::PARAM_INT);
        $saut = ($id_page-1)*$nb_articles;
        $stmt->bindParam(':saut', $saut, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $articles;
}

function selectArticlesOfPageByPseudo($id_page, $nb_articles, $pseudo) {
    try {
        $connexion = getConnection();

        $start_by = $pseudo."%";
        $sql = "SELECT a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["ID"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["TITLE"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["AUTHOR"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["DATE"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["TEXT"]." FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." a JOIN ".$GLOBALS['db']['tables']['USERS']['name']." u ON a.".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=u.".$GLOBALS['db']['tables']['USERS']['fields']['ID']." WHERE LOWER(u.".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].") LIKE LOWER(:start_by) ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." LIMIT :nb_articles OFFSET :saut";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':start_by', $start_by);
        $stmt->bindParam(':nb_articles', $nb_articles, PDO::PARAM_INT);
        $saut = ($id_page-1)*$nb_articles;
        $stmt->bindParam(':saut', $saut, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $articles;
}

function selectArticlesOfPageByPseudoExact($id_page, $nb_articles, $pseudo) {
    try {
        $connexion = getConnection();
        
        $sql = "SELECT a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["ID"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["TITLE"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["AUTHOR"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["DATE"].", a.".$GLOBALS['db']['tables']['ARTICLES']['fields']["TEXT"]." FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." a JOIN ".$GLOBALS['db']['tables']['USERS']['name']." u ON a.".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=u.".$GLOBALS['db']['tables']['USERS']['fields']['ID']." WHERE LOWER(u.".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].")=LOWER(:pseudo) ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." LIMIT :nb_articles OFFSET :saut";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':nb_articles', $nb_articles, PDO::PARAM_INT);
        $saut = ($id_page-1)*$nb_articles;
        $stmt->bindParam(':saut', $saut, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $articles;
}

function selectTotalArticles() {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS total_articles FROM ".$GLOBALS['db']['tables']['ARTICLES']['name'];
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_articles = $result["total_articles"];
    return $total_articles;
}

function selectTotalArticlesByPseudo($pseudo) {
    try {
        $connexion = getConnection();
        
        $start_by = $pseudo."%";
        $sql = "SELECT COUNT(*) AS total_articles FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." a JOIN ".$GLOBALS['db']['tables']['USERS']['name']." u ON a.".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=u.".$GLOBALS['db']['tables']['USERS']['fields']['ID']." WHERE LOWER(u.".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].") LIKE LOWER(:start_by) ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID'];
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':start_by', $start_by);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_articles = $result["total_articles"];
    return $total_articles;
}

function selectTotalArticlesByPseudoExact($pseudo) {
    try {
        $connexion = getConnection();
        
        $sql = "SELECT COUNT(*) AS total_articles FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." a JOIN ".$GLOBALS['db']['tables']['USERS']['name']." u ON a.".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=u.".$GLOBALS['db']['tables']['USERS']['fields']['ID']." WHERE LOWER(u.".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].")=LOWER(:pseudo) ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID'];
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_articles = $result["total_articles"];
    return $total_articles;
}

function createArticle() {
    try {
        $connexion = getConnection();

        if (isConnected()) {
            if(!empty($_SESSION['article-title']) && !empty($_SESSION['article-content']) && !empty($_SESSION['categories'])){
                $sql = "INSERT INTO ".$GLOBALS['db']['tables']['ARTICLES']['name']."(".$GLOBALS['db']['tables']['ARTICLES']['fields']['TITLE'].", ".$GLOBALS['db']['tables']['ARTICLES']['fields']['TEXT'].", ".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR'].") VALUES (:title, :description, :id_author)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':title', $_SESSION['article-title']);
                $stmt->bindParam(':description', $_SESSION['article-content']);
                $stmt->bindParam(':id_author', $_SESSION['currentUser']);
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
                        $checkSql = "SELECT COUNT(*) FROM ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['ARTICLE']."=:id_article AND ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['CATEGORY']."=:id_category";
                        $checkStmt = $connexion->prepare($checkSql);
                        $checkStmt->bindValue(':id_article', $id_last_article);
                        $checkStmt->bindValue(':id_category', $id_category);
                        $checkStmt->execute();
                        $exists = $checkStmt->fetchColumn();
                
                        if ($exists == 0) {
                            $sql = "INSERT INTO ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['name']."(".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['ARTICLE'].", ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['CATEGORY'].") VALUES (:id_article, :id_category)";
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
        }
    }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function deleteArticle() {
    try {
        $connexion = getConnection();

        if (isConnected()) {
            // Si c'est un admin
            if(selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") {
                $sql = "DELETE FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']."=:id_article";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
            } else { // Si c'est l'auteur
                $sql = "DELETE FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']."=:id_article AND ".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=:id_author";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
                $stmt->bindParam(':id_author', $_SESSION['currentUser']);
            }
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

function selectLastArticle() {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." ORDER BY ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']." DESC LIMIT 1";
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

/* 
-------------    CATEGORIES    -------------------------------------------------------------------------------------------------------------
*/

function selectAllCategories() {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['CATEGORIES']['name'];
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

function selectTotalCategories() {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS total_categories FROM ".$GLOBALS['db']['tables']['CATEGORIES']['name'];
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_categories = $result["total_categories"];
    return $total_categories;
}

function isCategoryInDB($category,&$errorMessage) {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) FROM ".$GLOBALS['db']['tables']['CATEGORIES']['name']." WHERE ".$GLOBALS['db']['tables']['CATEGORIES']['fields']['NAME']." = :category";
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

/* 
-------------    ARTICLES_CATEGORIES    -------------------------------------------------------------------------------------------------------------
*/

function selectCategories($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['CATEGORIES']['name']." AS c JOIN ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['name']." AS ac ON c.".$GLOBALS['db']['tables']['CATEGORIES']['fields']['ID']."=ac.".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['CATEGORY']." WHERE ".$GLOBALS['db']['tables']['ARTICLES_CATEGORIES']['fields']['ARTICLE']."=:id_article";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $categories;
}

/* 
-------------    COMMENTAIRES    -------------------------------------------------------------------------------------------------------------
*/

function createComment($comment_text) {
    try {
        $connexion = getConnection();
        
        $sql = "INSERT INTO ".$GLOBALS['db']['tables']['COMMENTS']['name']."(".$GLOBALS['db']['tables']['COMMENTS']['fields']['AUTHOR'].", ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ARTICLE'].", ".$GLOBALS['db']['tables']['COMMENTS']['fields']['TEXT'].") VALUES (:id_author, :id_article, :text)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_author', $_SESSION['currentUser']);
        $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
        $stmt->bindParam(':text', $comment_text);
        $stmt->execute();
        
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function selectComments($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['COMMENTS']['name']." WHERE ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ARTICLE']."=:id_article";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    return $comments;
}

function selectNumberOfComments($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS nb_comments FROM ".$GLOBALS['db']['tables']['COMMENTS']['name']." WHERE ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ARTICLE']."=:id_article";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $nb_comments = $result["nb_comments"];
    return $nb_comments;
}

function selectTotalComments() {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS total_comments FROM ".$GLOBALS['db']['tables']['COMMENTS']['name'];
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_comments = $result["total_comments"];
    return $total_comments;
}

function deleteComment() {
    try {
        $connexion = getConnection();

        if (isConnected()) {
            // Si c'est un admin
            if(selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] == "admin") {
                $sql = "DELETE FROM ".$GLOBALS['db']['tables']['COMMENTS']['name']." WHERE ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ID']."=:id_comment";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':id_comment', $_POST['id_comment']);
            } else { // Si c'est l'auteur
                $sql = "DELETE FROM ".$GLOBALS['db']['tables']['COMMENTS']['name']." WHERE ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ID']."=:id_comment AND ".$GLOBALS['db']['tables']['COMMENTS']['fields']['AUTHOR']."=:id_author";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':id_comment', $_POST['id_comment']);
                $stmt->bindParam(':id_author', $_SESSION['currentUser']);
            }
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

/* 
-------------    LIKES    -------------------------------------------------------------------------------------------------------------
*/

function selectNumberOfLikes($id_article) {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS nb_likes FROM ".$GLOBALS['db']['tables']['LIKES']['name']." WHERE ".$GLOBALS['db']['tables']['LIKES']['fields']['ARTICLE']."=:id_article";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $nb_likes = $result["nb_likes"];
    return $nb_likes;
}

function selectTotalLikes() {
    try {
        $connexion = getConnection();

        $sql = "SELECT COUNT(*) AS total_likes FROM ".$GLOBALS['db']['tables']['LIKES']['name'];
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
    $total_likes = $result["total_likes"];
    return $total_likes;
}