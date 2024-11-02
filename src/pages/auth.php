<?php
    require_once('../utils/functions.php');
    require_once('../includes/header.php');   


    // Pour la déconnexion
    if(isset($_GET['logout'])) {
        unset($_SESSION['currentUser']);
        header("Location: ./catalog.php?id=1");
        exit();
    }

    if(isConnected()){
        header("Location: ./catalog.php?id=1");
    }

    if(isset($_POST['email']) && !empty($_POST['email'])) {
        $_SESSION['email'] = $_POST['email'];
    }
        
    if(isset($_POST['password']) && !empty($_POST['password'])) {
        $_SESSION['password'] = $_POST['password'];
    }
        
    if(isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        $_SESSION['pseudo'] = $_POST['pseudo'];
        $_SESSION['lastActivity'] = time();
    }

    $timeout_duration = 1;
    // Expiration de la création de compte si la page est reload
    if (isset($_SESSION['lastActivity']) && !empty($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity']) > $timeout_duration) {
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['pseudo']);
        unset($_SESSION['displayPseudo']);
        unset($_SESSION['lastActivity']);
    }
    
    if(!isset($_SESSION['displayPseudo'])) {
        $_SESSION['displayPseudo'] = false;
    }

    if(areIdentifiersCompleted() && areIdentifiersVerified($errorMessage)) {
        if(connectUser($errorMessage)) { // Si la connexion se passe bien, on redirige
            // Système de redirection en fonction de la page de provenance
            isset($_GET['from']) ? header("Location: ".$_GET['from']) : header("Location: ./catalog.php?id=1");
        }
    }
?>    
    <main>
        <h1>Page de connexion</h1>
        
        <?php 
            if(isset($errorMessage)){
                echo "<div class='error-message error-message-auth'>{$errorMessage}</div>";
                unset($errorMessage);
            }
        ?>

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

