<?php
try{
 $user = 
 $pass = 
 $conn = new PDO('mysql:host=localhost;dbname=R2024PHP3041;charset=UTF8'  
				,$user, $pass, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
}
catch (PDOException $e){
  echo "Erreur: ".$e->getMessage()."<br>";
  die() ;
}
?>
