<?php
    require_once('../session_var.php');
    require_once('../header.php');   
    require_once('./functions_auth.php');

    if(!empty($_SESSION['currentUser'])){
        header("Location: ../start.php");
    }

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
                    header("Location: ../start.php");
                } else {
                    $errorMessage = "Mot de passe incorrect.";
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
            <?php echo $errorMessage ;
            $user = $_SESSION['currentUser'];
            echo $user['pseudo']; ?>
        </div>
        <form method='POST'>
            <label for="email">Adresse email*
                <input type="text" name="email" REQUIRED>
            </label>
            <label for="password">Mot de passe*
                <input type="text" name="password" REQUIRED>
            </label>
            <label for="password">Mot de passe*
                <input type="text" name="password" REQUIRED>
            </label>
            <button type="submit" >Se connecter</button>
        </form>    
    </div>
    <?php
    ?>
<?php
    require_once('../footer.php');
?>
