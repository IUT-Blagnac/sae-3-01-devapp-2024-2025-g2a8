<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

                $userId = $_SESSION['user_id'];
                //Informations de l'utilisateur
                $user = $conn->prepare("SELECT * FROM Utilisateur WHERE user_id = :id");
                $user->execute(array('id' => $userId));
                $donneesUser = $user->fetch(PDO::FETCH_ASSOC);

                $addressUser = $conn->prepare("SELECT * FROM Adresse WHERE user_id = :id");
                $addressUser->execute(array('id' => $userId));
                $adresse = $addressUser->fetch(PDO::FETCH_ASSOC);

                $messageErreurMail = false;
                $messageErreurTel = false;
                $messageErreur = false;
                if(isset($_POST['modifier'])){
                    $nomPost = htmlentities($_POST['nom']);
                    $prenomPost = htmlentities($_POST['prenom']);
                    $emailPost = htmlentities($_POST['email']);

                    $reqVerifUtilisateurExistant = $conn->prepare("SELECT * FROM Utilisateur WHERE mail = :email");
                    $reqVerifUtilisateurExistant->execute(array("email" => $emailPost));

                    if($reqVerifUtilisateurExistant->rowCount() > 0 && $emailPost != $donneesUser['mail']){
                        $messageErreur = true;
                        $messageErreurMail = true;
                        $emailPost = $donneesUser['mail'];
                    }

                    $regexTel = "#^[0-9]{10}$#";

                    $numeroPost = htmlentities($_POST['numero']);
                    if (!preg_match($regexTel, $numeroPost)) {
                        $messageErreur = true;
                        $messageErreurTel = true;
                        $numeroPost = $donneesUser['numero'];
                    }

                    $nomRuePost = htmlentities($_POST['nomRue']);
                    $villePost = htmlentities($_POST['ville']);
                    $codePostalPost = htmlentities($_POST['codePostal']);
                    $numRuePost = htmlentities($_POST['numRue']);


                    // if(!$messageErreur){
                    //     $reqUpdateUser = $conn->prepare("UPDATE Utilisateur SET nom = :nom, prenom = :prenom, mail = :email, numero = :numero WHERE user_id = :id");
                    //     $reqUpdateUser->execute(array('nom' => $nomPost, 'prenom' => $prenomPost, 'email' => $emailPost, 'numero' => $numeroPost, 'id' => $userId));
                    //     if(!$adresse && $nomRuePost != "" && $villePost != "" && $codePostalPost != "" && $numRuePost != ""){
                    //         "INSERT  INTO Adresse (user_id, numRue, nomRue, ville, codePostal, ) VALUES (:id, :numRue, :nomRue,:ville, :codePostal)";
                    //     }else {
                    //         $reqUpdateAdresse = $conn->prepare("UPDATE Adresse SET ville = :ville, codePostal = :codePostal, numRue = :numRue, nomRue = :nomRue WHERE id_adresse = :id");
                    //         $reqUpdateAdresse->execute(array('ville' => $villePost, 'codePostal' => $codePostalPost, 'numRue' => $numRuePost, 'nomRue' => $nomRuePost, 'id' => $adresseId));
    
                    //     }
                    // }
                    
                }

                
                //Récupération des données de l'utilisateur
                $nom = $donneesUser['nom'];
                $prenom = $donneesUser['prenom'];
                $email = $donneesUser['mail'];
                $numero = $donneesUser['numero'];

                //Adresse de l'utilisateur
                
                if($adresse){
                    $adresseId = $adresse['id_adresse'];
                    $ville = $adresse['ville'];
                    $codePostal = $adresse['codePostal'];
                    $numRue = $adresse['numRue'];
                    $nomRue = $adresse['nomRue'];
                }else{
                    $ville = "";
                    $codePostal = "";
                    $numRue = "";
                    $nomRue = "";
                }
                

            ?>

            <!-- Contenu principal -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="container container-fluid">
                <div class="main-body">
                    <div class="row align-items-start">
                        <div class="col-md-4 d-flex flex-column gap-3 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="icons/userCircleIcon.png" alt="Admin" class="rounded-circle p-1" width="110">
                                        <div class="mt-3">
                                            <?php echo "<h4>".$prenom." ".$nom."</h4>"; ?>
                                            <?php echo "<p class='text-muted font-size-sm'>"." ".$numRue." ".$nomRue." ".$codePostal." ".$ville."</p>"; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="d-flex align-items-center">Vos produits favoris</h3>
                                </div>
                                <hr/>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <!-- Colonne de l'image (réduite pour laisser plus d'espace à la description) -->
                                    <div class="col-3">
                                        <img src="./imagesProduits/prod42.png" class="w-100 h-auto ml-3" alt="Image de Produit 1">
                                    </div>
                                    <hr/>
                                    <!-- Colonne du produit et du prix (mis l'un sous l'autre) -->
                                    <div class="col-9">
                                        <div>
                                            <h4>RockMon dragon de feu</h4>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold">99,99€</h5>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row d-flex justify-content-between align-items-center mb-4">
                                    <!-- Colonne de l'image (réduite pour laisser plus d'espace à la description) -->
                                    <div class="col-3">
                                        <img src="./imagesProduits/prod44.png" class="w-100 h-auto ml-3" alt="Image de Produit 1">
                                    </div>
                                    <hr/>
                                    <!-- Colonne du produit et du prix (mis l'un sous l'autre) -->
                                    <div class="col-9">
                                        <div>
                                            <h4>Bras</h4>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold">299,99€</h5>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-8 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row mb-3">
                                            <div class="col-sm-2">
                                                <h6 class='mb-0'>Nom</h6>
                                            </div>
                                            <div class="col-sm-4 text-secondary">
                                                <input type="text" class="form-control" name="nom" value="<?php echo $messageErreur ? $_POST["nom"] : $nom; ?>" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <h6 class="mb-0">Prenom</h6>
                                            </div>
                                            <div class="col-sm-4 text-secondary">
                                                <input type="text" class="form-control" name="prenom" value="<?php echo $messageErreur ? $_POST["prenom"] : $prenom;  ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php   if($messageErreur){
                                                            if($messageErreurMail){
                                                                echo "<p style='color: red' class='mt-0 mb-0'>L'adresse e-mail existe déjà</p>";
                                                                echo "<input type='email' class='form-control' style=border-color:red; name='email' value= '".$_POST['email']."' required>";
                                                            }else{
                                                                echo "<input type='email' class='form-control' name='email' value= '".$_POST['email']."' required>";
                                                            }
                                                            
                                                        }else{
                                                            echo "<input type='email' class='form-control' name='email' value= '$email' required>";
                                                        } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Numéro de téléphone</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php   if($messageErreur){
                                                            if($messageErreurTel){
                                                                echo "<p style='color: red' class='mt-0 mb-0'>Le numéro de téléphone n'est pas valide</p>";
                                                                echo "<input type='text' class='form-control' style=border-color:red; name='numero' value= '".$_POST['numero']."' required>";
                                                            }else{
                                                                echo "<input type='text' class='form-control' name='numero' value= '".$_POST['numero']."' required>";
                                                            }
                                                            
                                                        }else{
                                                            echo "<input type='text' class='form-control' name='numero' value= '$numero' required>";
                                                        } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Adresse</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" class="form-control" name="nomRue" value="<?php echo $messageErreur ? $_POST["nomRue"] : $nomRue; ?>" >
                                            </div>
                                        </div>
                                        <div class="form-row mb-3" style="align-items: center;">
                                            <div class="col-sm-1">
                                                <h6 class="mb-0">Ville</h6>
                                            </div>
                                            <div class="col-sm-3 text-secondary">
                                                <input type="text" class="form-control" name="numRue" value="<?php echo $messageErreur ? $_POST["numRue"] : $numRue; ?>" >
                                            </div>
                                            <div class="col-sm-1">
                                                <h6 class="mb-0">Code Postale</h6>
                                            </div>
                                            <div class="col-sm-3 text-secondary">
                                                <input type="text" class="form-control" name="codePostal" value="<?php echo $messageErreur ? $_POST["codePostal"] : $codePostal; ?>" >
                                            </div>
                                            <div class="col-sm-1">
                                                <h6 class="mb-0">N°Rue</h6>
                                            </div>
                                            <div class="col-sm-3 text-secondary">
                                                <input type="text" class="form-control" name="ville" value="<?php echo $messageErreur ? $_POST["ville"] : $ville; ?>" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary px-4" name="modifier" style="border-radius: 5px;">Modifier</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="d-flex align-items-center mb-3">Vos commandes</h4>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row d-flex justify-content-between mb-3">
                                                                <div class="p-2">
                                                                    <h6 class="mb-0">Commande n°1</h6>
                                                                </div>
                                                                <div class="p-2">
                                                                    <h6 class="mb-0 font-weight-bold">Total de la commande : 365.99 €</h6>
                                                                </div>
                                                                <div class="p-2">
                                                                    <h6 class="mb-0">Date : 16/05/2005</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row d-flex justify-content-between align-items-center">
                                                                <!-- Colonne de l'image (réduite pour laisser plus d'espace à la description) -->
                                                                <div class="col-2">
                                                                    <img src="./imagesProduits/prod42.png" class="w-100 h-auto" alt="Image de Produit 1">
                                                                </div>
                                                                
                                                                <!-- Colonne du produit et du prix (mis l'un sous l'autre) -->
                                                                <div class="col-3">
                                                                    <div>
                                                                        <h4>RockMon dragon de feu</h4>
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="font-weight-bold">99,99€</h5>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Colonne de la description -->
                                                                <div class="col-7">
                                                                    <h3 class="font-weight-bold">Description : </h3>
                                                                    <p>Les Yeux de Rockmon sont de petits yeux ronds, spécialement conçus pour donner vie à votre compagnon Rockmon. Avec leur surface bombée et leurs petites pupilles noires flottantes, ils ajoutent une touche de personnalité et d’expressivité. Faciles à fixer, ils permettent à chaque Rockmon de développer un "regard" unique, qu’il soit malicieux, curieux ou serein. Ces yeux sont parfaits pour donner un peu plus de caractère à votre caillou de compagnie.</p>
                                                                </div>
                                                            </div>
                                                            <hr/>
                                                            <div class="row d-flex justify-content-between align-items-center">
                                                                <!-- Colonne de l'image (réduite pour laisser plus d'espace à la description) -->
                                                                <div class="col-2">
                                                                    <img src="./imagesProduits/prod45.png" class="w-100 h-auto" alt="Image de Produit 1">
                                                                </div>
                                                                
                                                                <!-- Colonne du produit et du prix (mis l'un sous l'autre) -->
                                                                <div class="col-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <h4>Jambes</h4>
                                                                    </div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h5 class="font-weight-bold">199,99€</h5>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Colonne de la description -->
                                                                <div class="col-7">
                                                                    <h3 class="font-weight-bold">Description : </h3>
                                                                    <p>Les Yeux de Rockmon sont de petits yeux ronds, spécialement conçus pour donner vie à votre compagnon Rockmon. Avec leur surface bombée et leurs petites pupilles noires flottantes, ils ajoutent une touche de personnalité et d’expressivité. Faciles à fixer, ils permettent à chaque Rockmon de développer un "regard" unique, qu’il soit malicieux, curieux ou serein. Ces yeux sont parfaits pour donner un peu plus de caractère à votre caillou de compagnie.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
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