<?php
// Vérification de l'existence de l'idProduit
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
                    $moyenneAvis = 0;

                    //modifier produit
                    if(isset($_POST['modifProduit'])){
                        $nomProd = $_POST['nom'];
                        $description = $_POST['description'];
                        $categ = $_POST['categ'];
                        $prix = $_POST['prix'];
                        $stock = $_POST['stock'];
                        try{
                            $modifProd = $conn->prepare("UPDATE Produit SET nom = :nom, description = :description, id_categorie = :categ, prix = :prix, stock = :stock WHERE id_produit = :idProduit");
                            $modifProd->execute(['nom' => $nomProd, 'description' => $description, 'categ' => $categ, 'prix' => $prix, 'stock' => $stock, 'idProduit' => $_GET['idProduit']]);
                            echo '<div class="alert alert-success" role="alert">';
                            echo 'Le produit a bien été modifié';
                            echo '</div>';
                        }catch(PDOException $e){
                            echo '<div class="alert alert-danger" role="alert">';
                            echo 'Erreur : Veuillez saisir des informations correctes';
                            echo '</div>';
                        }

                        if (!empty($_FILES['imageProduit']) AND $_FILES['imageProduit']['error'] == 0) {
                            $infosfichier = pathinfo($_FILES['imageProduit']['name']);
                            $extension_upload = $infosfichier['extension'];
                            $extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
                            if (in_array($extension_upload, $extensions_autorisees) &&  500000 > $_FILES["imageProduit"]["size"]) {
                                $nomFichier = 'prod' . $_GET['idProduit'] . '.png';
                                $destination = __DIR__ . '/imagesProduits/' . $nomFichier;
                                move_uploaded_file($_FILES['imageProduit']['tmp_name'],$destination);
                            }
                            else {
                                $message =  "<div class='alert alert-danger'> Le fichier n'est pas du bon type ou il est trop volumineux !</div>";
                            }

                        }
                    }


                    // Récupération des informations du produit
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

                    // Ajout au panier
                    if (isset($_POST["AjoutPanier"]) && isset($_POST["quantite"]) && isset($_SESSION["user_id"])) {
                        try {
                            $reqAjoutPanier = $conn->prepare("INSERT INTO Panier (user_id, id_produit, quantiter) VALUES (:idUser, :Produit, :quantite)");
                            $reqAjoutPanier->execute(array("idUser" => $_SESSION["user_id"], "Produit" => $_GET["idProduit"], "quantite" => $_POST["quantite"]));

                            if ($reqAjoutPanier->rowCount() == 1) {
                                echo '<div class="alert alert-success" role="alert">';
                                echo 'Le produit a bien été ajouté au panier';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning" role="alert">';
                                echo 'Une erreur est survenue lors de l\'ajout du produit au panier';
                                echo '</div>';
                            }
                        } catch (PDOException $e) {
                            echo '<div class="alert alert-warning" role="alert">';
                            echo 'Ce produit est déjà dans votre panier';
                            echo '</div>';
                        }
                    } elseif (isset($_POST["AjoutPanier"]) && !isset($_SESSION["user_id"])) {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo 'Vous devez être connecté pour ajouter un produit au panier';
                        echo '</div>';
                    }


                    // Ajout d'un avis
                    if (isset($_POST["ValiderAvis"])) {
                        if ($_POST["Note"] < 0 || $_POST["Note"] > 5) {
                            echo '<div class="alert alert-warning" role="alert">';
                            echo 'La note doit être comprise entre 0 et 5';
                            echo '</div>';
                        } else {
                            $reqAjoutAvis = $conn->prepare("INSERT INTO Avis (id_produit, user_id, note, commentaire) VALUES (:idProduit, :idUser, :note, :commentaire)");
                            $reqAjoutAvis->execute(array("idUser" => $_SESSION["user_id"], "idProduit" => $_GET["idProduit"], "note" => $_POST["Note"], "commentaire" => $_POST["Commentaire"]));

                            if ($reqAjoutAvis->rowCount() == 1) {
                                echo '<div class="alert alert-success" role="alert">';
                                echo 'Votre avis a bien été ajouté';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning" role="alert">';
                                echo 'Une erreur est survenue lors de l\'ajout de votre avis';
                                echo '</div>';
                            }
                        }
                    }

                    // Ajout aux favoris
                    $isFavoris = false;
                    if (isset($_SESSION["user_id"])) {
                        $reqFavoris = $conn->prepare("SELECT * FROM Favoris WHERE id_user = :idUser AND id_produit = :idProduit");
                        $reqFavoris->execute(array("idUser" => $_SESSION["user_id"], "idProduit" => $_GET["idProduit"]));

                        if ($reqFavoris->rowCount() == 1) {
                            $isFavoris = true;
                        }

                        if (isset($_POST["Favoris"])) {
                            if ($isFavoris) {
                                $reqSupprFavoris = $conn->prepare("DELETE FROM Favoris WHERE id_user = :idUser AND id_produit = :idProduit");
                                $reqSupprFavoris->execute(array("idUser" => $_SESSION["user_id"], "idProduit" => $_GET["idProduit"]));

                                if ($reqSupprFavoris->rowCount() == 1) {
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo 'Le produit a bien été retiré de vos favoris';
                                    echo '</div>';
                                    $isFavoris = false;
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">';
                                    echo 'Une erreur est survenue lors de la suppression du produit de vos favoris';
                                    echo '</div>';
                                }
                            } else {
                                $reqAjoutFavoris = $conn->prepare("INSERT INTO Favoris (id_user, id_produit) VALUES (:idUser, :idProduit)");
                                $reqAjoutFavoris->execute(array("idUser" => $_SESSION["user_id"], "idProduit" => $_GET["idProduit"]));

                                if ($reqAjoutFavoris->rowCount() == 1) {
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo 'Le produit a bien été ajouté à vos favoris';
                                    echo '</div>';
                                    $isFavoris = true;
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">';
                                    echo 'Une erreur est survenue lors de l\'ajout du produit à vos favoris';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                ?>

                <div class="justify-content-between mt-3 row">
                    <div class = "col-8">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent ml-3">
                                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                <?php
                                    if ($infoProduit["parent"] != null) {
                                        echo "<li class='breadcrumb-item'><a href='sousCategorie.php?idSousCateg=".$infoCategParent["id_categorie"]."'>".$infoCategParent["nom_categorie"]."</a></li>";
                                    }
                                    echo "<li class='breadcrumb-item'><a href='sousCategorie.php?idSousCateg=".$infoProduit["id_categorie"]."'>".$infoProduit["nom_categorie"]."</a></li>";

                                    echo "<li class='breadcrumb-item active' aria-current='page'>".$infoProduit["nom"]."</li>";
                                ?>
                            </ol>
                        </nav>
                    </div>
                    <?php 
                        $admin = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = :user_id AND role = 'A'");
                        if (isset($_SESSION['user_id'])) {
                        $admin->execute(['user_id' => $_SESSION['user_id']]);
                        if($admin->rowCount() > 0){
                            
                    ?>
                    <div class="col-4 d-flex justify-content-end">
                        <button class="button-28 p-2 px-5 mx-3" name="cancelModif" id="cancelModif" style="display: none;">Annuler</button>
                        <button class="button-28 p-2 px-5 mx-3" name="modifProd" id="modifProd">Modifier</button>
                        <button class="button-28 p-2 px-5 mx-3" onclick="location.href='deleteProd.php?idProduit=<?php echo $_GET['idProduit'] ?>'">Supprimer</button>
                    </div>
                    <?php 
                        }
                        $admin->closeCursor();
                    }
                    ?>
                </div>

                <!-- modifier produit -->
                <div class="card w-50 mt-5 mx-auto" style="display: none;" id="formulaireModif">
                    <div class="card-body">
                        <h4 class="d-flex align-items-center mb-4">Modifier <?php echo $infoProduit["nom"] ?></h4>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Nom produit</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $infoProduit["nom"] ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Description du produit</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="description" value="<?php echo $infoProduit["description"] ?>" required>
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
                                            if ($categ['id_categorie'] == $infoProduit['id_categorie']) {
                                                echo "<option value='".$categ['id_categorie']."' selected>".$categ['nom_categorie']."</option>";
                                            }else{
                                                echo "<option value='".$categ['id_categorie']."'>".$categ['nom_categorie']."</option>";
                                            }

                                        }
                                        $categParente->closeCursor();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Prix du produit</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nom" name="prix" min= "0" value="<?php echo $infoProduit["prix"] ?>"required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Stock du produit</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="nom" name="stock" min="0" value="<?php echo $infoProduit["stock"] ?>"required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="imageProduit" class="col-sm-4 col-form-label">Image du produit : </label>
                                <div class="col-sm-8">
                                    <input type="file" name="imageProduit" id="imageProduit" class="form-control"> 
                                </div>
                            </div>
                            
                              
                                <button type="submit" class="button-28 mt-3" name="modifProduit">Modifier</button>
                        </form>
                    </div>
                </div>

                    <script>
                    document.getElementById("modifProd").addEventListener("click", function() {
                        document.getElementById("formulaireModif").style.display = "flex";
                        document.getElementById("cancelModif").style.display = "block";
                        document.getElementById("modifProd").style.display = "none";
                    });
                    </script>

                    <script>
                    document.getElementById("cancelModif").addEventListener("click", function() {
                        document.getElementById("formulaireModif").style.display = "none";
                        document.getElementById("cancelModif").style.display = "none";
                        document.getElementById("modifProd").style.display = "block";
                    });
                    </script>



                <div class="d-flex justify-content-between align-items-center px-5">
                    <img src="<?php echo file_exists("imagesProduits/prod" . $infoProduit["id_produit"] . ".png") ? "imagesProduits/prod" . $infoProduit["id_produit"] . ".png" : "imagesProduits/noImage.png"; ?>" class="img-fluid w-50 h-65 d-block mx-start" alt="RockMons Produit">
                    <div class="container mt-5">
                        <div class="card shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><?php echo $infoProduit["nom"] ?></h3>
                                <?php 
                                    if (isset($_SESSION["user_id"])) {
                                        echo '<form method="post" class="">
                                                <button type="submit" name="Favoris" class="btn btn-link">
                                                    <i class="' . ($isFavoris ? 'bi bi-star-fill text-warning' : 'bi bi-star text-secondary') . '"></i>
                                                </button>
                                              </form>';
                                    }                                    
                                ?>
                            </div>

                            <p><a href="#Description" class="text-decoration-underline">Description détaillée</a></p>

                            <div class="mb-2">
                                <h4 class="fw-bold my-3" style="font-weight: 400;"><?php echo $infoProduit["prix"] ?>€</h4>
                            </div>

                            <?php
                                $reqAvis = $conn->prepare("SELECT * FROM Avis WHERE id_produit = :idProduit");
                                $reqAvis->execute(array("idProduit" => $_GET["idProduit"]));

                                echo '<div class="mb-2">';
                                if ($reqAvis->rowCount() != 0) {
                                    while ($infoAvis = $reqAvis->fetch()) {
                                        $moyenneAvis += $infoAvis["note"];
                                    }
                                    $moyenneAvis /= $reqAvis->rowCount();

                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < $moyenneAvis) {
                                            echo '<i class="bi bi-star-fill text-warning"></i>';
                                        } else {
                                            echo '<i class="bi bi-star text-warning"></i>';
                                        }
                                    }
                                    echo "<span class='text-muted ml-1'>(" . ceil($moyenneAvis) . "/5)</span>";
                                }
                                echo '</div>';
                            ?>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-3">
                                <span class="text-success fw-bold">✔ Livraison gratuite</span>
                                <span class="text-muted">Expédié sous 3 jours</span>
                            </div>

                            <div class="d-flex align-items-center mb-3 ml-3">
                                <div class="row">
                                    <form method="post">
                                        <div class="input-group" style="width: 125px;">
                                            <select class="form-select" name="quantite">
                                                <?php
                                                    $max = $infoProduit["stock"] > 9 ? 9 : $infoProduit["stock"];
                                                    if ($infoProduit["stock"] > 0) {
                                                        for ($i = 1; $i <= $max; $i++) {
                                                            echo "<option value='$i'>$i</option>";
                                                        }
                                                    } else {
                                                        echo "<option value='0'>Rupture de stock</option>";
                                                    }
                                                ?>
                                            </select>
                                            <p class="<?php echo $infoProduit['stock'] == 0 ? 'text-danger' : 'text-success' ?>">
                                                <?php echo $infoProduit['stock'] == 0 ? 'Rupture de stock' : "Encore {$infoProduit['stock']} en stock" ?>
                                            </p>
                                        </div>
                                        <button class="button-28 p-2 px-5" name="AjoutPanier" <?php echo $infoProduit["stock"] <= 0 ? "disabled" : "" ?>>Ajouter au Panier</button>
                                    </form>
                                    
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
                    <h2 name="AvisClients" class="text-start" style="font-weight: 400;"> Avis Clients</h2>

                    <?php
                        if (isset($_SESSION["user_id"])) {
                            $reqVerifCommande = $conn->prepare("SELECT * FROM Commande C, ProduitCommander PC WHERE C.id_commande = PC.id_commande AND C.user_id = :idUser AND PC.id_produit = :idProduit");
                            $reqVerifCommande->execute(array("idUser" => $_SESSION["user_id"], "idProduit" => $_GET["idProduit"]));

                            if ($reqVerifCommande->rowCount() != 0) {
                                echo '<button class="button-28 p-2" id="DonnerAvis" style="width: auto;">Donner votre avis</button>';
                            }
                        }
                        
                    ?>

                    <div id="formulaire" style="display: none;" class="justify-content-center align-items-center ">
                    <form method="POST" style="width: 45%;">
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <label for="inputNote">Note</label>
                                <input type="number" class="form-control" id="inputNote" name="Note" placeholder="Note" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="inputCommentaire">Commentaire</label>
                                <textarea class="form-control" id="inputCommentaire" name="Commentaire" placeholder="Commentaire" required></textarea>
                            </div>
                        </div>
                        <button type="submit" name="ValiderAvis" class="button-28 mb-3">Valider l'avis</button>
                    </form>
                    </div>

                    <script>
                    document.getElementById("DonnerAvis").addEventListener("click", function() {
                        document.getElementById("formulaire").style.display = "flex";
                    });
                    </script>
                
                    <div class="row">
                        <?php
                            $reqAvis = $conn->prepare("SELECT * FROM Avis WHERE id_produit = :idProduit");
                            $reqAvis->execute(array("idProduit" => $_GET["idProduit"]));

                            if ($reqAvis->rowCount() == 0) {
                                echo '<div class="alert alert-warning" role="alert">';
                                echo 'Aucun avis pour ce produit';
                                echo '</div>';
                            }

                            while ($infoAvis = $reqAvis->fetch()) {
                                $reqUser = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = :idUtilisateur");
                                $reqUser->execute(array("idUtilisateur" => $infoAvis["user_id"]));

                                $infoUser = $reqUser->fetch();
                                echo '<div class="col-md-4 mb-4">';
                                echo '<div class="card shadow-sm">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . htmlentities($infoUser["nom"]) . " " . htmlentities($infoUser["prenom"]) . '</h5>';
                                echo '<div class="stars">';
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < $infoAvis["note"]) {
                                        echo '<i class="bi bi-star-fill text-warning"></i>';
                                    } else {
                                        echo '<i class="bi bi-star text-warning"></i>';
                                    }
                                }
                                echo '</div>';
                                echo '<p class="card-text mt-2">' . htmlentities($infoAvis["commentaire"]) . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>

                </div>

            </main>
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>