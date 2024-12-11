<?php 
    include("connect.inc.php");

    $mail = htmlentities($_POST["mail"]);
    $passBrute = htmlentities($_POST["pass"]);
    $isRemember = htmlentities($_POST["remember"]);

    $userGet = $conn->prepare("SELECT * FROM Utilisateur WHERE mail='$mail'");

    $userGet->execute();

    if($userGet->rowCount() != 1){
        header("location:connexion.php");
        exit();
    }

    $user = $userGet->fetch();

    $hashInput = password_hash($passBrute, PASSWORD_DEFAULT);

    if($hashInput == $user['password']){
        session_name($user['mail']);
        session_start();

        $_SESSION["user_id"] = $user['user_id'];

        header("location:index.php");
        exit();
    } else {
        header("location:connexion.php");
        exit();
    }
?>