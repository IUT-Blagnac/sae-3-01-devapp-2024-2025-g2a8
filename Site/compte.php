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
            ?>

            <!-- Contenu principal -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="container container-fluid">
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="icons/userCircleIcon.png" alt="Admin" class="rounded-circle p-1" width="110">
                                        <div class="mt-3">
                                            <h4>John Doe</h4>
                                            <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Full Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" value="John Doe">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" value="john@example.com">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" value="(239) 816-9029">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Mobile</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" value="(320) 380-4539">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Address</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" value="Bay Area, San Francisco, CA">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="button" class="btn btn-primary px-4" value="Save Changes">
                                        </div>
                                    </div>
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
                                                                    <h6 class="mb-0">Date : 16/05/2005</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-sm-3">
                                                                    <img src="./imagesProduits/prod42.png" class="w-75 h-75" alt="Image de">
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                        <h3>Produit 1</h3>
                                                                        <h3>99,99€</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <p>description : djcsjjicjsidjcsdnchig gcisdhgc isci gsidc hiusuhdcius hihcdisdhcishc siuhdcjxhsc iuhihkhjh</p>
                                                                </div>
                                                                <div>
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