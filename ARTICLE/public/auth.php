<!DOCTYPE html>
<html>
    <head>
        <title>Sport News</title>
        <link rel="stylesheet" href="./app.css" />
        <?php
        session_start();

        require "../src/functions.php";
        require "../src/components/navbar.php";


        /*
        ---------- GESTION DU FORMULAIRE D'AUTHENTIFICATION ------------------------------------------------------------
        */
        if (isConnected()) {
            redirectWhenConnected();
        }
        
        function existIdentifier() {
            return isset($_POST['identifier']) && !empty($_POST['identifier']);
        }

        function existPassword() {
            return isset($_POST['identifier']) && !empty($_POST['identifier']);
        }

        if (existIdentifier()) {
            if (existPassword()) {
                authUser($_POST['identifier'], $_POST['password']);
            } else {
                $errorMessageAuth = "Le mot de passe ne peut pas être vide";
            } 
        } 

        ?>
    </head>
    <body>
        <main class="main-auth">
            <form class="form-auth" method="POST">
                <div class="form-auth-title">
                    <h1>Se connecter</h1>
                </div>
                <div class="form-auth-item">
                    <label class="form-auth-label" for="identifier">Identifiant (email, pseudo)*</label>
                    <input class="form-auth-input" type="text" name="identifier">
                </div>
                <div class="form-auth-item">
                    <label class="form-auth-label" for="password">Mot de passe</label>
                    <input class="form-auth-input" type="password" name="password">
                </div>
                <div class="form-auth-item">
                    <button class="form-auth-button" type="submit">
                </div>
            </form>
        </main>
        <?php if (isset($errorMessageAuth) && !empty($errorMessageAuth)) { ?>
            <div class="error-message">
                <?php echo $errorMessageAuth ?>
            </div>
        <?php } ?>
    </body>
</html>



<?php
/*



    if(areIdentifiersCompleted() && areInputsVerified($errorMessage)) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            connectUser($errorMessage,$connexion);
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }
?>

    <main>
        
            </main>
    <script>
    */
        /* Cet EventListener sert à supprimer les $_SESSION si la page est quitté lors de la connexion
                - Changement de l'URL
                - Fermeture de la page
        */
        /*
        window.addEventListener("beforeunload", function(event) {
            // Utilise fetch pour envoyer une requête au serveur
            navigator.sendBeacon(<?php
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                unset($_SESSION['pseudo']);
                unset($_SESSION['displayPseudo']);
            ?>);
        });
    </script>
?>*/
