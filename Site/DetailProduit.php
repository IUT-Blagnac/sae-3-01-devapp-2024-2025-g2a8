<?php
if (!isset($_GET["idProduit"])) {
    header("location:index.php");
    exit();
}

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
                <?php
                    $reqProduit = $conn->prepare("SELECT * FROM Produit P, Categorie C WHERE id_produit = :idProduit AND P.id_categorie = C.id_categorie");
                    $reqProduit->execute(array("idProduit" => $_GET["idProduit"]));

                    if ($reqProduit->rowCount() != 1) {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo 'Le produit est introuvable';
                        echo '</div>';
                        exit();
                    }
                    $infoProduit = $reqProduit->fetch();

                    $reqCategParent = $conn->prepare("SELECT * FROM Categorie WHERE id_categorie = :idCategorie");
                    $reqCategParent->execute(array("idCategorie" => $infoProduit["parent"]));

                    $infoCategParent = $reqCategParent->fetch();
                ?>


                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent ml-3">
                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                        <?php
                            if ($infoProduit["parent"] != null) {
                                echo "<li class='breadcrumb-item'><a href='#'>".$infoCategParent["nom_categorie"]."</a></li>";
                            }
                            echo "<li class='breadcrumb-item'><a href='#'>".$infoProduit["nom_categorie"]."</a></li>";

                            echo "<li class='breadcrumb-item active' aria-current='page'>".$infoProduit["nom"]."</li>";
                        ?>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center px-5">
                    <img src="<?php echo file_exists("imagesProduits/prod" . $infoProduit["id_produit"] . ".png") ? "imagesProduits/prod" . $infoProduit["id_produit"] . ".png" : "imagesProduits/noImage.png"; ?>" class="img-fluid w-50 h-65 d-block mx-start" alt="RockMons Produit">
                    <div class="container mt-5">
                        <div class="card shadow-sm p-4">
                            <h3 class="card-title"><?php echo $infoProduit["nom"] ?></h3>

                            <p><a href="#Description" class="text-decoration-underline">Description détaillée</a></p>

                            <div class="mb-2">
                                <h4 class="fw-bold my-3" style="font-weight: 400;"><?php echo $infoProduit["prix"] ?>€</h4>
                            </div>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-3">
                                <span class="text-success fw-bold">✔ Livraison gratuite</span>
                                <span class="text-muted">Expédié sous 3 jours</span>
                            </div>

                            <div class="d-flex align-items-center mb-3 ml-3">
                                <div class="row">
                                    <div class="input-group" style="width: 125px;">
                                        <select class="form-select">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                        <p>Encore <?php echo $infoProduit["stock"] ?> en stock</p>
                                    </div>
                                    <button class="btn btn-primary rounded w-100 bg-primary">Ajouter au Panier</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mt-5">
                    <h2 class="text-start" style="font-weight: 400;" id="Description" >Description</h2>
                    <p class="text-start fs-5">
                        <?php echo $infoProduit["description"] ?>
                    </p>
                </div>

                <div class="container mt-5">
                    <h2 class="text-start" style="font-weight: 400;"> Avis Clients</h2>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Jean Dupont</h5>
                                <div class="stars">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="card-text mt-2">Un service incroyable, je suis très satisfait de la qualité du produit. Je recommande vivement !</p>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Jean Dupont</h5>
                                <div class="stars">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="card-text mt-2">Un service incroyable, je suis très satisfait de la qualité du produit. Je recommande vivement !</p>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Jean Dupont</h5>
                                <div class="stars">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="card-text mt-2">Un service incroyable, je suis très satisfait de la qualité du produit. Je recommande vivement !</p>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Jean Dupont</h5>
                                <div class="stars">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="card-text mt-2">Un service incroyable, je suis très satisfait de la qualité du produit. Je recommande vivement !</p>
                            </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>