<?php

require_once 'config/global.php';

//Gestion du controller par defaut
if(isset($_GET["controller"])){
    $controllerObj = loadController($_GET["controller"]);
    loadAction($controllerObj);
    
}else{
    $controllerObj=loadController(CONTROLLER_DEFAULT);
    loadAction($controllerObj);  
}

// Chargement du contrôleur
function loadController($controller){
    // Choix du contrôleur en fonction de la valeur passée en paramètre
    switch($controller){
        case 'animals' :
            $strFileController = 'controller/animalController.php';
            require_once $strFileController;
            $controllerObj = new AnimalController();
        break;

        default :
            $strFileController = 'controller/animalController.php';
            require_once $strFileController;
            $controllerObj = new AnimalController();
        break;
    }
    return $controllerObj;
}

// Chargement de l'action
function loadAction($controllerObj){
    if(isset($_GET["action"])){
        $controllerObj->run($_GET["action"]);
    }else{
        $controllerObj->run(ACTION_DEFAULT);
    }
}



?>