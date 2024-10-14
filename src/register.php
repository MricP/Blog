<?php
    require_once('session_var.php');
    require_once('header.php');

    function areInputsCompleted(){
        // Vérifiez d'abord si $_POST est défini avant d'accéder à ses clés
        return isset($_POST['pseudo'], $_POST['mdp'], $_POST['mail']) && 
            !empty($_POST['pseudo']) && 
            !empty($_POST['mdp']) && 
            !empty($_POST['mail']);
    }

    function findErrorMessage() {
        $verif = true;
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $mail = $_POST['mail'];
        if(strlen($pseudo) < 4) {
            $errorMessage = "Votre pseudo doit comporter au moins 4 caractères.";
        }
        elseif(strlen($mdp) < 6) {
            $errorMessage = "Votre mot de passe doit comporter au moins 6 caractères.";
        }
        elseif(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "L'adresse email n'a pas un format valide.";
        }
    }

    function areInputsVerified(&$errorMessage) {
        $verif = true;
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $mail = $_POST['mail'];
        if(strlen($pseudo) < 4) {
            $errorMessage = "Votre pseudo doit comporter au moins 4 caractères.";
            $verif = false;
        }
        elseif(strlen($mdp) < 6) {
            $errorMessage = "Votre mot de passe doit comporter au moins 6 caractères.";
            $verif = false;
        }
        elseif(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "L'adresse email n'a pas un format valide.";
            $verif = false;
        }
        
        return $verif;
    }

    function createAccount(&$errorMessage) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérification si le pseudo existe déjà
            $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $_POST['pseudo']);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($res)) {
                $errorMessage = "Ce pseudo est déjà emprunté.";    
            } else {
                // Insérer le nouvel utilisateur
                $sql = "INSERT INTO utilisateur (pseudo, mdp, mail) VALUES (:pseudo, :mdp, :mail)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':pseudo', $_POST['pseudo']);
                $stmt->bindParam(':mdp', $_POST['mdp']);
                $stmt->bindParam(':mail', $_POST['mail']);
                $stmt->execute();
                echo "Utilisateur créé avec succès.";
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
            <?php echo $errorMessage ?>
        </div>
        <form method='POST'>
            <label for="pseudo">Pseudo*
                <input type="text" name="pseudo" REQUIRED>
            </label>
            <label for="mail">Adresse mail*
                <input type="text" name="mail" REQUIRED>
            </label>
            <label for="mdp">Mot de passe*
                <input type="text" name="mdp" REQUIRED>
            </label>
            <button type="submit">S'inscrire</button>
        </form>
    </div>

<?php
    require_once('./footer.php');
?>
