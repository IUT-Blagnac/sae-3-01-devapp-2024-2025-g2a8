<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    <?php    
        include("include/connect.inc.php");

        if (isset($_POST["Valider"])) {
            $nom = htmlentities($_POST["nom"]);
            $prenom = htmlentities($_POST["prenom"]);
            $email = htmlentities($_POST["email"]);
            $mdp = htmlentities($_POST["mdp"]);
            $tel = htmlentities($_POST["tel"]);
            $regexTel = "#^[0-9]{10}$#";

            if (!preg_match($regexTel, $tel)) {
                $error = "<p style='color: red'>Le numéro de téléphone n'est pas valide</p></br>";
                echo $error;
            } else {
                $reqVerifUtilisateurExistant = $conn->prepare(
                    "SELECT * FROM Utilisateur WHERE mail = :email"
                );
                $reqVerifUtilisateurExistant->execute(array("email" => $email));

                if ($reqVerifUtilisateurExistant->rowcount() >= 1) {
                    $error = "<p style='color: red'>L'adresse e-mail existe déjà</p>";
                    echo $error;
                } else {
                    $mdpHash = password_hash($mdp, null);
                    $reqInsertCompte = $conn->prepare("
                        INSERT INTO Utilisateur (nom, prenom, password, mail, numero, role) VALUES (:nom, :prenom, :password, :mail, :numero, :role)
                    ");
                    $reqInsertCompte->execute(array("nom" => $nom, "prenom" => $prenom, "password" => $mdpHash, "mail" => $email, "numero" => $tel, "role" => "U"));
                    header("location:connexion.php");
                }
            }
        }

        require_once("./include/header.php");
                
    ?>

    <!-- Conteneur principal -->
    <div class="container-fluid flex-grow-1">
            <!-- Menu vertical sur la gauche -->
            <?php
                require_once("./include/menu.php");
            ?>

            <!-- Contenu principal -->
            <main role="main" class="px-4 d-flex justify-content-center align-items-center" style="min-height: 50vh;">
                <div class="text-center">
                <h2 class="text-center mb-4" style="font-weight: 400;">Création Compte</h2>

                


                <form method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputNom" class="d-flex justify-content-start">Nom</label>
                            <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Nom" value="<?php echo isset($_POST["nom"]) ? $_POST["nom"] : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPrenom">Prénom</label>
                            <input type="text" class="form-control" id="inputPrenom" name="prenom" placeholder="Prénom" value="<?php echo isset($_POST["prenom"]) ? $_POST["prenom"] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputMdp">Mot de Passe</label>
                            <input type="password" class="form-control" id="inputMdp" name="mdp" placeholder="Mot de passe" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTel">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="inputTel" name="tel" required>
                    </div>
                    <button type="submit" name="Valider" class="btn btn-primary">S'inscrire</button>
                </form>
                </div>
            </main>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>