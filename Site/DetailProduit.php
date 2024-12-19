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
                ?>


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
                                    <form method="post">
                                        <div class="input-group" style="width: 125px;">
                                            <select class="form-select" name="quantite">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
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