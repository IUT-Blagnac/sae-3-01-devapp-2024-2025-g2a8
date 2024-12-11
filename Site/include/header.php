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
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person" viewBox="0 1 16 16">
                <path transform="scale(1.15)" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg>

            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
            </svg>
        </div>
    </div>
</header>