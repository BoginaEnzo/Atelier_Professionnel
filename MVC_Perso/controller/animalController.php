<?php
    // Création de la classe AnimalController
    class AnimalController{
        private $connecteur;
        private $connexion;

        // Constructeur pour les connexions
        public function __construct(){
            require_once __DIR__ ."/../core/connecteur.php";
            require_once __DIR__ ."/../model/animal.php";

            $this->connecteur = new Connecteur();
            $this->connexion=$this->connecteur->connexion();
        }

        // Méthode pour exécuter les actions
        public function run($action){
            switch($action){
                case "index" :
                    $this->index();
                    break;
                case "detail" : 
                    $this->detail();
                    break;
                case "creer" :
                    $this->creer();
                    break;
                case "maj" :
                    $this->maj();
                    break;
                case "delete" :
                    $this->delete();
                    break;
                default :
                    $this->index();
                    break;
            }
        }
    
        // Méthodes pour chaque action
        // Afficher la liste des animaux
        function index(){
            $Animal = new Animal($this->connexion);
            $listeAnimals = $Animal->getAll();
            $this->view("index", array("animal"=>$listeAnimals, "titre" => "PHP MVC EXEMPLE"));
        }

        // Afficher les détails d'un animal
        function detail(){
            $Animal = new Animal($this->connexion);
            $unAnimal = $Animal->getById($_GET["id"]);
            $this->view("detail", array("animal"=>$unAnimal, "titre" => "DETAIL ANIMAL"));
        }

        // Ajouter un nouvel animal
        function creer(){
            $Animal = new Animal($this->connexion);
            $Animal->setAnimal_nom($_POST["nom"]);
            $Animal->setAnimal_espece($_POST["espece"]);
            $Animal->setAnimal_statut($_POST["statut"]);
            $Animal->setAnimal_age($_POST["age"]);
            $Animal->setAnimal_race($_POST["race"]);
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $fileName = basename($_FILES['photo']['name']);
                $targetFile = $uploadDir . $fileName;
                move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
                $Animal->setAnimalphoto($fileName); // Stocke juste le nom dans la base
            } else {
                $Animal->setAnimalphoto(null);
            }

            if($Animal->insert()){
                header('Location: index.php');
            }     
        }

        // Mettre à jour les informations d'un animal
        function maj(){
            $Animal = new Animal($this->connexion);
            $Animal->setAnimal_id($_POST["id"]);
            $Animal->setAnimal_nom($_POST["nom"]);
            $Animal->setAnimal_espece($_POST["espece"]);
            $Animal->setAnimal_statut($_POST["statut"]);
            $Animal->setAnimal_age($_POST["age"]);
            $Animal->setAnimal_race($_POST["race"]);
            $Animal->setAnimalphoto($_POST["photo"]);
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $fileName = basename($_FILES['photo']['name']);
                $targetFile = $uploadDir . $fileName;
                move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
                $Animal->setAnimalphoto($fileName); // Stocke juste le nom dans la base
            } else {
                $Animal->setAnimalphoto(null);
            }

            if($Animal->update()){
                header('Location: index.php');
            }   
        }

        // Supprimer un animal
        function delete(){
            $Animal = new Animal($this->connexion);
            $Animal->setAnimal_id($_POST["idDel"]);   
            if($Animal->delete()){
                header('Location: index.php');
            }     
        }

        // Méthode pour charger les vues
        function view($name, $data){
            extract($data);
            require_once __DIR__ . "/../view/".$name."View.php";
        } 
    }
?>