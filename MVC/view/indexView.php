<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exemple d'Application PHP avec  PDO, POO, DAO, MVC</title>
        <!-- TODO ajouter bootstrap-->
        <style>
            input{
                margin-top:5px;
                margin-bottom:5px;
            }
            .right{
                float: right;
            }
        </style>
    </head>
    <body>
        <form action = "index.php?controller=articles&action=creer" method ="POST" class="col-lg-S">
            <h3> Add Article </h3>
            Nom: <input type="text" name = "nom" class="form-control">
            Poid: <input type="text" name = "poid" class="form-control">
            Prix: <input type="text" name = "prix" class="form-control">
            <input type="submit" value="Send" class="btn btn-sucess"/>
        </form>
    </body>
</html>