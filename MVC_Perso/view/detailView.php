<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Détails de l'animal</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
    <!-- Conteneur principal -->
    <div class="container">
        <h2>Détails de l'animal</h2>
        <form action="index.php?controller=animal&action=maj" method="post" class="form-horizontal">
            <input type="hidden" name="id" value="<?= $unAnimal['id'] ?>" />
            <!-- Champ pour le nom -->
            <div class="form-group">
                <label class="control-label col-sm-2">Nom</label>
                <div class="col-sm-10">
                    <input type="text" name="nom" value="<?= $unAnimal['nom'] ?>" required class="form-control" />
                </div>
            </div>
            <!-- Champ pour l'espèce avec options prédéfinies -->
            <div class="form-group">
                <label class="control-label col-sm-2">Espèce</label>
                <div class="col-sm-10">
                    <select name="espece" required class="form-control">
                        <option value="chien" <?= $unAnimal['espece'] == 'chien' ? 'selected' : '' ?>>Chien</option>
                        <option value="chat" <?= $unAnimal['espece'] == 'chat' ? 'selected' : '' ?>>Chat</option>
                    </select>
                </div>
            </div>
            <!-- Champ pour le statut avec options prédéfinies -->
            <div class="form-group">
                <label class="control-label col-sm-2">Statut</label>
                <div class="col-sm-10">
                    <select name="statut" required class="form-control">
                        <option value="adoption" <?= $unAnimal['statut'] == 'adoption' ? 'selected' : '' ?>>À l'adoption</option>
                        <option value="adopte" <?= $unAnimal['statut'] == 'adopte' ? 'selected' : '' ?>>Adopté</option>
                        <option value="acceuil" <?= $unAnimal['statut'] == 'acceuil' ? 'selected' : '' ?>>En accueil</option>
                    </select>
                </div>
            </div>
            <!-- Champ pour l'âge -->
            <div class="form-group">
                <label class="control-label col-sm-2">Âge</label>
                <div class="col-sm-10">
                    <input type="number" name="age" value="<?= $unAnimal['age'] ?>" class="form-control" />
                </div>
            </div>
            <!-- Champ pour la race -->
            <div class="form-group">
                <label class="control-label col-sm-2">Race</label>
                <div class="col-sm-10">
                    <input type="text" name="race" value="<?= $unAnimal['race'] ?>" class="form-control" />
                </div>
            </div>
            <!-- Champ pour la photo avec aperçu si URL fournie -->
            <form action="upload.php" method="post">
                <label for="photo">Choisir une photo :</label>
                <input type="file" name="photo" id="photo" accept="image/*" />
                <br />
                <input type="submit" value="Envoyer" />
            </form>

            <!-- Boutons pour soumettre le formulaire -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <a href="index.php?controller=animal&action=index" class="btn btn-default">Retour</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
