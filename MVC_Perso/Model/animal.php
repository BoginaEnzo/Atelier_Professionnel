    <?php
        // Création classe Animal
        class Animal{
            private $table="animaux";
            private $connexion;

            private $animal_id;
            private $animal_nom;
            private $animal_espece;
            private $animal_statut;
            private $animal_age;
            private $animal_race;
            private $animal_photo;

            // Constructeur pour les connexions et les caractéristiques de l'animal
            public function __construct($connexion){
                $this->connexion = $connexion;
            }

            public function getAnimal_id(){
                return $this->animal_id;
            }

            public function getAnimal_nom(){
                return $this->animal_nom;
            }

            public function getAnimal_espece(){
                return $this->animal_espece;
            }

            public function getAnimal_statut(){
                return $this->animal_statut;
            }

            public function getAnimal_age(){
                return $this->animal_age;
            }

            public function getAnimal_race(){
                return $this->animal_race;
            }

            public function getAnimal_photo(){
                return $this->animal_photo;
            }

            // Setters pour chaque caractéristique de l'animal
            public function setAnimal_id($id){
                $this->animal_id = $id;
            }

            public function setAnimal_nom($nom){
                $this->animal_nom = $nom;
            }

            public function setAnimal_espece($espece){
                $this->animal_espece = $espece;
            }

            public function setAnimal_statut($statut){
                $this->animal_statut = $statut;
            }

            public function setAnimal_age($age){
                $this->animal_age = $age;
            }

            public function setAnimal_race($race){
                $this->animal_race = $race;
            }

            public function setAnimal_photo($photo){
                $this->animal_photo = $photo;
            }

            // Méthodes pour les opérations CRUD
            // Récupérer tous les animaux
            public function getAll(){
                $query = $this->connexion->prepare("SELECT animal_id, animal_nom, animal_espece, animal_statut, animal_age, animal_race, animal_photo
                FROM ".$this->table);
                $query->execute();
                $result = $query->fetchAll();
                $this->connexion = null;
                return $result;
            }

            // Récupérer un animal par son ID
            public function getById($id){
                $query = $this->connexion->prepare("SELECT animal_id, animal_nom, animal_espece, animal_statut, animal_age, animal_race, animal_photo
                FROM ".$this->table." WHERE animal_id = :id");
                $query-> execute(array("id"=> $id));
                $result = $query->fetchObject();
                $this->connexion = null;
                return $result;
            }

            // Insérer un nouvel animal dans la base de données
            public function insert(){
                $query = $this->connexion->prepare("INSERT INTO ".$this->table."(animal_nom, animal_espece, animal_statut, animal_age, animal_race, animal_photo) 
                VALUES (:nom, :espece, :statut, :age, :race, :photo)");

                $result = $query->execute(array(
                    "nom"=>$this->animal_nom,
                    "espece"=>$this->animal_espece,
                    "statut"=>$this->animal_statut,
                    "age"=>$this->animal_age,
                    "race"=>$this->animal_race,
                    "photo"=>$this->animal_photo,
                ));
                $this->connexion = null;
                return $result;
            }

            // Mettre à jour les informations d'un animal
            public function update(){
                $query = $this->connexion->prepare("UPDATE ".$this->table." SET animal_nom = :nom, animal_espece = :espece, animal_statut = :statut, animal_age = :age, animal_race = :race, animal_photo = :photo
                WHERE animal_id = :id");

                $result = $query->execute(array(
                    "id" =>$this-> animal_id,
                    "nom"=>$this->animal_nom,
                    "espece"=>$this->animal_espece,
                    "statut"=>$this->animal_statut,
                    "age"=>$this->animal_age,
                    "race"=>$this->animal_race,
                    "photo"=>$this->animal_photo,
                ));
                $this->connexion = null;
                return $result;
            }

            // Supprimer un animal de la base de données
            public function delete(){
                $query = $this->connexion->prepare("DELETE FROM ".$this->table." WHERE animal_id = :id");
                $result = $query->execute(array(
                    "id" =>$this->animal_id
                ));
                $this->connexion = null;
                return $result;
            }
        }
    ?>