<header class="bg-white border-bottom">
    <div class="container-fluid d-flex align-items-center py-3 px-3">
        <!-- Logo -->
        <div class="me-auto">
            <a href="index.php" class="text-dark text-decoration-none fw-bold fs-4" style="font-size: 2rem;font-weight: 400;">ROCKWORLD</a>
        </div>
        
        <!-- Barre de recherche -->
        <form class="d-flex flex-grow-1 mx-3" action="recherche.php">
            <input 
                class="form-control me-2 rounded-pill" 
                type="search" 
                name="query" 
                placeholder="Rechercher" 
                aria-label="Rechercher"
                >
        </form>
        
        <!-- Icônes -->
        <div class="d-flex align-items-center gap-3">
            <img src='./icons/utilisateur.png' alt="utilisateur" width="30" height="30">
            <img src='./icons/carte.png' alt="Panier" width="40" height="40">
        </div>
    </div>
</header>