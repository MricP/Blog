<?php
    require_once('../includes/header.php');   
?>
    <main>
        <?php
            $user = $_SESSION['currentUser'];
            echo "CurrentUser : \n";
            echo "Pseudo : ".$user['pseudo']."\n";
            echo "Email : ".$user['email']."\n";
            echo "Password : ".$user['password']."\n";
        ?>
    </main>
<?php
    require_once('./footer.php');
?>
