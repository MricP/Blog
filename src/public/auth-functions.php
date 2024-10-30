<?php
    function getUserFromDBWithEmail($email) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM user WHERE email = :email";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return empty($res) ? NULL : $res;
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }

    
    function getUserFromDBWithPseudo($pseudo) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM user WHERE pseudo = :pseudo";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return empty($res) ? NULL : $res;
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }


    function areIdentifiersCompleted() {
        //Vérifie si l'email et le password ont bien été renseigné
        return isset($_SESSION['email'],$_SESSION['password']);
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


    function insertUserToDB(&$errorMessage) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if(!empty(getUserFromDBWithPseudo($_SESSION['pseudo']))) {
                $errorMessage = "Ce pseudo est déjà emprunté.";
                return false;
            } else {
                // Insérer le nouvel utilisateur
                $sql = "INSERT INTO user (pseudo, password, email) VALUES (:pseudo, :password, :email)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
                $stmt->bindParam(':password', hashPassword($_SESSION['password']));
                $stmt->bindParam(':email', $_SESSION['email']);
                $stmt->execute();
                return true;
            }
        } catch (PDOException $e) { 
            die('Erreur PDO : ' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur Générale : ' . $e->getMessage());
        }
    }


    function connectUser(&$errorMessage) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Vérification si le pseudo existe déjà
            $user = getUserFromDBWithEmail($_SESSION['email']);
            if(empty($user)) { //Créer un compte
                $_SESSION['displayPseudo']=true;
                if(isPseudoCompleted()){
                    //L'insertion s'est bien passé, on redirige
                    if(insertUserToDB($errorMessage)) {
                        $_SESSION['currentUser']['pseudo'] = $_SESSION['pseudo'];
                        $_SESSION['currentUser']['password'] = hashPassword($_SESSION['password']); // Pensez à hacher les mots de passe
                        $_SESSION['currentUser']['email'] = $_SESSION['email'];
                        unset($_SESSION['email']);
                        unset($_SESSION['password']);
                        unset($_SESSION['pseudo']);
                        unset($_SESSION['displayPseudo']);
                        unset($_SESSION['lastActivity']);
                        header("Location: ./page.php"); 
                    }
                }
            } else { //Se connecter
                if($user['password'] == hashPassword($_SESSION['password'])) {
                    $_SESSION['currentUser']=$user;
                    unset($_SESSION['email']);
                    unset($_SESSION['password']);
                    unset($_SESSION['pseudo']);
                    unset($_SESSION['displayPseudo']);
                    unset($_SESSION['lastActivity']);
                    header("Location: ./page.php");
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


    function hashPassword($password) {
        // sha256 permet d'avoir le même hashage pour des chaînes identiques
        return hash('sha256', $password);
    }
?>