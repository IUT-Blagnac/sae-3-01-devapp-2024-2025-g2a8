<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    <?php    
        require_once("./include/header.php");
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1 p-0">
            <!-- Menu vertical sur la gauche -->
            <?php
                require_once("./include/menu.php");
            ?>

            <!-- Contenu principal -->
            <main role="main" class="" style="min-height: 50vh;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="#">...</a></li>
                        <li class="breadcrumb-item active" aria-current="page">RockMon</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center px-5">
                    <img src="icons/RockDragon.png" class="img-fluid w-50 h-65 d-block mx-start" alt="RockMons Produit">
                    <div class="container mt-5">
                        <div class="card shadow-sm p-4">
                            <h3 class="card-title">RockMon Dragon De Feu</h3>

                            <p><a href="#" class="text-decoration-underline">Description détaillée</a></p>

                            <div class="mb-2">
                                <h4 class="fw-bold mb-0">99,99€</h4>
                                <p>Ou payez en 3x ou 4x</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-3">
                                <span class="text-success fw-bold">✔ Livraison gratuite</span>
                                <span class="text-muted">Expédié sous 3 jours</span>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="input-group me-3" style="width: 125px;">
                                    <select class="form-select">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    <p>Encore 5 en stock</p>
                                </div>
                                <button class="btn btn-dark w-75">J'achète</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>