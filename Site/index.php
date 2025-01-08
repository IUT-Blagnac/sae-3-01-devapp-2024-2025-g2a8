<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    <?php    
        require_once("./include/header.php");
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1">
        <!-- Menu vertical sur la gauche -->
        <?php
            require_once("./include/menu.php");
        ?>

        <h2 class="mx-4 mt-3" style="font-weight: 500;">Nouveautés</h2>
        
        <!-- Début de la ligne de produits -->
         <div class="container">
        <div class="row justify-content-center">
        
        <?php
        try {
            // Préparation de la requête pour récupérer les produits par leur ID
            $reqProduitsNouv = $conn->prepare("SELECT * FROM Produit WHERE id_produit IN (3, 4, 42, 31)");
            $reqProduitsNouv->execute();

            // Vérification si des produits ont été trouvés
            if ($reqProduitsNouv->rowCount() > 0) {
                // Parcours des produits
                while ($prod = $reqProduitsNouv->fetch()) {
                    // Récupération des données du produit
                    $id = $prod['id_produit'];
                    $nom = $prod['nom'];
                    $description = $prod['description'];
                    $prix = $prod['prix'];
                    $stock = $prod['stock'];
                    ?>
                    
                    <!-- Affichage du produit sous forme de carte -->
                    <div class="col">
                        <a href="DetailProduit.php?idProduit=<?php echo $id; ?>" class="noHoverLine">
                            <div class="card productDetailsContainer" style="width: 100%; max-width: 18rem;">
                                <img src="./imagesProduits/prod<?php echo $id; ?>.png" class="card-img-top" alt="Image de <?php echo $nom; ?>">
                                <div class="card-body">
                                    <h5 class="card-title blackText"><?php echo $nom; ?></h5>
                                    <p class="card-text blackText"><?php echo substr($description, 0, 83) . "..."; ?></p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bold blackText"><?php echo $prix . "€"; ?></li>
                                    <li class="list-group-item greenText">Expédié en 48/72 heures</li>
                                    <li class="list-group-item blueText"><?php echo $stock . " en stock"; ?></li>
                                </ul>
                            </div>
                        </a>
                    </div>

                    <?php
                }
            }
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo 'Erreur : ' . $e->getMessage();
        }
        ?>

        </div>
        <!-- Fin de la ligne de produits -->

    </div>
    </div>

    <!-- Pied de page -->
    <?php
        require_once("./include/footer.php");
    ?>

</body>

</html>
