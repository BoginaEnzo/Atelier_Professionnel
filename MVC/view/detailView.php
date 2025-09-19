<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple PDO POO DAO MVC</title>
    <!--//bibliotheque graphique bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="col-lg-5 mr-auto">
        <form action="index.php?controller=articles&action=maj" method="POST">
            <h3>Article détaillé</h3>
            <hr />
            <input type="hidden" name="id" value="<?php echo $data["article"]->art_id ?>"/>
            Nom: <input type="text" name="nom" value="<?php echo $data["article"]->art_nom ?>" class="form-control"/>
            Prix: <input type="text" name="prix" value="<?php echo $data["article"]->art_prix ?>" class="form-control"/>
            Poids: <input type="text" name="poids" value="<?php echo $data["article"]->art_poids ?>" class="form-control"/>
            <input type="submit" value="Modifier" class="btn btn-success"/>
            
</body>
</html>