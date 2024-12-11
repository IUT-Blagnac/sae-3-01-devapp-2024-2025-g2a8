<?php
require_once("./include/head.php");
?>

<body class="d-flex flex-column min-vh-100">
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
            ?>

            <!-- Contenu principal -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">

            </main>
        </div>
    </div>

    <!-- Pied de page -->
    <?php
    require_once("./include/footer.php")
    ?>
</body>
</html>