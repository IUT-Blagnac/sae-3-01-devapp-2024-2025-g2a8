<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Menu horizontal -->

<?php 
    require_once('include/connect.inc.php');
    $categ = $conn->prepare("SELECT * FROM Categorie WHERE parent IS NULL");
    $categ->execute();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php
                foreach($categ->fetchAll(PDO::FETCH_ASSOC) as $cat){ ?>
                    <li class="nav-item display-navBar">
                        <a class="nav-link" href="sousCategorie.php?idSousCateg=<?php echo $cat['id_categorie']; ?>"><?php echo $cat['nom_categorie'] ?></a>
                        
                        <?php
                            $sousCateg = $conn->prepare("SELECT * FROM Categorie WHERE parent = :cat");
                            $sousCateg->execute(['cat' => $cat['id_categorie']]);
                            if($sousCateg->rowCount() > 0){ ?>
                                <div class="navBarToDisplay">
                                    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
                                    
                                        <div class="collapse navbar-collapse" id="navbarNav">
                                            <ul class="navbar-nav">
                                                <?php foreach($sousCateg->fetchAll(PDO::FETCH_ASSOC) as $sousCat){ ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="sousCategorie.php?idSousCateg=<?php echo $sousCat['id_categorie']; ?>"><?php echo $sousCat['nom_categorie']; ?></a>                                                
                                                </li>
                                            <?php } $sousCateg -> closeCursor();?>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                    </li>
            <?php }} $categ->closeCursor()?>
        </ul>
    </div>
</nav>
