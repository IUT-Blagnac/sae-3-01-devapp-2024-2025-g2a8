<?php
$user = getUserById($_SESSION['user_id']);
?>

<div class="dropdown">
    <a class="btn btn-secondary dropdown-toggle d-flex align-items-center gap-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

            <img src='./icons/utilisateur_claire.png' title='<?php echo ($user['nom']) ?>' width='30' height='30'>
            <p><?php echo ($user['prenom']); ?></p>
    </a>
    <ul class="dropdown-menu" style="z-index: 1002;">
        <li>
            <a class="dropdown-item" href="./compte.php">Mon Compte</a>
        </li>
        <?php 
            $admin = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = :user_id AND role = 'A'");
            $admin->execute(['user_id' => $_SESSION['user_id']]);
            if($admin->rowCount() > 0){
                
        ?>
        <li>
            <a class="dropdown-item" href="ajoutCat.php">Ajouter/Supprimer catégorie</a>
        </li>
        <li>
            <a class="dropdown-item" href="ajoutProd.php">Ajouter un produit</a>
        </li>
        <?php
        }
        $admin->closeCursor();
        ?>
        <li>
            <a class="dropdown-item" href="deconnexion.php">Deconnexion</a>
        </li>
        
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>