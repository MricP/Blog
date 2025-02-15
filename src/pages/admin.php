<?php
    require_once('../utils/functions.php');
    require_once('../includes/header.php');
?>

<?php
    if(!isset($_SESSION['currentUser'])) {
        header("Location: ./auth.php?from=admin.php");
    } else { // Si l'utilisateur tente d'acceder à la page admin sans etre admin, il est redirigé vers la 1ère page du catalogue
        if(selectUser($_SESSION['currentUser'])[$GLOBALS['db']['tables']['USERS']['fields']['TYPE_USER']] != 'admin') {
            header("Location: ./catalog.php?id=1");
        }
    }

    if(isset($_POST['delete']) && !empty($_POST['delete'])) {
        deleteCategory((int)$_POST['delete']);
        unset($_POST['delete']);
        unset($_POST['modify']);
        unset($_POST['newCategoryName']);
    }

    if(isset($_POST['newCategoryName']) && !empty($_POST['newCategoryName'])){
        modifyCategory($_POST['newCategoryName'],(int)$_POST['modify'],$errorMessage);
        unset($_POST['modify']);
        unset($_POST['newCategoryName']);
    }

    if(isset($_POST['newCategory']) && !empty($_POST['newCategory'])){
        createCategory($_POST['newCategory'],$errorMessage);
        unset($_POST['newCategory']);
        unset($_POST['modify']);
        unset($_POST['newCategoryName']);
    }
?>

<div class='main-admin'>
    <div class='admin-left-panel'>
        <h1>Espace Admin</h1>
        <div class='admin-left-menu'>
            <p class="button-left-menu menu-categories">Categories</p>
            <p class="button-left-menu ">Autre</p>
            <p class="button-left-menu ">Autre</p>
        </div>
    </div>
    <div class='admin-right-panel'>
        <h2>Categories</h2>
        <form class='admin-form-newCategory' method='POST'>
            <input type="text" name="newCategory">
            <button type="submit" name="add">Ajouter une catégorie</button>
        </form>
        <?php 
            if(isset($errorMessage)){
                echo "<div class='error-message'>{$errorMessage}</div>";
                unset($errorMessage);
            }
        ?>
        <div class="admin-div-categories">
            <?php 
            $categories = selectAllCategories();
            foreach($categories as $category) { ?>
                    <?php if(isset($_POST['modify']) && ($_POST['modify'] == $category['id_category'])) { ?>
                        <div class='admin-div-category with-input'>
                            <form class='admin-div-category-withInput' method='POST'>
                                <input type='text' name='newCategoryName' value='<?php echo $category['name_category']; ?>'></input>
                                <button type='submit' name='modify' value="<?php echo $category['id_category']; ?>">Modifier</button>
                            </form>
                            <form method='POST'>
                                <button type='submit' name='delete' value="<?php echo $category['id_category']; ?>">Supprimer</button>
                            </form>
                        </div>
                    <?php } else { ?>
                        <div class='admin-div-category with-p'>
                            <p><?php echo $category['name_category']; ?></p>
                            <form method='POST'>
                                <button type='submit' name='modify' value='<?php echo $category['id_category']; ?>'>Modifier</button>
                            </form>
                            <form method='POST'>
                                <button type='submit' name='delete' value='<?php echo $category['id_category']; ?>'>Supprimer</button>
                            </form>
                        </div>
                    <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<?php
    require_once('../includes/footer.php');
?>