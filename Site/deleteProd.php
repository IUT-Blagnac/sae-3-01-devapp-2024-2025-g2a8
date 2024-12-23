<?php 
    require_once("./include/connect.inc.php");
    session_start();
    require_once("./include/verifAdmin.php");
    

    if (!isset($_GET["idProduit"])){
        header("location:index.php");
        exit();
    }
    try{
    $idProduit = htmlentities($_GET["idProduit"]);

    $reqProduit = $conn->prepare("DELETE FROM Produit WHERE id_produit = :id_produit");
    $reqProduit->execute(['id_produit' => $idProduit]);
    }catch(PDOException $e){
        header("location:DetailProduit.php?idProduit=$idProduit");
    }
    header("location:sousCategorie.php");
?>