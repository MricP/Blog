<?php
    require_once('../header.php');   
    require_once('./auth-functions.php');

    // if(!empty($_SESSION['currentUser'])){
    //     header("Location: ../start.php");
    // }

    if(!isset($errorMessage)) {
        $errorMessage = NULL;
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
    <?php
        echo isset($_SESSION['email']) ? $_SESSION['email']."\n" : "NULL"."\n";
        echo isset($_SESSION['password']) ? $_SESSION['password']."\n" : "NULL"."\n";
        echo isset($_SESSION['pseudo']) ? $_SESSION['pseudo']."\n" : "NULL"."\n";
    ?>

    <div class='container'>
        <h1>Page de connexion</h1>
        <div class="error-message">
            <?php echo $errorMessage ?>
        </div>
        <form method='POST'>
            <?php
                if($_SESSION['displayPseudo']){ // Nouveau compte
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
    </div>
<?php
    require_once('../footer.php');
?>