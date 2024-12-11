<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
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
    <div class="container flex-grow-1 text-center">
        <h1>Connexion</h1>
        <div class="form-signin w-100">
            <form method="post" action="include/traitConnexion.php">
                <div class="form-floating">
                    <input type="email" class="form-control" id="mailConnexion" name="mail" placeholder="E-Mail">
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordConnexion" name="pass" placeholder="Mot de passe">
                </div>
                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="checkRemember" name="remember">
                    <label class="form-check-label" for="checkRemember">
                        Se souvenir de moi
                    </label>
                    <button class="btn btn-primary w-100 py-2" type="submit" name="Valider">Sign in</button>
            </form>
        </div>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
        ?>
</body>

</html>