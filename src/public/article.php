<?php
    require_once('../includes/header.php');

    $article = [
        'title' => "Haaland débarque à Lyon",
        'creator' => "Kilian Mbappé"
    ];

    $commentaire = [
        'text' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perferendis illo exercitationem, impedit, nobis qui odit assumenda dolores rerum quae recusandae nisi molestiae unde excepturi natus commodi sequi consequuntur soluta. Molestiae?",
        'creator' => "The Footix"
    ];
?>
    <main>
        <div class="article-container">
            <h1><?php echo $article['title']; ?></h1>
            <div class="article-paragraphes-container">
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
                <p class="article-paragraphe">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias modi consequatur voluptatum laudantium veniam nobis ut quo! Quia quae ratione debi tis ducimus modi similique, quod ex reprehenderit facilis cumque ipsa? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas maiores voluptate veritatis minima harum ex magnam tempora porro ratione dolorem, laboriosam corrupti facilis voluptates excepturi eius quod natus, aliquam ab?</p>
            </div>
            <p><?php echo $article['creator']; ?></p>
        </div>
        <div class="section-separator"></div>
        <div class="commentaires-container">
            <h2>Commentaires</h2>
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
    </main>
<?php
    require_once('../includes/footer.php');
?>