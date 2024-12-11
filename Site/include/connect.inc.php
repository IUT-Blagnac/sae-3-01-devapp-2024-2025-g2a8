<?php
  try{
    $user = 'R2024MYSAE3006';
    $pass = '3zK3J6bc7gsW3Z';
    $conn = new PDO('mysql:host=localhost;dbname=R2024MYSAE3006;charset=UTF8'  
            ,$user, $pass, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
  }
  catch (PDOException $e){
    echo "Erreur: ".$e->getMessage()."<br>";
    die() ;
  }
?>