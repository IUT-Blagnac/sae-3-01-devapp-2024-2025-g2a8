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
                
            ?>

            <!-- Contenu principal -->
            <main role="main" class="w-100 px-4">
                
            <?php
                if(!isset($_POST['filtrer'])){
                    $prixMin = 0;
                    $ordre = "ASC";
                    $ordreAvec = "prix";
                    $reqPrixMax = $conn->prepare("SELECT MAX(prix) as prixMax FROM Produit");
                    $reqPrixMax->execute();
                    $prixMaxProds = $reqPrixMax->fetch(PDO::FETCH_ASSOC);
                    $prixMax = $prixMaxProds['prixMax'];
                    $reqPrixMax->closeCursor();
                }else{
                    $prixMax = htmlentities($_POST['prixMax']);
                    $prixMin = htmlentities($_POST['prixMin']);
                    $ordre = htmlentities($_POST['ordre']);
                    $ordreAvec = htmlentities($_POST['ordreAvec']);
                }

                if(!isset($_GET['idSousCateg']) || empty($_GET['idSousCateg'])){
                    $prods = $conn->prepare("SELECT * FROM Produit WHERE prix BETWEEN $prixMin AND $prixMax ORDER BY $ordreAvec $ordre");
                    $prods->execute();
                }else{
                    $id = htmlentities($_GET['idSousCateg']);
                    $categorie = $conn->prepare("SELECT * FROM Categorie WHERE id_categorie = $id");
                    $categorie->execute();
                    $donnees = $categorie->fetch(PDO::FETCH_ASSOC);
                    if($donnees && htmlspecialchars($donnees['parent']) == NULL){
                        $prods = $conn->prepare("SELECT * FROM Produit WHERE id_categorie IN (SELECT id_categorie FROM Categorie WHERE parent = $id) AND prix BETWEEN $prixMin AND $prixMax ORDER BY $ordreAvec $ordre");
                        $prods->execute();
                    }else{
                        $prods = $conn->prepare("SELECT * FROM Produit WHERE id_categorie = $id AND prix BETWEEN $prixMin AND $prixMax ORDER BY $ordreAvec $ordre");
                        $prods->execute();
                    }                                    
                }


                ?>

                <div class="card w-100 mt-4 mb-1 me-3 ms-4 filterBar">
                    <div class="card-body justify-content-between">
                        <form method='Post'>
                            <div class="row">
                                <div class='col'>
                                    <label for="prixMin">Prix Minimum</label>
                                    <input type="number" class="form-control" id="prixMin" name="prixMin" value="0" min="0" placeholder="Prix Minimum">
                                </div>
                                <div class='col'>
                                    <label for="prixMax">Prix Maximum</label>
                                    <input type="number" class="form-control" id="prixMax" name="prixMax" value=<?php echo $prixMax ?> min="0" placeholder="Prix Maximum">
                                </div>
                                <div class='col'>
                                    <label for="ordreAvec">Trier par : </label>
                                    <select id="ordreAvec" name="ordreAvec" class="form-control">
                                        <option value="NULL" <?php echo (!isset($_POST['ordreAvec']) || $_POST['ordreAvec'] == "NULL") ? "selected" : ""; ?>>Par défaut</option>
                                        <option value="nom" <?php echo (isset($_POST['ordreAvec']) && $_POST['ordreAvec'] == "nom") ? "selected" : ""; ?>>Nom</option>
                                        <option value="prix" <?php echo (isset($_POST['ordreAvec']) && $_POST['ordreAvec'] == "prix") ? "selected" : ""; ?>>Prix</option>
                                    </select>
                                </div>
                                <div class='col'>
                                    <label for="ordre">Trier par ordre : </label>
                                    <select id="ordre" name="ordre" class="form-control">
                                        <option value="ASC" <?php echo (isset($_POST['ordre']) && $_POST['ordre'] == "ASC") ? "selected" : ""; ?>>Croissant</option>
                                        <option value="DESC" <?php echo (isset($_POST['ordre']) && $_POST['ordre'] == "DESC") ? "selected" : ""; ?>>Décroissant</option>
                                    </select>
                                </div>
                                <div class='col'>
                                    <button type="submit" class="button-28 mt-4" name='filtrer'>Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class = "productContainer">
                        <?php
                        
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
                                <a href="DetailProduit.php?idProduit=<?php echo $id; ?>" class="noHoverLine"><div class="card productDetailsContainer" style="width: 18rem;">
                                    <img src="./imagesProduits/prod<?php echo $id; ?>.png" class="card-img-top" alt="Image de <?php echo $nom; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title blackText"><?php echo $nom ?></h5>
                                        <p class="card-text blackText"><?php echo substr($description, 0, 83)."..."; ?></p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bold blackText"><?php echo $prix."€" ?></li>
                                        <li class="list-group-item greenText">Expédié en 48/72 heures</li>
                                        <li class="list-group-item blueText"><?php echo $stock." en stock" ?></li>
                                    </ul>
                                </div></a>
                            <?php }
                        }else{
                            echo "<h3 class='mt-3'>Aucun produit n'est disponible pour cette catégorie</h3";
                        }
                        
                        $prods->closeCursor();
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