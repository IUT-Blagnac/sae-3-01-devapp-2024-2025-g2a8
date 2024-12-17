<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    <?php
    require_once("./include/header.php");
    include("./include/verifConnexion.php");
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1">
        <section class="h-100 gradient-custom">
            <div class="container py-5">
                <div class="row d-flex justify-content-center my-4">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Panier - <?php echo getItemCartNumber($_SESSION['user_id'])?> Produit</h5>
                            </div>
                            <div class="card-body">
                                <!-- Single item -->

                                <?php
                                require("include/connect.inc.php");

                                $userId = $_SESSION["user_id"];
                                $productList = $conn->prepare("SELECT * FROM Produit P INNER JOIN Panier PA ON P.id_produit = PA.id_produit WHERE user_id = $userId");

                                $productList->execute();

                                if ($productList->rowCount() > 0) {
                                    foreach ($productList->fetchAll(PDO::FETCH_ASSOC) as $produit) {
                                        $prodId = $produit['id_produit'];
                                        $prodNom = $produit['nom'];
                                        $prodPrix = $produit['prix'];
                                        $prodQuant = $produit['quantiter']

                                            ?>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                                <!-- Image -->
                                                <div class="bg-image hover-overlay hover-zoom ripple rounded"
                                                    data-mdb-ripple-color="light">
                                                    <img src="imagesProduits/prod<?php echo $prodId; ?>" class="w-100" />
                                                    <a href="#!">
                                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)">
                                                        </div>
                                                    </a>
                                                </div>
                                                <!-- Image -->
                                            </div>

                                            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                                <!-- Data -->
                                                <p><strong><?php echo $prodNom; ?></strong></p>

                                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                                    class="btn btn-primary btn-sm me-1 mb-2" data-mdb-tooltip-init
                                                    title="Remove item">
                                                    Supprimer
                                                </button>
                                                <!-- Data -->
                                            </div>

                                            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                                <!-- Quantity -->
                                                <div class="d-flex mb-4" style="max-width: 300px">
                                                    <button data-mdb-button-init data-mdb-ripple-init
                                                        class="btn btn-primary px-3 me-2"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                        -
                                                    </button>

                                                    <div data-mdb-input-init class="form-outline">
                                                        <input id="form1" min="0" name="quantity"
                                                            value="<?php echo $prodQuant ?>" type="number" class="form-control"
                                                            disabled />
                                                        <label class="form-label" for="form1">Quantity</label>
                                                    </div>

                                                    <button data-mdb-button-init data-mdb-ripple-init
                                                        class="btn btn-primary px-3 ms-2"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                        +
                                                    </button>
                                                </div>
                                                <!-- Quantity -->

                                                <!-- Price -->
                                                <p class="text-start text-md-center">
                                                    <strong><?php $prodPrix; ?></strong>
                                                </p>
                                                <!-- Price -->
                                            </div>
                                        </div>
                                        <?php

                                        $nbProdFor += 1;

                                        if($nbProdFor <= $productList->rowCount()){
                                            echo "<hr class='my-4'>";
                                        }
                                    }

                                } else {
                                    echo "<h3>Aucun produit n'est disponible pour cette catégorie</h3>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Summary</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                        Products
                                        <span>$53.98</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        Shipping
                                        <span>Gratis</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                        <div>
                                            <strong>Total amount</strong>
                                            <strong>
                                                <p class="mb-0">(including VAT)</p>
                                            </strong>
                                        </div>
                                        <span><strong>$53.98</strong></span>
                                    </li>
                                </ul>

                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-primary btn-lg btn-block">
                                    Commander
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
        ?>
</body>

</html>