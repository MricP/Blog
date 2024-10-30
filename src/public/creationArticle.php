    <?php 
        require("../includes/header.php");
        require("./function.php");
        $categories = selectAllCategories();

        if(!empty($_POST['article-title']) && !empty($_SESSION['categories']) && !empty($_POST['article-content'])){
            $_SESSION['currentUser'] = 8;                 
            $_SESSION['article-title'] = $_POST['article-title'];
            $_SESSION['article-content'] = $_POST['article-content'];
            createArticle();
            session_destroy();

            header("Location: ./page.php");
        }

        if(!empty($_POST['deleteCategory'])) {
            $valeurSup = $_POST['deleteCategory'];
            $index = array_search($valeurSup, $_SESSION['categories']);
            if ($index !== false) {
                unset($_SESSION['categories'][$index]);
                $_SESSION['categories'] = array_values($_SESSION['categories']);
            }
        }

        if (!empty($_POST['selectedCategory']) && sizeof($_SESSION['categories']) < 5 && !in_array($_POST['selectedCategory'], $_SESSION['categories'])) {
            if (isCategoryInDB($_POST['selectedCategory'], $errorMessage)) {
                array_push($_SESSION['categories'], $_POST['selectedCategory']);
            }
        }
    ?>

    <datalist id='categories-list'>
        <?php
            foreach($categories as $category) {
                echo "<option value='{$category['name_category']}'></option>";
            }
        ?>
    </datalist>

    <main class="creationPage-container">
        <form class="creationArticle-form" method="POST">
            <button type="submit" style="display: none"></button>
            <h1>Créez votre article</h1>

            <div class="formInput-container">
                <label for="articleTitle">Titre de l'article</label>

                <div>
                    <input class="article-title" name="article-title" type="text">
                    <input class="select-category-input" type="text" list="categories-list" placeholder="Catégorie" name="selectedCategory">
                </div>

                <?php 
                    if(isset($errorMessage)){
                        echo "<div class='error-message'>{$errorMessage}</div>";
                        unset($errorMessage);
                    } 
                ?>
                
                <div class="filter-container">
                    <div class="selected-categories">
                        <?php 
                            $i = 0;
                            while($i < sizeof($_SESSION['categories'])){
                                echo "<div class='selected-category'>";
                                echo    "<p>{$_SESSION['categories'][$i]}</p>";
                                echo    "<button type='submit' name='deleteCategory' value='{$_SESSION['categories'][$i]}' class='filterButton'>";
                                echo        "<i class='bx bx-x deleteCross' alt='supprimer'></i>";
                                echo    "</button>";
                                echo "</div>";  
                                $i = $i+1;
                            }
                            $temp = sizeof($_SESSION['categories']);
                        ?>
                    </div>
                    <?php if($temp>0) echo "<div class='category-count'>$temp / 5</div>" ?>
                </div>
            </div>

            <div class="formContent-container">
                <label for="article-content">Contenu de l'article</label>
                <textarea class="article-content" name="article-content" type="text"></textarea>
            </div> 

            <button type="submit" class="creationArticle-button">Valider</button>
        </form>
    </main>
<?php  
    require_once('../includes/footer.php')
?>    