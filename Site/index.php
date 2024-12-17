<?php
require_once("./include/head.php");

?>

<body class="d-flex flex-column min-vh-100">
    <!-- En-tête -->
    

<body>
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
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
            <img src="./imagesProduits/prod45.png" alt="Los Angeles">
            </div>

            <div class="item">
            <img src="./imagesProduits/prod45.png" alt="Chicago">
            </div>

            <div class="item">
            <img src="./imagesProduits/prod45.png" alt="New York">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>

<!-- Scripts nécessaires pour Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
