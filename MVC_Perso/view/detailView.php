<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple PHP+PDO+POO+MVC</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> <!-- Correction ajout CSS bootstrap -->
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> <!-- Correction ajout JS bootstrap -->
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
    // Form pour modifier un animal
    <div class="col-lg-5 mr-auto">
        <form action="index.php?controller=animals&action=maj" method="post">
            <h3>Animal detaillé</h3>
            <hr/>
            <input type="hidden" name="id" value="<?php echo $data["animal"]->animal_id ?>" />
            Nom: <input type="text" name="nom" value="<?php echo $data["animal"]->animal_nom ?>" class="form-control" />
            Espece: <input type="text" name="espece" value="<?php echo $data["animal"]->animal_espece ?>" class="form-control" />
            Age: <input type="text" name="age" value="<?php echo $data["animal"]->animal_age ?>" class="form-control" />
            Race: <input type="text" name="race" value="<?php echo $data["animal"]->animal_race ?>" class="form-control" />
            Photo: <input type="text" name="photo" value="<?php echo $data["animal"]->animal_photo ?>" class="form-control" />
            <input type="submit" value="Modifier" class="btn btn-info"/>
        </form>
        // Form pour supprimer un animal
        <form action="index.php?controller=animals&action=delete" method="post">
            <input type="hidden" id="idDel" name="idDel" value="<?php echo $data["animal"]->animal_id ?>" />
            <input type="submit" value="Supprimer" class="btn btn-danger"/>
        </form>

    </div>

</body>
</html>