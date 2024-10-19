    <?php 
        require("header.php");
        require("function.php");
        $categories = selectAllCategories();
    ?>

    <datalist id='categories-list'>
        <?php
            foreach($categories as $category) {
                echo "<option value='{$category['name_category']}'></option>";
            }
        ?>
    </datalist>

    <div class="creationPage-container">
        <form class="creationArticle-form" method="POST">
            <button type="submit" style="display: none"></button>
            <h1>Créez votre Article</h1>
            <div class="formInput-container">
                <label for="articleTitle">Titre de l'article</label>
                <div>
                    <input class="article-title" name="articleTitle" type="text">
                    <input class="selected-category" type="text" list="categories-list" placeholder="Catégorie" name="selectedCategory">
                    <?php 
                        if(!empty($_POST['deleteCategory'])) {
                            $valeurSup = $_POST['deleteCategory'];
                            $index = array_search($valeurSup, $_SESSION['categories']);
                            if ($index !== false) {
                                unset($_SESSION['categories'][$index]);
                                $_SESSION['categories'] = array_values($_SESSION['categories']);
                            }
                        }

                        if(!empty($_POST['selectedCategory']) && sizeof($_SESSION['categories']) < 5 && !in_array($_POST['selectedCategory'],$_SESSION['categories'])){
                            array_push($_SESSION['categories'],$_POST['selectedCategory']);
                        }
                    ?>
                    <div class="filter-container">
                        <?php 
                            $i = 0;
                            while($i < sizeof($_SESSION['categories'])){
                                echo "<div class='selected-categories'>";
                                echo    "<p>{$_SESSION['categories'][$i]}</p>";
                                echo    "<button type='submit' name='deleteCategory' value='{$_SESSION['categories'][$i]}' class='filterButton'>";
                                echo        "<img class='deleteCross' src='croix.svg' alt='croix supprimer'>";
                                echo    "</button>";
                                echo "</div>";  
                                $i = $i+1;
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="formContent-container">
                <label for="article-content">Contenu de l'article</label>
                <textarea class="article-content" name="article-content" type="text"></textarea>
            </div> 
            <?php 
                if(!empty($_POST['article-title']) && !empty($_SESSION['categories']) && !empty($_POST['article-content'])){
                    $_SESSION['article-title'] = $_POST['article-title'];
                    $_SESSION['article-content'] = $_POST['article-content'];
                    createArticle();
                }
            ?> <button type="submit" class="creationArticle-button">Valider</button>
        </form>
    </div>
    <?php?>    
</body>
</html>