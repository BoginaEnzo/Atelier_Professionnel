<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'animal</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <!-- Détails de l'animal -->
    <div class="container" style="display:flex; flex-direction:column; align-items:center;">
        <h2>Détails de l'animal</h2>

        <form action="index.php?controller=animal&action=maj" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" name="id" value="<?= ($animal->id ?? '') ?>" />

            <div class="form-group">
                <label for="nom" class="control-label col-sm-2">Nom</label>
                <div class="col-sm-10">
                    <input type="text" name="nom" value="<?= ($animal->nom ?? '') ?>" required class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="espece" class="control-label col-sm-2">Espèce</label>
                <div class="col-sm-10">
                    <select name="espece" required class="form-control">
                        <option value="chien" <?= (isset($animal->espece) && $animal->espece == 'chien') ? 'selected' : '' ?>>Chien</option>
                        <option value="chat" <?= (isset($animal->espece) && $animal->espece == 'chat') ? 'selected' : '' ?>>Chat</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="statut" class="control-label col-sm-2">Statut</label>
                <div class="col-sm-10">
                    <select name="statut" required class="form-control">
                        <option value="adoption" <?= (isset($animal->statut) && $animal->statut == 'adoption') ? 'selected' : '' ?>>À l'adoption</option>
                        <option value="adopte" <?= (isset($animal->statut) && $animal->statut == 'adopte') ? 'selected' : '' ?>>Adopté</option>
                        <option value="acceuil" <?= (isset($animal->statut) && $animal->statut == 'acceuil') ? 'selected' : '' ?>>En accueil</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="age" class="control-label col-sm-2">Âge</label>
                <div class="col-sm-10">
                    <input type="number" name="age" value="<?= ($animal->age ?? '') ?>" required class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="race" class="control-label col-sm-2">Race</label>
                <div class="col-sm-10">
                    <input type="text" name="race" value="<?= ($animal->race ?? '') ?>" required class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="photo" class="control-label col-sm-2">Photo</label>
                <div class="col-sm-10">
                    <?php if (!empty($animal->photo)): ?>
                        <img src="uploads/<?= ($animal->photo) ?>" alt="Photo de l'animal" style="max-width:150px;" />
                    <?php else: ?>
                        <p>Aucune photo disponible</p>
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Modifier" class="btn btn-primary" />
                    <a href="index.php" class="btn btn-primary detail">Accueil</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
