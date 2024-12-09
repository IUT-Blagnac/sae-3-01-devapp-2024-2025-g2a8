<?php
    session_name("SserreLohan");
    session_start();

    if (!isset($_SESSION['acces']) || $_SESSION['acces'] !== 'oui') {
        header("Location: FormConnexion.php?msgErreur=Vous devez être connecté");
        exit();
    }
?>  