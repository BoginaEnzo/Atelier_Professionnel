<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple PHP+PDO+POO+MVC</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
    <style>
        input{
            margin-top:5px;
            margin-bottom:5px;
        }
        .right{
            float:right;
        }
    </style>
</head>
<body>
    <!-- Form pour ajouter un animal -->
    <form action ="index.php?controller=animals&action=creer" 
    method ="post" class="col-lg-5">
        <h3>Add animal</h3>
        Nom: <input type="text" name="nom" class="form-control">
        Espece: <input type="text" name="espece" class="form-control">
        Age: <input type="text" name="age" class="form-control">
        Race: <input type="text" name="race" class="form-control">
        Photo: <input type="text" name="photo" class="form-control">
        <input type="submit" value="Send" class="btn btn-success"/>
    </form>

    <div class="col-lg-7">
        <h3>Animals</h3>
        <hr/> 
    </div>

    <!-- Section pour afficher la liste des animaux -->
    <section class="col-lg-7" style="height:400px;overflow-y:scroll;">
        <?php foreach($data["animal"] as $animal) {?>
            <?php echo $animal["animal_nom"]; ?> - 
            <?php echo $animal["animal_espece"]; ?> - 
            <?php echo $animal["animal_age"]; ?> - 
            <?php echo $animal["animal_race"]; ?> - 
            <div class="right">
                <div class="btn btn-group">
                <form action="index.php?controller=animals&action=delete" method="post">
                    <input type="hidden" id="idDel" name="idDel" value="<?php echo $animal['animal_id']; ?>" />
                    <input type="submit" value="Supprimer" class="btn btn-danger"/>
                </form>

                <a href="index.php?controller=animals&action=detail&id=<?php echo $animal['animal_id']; ?>" 
                class="btn btn-info">detail</a>  
            </div>
        <hr/>
        <?php } ?>
    </section>
</body>
</html>