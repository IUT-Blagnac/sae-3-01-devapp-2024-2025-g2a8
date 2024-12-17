<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <script src="include_script/alert.js"></script>
    <!-- En-tête -->
    <header class="bg-white border-bottom">
        <div class="container-fluid d-flex align-items-center py-3 px-3">
            <!-- Logo -->
            <div class="me-auto">
                <a href="index.php" class="text-dark text-decoration-none fw-bold fs-4"
                    style="font-size: 2rem;font-weight: 400;">ROCKWORLD</a>
            </div>
        </div>
    </header>

    <!-- Conteneur principal -->
    <div class="container flex-grow-1 text-center w-50">
        <center>
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h1 class="mb-0">Connexion</h1>
                </div>
                <div class="card-body">
                    <div class="form-signin w-50 text-center">
                        <form method="post">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="mailConnexion" name="mail"
                                    placeholder="E-Mail" required>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="passwordConnexion" name="pass"
                                    placeholder="Mot de passe" required>
                            </div>
                            <div class="form-floating text-start my-3">
                                <input class="form-check-input" type="checkbox" value="remember-me" id="checkRemember"
                                    name="remember">
                                <label class="form-check-label" for="checkRemember">
                                    Se souvenir de moi
                                </label>
                                <button class="btn btn-primary w-100 py-2" type="submit"
                                    name="valider">Connexion</button>
                            </div>
                        </form>
                        <a href="CreaCompte.php" class="btn btn-secondary w-100 py-2">
                            S'inscrire
                        </a>
                    </div>
                </div>
            </div>


        </center>

        <div id="liveAlertPlaceholder"></div>

        <?php
        if (isset($_POST["valider"]) and isset($_POST['mail']) and isset($_POST['pass'])) {
            include("include/connect.inc.php");

            $mailUser = htmlentities($_POST['mail']);

            $userGet = $conn->prepare("SELECT * FROM Utilisateur WHERE mail='$mailUser'");

            $userGet->execute();
            $cou = $userGet->rowCount();

            if ($userGet->rowCount() != 1) {
                echo "<script>appendAlert('Compte introuvable', 'danger')</script>";

            } else {
                $user = $userGet->fetch();

                if (password_verify($_POST['pass'], $user['password'])) {
                    session_start();
                    session_reset();

                    $_SESSION["user_id"] = $user['user_id'];

                    header("location:index.php");
                } else {
                    echo "<script>appendAlert('Mot de passe incorect', 'danger')</script>";
                }
            }
        }
        ?>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
        ?>
</body>

</html>