<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <script src="include_script/alert.js"></script>
    <!-- En-tête -->
    <?php
    require_once("./include/header.php");
    require("include/connect.inc.php");
    include("./include/verifConnexion.php");
    ?>

    <?php
    if (!isset($_GET["id"])) {
        header("Location: index.php");
        exit();
    }

    $idCommande = htmlentities($_GET["id"]);

    $commandeGet = $conn->prepare("SELECT * FROM Commande WHERE id_commande=:commandId");
    $commandeGet->bindParam(":commandId", $idCommande, PDO::PARAM_INT);
    $commandeGet->execute();

    $commande = $commandeGet->fetch();

    if ($commande["user_id"] != $_SESSION["user_id"]) {
        header("Location: index.php");
        exit();
    }

    $productsGet = $conn->prepare("SELECT * FROM ProduitCommander WHERE id_commande=:commandId");
    $productsGet->bindParam(":commandId", $idCommande, PDO::PARAM_INT);
    $productsGet->execute();

    $produits = $productsGet->fetchAll(PDO::FETCH_ASSOC);

    $livraisonGet = $conn->prepare("SELECT * FROM Adresse WHERE id_adresse=:adresseId");
    $livraisonGet->bindParam(":adresseId", $commande["id_adresse"], PDO::PARAM_INT);
    $livraisonGet->execute();

    $livraison = $livraisonGet->fetch();
    ?>

    <!-- Conteneur principal -->
    <div class="container flex-grow-1 text-center w-50">
        <center>
            <div class="card mb-4 mt-4">
                <div class="card-header py-3">
                    <h1 class="mb-0">Merci de votre commande !</h1>
                </div>
                <div class="card-body">
                    <h3>Résumer de la commande n°<?php echo $idCommande; ?></h3>
                    <hr>
                    <h4 class="text-left">Produits commander :</h4>
                    <ul class="list-group mb-3">
                        <?php
                        $prixTotal = 0;

                        foreach ($produits as $prodRef) {
                            $prodRefGet = $conn->prepare("SELECT * FROM Produit WHERE id_produit=:prodId");
                            $prodRefGet->bindParam(":prodId", $prodRef["id_produit"], PDO::PARAM_INT);
                            $prodRefGet->execute();

                            $prod = $prodRefGet->fetch();

                            $prodId = $prod['id_produit'];
                            $prodNom = $prod['nom'];
                            $prodPrix = $prod['prix'];
                            $prodQuant = $prodRef['quantiter'];

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
                        ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (EUR)</span>
                            <strong><?php echo $prixTotal ?>€</strong>
                        </li>
                    </ul>
                    <hr>
                    <div class="text-left">
                        <h4>Livraison :</h4>
                        <div class="card w-25 ml-4 mt-3">
                            <div class="card-header py-3">
                                <h5 class="mb-0 text-center">
                                    <?php
                                        echo $livraison["nom"] . " " . $livraison["prenom"]
                                    ?>
                                </h5>
                            </div>
                            <div class="card-body">
                            <p class="text-left">
                                    <?php
                                    echo $livraison["numRue"] . " " . $livraison["nomRue"]
                                        ?>
                                    <br>
                                    <?php
                                    echo $livraison["codePostal"] . " " . $livraison["ville"]
                                        ?>
                                    <br>
                                    <?php
                                    echo $livraison["pays"]
                                        ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <a class="btn button-28 pl-4 pr-4 p-2">
                        Retour a l'accueil
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a class="btn button-28 pl-4 pr-4 p-2">
                        Mon compte
                    </a>
                </div>
            </div>

        </center>


    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
        ?>
</body>

</html>