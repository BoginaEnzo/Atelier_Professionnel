<?php
    require_once 'config/global.php';

    //Gestion du controller par défaut
    if(isset($_GET["controller"])){
        $ccontrollerObj = loadController($GET["controller"]);
        loadAction($controllerObj);
    }
    else{
        $controllerObj = loadController(CONTROLLER_DEFAULT);
    }
?>