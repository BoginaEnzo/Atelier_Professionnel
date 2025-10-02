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
    <header style="margin-bottom:20px;">
        <nav class="navbar navbar-default" >
            <ul class="nav navbar-nav navbar-center" style="margin-top: 5px; display: flex; justify-content: center; width: 100%;">
                <li class="active"><a href="index.php">Accueil</a></li>
                <li class="active"><a href="view/ajouter.php">Ajouter un animal</a></li>
            </ul>
        </nav>
    </header>

    <div class="container" style="display:flex; flex-direction:column; align-items:center;">

        <!-- Liste des animaux centrée -->
        <section style="max-width: 900px; width: 100%;">
            <?php foreach ($data['animal'] as $Animal): ?>
                <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; text-align: center;">
                    <strong><?= htmlspecialchars($Animal['nom']) ?></strong><br>
                    Espèce : <?= htmlspecialchars($Animal['espece']) ?><br>
                    Âge : <?= htmlspecialchars($Animal['age']) ?><br>
                    Race : <?= htmlspecialchars($Animal['race']) ?><br>
                    Statut : <?= htmlspecialchars($Animal['statut']) ?><br>
                    <?php if (!empty($Animal['photo'])): ?>
                        <img src="uploads/<?= htmlspecialchars($Animal['photo']) ?>" alt="Photo de <?= htmlspecialchars($Animal['nom']) ?>" style="max-width: 200px;">
                    <?php else: ?>
                        <p>Aucune photo disponible</p>
                    <?php endif; ?>
                    <br>
                    <div class="btn-group" style="margin-top: 10px;">
                        <!-- Modifier bouton detail -->
                        <form>
                            <a href="index.php?controller=animal&action=detail&id=<?= htmlspecialchars($Animal['id']) ?>" class="btn btn-primary" style="align:center; margin-bottom:5px;">Modifier</a>
                        </form>

                        <!-- Supprimer bouton -->
                        <form action="index.php?controller=animals&action=delete" method="post" style="display:inline;">
                            <input type="hidden" name="idDel" value="<?= htmlspecialchars($Animal['id']) ?>">
                            <button type="submit" class="btn btn-danger" style="align:center; margin-bottom:5px;">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

    </div>


</body>
</html>