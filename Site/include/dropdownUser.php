<?php
$user = getUserById($_SESSION['user_id']);
?>

<div class="dropdown">
    <a class="btn btn-secondary dropdown-toggle d-flex align-items-center gap-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

            <img src='./icons/utilisateur_claire.png' title='<?php echo ($user['nom']) ?>' width='30' height='30'>
            <p><?php echo ($user['prenom']); ?></p>
    </a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="#">Mon Compte</a>
        </li>
        <li>
            <a class="dropdown-item" href="#">Deconnexion</a>
        </li>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>