<?php

function getUserById($id){
    require("connect.inc.php");

    $userGet = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = $id");

    $userGet->execute();

    if($userGet->rowCount() == 1){
        $user = $userGet->fetch();

        return $user;
    } else {
        return false;
    }
}

function getItemCartNumber($id){
    require("connect.inc.php");

    $panierGet = $conn->prepare("SELECT * FROM Panier WHERE user_id = $id");
    $panierGet->execute();

    return $panierGet->rowCount();
}