<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Menu horizontal -->
<nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'index.php') echo 'active'; ?>" href="index.php">Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link <?php if ($current_page == 'ConsultPrix.php' || $current_page == 'ConsultCat.php') echo 'active'; ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Nos Produits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'AjouterCategorie.php') echo 'active'; ?>" href="AjouterCategorie.php">Nouveautés</a>
                </li>
            </ul>
        </div>
    </nav>
