    <?php 
        require("header.php");
        require("function.php");

        $categories = selectAllCategories();
    ?>
    <div class="creationPage-container">
        <form class="creationArticleForm" method="POST">
            <h1>Créez votre Article</h1>
            <button type="submit" style="display: none;"></button>
            <div class="formInput-container">
                <label for="titreArticle">Titre de l'article</label>
                <div>
                    <input class="titreArticle" name="titreArticle" type="text">
                    <input class="choixCategorie" type="text" list="Sports" placeholder="Catégorie" name="choixCategorie">
                    <?php 
                    if(!empty($_POST['choixCategorie']) && sizeof($_SESSION['categories']) < 5 && !in_array($_POST['choixCategorie'],$_SESSION['categories'])){
                        array_push($_SESSION['categories'],$_POST['choixCategorie']);
                    }?>
                        <div class="filter-container">
                            <?php 
                                $i = 0;
                                while($i < sizeof($_SESSION['categories'])){
                            ?>
                            <div class="categorieType">
                                <p><?php echo $_SESSION['categories'][$i]['category_name'] ?></p>
                                <button type="submit" name="sup" value="<?= $_SESSION['categories'][$i];?>" class="filterButton"><img class="deleteCross" src="croix.svg" alt="croix supprimer"></button>
                            </div>  
                            <?php 
                                $i = $i +1;
                                }
                                    if(!empty($_POST['sup'])) {
                                        $valeurSup = $_POST['sup'];
                                        $index = array_search($valeurSup, $_SESSION['categories']);
                                        if ($index !== false) {
                                            unset($_SESSION['categories'][$index]);
                                        } 
                                    } 
                            ?>
                        </div>       
                </div>
                <datalist id="Sports">
                    <option value=" "></option>
                    <?php 
                        for ($i = 0; $i < sizeof($categories); $i++) {
                            echo '<option value="'.$i.'">'.$categories[$i]['name_category'].' <option/>';
                        }
                    ?>

                </datalist>
            </div>
            <div class="formContent-container">
                <label for="contentArticle">Contenu de l'article</label>
                <textarea class="contentArticle" name="contentArticle" type="text"></textarea>
            </div> 
            <?php 

                if(!empty($_POST['titreArticle']) && !empty($_SESSION['categories']) && !empty($_POST['contentArticle'])){
                    $_SESSION['titreArticle'] = $_POST['titreArticle'];
                    $_SESSION['contentArticle'] = $_POST['contentArticle'];
                }
            ?> <button type="submit" class="buttonCreationArticle">Valider</button>
        </form>
       
    </div>
    <?php    ?>
    
</body>
</html>