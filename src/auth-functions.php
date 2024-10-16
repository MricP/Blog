<?php
    function getUserFromDB($email,$connexion) {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($res) ? NULL : $res;
    }


    function areIdentifiersCompleted() {
        //Vérifie si l'email et le password ont bien été renseigné
        return isset($_SESSION['email'],$_SESSION['email']);
    }


    function isPseudoCompleted(){
        //Vérifie si le pseudo a bien été renseigné
        return isset($_SESSION['pseudo']);
    }


    function areInputsVerified(&$errorMessage) {
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
    
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Couvre la connexion et la création
            $errorMessage = "L'adresse email n'a pas un format valide.";
            return false;
        } elseif(strlen($password) < 6 || strlen($password)>30) { //Couvre la connexion et la création
            $errorMessage = "Votre mot de passe doit comporter 6 à 15 caractères.";
            return false;
        } elseif(isPseudoCompleted()){
            //Si on est dans la phase de création (qu'un pseudo est entré)
            if(strlen($_SESSION['pseudo'])<4 || strlen($_SESSION['pseudo'])>64) { 
                $errorMessage = "Votre pseudo doit comporter 4 à 15 caractères.";
                return false;
            }
        }
        return true;
    }


    function insertUserToDB(&$errorMessage,$connexion) {
        if(!empty(getUserFromDB($_SESSION['pseudo'],$connexion))) {
            $errorMessage = "Ce pseudo est déjà emprunté.";
            return false;
        } else {
            // Insérer le nouvel utilisateur
            $sql = "INSERT INTO user (pseudo, password, email) VALUES (:pseudo, :password, :email)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
            $stmt->bindParam(':password', $_SESSION['password']);
            $stmt->bindParam(':email', $_SESSION['email']);
            $stmt->execute();
            return true;
        }
    }


    function connectUser(&$errorMessage,$connexion) {
        // Vérification si le pseudo existe déjà
        $user = getUserFromDB($_SESSION['email'],$connexion);
        if(empty($user)) { //Créer un compte
            $_SESSION['displayPseudo']=true;
            if(isPseudoCompleted()){
                //L'insertion s'est bien passé, on redirige
                if(insertUserToDB($errorMessage,$connexion)) {
                    echo "ok";
                    //$_SESSION['currentUser'] =;
                    unset($_SESSION['email']);
                    unset($_SESSION['password']);
                    unset($_SESSION['pseudo']);
                    unset($_SESSION['displayPseudo']);
                    //header("Location: ../start.php");
                    
                }
                //L'insertion ne s'est pas bien passé, le pseudo est déjà utlisé
            }
        } else { //Se connecter
            if($user['password'] == $_POST['password']) {
                $_SESSION['currentUser'] = getUserFromDB($_SESSION['email'],$connexion);
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                unset($_SESSION['pseudo']);
                unset($_SESSION['displayPseudo']);
                //header("Location: ../start.php");
            } else {
                $errorMessage = "Mot de passe incorrect.";
            }
        }
    }


    function isPseudoAlreadyUsedInDB() {

    }


    function isEmailAlreadyUsedInDB() {

    }
?>