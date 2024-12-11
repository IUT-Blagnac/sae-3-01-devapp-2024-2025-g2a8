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
        <div class="row">
            <!-- Menu vertical sur la gauche -->
            <?php
                require_once("./include/menu.php");
                require_once("./include/connect.inc.php");
            ?>

            <!-- Contenu principal -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class = "productContainer">
                    <?php
                        $id = htmlentities($_GET['idSousCateg']);
                        $prods = $conn->prepare("SELECT * FROM Produit WHERE id_categorie = $id");
                        $prods->execute();
                        if ($prods->rowCount() > 0) {
                            foreach($prods->fetchAll(PDO::FETCH_ASSOC) as $produits){ 
                                /* Récupération des données du produits */ 
                                $id = $produits['id_produit'];
                                $nom = $produits['nom'];
                                $description = $produits['description'];
                                $prix = $produits['prix'];
                                $stock = $produits['stock'];
                                ?>
                                <!-- changer les liens pour mettre un id en parametre et le nom image -->
                                <a href="DetailProduit.php" class="noHoverLine"><div class="card productDetailsContainer" style="width: 18rem;">
                                    <img src=<?php"./imagesProduits/prod".$id.".png"?> class="card-img-top" alt=<?php"./imagesProduits/prod".$id.".png"?>>
                                    <div class="card-body">
                                        <h5 class="card-title blackText"><?php echo $nom ?></h5>
                                        <p class="card-text blackText"><?php echo substr($description, 0, 83)."..."; ?></p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bold blackText"><?php echo $prix."€" ?></li>
                                        <li class="list-group-item greenText">Disponible en 48/72 heures</li>
                                        <li class="list-group-item blueText"><?php echo $stock." en stock" ?></li>
                                    </ul>
                                </div></a>
                            <?php }
                        }else{
                            echo "<h3>Aucun produit n'est disponible pour cette catégorie</h3";
                            $prods->closeCursor();
                        }
                    ?>


                </div>
            
            </main>
        </div>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>