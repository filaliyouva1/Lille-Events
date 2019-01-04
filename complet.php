<?php
spl_autoload_register(function ($className) {
    include ("lib/{$className}.class.php");
});

session_start();
if (isset($_SESSION['ident'])){
    $personne = $_SESSION['ident'];
}

date_default_timezone_set ('Europe/Paris');
try{
    $data = new DataLayer();
    $listeCategories = $data->getCategories();
    $listeEvenements = $data->getAllEvents();
    $coureurs = $data->getCoureurs();
    $stats = $data->getStats();
    require ('views/pageComplet.php');
} catch (PDOException $e){
    $errorMessage = $e->getMessage();
    require("views/pageErreur.php");
}

?>
