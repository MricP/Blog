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
    "name" => "tpblog",
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
            "name" => "users",
            "fields" => [
                "ID" => "id_user",
                "PSEUDO" => "pseudo",
                "EMAIL" => "email",
                "PASSWORD" => "password",
                "TYPE_USER" => "type_user",
                "IS_HASHED" => "is_hashed"
            ]
        ],
        "ARTICLES" => [
            "name" => "articles",
            "fields" => [
                "ID" => "id_article",
                "TITLE" => "title",
                "AUTHOR" => "id_author",
                "DATE" => "date_creation",
                "TEXT" => "text"
            ]
        ], 
        "CATEGORIES" => [
            "name" => "categories",
            "fields" => [
                "ID" => "id_category",
                "NAME" => "name_category",
            ]
        ], 
        "ARTICLES_CATEGORIES" => [
            "name" => "articles_categories",
            "fields" => [
                "ARTICLE" => "id_article",
                "CATEGORY" => "id_category",
            ]
        ],  
        "COMMENTS" => [
            "name" => "comments",
            "fields" => [
                "ID" => "id_comment",
                "AUTHOR" => "id_author",
                "ARTICLE" => "id_article",
                "TEXT" => "text",
            ]
        ],  
        "LIKES" => [
            "name" => "likes",
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
    $formatter = new IntlDateFormatter(
        'fr_FR', // Paramètre régional (français)
        IntlDateFormatter::LONG, // Longueur de la date (FULL pour inclure le jour complet)
        IntlDateFormatter::NONE // Aucune partie pour l'heure
    );
    return $formatter->format(new DateTime($date));
}

/* 
-------------    AUTHENTIFICATION    -------------------------------------------------------------------------------------------------------------
*/

function existLastArticle() {
    return isset($_SESSION['lastArticle']) && !empty($_SESSION['lastArticle']);
}

function redirectWhenConnected() {
    if (existLastArticle()) {
        header("Location: ./article.php?id=".$_SESSION['lastArticle']);
    } else {
        header("Location: ./page.php?id=1");
    }
    exit();
}

function isPasswordValid($password) {
    return strlen($password) < 8 || strlen($password)>30;
}

function verifyPasswordUser($user, $password) {
    if ($user[$GLOBALS['db']['tables']['USERS']['fields']['IS_HASHED']] == 0) {
        $result = $password == $user[$GLOBALS['db']['tables']['USERS']['fields']['PASSWORD']];
        if ($result) {
            updatePasswordToHash($user, $password);
        }
    } else {
        $result = password_verify($password, $user[$GLOBALS['db']['tables']['USERS']['fields']['PASSWORD']]);
    }

    return $result;
}

function updatePasswordToHash($user, $password) {
    try {
        $connexion = getConnection();

        if (!isConnected()) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE ".$GLOBALS['db']['tables']['USERS']['name']." SET ".$GLOBALS['db']['tables']['USERS']['fields']['PASSWORD']."=:password, ".$GLOBALS['db']['tables']['USERS']['fields']['IS_HASHED']."=1 WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['ID']."=:id_user";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id_user', $user[$GLOBALS['db']['tables']['USERS']['fields']['ID']]);
            $stmt->execute();
        } else {
            header("Location: ./auth.php");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

/*-------------     AUTHENTIFICATION GENERALE     -------------*/

function authUser($identifier, $password) {
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // L'utilisateur s'authentifie avec une adresse email
        $user = selectUserByEmail($identifier);
        if (empty($user)) {
            // Si l'utilisateur n'existe pas dans la BDD, il doit renseigner son pseudo
            createUser($identifier, NULL, $password);
            connectUserByEmail($identifier, $password);
            echo "NEW EMAIL";
        } else {
            // Si l'utilisateur existe, on vérifie que son mot de passe est valide
            if (verifyPasswordUser($user, $password)) {
                // Connexion réussie par email
                connectUserByEmail($identifier, $password);
            } else {
                // Mot de passe invalide
                $errorMessageAuth = "L'identifiant ou le mot de passe ne correspond pas";
            }
        }
    } else {
        // L'utilisateur s'authentifie avec un pseudo
        $user = selectUserByPseudo($identifier);
        if (empty($user)) {
            // Si l'utilisateur n'existe pas dans la BDD, le compte est créé
            createUser(NULL, $identifier, $password);
            connectUserByPseudo($identifier, $password);
        } else {
            // Si l'utilisateur existe, on vérifie que son mot de passe est valide
            if (verifyPasswordUser($user, $password)) {
                // Connexion réussie par pseudo
                connectUserByPseudo($identifier, $password);
            } else {
                // Mot de passe invalide
                $errorMessageAuth = "L'identifiant ou le mot de passe ne correspond pas";
            }
        }
    }
    if (isConnected()) {
        redirectWhenConnected();
    }
}

/*-------------     CONNEXION     -------------*/

function connectUserByPseudo($pseudo, $password) {
    try {
        $connexion = getConnection();

        if (!isConnected()) {
            $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['USERS']['name']." WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO']."=:pseudo";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['currentUser'] = $user[$GLOBALS['db']['tables']['USERS']['fields']['ID']];

        } else {
            header("Location: ./page.php?id=1");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function connectUserByEmail($email, $password) {
    try {
        $connexion = getConnection();

        if (!isConnected()) {
            $sql = "SELECT * FROM ".$GLOBALS['db']['tables']['USERS']['name']." WHERE ".$GLOBALS['db']['tables']['USERS']['fields']['EMAIL']."=:email";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['currentUser'] = $user[$GLOBALS['db']['tables']['USERS']['fields']['ID']];

        } else {
            header("Location: ./page.php?id=1");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
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

function createUser($email, $pseudo, $password) {
    try {
        $connexion = getConnection();

        if (!isConnected()) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO ".$GLOBALS['db']['tables']['USERS']['name']."(".$GLOBALS['db']['tables']['USERS']['fields']['PSEUDO'].", ".$GLOBALS['db']['tables']['USERS']['fields']['EMAIL'].", ".$GLOBALS['db']['tables']['USERS']['fields']['PASSWORD'].", ".$GLOBALS['db']['tables']['USERS']['fields']['IS_HASHED'].") VALUES (:pseudo, :email, :password, 1)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();
        } else {
            header("Location: ./auth.php");
        }
    } catch (PDOException $e) { 
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
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
        $connexion = new PDO("mysql:host=".$GLOBALS['db']['host'].";dbname=".$GLOBALS['db']['name'].";charset=utf8", 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            $sql = "DELETE FROM ".$GLOBALS['db']['tables']['ARTICLES']['name']." WHERE ".$GLOBALS['db']['tables']['ARTICLES']['fields']['ID']."=:id_article AND ".$GLOBALS['db']['tables']['ARTICLES']['fields']['AUTHOR']."=:id_author";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
            $stmt->bindParam(':id_author', $_SESSION['currentUser']);
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

        if (isConnected()) {
            $sql = "INSERT INTO ".$GLOBALS['db']['tables']['COMMENTS']['name']."(".$GLOBALS['db']['tables']['COMMENTS']['fields']['AUTHOR'].", ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ARTICLE'].", ".$GLOBALS['db']['tables']['COMMENTS']['fields']['TEXT'].") VALUES (:id_author, :id_article, :text)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_author', $_SESSION['currentUser']);
            $stmt->bindParam(':id_article', $_SESSION['lastArticle']);
            $stmt->bindParam(':text', $comment_text);
            $stmt->execute();
        } else {
            header("Location: ./auth.php");
        }
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
            $sql = "DELETE FROM ".$GLOBALS['db']['tables']['COMMENTS']['name']." WHERE ".$GLOBALS['db']['tables']['COMMENTS']['fields']['ID']."=:id_comment AND ".$GLOBALS['db']['tables']['COMMENTS']['fields']['AUTHOR']."=:id_author";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':id_comment', $_POST['id_comment']);
            $stmt->bindParam(':id_author', $_SESSION['currentUser']);
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