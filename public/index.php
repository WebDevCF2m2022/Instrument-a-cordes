<?php
session_start();
require_once "../config.php";
require_once "../modele/modeleAdmin.php";
require_once "../modele/modeleCategory.php";
require_once "../modele/modeleInstrument.php";
#require_once "../vendor/autoload.php";

try {
    $dbConnect = new PDO (DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
DB_USER,DB_PWD);
}catch(Exception $e){
    $e = throw new Exception('Pas de connexion');

}

if(ENV=="dev"||ENV=="test"){
    // activation de l'affichage des erreurs
    $dbConnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}


if(isset($_SESSION['uniqueId'])&&$_SESSION['uniqueId']==session_id()){

        
        require_once "../controller/privateController.php";
}else{ 
        require_once "../controller/publicController.php";
} 


$dbConnect = null;