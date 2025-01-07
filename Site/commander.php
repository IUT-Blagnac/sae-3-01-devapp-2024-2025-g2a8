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
    <script src="include_script/alert.js"></script>

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
                    <form class="needs-validation" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">Nom</label>
                                <input type="text" class="form-control" id="firstName"
                                    value="<?php echo $userCommande["nom"] ?>" name="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Prenom</label>
                                <input type="text" class="form-control" id="lastName"
                                    value="<?php echo $userCommande["prenom"] ?>" name="lastName" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email"
                                value="<?php echo $userCommande["mail"] ?>" name="email">
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
                                name="compadresse">
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
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Numero de carte</label>
                                <input type="text" class="form-control" id="cc-number" placeholder="" name="cc-number"
                                    required>
                                <small class="text-muted">Le numero au devant de la carte</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" name="cc-expiration"
                                    required>
                                <small class="text-muted">Date d'expiration au format MM/AA</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" name="cc-cvv" required>
                                <small class="text-muted">3 numero au dos de la carte</small>
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
            $reg_numRue = "#^[1-9][0-9]{0,}$#";
            $reg_cp = "#^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$#";
            $reg_cvv = "#^[0-9]{3}$#";
            $reg_exp = "#^(0[1-9]|1[0-2])\/?([0-9]{2})$#";

            if(!preg_match($reg_numRue, $_POST["numRue"])){
                echo "<script>appendAlert('Numero de rue invalide', 'danger')</script>";
                die();
            }

            if(!preg_match($reg_cp, $_POST["cp"])){
                echo "<script>appendAlert('Code postale invalide', 'danger')</script>";
                die();
            }

            if(!preg_match($reg_cvv, $_POST["cc-cvv"])){
                echo "<script>appendAlert('Code de securité invalide', 'danger')</script>";
                die();
            }

            if(!preg_match($reg_exp, $_POST["cc-expiration"])){
                echo "<script>appendAlert('Date d'expiration de la carte invalide', 'danger')</script>";
                die();
            }

            $insertAdresse = $conn->prepare("INSERT INTO Adresse (user_id, numRue, nomRue, ville, codePostal, nom, prenom) VALUES (:userId, :numRue, :nomRue, :ville, :codePostale, :nom, :prenom)");

            $insertAdresse->bindParam(":userId", $_SESSION["user_id"], PDO::PARAM_INT);
            $insertAdresse->bindParam(":numRue", $_POST["numRue"], PDO::PARAM_STR);
            $insertAdresse->bindParam(":nomRue", $_POST["nomRue"], PDO::PARAM_STR);
            $insertAdresse->bindParam(":ville", $_POST["ville"], PDO::PARAM_STR);
            $insertAdresse->bindParam(":codePostale", $_POST["cp"], PDO::PARAM_STR);
            $insertAdresse->bindParam(":nom", $_POST["firstName"], PDO::PARAM_STR);
            $insertAdresse->bindParam(":prenom", $_POST["lastName"], PDO::PARAM_STR);

            if($insertAdresse->execute()){
                $insertAdresseId = $conn->lastInsertId();
                echo "<script>appendAlert('id adresse = $insertAdresseId', 'danger')</script>";
            } else {
                echo "<script>appendAlert('Une erreur est survenu lors de votre commande', 'danger')</script>";
                die();
            }

            $insertCard = $conn->prepare("INSERT INTO CarteBancaire (numCb, user_id, crypto, date_exp) VALUES (:numCb, :userId, :cvv, :exp)");

            $insertCard->bindParam(":numCb", $_POST["cc-number"], PDO::PARAM_STR);
            $insertCard->bindParam(":userId", $_SESSION["user_id"], PDO::PARAM_INT);
            $insertCard->bindParam(":cvv", $_POST["cc-cvv"], PDO::PARAM_STR);
            $insertCard->bindParam(":exp", $_POST["cc-expiration"], PDO::PARAM_STR);

            if($insertCard->execute()){
                $insertCbId = $conn->lastInsertId();
                echo "<script>appendAlert('id cb, adresse = $insertCbId, $insertAdresseId', 'danger')</script>";
            } else {
                echo "<script>appendAlert('Une erreur est survenu lors de votre commande', 'danger')</script>";
                die();
            }

        }
    ?>
        <!-- Pied de page -->
        <?php
        require_once("./include/footer.php")
            ?>
</body>

</html>