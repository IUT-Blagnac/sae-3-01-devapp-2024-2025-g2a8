<?php
    $admin = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = :user_id AND role = 'A'");
    $admin->execute(['user_id' => $_SESSION['user_id']]);
    if (!isset($_SESSION['user_id']) || $admin->rowCount() == 0) {
            header("location:index.php");
            exit();
        }
?>  