<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    

<body>
    <?php    
            require_once("./include/header.php");
            require_once("./include/verifConnexion.php");
            // require_once("./include/verifAdmin.php");
        ?>

        <!-- Conteneur principal -->
        <div class="container-fluid flex-grow-1">                

                <?php 
                //Formulaire ajouter une catégorie
                if(isset($_POST['ajouter'])){
                    $nomCategAdd = $_POST['nomCategAdd'];
                    $categParenteAdd = ($_POST['categParenteAdd'] == 'NULL') ? NULL : $_POST['categParenteAdd'];
                    $ajoutCateg = $conn->prepare("INSERT INTO Categorie (nom_categorie, parent) VALUES (:nom, :parent_id)");
                    $ajoutCateg->execute(['nom' => $nomCategAdd, 'parent_id' => $categParenteAdd]);
                    $reussite =  "<div class='alert alert-success'>La catégorie a bien été ajoutée</div>";
                }

                
                //Formulaire supprimer une catégorie
                if(isset($_POST['supprimer'])){
                    $categSuppr = $_POST['categSuppr'];
                    try{
                        $supprCateg = $conn->prepare("DELETE FROM Categorie WHERE id_categorie = :id_categorie");
                        $supprCateg->execute(['id_categorie' => $categSuppr]);
                        $reussite = "<div class='alert alert-success'>La catégorie a bien été supprimée</div>";
                    }catch(PDOException $e){
                        $erreurSuppr = "Veuillez d'abord supprimer les sous-catégories";
                    }
                }
                
                ?>

                <?php
                    require_once("./include/menu.php");
                    if (isset($reussite)) echo $reussite;
                ?>

                <div class="card w-50 mt-5 mx-auto">
                    <div class="card-body">
                        <h4 class="d-flex align-items-center mb-4">Ajouter une categorie</h4>
                        <form method="post">
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Nom catégorie</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="nomCategAdd" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-sm-4 col-form-label">Catégorie parente</label>
                                <div class="col-sm-8">
                                    <select name="categParenteAdd" class="form-control">
                                        <?php 
                                        $categParente = $conn->prepare("SELECT * FROM Categorie WHERE parent IS NULL");
                                        $categParente->execute();
                                        ?>
                                        <option value="NULL">Aucune</option>
                                        <?php 
                                        foreach($categParente->fetchAll(PDO::FETCH_ASSOC) as $categ){
                                            echo "<option value='".$categ['id_categorie']."'>".$categ['nom_categorie']."</option>";
                                        }
                                        $categParente->closeCursor();
                                        ?>
                                    </select>
                                </div>
                            </div>  
                                <button type="submit" class="button-28 mt-3" name="ajouter">Ajouter</button>
                        </form>
                    </div>
                </div>

                <div class="card w-50 mt-5 mx-auto">
                    <div class="card-body">
                        
                        <h4 class="d-flex align-items-center mb-4">Modifier une categorie</h4>
                        <div class="row mb-3">
                                <label for="description" class="col-sm-4 col-form-label">Catégorie</label>
                                <div class="col-sm-8">
                                    <select name="categModif" class="form-control">
                                        <option value="2">Mystique</option>
                                    </select>
                                </div>
                            </div>
                        <form method="post">
                            <div class="row mb-3">
                                <label for="nom" class="col-sm-4 col-form-label">Nom catégorie</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nom" name="nomCategModif" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-sm-4 col-form-label">Catégorie parente</label>
                                <div class="col-sm-8">
                                    <select name="categParenteModif" class="form-control">
                                        <option value="NULL">Aucune</option>
                                        <option value="2">Mystique</option>
                                    </select>
                                </div>
                            </div>  
                            <button type="submit" class="button-28 mt-3" name="modifier">Modifier</button>
                        </form>
                    </div>
                </div>

                <div class="card w-50 mt-5 mx-auto">
                    <div class="card-body" style= <?php isset($erreurSuppr) ? 'border-color: red;' : "" ?>>
                        <h4 class="d-flex align-items-center mb-4">Supprimer une categorie</h4>
                        <?php if(isset($erreurSuppr)) echo "<div class='alert alert-danger'>$erreurSuppr</div>"; ?>
                        <form method="post">
                            <div class="row mb-3">
                                <label for="description" class="col-sm-4 col-form-label">Catégorie</label>
                                <div class="col-sm-8">
                                    <select name="categSuppr" class="form-control">
                                    <?php 
                                        $categParente = $conn->prepare("SELECT * FROM Categorie");
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
                            <button type="submit" class="button-28 mt-3" name="supprimer">Supprimer</button> 
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
