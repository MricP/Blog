    <?php 
        require_once('../utils/functions.php');
        require_once("../includes/header.php");
        // require_once("./function.php");

        $categories = selectAllCategories();

        if(!isConnected()) {
            header("Location: ./auth.php?from=creationArticle.php");
        }

        if(isset($_POST['article-content'])) { 
            if(empty($_POST['article-content']) || strlen($_POST['article-content'])<=100) {
                $errorMessage = "L'article doit comporter plus de 100 caractères.";
                $_SESSION['lastActivity'] = time(); //On relance le timer pour repousser l'unset de $_SESSION['categories'];
            } else {
                $_SESSION['article-content'] = $_POST['article-content'];
            }   
        }

        if(isset($_POST['article-title'])) {
            if(empty($_POST['article-title'])) {
                $errorMessage = "L'article doit porter un titre.";
                $_SESSION['lastActivity'] = time(); //On relance le timer pour repousser l'unset de $_SESSION['categories'];
            } else {
                $_SESSION['article-title'] = $_POST['article-title'];
            }
        }

        // Dans le cas ou les 2 variables POST on été complétée
        if(isset($_SESSION['article-title'],$_SESSION['article-content']) && !empty($_SESSION['article-title']) &&!empty($_SESSION['article-content'])){  
            if(!empty($_SESSION['categories'])) {
                /*
                    Dernière action à effectuer, elle permet à l'utilisateur de continuer d'ajouter des categories à sont 
                    article sans risqué qu'il soit posté au moment ou l'utilisateur clique sur entrer
                */
                if(isConsentCheckBoxChecked()) {
                    createArticle();
                    header("Location: ./catalog.php?id=1");
                    unset($_SESSION['article-title'],$_SESSION['article-content'],$_SESSION['categories']);
                } else {
                    $errorMessage = "Veuillez consentir à poster un contenu sérieux et approprié";
                }
            } else {
                $errorMessage = "Selectionnez au moins une catégorie en lien avec votre post.";
            }
        }

        if(!empty($_POST['deleteCategory'])) {
            $valeurSup = $_POST['deleteCategory'];
            $index = array_search($valeurSup, $_SESSION['categories']);
            if ($index !== false) {
                unset($_SESSION['categories'][$index]);
                $_SESSION['categories'] = array_values($_SESSION['categories']);
            }
        }

        if (!empty($_POST['selectedCategory']) && sizeof($_SESSION['categories']) < 5) {
            if (!in_array($_POST['selectedCategory'], $_SESSION['categories']) && isCategoryInDB($_POST['selectedCategory'], $errorMessage)) {
                array_push($_SESSION['categories'], $_POST['selectedCategory']);
            }
            $_SESSION['lastActivity'] = time(); //Timer pour la le reset de la page quand on la reload
        }

        $timeout_duration = 1;
        // Expiration de la création d'article si la page est reload
        if (sizeof($_SESSION['categories']) != 0 && isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity']) > $timeout_duration) {
            unset($_SESSION['categories']);
        }

        
    ?>

    <datalist id='categories-list'>
        <?php
            foreach($categories as $category) {
                ?><option value="<?php echo $category['name_category']?>"></option>;<?php
            }
        ?>
    </datalist>

    <main class="creationPage-container">
        <form class="creationArticle-form" method="POST">
            <button type="submit" style="display: none"></button>  <!-- Bouton invisible empechant de casser le système de categories-->
            <h1>Créez votre article</h1>

            <div class="formInput-container">
                <label for="articleTitle">Titre de l'article*</label>

                <div class="article-title-container">
                    <input class="article-title" name="article-title" type="text" value="<?php if(isset($_POST['article-title'])) echo $_POST['article-title']?>">
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
                            if(!empty($_SESSION['categories'])){
                                while($i < sizeof($_SESSION['categories'])){
                                echo "<div class='selected-category'>";
                                echo    "<p>{$_SESSION['categories'][$i]}</p>";
                                echo    "<button type='submit' name='deleteCategory' value='{$_SESSION['categories'][$i]}' class='filterButton'>";
                                echo        "<i class='bx bx-x deleteCross' alt='supprimer'></i>";
                                echo    "</button>";
                                echo "</div>";  
                                $i = $i+1;
                                }
                            } 
                            if(isset($_SESSION['categories'])){
                                if($_SESSION['categories'] !== null){
                                    $temp = sizeof($_SESSION['categories']);
                                }   
                                
                            } else {
                                $temp = 0;
                            }
                        ?>
                    </div>
                    <?php if($temp>0) echo "<div class='category-count'>$temp / 5</div>" ?>
                </div>
            </div>

            <div class="formContent-container">
                <label for="article-content">Contenu de l'article*</label>
                <textarea id="textarea" class="article-content" name="article-content" maxlength='4000' oninput="updateCharacterCount(4000)"><?php if(isset($_POST['article-content'])) echo $_POST['article-content']?></textarea>
                <div id="characterCount"></div>
            </div> 

            <label class="label-consent">
                <input type="checkbox" class="consentCheckbox" name="consent"/>
                Je m'engage à poster un contenu sérieux et approprié.
            </label>
            <button type="submit" class="creationArticle-button">Valider</button>
        </form>
    </main>
    <script>
    // Appel de la fonction pour mettre à jour le compteur au chargement de la page
        window.onload = function() {
            updateCharacterCount(4000); // Met à jour le compteur dès le chargement
        };
    </script>
    
<?php  
    require_once('../includes/footer.php');
?>    