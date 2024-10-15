<?php
    function findUser($pseudo,$connexion) {
        $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    function userFromDB($pseudo,$connexion) {
        if(!empty(findUser($pseudo,$connexion))) {
            $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res;
        } else {
            return NULL;
        }
    }
?>