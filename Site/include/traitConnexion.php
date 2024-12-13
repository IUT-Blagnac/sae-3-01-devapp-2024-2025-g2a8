<?php

function traitConnexion($mail, $pass){
    include("connect.inc.php");

    $mailUser = htmlentities($mail);

    $userGet = $conn->prepare("SELECT * FROM Utilisateur WHERE mail='$mailUser'");

    $userGet->execute();
    $cou = $userGet->rowCount();

    if ($userGet->rowCount() != 1) {
        return("Impossible de trouver votre compte $cou");
    }

    $user = $userGet->fetch();

    if (password_verify($pass, $user['password'])) {
        session_name($user['mail']);
        session_start();

        $_SESSION["user_id"] = $user['user_id'];

        return("OK");
    } else {
        return("Mot de passe invalide $pass, $mailUser");
    }
}