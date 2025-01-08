<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    

<body>
    <?php    
            require_once("./include/header.php");
            require_once("./include/verifConnexion.php");
            require_once("./include/verifAdmin.php");
        ?>

        <!-- Conteneur principal -->
        <div class="container-fluid flex-grow-1">                

                <?php 
                
                if(isset($_POST['ajouter'])){
                    $nomProd = $_POST['nom'];
                    $description = $_POST['description'];
                    $categ = $_POST['categ'];
                    $prix = $_POST['prix'];
                    $stock = $_POST['stock'];
                    try{
                        $ajoutProd = $conn->prepare("INSERT INTO Produit (nom, description, id_categorie, prix, stock) VALUES (:nom, :description, :categ, :prix, :stock)");
                        $ajoutProd->execute(['nom' => $nomProd, 'description' => $description, 'categ' => $categ, 'prix' => $prix, 'stock' => $stock]);
                        $message =  "<div class='alert alert-success'>Le produit a bien été ajouté</div>";
                    }catch(PDOException $e){
                        $message = "<div class='alert alert-danger'>Erreur : Veuillez saisir des informations correctes</div>";
                        die();
                    }

                    $lastInsertId = $conn->lastInsertId();

                    if (!empty($_FILES['imageProduit']) AND $_FILES['imageProduit']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['imageProduit']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
                        if (in_array($extension_upload, $extensions_autorisees) &&  500000 > $_FILES["imageProduit"]["size"]) {
                            $nomFichier = 'prod' . $lastInsertId . '.png';
                            $destination = __DIR__ . '/imagesProduits/' . $nomFichier;
                            move_uploaded_file($_FILES['imageProduit']['tmp_name'],$destination);
                        }
                        else {
                            $message =  "<div class='alert alert-danger'> Le fichier n'est pas du bon type ou il est trop volumineux !</div>";
                        }

                    }

                }

                
              
                ?>

                <?php
                    require_once("./include/menu.php");
                    if (isset($message)) echo $message;
                ?>

                <div class="card w-50 mt-5 mx-auto">
                    <div class="card-body">
                        <h4 class="d-flex align-items-center mb-4">Ajouter un produit</h4>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Nom produit</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Description du produit</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="description" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-sm-4 col-form-label">Catégorie du produit</label>
                                <div class="col-sm-8">
                                    <select name="categ" class="form-control">
                                        <?php 
                                        $categParente = $conn->prepare("SELECT * FROM Categorie ORDER BY nom_categorie ASC");
                                        $categParente->execute();
                                        ?>
                                        <?php 
                                        foreach($categParente->fetchAll(PDO::FETCH_ASSOC) as $categ){
                                            echo "<option value='".$categ['id_categorie']."'>".$categ['nom_categorie']."</option>";
                                        }
                                        $categParente->closeCursor();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Prix du produit</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nom" name="prix" min= "0" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Stock du produit</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nom" name="stock" min="0" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="imageProduit" class="col-sm-4 col-form-label">Image du produit : </label>
                                <div class="col-sm-8">
                                    <input type="file" name="imageProduit" id="imageProduit" class="form-control"> 
                                </div>
                            </div>
                            
                              
                                <button type="submit" class="button-28 mt-3" name="ajouter">Ajouter</button>
                        </form>
                    </div>
                </div>

                
        </div>

<!-- Pied de page -->
<?php
    require_once("./include/footer.php")
    ?>

</body>
</html>
