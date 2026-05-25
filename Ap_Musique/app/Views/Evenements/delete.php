<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> <!-- Correction ajout CSS bootstrap -->
</head>
<body>
    <form action="<?= site_url('evenements/delete/'.$evenement['id_evenement']) ?>" 
    method="post">
    <input type="hidden" name="_method" value="DELETE">
    <?= csrf_field() ?>
    <button class="btn btn-danger"  type="submit">Supprimer</button>
</form>
</body>
</html>