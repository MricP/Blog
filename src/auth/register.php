<?php
    require_once('../session_var.php');
    require_once('./functions_auth.php');
    require_once('../header.php');
    
    if(!empty($_SESSION['currentUser'])){
        header("Location: ../start.php");
    }

    if(!isset($errorMessage)) {
        $errorMessage = NULL;
    }

    function areInputsCompleted(){
        // Vérifiez d'abord si $_POST est défini avant d'accéder à ses clés
        return isset($_POST['pseudo'], $_POST['password'], $_POST['email']) && 
            !empty($_POST['pseudo']) && 
            !empty($_POST['password']) && 
            !empty($_POST['email']);
    }

    function findErrorMessage() {
        $verif = true;
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        if(strlen($pseudo)<4 && strlen($pseudo)>15 ) {
            $errorMessage = "Votre pseudo doit comporter au moins 4 à 15 caractères.";
        }
        elseif(strlen($password) < 6 && strlen($password)>15) {
            $errorMessage = "Votre mot de passe doit comporter au moins 6 à 15 caractères.";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "L'adresse email n'a pas un format valide.";
        }
    }

    function areInputsVerified(&$errorMessage) {
        $verif = true;
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        if(strlen($pseudo) < 4) {
            $errorMessage = "Votre pseudo doit comporter au moins 4 caractères.";
            $verif = false;
        }
        elseif(strlen($password) < 6) {
            $errorMessage = "Votre mot de passe doit comporter au moins 6 caractères.";
            $verif = false;
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "L'adresse email n'a pas un format valide.";
            $verif = false;
        }
        
        return $verif;
    }

    function createAccount(&$errorMessage) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if(!empty(findUser($_POST['pseudo'],$connexion))) {
                $errorMessage = "Ce pseudo est déjà emprunté.";    
            } else {
                // Insérer le nouvel utilisateur
                $sql = "INSERT INTO utilisateur (pseudo, password, email) VALUES (:pseudo, :password, :email)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':pseudo', $_POST['pseudo']);
                $stmt->bindParam(':password', $_POST['password']);
                $stmt->bindParam(':email', $_POST['email']);
                $stmt->execute();
                header("Location: ./login.php");
            }
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }

    // Vérification des entrées
    if(areInputsCompleted() && areInputsVerified($errorMessage)) {
        //creation du compte de l'utilisateur
        createAccount($errorMessage);
    }
    ?>

    <div class='container'>
        <h1>Page d'inscription</h1>
        <div class="error-message">
            <?php echo $errorMessage ;
            $user = $_SESSION['currentUser'];
            echo $user['pseudo'];?>
        </div>
        <form method='POST'>
            <label for="pseudo">Pseudo*
                <input type="text" name="pseudo" REQUIRED>
            </label>
            <label for="email">Adresse mail*
                <input type="text" name="email" REQUIRED>
            </label>
            <label for="password">Mot de passe*
                <input type="text" name="password" REQUIRED>
            </label>
            <button type="submit">S'inscrire</button>
        </form>
        <div id="redirection-div">
            <p>Vous avez déjà un compte ? </p>
            <a href="./login.php"> Se connecter</a>
        </div>
    </div>

<?php
    require_once('../footer.php');
?>
