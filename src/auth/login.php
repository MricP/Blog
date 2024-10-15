<?php
    require_once('../session_var.php');
    require_once('../header.php');   
    require_once('./functions_auth.php');

    if(!isset($errorMessage)) {
        $errorMessage = NULL;
    }

    

    function connectUser(&$errorMessage) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérification si le pseudo existe déjà
            $user = userFromDB($_POST['pseudo'],$connexion);
            if(empty($user)) {
                $errorMessage = "Ce pseudo n'existe pas.";
            } else {
                if($user['mdp'] == $_POST['password']) {
                    $_SESSION['currentUser'] = $user;
                } else {
                    $errorMessage = "Mot de passe incorrect.";
                    echo 'not ok';
                }
            }
            
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }

    function areInputsCompleted(){
        // Vérifiez d'abord si $_POST est défini avant d'accéder à ses clés
        return isset($_POST['pseudo'], $_POST['password']) && 
            !empty($_POST['pseudo']) && 
            !empty($_POST['password']);
    }

    if(areInputsCompleted()) {
        connectUser($errorMessage);
    }

?>
    <div class='container'>
        <h1>Page de connexion</h1>
        <div class="error-message">
            <?php echo $errorMessage ?>
        </div>
        <form method='POST'>
            <label for="pseudo">Pseudo*
                <input type="text" name="pseudo" REQUIRED>
            </label>
            <label for="password">Mot de passe*
                <input type="text" name="password" REQUIRED>
            </label>
            <button type="submit" >Se connecter</button>
        </form>
        <div id="redirection-dic">
            <p>Vous n'avez pas de compte ? </p>
            <a href="./register.php"> S'inscrire</a>
        </div>
        
    </div>
    
    <?php
    ?>
<?php
    require_once('../footer.php');
?>
