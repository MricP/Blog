    <?php require("header.php"); ?>
    <div class="creationPage-container">
        <form class="creationArticleForm" methods="GET">
            <h1>Créez votre Article</h1>
            <div class="formInput-container">
                <label for="titreArticle">Titre de l'article</label>
                <div>
                    <input class="titreArticle" name="titreArticle" type="text">
                    <input class="choixCategorie" type="text" list="Sports" placeholder="Catégorie" name="choixCategorie">
                </div>
                <datalist id="Sports">
                    <option value=" "></option>
                    <option value="Football"></option>
                    <option value="Tennis"></option>
                    <option value="Rugby"></option>
                    <option value="Hockey"></option>
                    <option value="Golf"></option>
                    <option value="F1"></option>
                    <option value="Natation"></option>
                    <option value="Handball"></option>
                    <option value="Athlétisme"></option>
                    <option value="Football Américain"></option>
                    <option value="Basketball"></option>
                </datalist>
            </div>
            <div class="formContent-container">
                <label for="contentArticle">Contenu de l'article</label>
                <textarea class="contentArticle" name="contentArticle" type="text"></textarea>
            </div> 
            <?php 
                if(!empty($_GET['titreArticle']) && !empty($_GET['choixCategorie']) && !empty($_GET['contentArticle'])){
                    $_SESSION['titreArticle'] = $_GET['titreArticle'];
                    $_SESSION['choixCategorie'] = $_GET['choixCategorie'];
                    $_SESSION['contentArticle'] = $_GET['contentArticle'];
                }
            ?> <button type="submit" class="buttonCreationArticle">Valider</button>
        </form>
       
    </div>
    
    
</body>
</html>