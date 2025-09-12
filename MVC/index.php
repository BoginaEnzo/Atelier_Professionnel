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

    function loadController($controller){
        switch($controller){
            case 'articles' :
                $strFileController = 'controller/articleController.php';
                require_once $strFileController;
                $controllerObj = new ArticlesController();
            break;

            default :
                $strFileController = 'controller/articleController.php';
                require_once $strFileController;
                $controllerObj = new ArticlesController();
            break;
        }

        return $controllerObj;
    }

    function loadAction($controllerObj){
        if(isset($_GET["action"])){
            $controllerObj->run($_GET["action"]);
        }else{
            $controllerObj->run(ACTION_DEFAULT);
        }
    }
?>