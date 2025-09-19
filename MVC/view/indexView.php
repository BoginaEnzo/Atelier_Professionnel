<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exemple d'Application PHP avec  PDO, POO, DAO, MVC</title>
        <!--//bibliotheque graphique bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <form action = "index.php?controller=articles&action=creer" method ="POST" class="col-lg-S">
            <h3> Add Article </h3>
            Nom: <input type="text" name = "nom" class="form-control">
            Poid: <input type="text" name = "poid" class="form-control">
            Prix: <input type="text" name = "prix" class="form-control">
            <br>
            <input type="submit" value="Send" class="btn btn-secondary"/>
        </form>
    </body>
</html>