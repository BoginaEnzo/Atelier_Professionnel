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
    <form action="index.php?controller=animals&action=creer" method="post" enctype="multipart/form-data" class="col-lg-5">
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


    <div class="col-lg-7">
        <h3>Animaux</h3> 
        <hr/> 
    </div>

    <!-- Section pour afficher la liste des animaux -->
    <section class="col-lg-7" style="height:700px;overflow-y:scroll;">
        <?php foreach ($data['animal'] as $Animal): ?>
            <div>
                <?= htmlspecialchars($Animal['nom']) ?><br>
                Espèce : <?= htmlspecialchars($Animal['espece']) ?><br>
                Age : <?= htmlspecialchars($Animal['age']) ?><br>
                Race : <?= htmlspecialchars($Animal['race']) ?><br>
                Statut : <?= htmlspecialchars($Animal['statut']) ?><br>
                <?php if (!empty($Animal['photo'])): ?>
                    <img src="uploads/<?= htmlspecialchars($Animal['photo']) ?>" alt="Photo de <?= htmlspecialchars($Animal['nom']) ?>" style="max-width:200px;">
                <?php else: ?>
                    <p>Aucune photo</p>
                <?php endif; ?>
            </div>
            <div class="center-block">
                <div class="btn btn-group">
                    <form action="index.php?controller=animals&action=delete" method="post">
                        <input type="hidden" id="idDel" name="idDel" value="<?php echo $Animal['id']; ?>" />
                        <input type="submit" value="Supprimer" class="btn btn-danger"/>
                    </form>
                    <form>
                        <a href="index.php?controller=animals&action=detail&id=<?php echo $Animal['id']; ?>" 
                        class="btn btn-primary detail">Detail</a>  
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <hr/>
    </section>
</body>
</html>