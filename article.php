<!DOCTYPE html>
<html>
    <head>
        <title>Sport News</title>
        <link rel="stylesheet" href="./app.css" />
        <link href="/your-path-to-fontawesome/css/solid.css" rel="stylesheet" />
        <?php 

        function createComment() {
            try {
                $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (!(isset($_POST['currentUser']))) {
                    $sql = "INSERT INTO comment(commentText, commentAuthor) VALUES (:commentText, :commentAuthor)";
                    $stmt = $connexion->prepare($sql);
                    $stmt->bindParam(':commentText', $_POST['commentText']);
                    $stmt->bindParam(':commentAuthor', $_POST['currentUser']);
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

        $article = [
            'title' => "Haaland débarque à Lyon",
            'categories' => [
                '0' => "OL",
                '1' => "Ligue 1",
                '2' => "France",
                '3' => "Football",
                '4' => "Mercato",
            ],
            'creator' => "Kilian Mbappé"
        ];

        $commentaire = [
            'text' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perferendis illo exercitationem, impedit, nobis qui odit assumenda dolores rerum quae recusandae nisi molestiae unde excepturi natus commodi sequi consequuntur soluta. Molestiae?",
            'creator' => "The Footix"
        ];
        ?>
    </head>
    <body>
        <div class="article-container">
            <h1><?php echo $article['title']; ?></h1>
            <div class="pastilles-container">
                <?php 
                    for ($i = 0; $i < sizeof($article["categories"]); $i++) {
                        echo '<div class="pastille-category"><span>'.$article["categories"][strval($i)].'</span></div>';
                    }
                ?>
            </div>
            
            <div class="article-paragraphes-container">
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
            </div>
            <p class="article-creator"><?php echo $article['creator']; ?></p><i class="fa-solid fa-xmark"></i>
        </div>
        <div class="section-separator"></div>
        <div class="commentaires-container">
            <h2>Commentaires</h2>
            <form class="form-new-commentaire" method="POST">
                <label class="new-commentaire-label" for="new-commentaire">Votre commentaire</label>
                <textarea class="new-commentaire-text" name="new-commentaire"></textarea>
                <a class="new-commentaire-login-button" href="./login.php">Se connecter</a>
                <button type="submit" class="new-commentaire-submit-button">Envoyer le commentaire</button>
            </form>
            <div class="commentaire-box">
                <p class="commentaire-text"><?php echo $commentaire['text']; ?><p>
                <p class="commentaire-creator"><?php echo $commentaire['creator']; ?></p>
            </div>
            <div class="commentaire-box">
                <p class="commentaire-text"><?php echo $commentaire['text']; ?><p>
                <p class="commentaire-creator"><?php echo $commentaire['creator']; ?></p>
            </div>
            <div class="commentaire-box">
                <p class="commentaire-text"><?php echo $commentaire['text']; ?><p>
                <p class="commentaire-creator"><?php echo $commentaire['creator']; ?></p>
            </div>
            
        </div>
        <div class="section-separator"></div>
        <div class="page-switch-container">
            <span class="page-switch-text">Précédent</span>
            <div class="page-circle-buttons-container">
            <span class="page-switch-text">Suivant</span>
        </div>
    </body>
</html>