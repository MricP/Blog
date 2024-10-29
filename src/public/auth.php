<?php
    require_once('../includes/header.php');   
    require_once('./auth-functions.php');

    if(!empty($_SESSION['currentUser'])){
        // header("Location: ./start.php");
    }

    if(isset($_POST['email']) && !empty($_POST['email'])) {
        $_SESSION['email'] = $_POST['email'];
    }
        
    if(isset($_POST['password']) && !empty($_POST['password'])) {
        $_SESSION['password'] = $_POST['password'];
    }
        
    if(isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        $_SESSION['pseudo'] = $_POST['pseudo'];
    }

    $timeout_duration = 10;
    // Expiration de la création de compte au bout de 30s sans tenter de rentrer un pseudo
    if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity']) > $timeout_duration) {
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['pseudo']);
        unset($_SESSION['displayPseudo']);
        unset($_SESSION['lastActivity']);
    }
    
    if(!isset($_SESSION['displayPseudo'])) {
        $_SESSION['displayPseudo'] = false;
    }

    if(!isset($errorMessage)) {
        $errorMessage = NULL;
    }
    
    if(areIdentifiersCompleted() && areInputsVerified($errorMessage)) {
        connectUser($errorMessage);
    }

    /* TODO : créer un compte, créer un autre, se connecter avec le premier : BUG ICI*/ 
?>

    <?php
        echo isset($_SESSION['pseudo']) ? $_SESSION['pseudo']."\n" : "NULL"."\n";
        echo isset($_SESSION['email']) ? $_SESSION['email']."\n" : "NULL"."\n";
        echo isset($_SESSION['password']) ? $_SESSION['password']."\n" : "NULL"."\n";
    ?>
    <main>
        <h1>Page de connexion</h1>
        <div class="error-message">
            <?php echo $errorMessage ?>
        </div>
        <form method='POST'>
            <?php
                if($_SESSION['displayPseudo']){ // Nouveau compte
                    $_SESSION['lastActivity'] = time();
                    echo "<label for='pseudo'>Pseudo*";
                    echo "    <input type='text' name='pseudo' REQUIRED>";
                    echo "</label>";
                } else {
                    echo "<label for='email'>Adresse mail*";
                    echo "    <input type='text' name='email' REQUIRED>";
                    echo "</label>";
                    echo "<label for='password'>Mot de passe*";
                    echo "    <input type='text' name='password' REQUIRED>";
                    echo "</label>";
                }
            ?>
            <button type="submit" >Se connecter</button>
        </form>
    </main>
<?php
    require_once('../includes/footer.php');
?>

