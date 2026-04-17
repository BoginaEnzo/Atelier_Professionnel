    <?php
        // Création classe Animal
        class Animal{
            private $table="animaux";
            private $connexion;

            private $id;
            private $nom;
            private $espece;
            private $statut;
            private $age;
            private $race;
            private $photo;

            // Constructeur pour les connexions et les caractéristiques de l'animal
            public function __construct($connexion){
                $this->connexion = $connexion;
            }

            public function getAnimal_id(){
                return $this->id;
            }

            public function getAnimal_nom(){ 
                return $this->nom;
            }

            public function getAnimal_espece(){
                return $this->espece;
            }

            public function getAnimal_statut(){
                return $this->statut;
            }

            public function getAnimal_age(){
                return $this->age;
            }

            public function getAnimal_race(){
                return $this->race;
            }

            public function getAnimal_photo(){
                return $this->photo;
            }

            // Setters pour chaque caractéristique de l'animal
            public function setAnimal_id($id){
                $this->id = $id;
            }

            public function setAnimal_nom($nom){
                $this->nom = $nom;
            }

            public function setAnimal_espece($espece){
                $this->espece = $espece;
            }

            public function setAnimal_statut($statut){
                $this->statut = $statut;
            }

            public function setAnimal_age($age){
                $this->age = $age;
            }

            public function setAnimal_race($race){
                $this->race = $race;
            }

            public function setAnimalphoto($photo) {
                $this->photo = $photo;
            }


            // Méthodes pour les opérations CRUD
            // Récupérer tous les animaux
            public function getAll(){
                $query = $this->connexion->prepare("SELECT id, nom, espece, statut, age, race, photo
                FROM ".$this->table);
                $query->execute();
                $result = $query->fetchAll();
                $this->connexion = null;
                return $result;
            }

            // Récupérer un animal par son ID
            public function getById($id){
                $query = $this->connexion->prepare("SELECT id, nom, espece, statut, age, race, photo
                FROM ".$this->table." WHERE id = :id");
                $query-> execute(array("id"=> $id));
                $result = $query->fetchObject();
                $this->connexion = null;
                return $result;
            }

            // Insérer un nouvel animal dans la base de données
            public function insert(){
                $query = $this->connexion->prepare("INSERT INTO ".$this->table."(nom, espece, statut, age, race, photo) 
                VALUES (:nom, :espece, :statut, :age, :race, :photo)");

                $result = $query->execute(array(
                    "nom"=>$this->nom,
                    "espece"=>$this->espece,
                    "statut"=>$this->statut,
                    "age"=>$this->age,
                    "race"=>$this->race,
                    "photo"=>$this->photo,
                ));
                $this->connexion = null;
                return $result;
            }

            // Mettre à jour les informations d'un animal
            public function update(){
                $query = $this->connexion->prepare("UPDATE ".$this->table." SET nom = :nom, espece = :espece, statut = :statut, age = :age, race = :race, photo = :photo
                WHERE id = :id");

                $result = $query->execute(array(
                    "id" =>$this->id,
                    "nom"=>$this->nom,
                    "espece"=>$this->espece,
                    "statut"=>$this->statut,
                    "age"=>$this->age,
                    "race"=>$this->race,
                    "photo"=>$this->photo,
                ));
                $this->connexion = null;
                return $result;
            }

            // Supprimer un animal de la base de données
            public function delete(){
                $query = $this->connexion->prepare("DELETE FROM ".$this->table." WHERE id = :id");
                $result = $query->execute(array(
                    "id" =>$this->id
                ));
                $this->connexion = null;
                return $result;
            }
        }
    ?>