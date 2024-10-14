<?php
    require_once('session_var.php');
    require_once('header.php');   
?>

    <div class='container'>
        <h1>Page de connexion</h1>
        <form method='GET'>
            <label for="register">Pseudo*
                <input type="text" name="pseudo" REQUIRED>
            </label>
            <label for="register">Mot de passe*
                <input type="text" name="mdp" REQUIRED>
            </label>
            <button type="submit" name="register">Se connecter</button>
        </form>
    </div>
    
    <?php
    ?>
<?php
    require_once('./footer.php');
?>
