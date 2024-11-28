<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Menu vertical sur la gauche -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'index.php') echo 'active'; ?>" href="index.php">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'ConsultPrix.php') echo 'active'; ?>" href="ConsultPrix.php">Consulter les produits par prix</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'ConsultCat.php') echo 'active'; ?>" href="ConsultCat.php">Consulter les produits par catégorie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'AjouterCategorie.php') echo 'active'; ?>" href="AjouterCategorie.php">Ajouter Catégorie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'Deconnexion.php') echo 'active'; ?>" href="Deconnexion.php">Déconnexion</a>
            </li>
            <?php if (isset($_COOKIE['CserreLohan'])): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'DetruireCookie.php') echo 'active'; ?>" href="DetruireCookie.php">Détruire Cookie</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
