<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <div class="container" style="display:flex; flex-direction:column; align-items:center;">
        <form action="../index.php?controller=animals&action=creer" method="post" enctype="multipart/form-data" class="col-lg-5">
            <h3>Ajouter un animal</h3>
            Nom: <input type="text" name="nom" class="form-control" required>
            Age: <input type="number" name="age" class="form-control" required>
            Espèce: 
                <select name="espece" required class="form-control">
                    <option value="">Choisir une espèce</option>
                    <option value="chien">Chien</option>
                    <option value="chat">Chat</option>
                </select>
            Statut:
                <select name="statut" required class="form-control">
                    <option value="">Choisir un statut</option>
                    <option value="adoption">À l'adoption</option>
                    <option value="adopte">Adopté</option>
                    <option value="acceuil">En accueil</option>
                </select>
            Race: <input type="text" name="race" class="form-control" required>
            Photo: <input type="file" name="photo" id="photo" accept="image/*" class="form-control">
            <br>
            <input type="submit" value="Envoyer" class="btn btn-success">
        </form>
    </div>
</body>
</html>