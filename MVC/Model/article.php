    <?php
        class Article{
            private $table="article";
            private $connexion;

            private $art_id;
            private $art_nom;
            private $art_prix;
            private $art_poid;


            public function __construct($connexion){
                $this->connexion = $connexion;
            }

            public function getArt_id(){
                return $this->art_id;
            }

            public function getArt_nom(){
                return $this->art_nom;
            }

            public function getArt_prix(){
                return $this->art_prix;
            }

            public function getArt_poid(){
                return $this->art_poid;
            }

            public function setArt_id($id){
                $this->art_id = $id;
            }

            public function setArt_nom($nom){
                $this->art_nom = $nom;
            }

            public function setArt_prix($prix){
                $this->art_prix = $prix;
            }

            public function setArt_poid($poid){
                $this->art_poid = $poid;
            }

            public function getAll(){
                $query = $this->connexion->prepare("SELECT art_id, art_nom, art_prix, art_poid
                FROM ".$this->table);
                $query->execute();
                $result = $query->fetchAll();
                $this->connexion = null;
                return $result;
            }
        }
    ?>