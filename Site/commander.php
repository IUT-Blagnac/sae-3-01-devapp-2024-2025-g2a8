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
    if (isset($_POST["action"])) {
        $actionType = $_POST["typeAction"];
        $idP = $_POST["prodId"];
        $uId = $_POST["userId"];

        if ($actionType == "suppr") {
            $delReq = $conn->prepare("DELETE FROM Panier WHERE id_produit = $idP");

            if (!$delReq->execute()) {
                $error = "Impossible de supprimer le produit $idP";
            }
        }

        if ($actionType == "del") {
            $decReq = $conn->prepare("CALL DecPanier($uId, $idP)");

            if (!$decReq->execute()) {
                $error = "Impossible de supprimer le produit $idP";
            }
        }

        if ($actionType == "add") {
            $decReq = $conn->prepare("CALL IncPanier($uId, $idP)");

            if (!$decReq->execute()) {
                $error = "Impossible de supprimer le produit $idP";
            }
        }
    }
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1">
        <div class="container">
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Votre panier</span>
                        <span class="badge badge-secondary badge-pill">3</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Product name</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Second product</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$8</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Third item</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$5</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-success">
                                <h6 class="my-0">Promo code</h6>
                                <small>EXAMPLECODE</small>
                            </div>
                            <span class="text-success">-$5</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (USD)</span>
                            <strong>$20</strong>
                        </li>
                    </ul>

                    <form class="card p-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo code">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">Redeem</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Adresse de livraison</h4>
                    <form class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
                                <div class="invalid-feedback">
                                    Valid last name is required.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="you@example.com">
                            <div class="invalid-feedback">
                                Mail invalide !
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address">Adresse</label>
                            <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
                        </div>

                        <div class="mb-3">
                            <label for="compadresse">Complement d'adresse</label>
                            <input type="text" class="form-control" id="compadresse" placeholder="1234 Main St"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="pays">Pays</label>
                                <select class="custom-select d-block w-100" id="pays" required>
                                    <option value="France" selected>France</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cp">Code postale</label>
                                <input type="text" class="form-control" id="cp" placeholder="11800" required>
                                <div class="invalid-feedback">
                                    Code postale requis
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ville">Ville</label>
                                <input type="text" class="form-control" id="ville" placeholder="Barbaira"
                                    required>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="save-info">
                            <label class="custom-control-label" for="save-info">Save this information for next
                                time</label>
                        </div>
                        <hr class="mb-4">

                        <h4 class="mb-3">Paiment</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cc-name">Nom du titulaire</label>
                                <input type="text" class="form-control" id="cc-name" placeholder="" required>
                                <small class="text-muted">Votre nom sur la carte</small>
                                <div class="invalid-feedback">
                                    Nom du titulaire requis
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Numero de carte</label>
                                <input type="text" class="form-control" id="cc-number" placeholder="" required>
                                <div class="invalid-feedback">
                                    Numero requis
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                                <div class="invalid-feedback">
                                    Expiration requise
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                                <div class="invalid-feedback">
                                    Code de securité requis
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Commander</button>
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