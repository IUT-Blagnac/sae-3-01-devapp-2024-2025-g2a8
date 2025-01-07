<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    <?php
    require_once("./include/header.php");
    require("include/connect.inc.php");
    include("./include/verifConnexion.php");
    ?>

    <!-- form respond -->

    <?php
    $userCommande = getUserById($_SESSION['user_id'])
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1">
        <div class="container">
            <div id="liveAlertPlaceholder"></div>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Votre panier</span>
                        <span
                            class="badge badge-secondary badge-pill"><?php echo getItemCartNumber($_SESSION['user_id']) ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <?php

                        $userId = $_SESSION["user_id"];
                        $productList = $conn->prepare("SELECT * FROM Produit P INNER JOIN Panier PA ON P.id_produit = PA.id_produit WHERE user_id = $userId");

                        $productList->execute();

                        $prixTotal = 0;

                        if ($productList->rowCount() > 0) {

                            foreach ($productList->fetchAll(PDO::FETCH_ASSOC) as $produit) {
                                $prodId = $produit['id_produit'];
                                $prodNom = $produit['nom'];
                                $prodPrix = $produit['prix'];
                                $prodQuant = $produit['quantiter'];

                                $prixTotal += ($prodPrix * $prodQuant);

                                ?>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0"><?php echo $prodNom ?></h6>
                                        <small class="text-muted">Quantiter : <?php echo $prodQuant ?></small>
                                    </div>
                                    <span class="text-muted"><?php echo ($prodPrix * $prodQuant) ?>€</span>
                                </li>
                                <?php

                            }

                        } else {
                            echo "<h6>Aucun produit dans le panier</h6>";
                        }
                        ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (EUR)</span>
                            <strong><?php echo $prixTotal ?>€</strong>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Adresse de livraison</h4>
                    <form class="needs-validation" novalidate method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">Nom</label>
                                <input type="text" class="form-control" id="firstName"
                                    value="<?php echo $userCommande["nom"] ?>" name="firstName" required>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Prenom</label>
                                <input type="text" class="form-control" id="lastName"
                                    value="<?php echo $userCommande["prenom"] ?>" name="lastName" required>
                                <div class="invalid-feedback">
                                    Valid last name is required.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email"
                                value="<?php echo $userCommande["mail"] ?>" name="email">
                            <div class="invalid-feedback">
                                Mail invalide !
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="numRue">Numero de rue</label>
                                <input type="text" class="form-control" id="numRue" placeholder="1" name="numRue"
                                    required>
                            </div>
                            <div class="col-md-9 mb-3 mb-3">
                                <label for="nomRue">Nom de rue</label>
                                <input type="text" class="form-control" id="nomRue" placeholder="Rue du fort"
                                    name="nomRue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="compadresse">Complement d'adresse (Optionnel)</label>
                            <small class="text-muted">Num d'appartement, code interphone, ...</small>
                            <input type="text" class="form-control" id="compadresse" placeholder="Appartement 18"
                                name="compadresse" required>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="pays">Pays</label>
                                <select class="custom-select d-block w-100" id="pays" name="pays" required>
                                    <option value="France" selected>France</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cp">Code postale</label>
                                <input type="text" class="form-control" id="cp" placeholder="11800" name="cp" required>
                                <div class="invalid-feedback">
                                    Code postale requis
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ville">Ville</label>
                                <input type="text" class="form-control" id="ville" placeholder="Barbaira" name="ville"
                                    required>
                            </div>
                        </div>
                        <hr class="mb-4">

                        <h4 class="mb-3">Paiment</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cc-name">Nom du titulaire</label>
                                <input type="text" class="form-control" id="cc-name" placeholder="" name="cc-name"
                                    required>
                                <small class="text-muted">Votre nom sur la carte</small>
                                <div class="invalid-feedback">
                                    Nom du titulaire requis
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Numero de carte</label>
                                <input type="text" class="form-control" id="cc-number" placeholder="" name="cc-number"
                                    required>
                                <small class="text-muted">Le numero au devant de la carte</small>
                                <div class="invalid-feedback">
                                    Numero requis
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" name="cc-expiration"
                                    required>
                                <small class="text-muted">Date d'expiration au format MM/AA</small>
                                <div class="invalid-feedback">
                                    Expiration requise
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" name="cc-cvv" required>
                                <small class="text-muted">3 numero au dos de la carte</small>
                                <div class="invalid-feedback">
                                    Code de securité requis
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit"
                            name="commande" value="commande">Commander</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if (isset($_POST["commande"])) {
            echo $_POST["numRue"];
            if(!isset($_POST["firstName"])){
                echo "<script>appendAlert('Nom obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["lastName"])){
                echo "<script>appendAlert('Prenom obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["email"])){
                echo "<script>appendAlert('Mail obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["numRue"])){
                echo "<script>appendAlert('Numero de rue obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["nomRue"])){
                echo "<script>appendAlert('Nom de rue obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["pays"])){
                echo "<script>appendAlert('Pays obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["cp"])){
                echo "eeuuuuh";
                echo "<script>appendAlert('Code postale obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["ville"])){
                echo "<script>appendAlert('Ville obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["cc-name"])){
                echo "<script>appendAlert('Titulaire de carte obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["cc-number"])){
                echo "<script>appendAlert('Numero de carte obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["cc-expiration"])){
                echo "<script>appendAlert('Date d'expiration de carte obligatoire !', 'danger')</script>";

            } else if(!isset($_POST["cc-cvv"])){
                echo "<script>appendAlert('Code de securité obligatoire !', 'danger')</script>";

            } else {

            }
        }
    ?>
        <!-- Pied de page -->
        <?php
        require_once("./include/footer.php")
            ?>
</body>

</html>