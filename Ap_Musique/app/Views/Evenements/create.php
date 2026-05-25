<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un événement</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />

<style>
/* RESET */
html, body {
    margin:0;
    padding:0;
    font-family:'Poppins', sans-serif;
    background:#0d0d0d;
    color:white;
}

/* CONTAINER NEON */
.container {
    margin-top:40px;
    background:rgba(0,0,0,0.55);
    padding:30px 40px;
    border-radius:20px;
    border:1px solid rgba(0,255,255,0.3);
    box-shadow:0 0 25px rgba(0,255,255,0.18);
    animation:fadeIn 0.8s ease-out;
}

/* TITRE */
h1.text-center {
    color:#00eaff;
    font-weight:900;
    text-shadow:0 0 18px #00eaff;
    animation:popIn 1s ease-out;
}

/* LABELS */
label {
    color:#00cfff;
    font-weight:600;
    text-shadow:0 0 8px #0099ff80;
}

/* INPUTS GLOWS */
.form-control {
    background:#0f0f0f;
    border:1px solid #00a8ff80;
    color:white;
    border-radius:12px;
    transition:0.3s;
}

.form-control:focus {
    border-color:#00eaff;
    box-shadow:0 0 12px #00eaff;
}

/* SECTION DYNAMIQUE (slide) */
#concertFields,
#festivalFields {
    animation:slideDown 0.4s ease;
}

/* BOUTON NEON */
.btn-primary {
    background:#0f0f0f;
    border:1px solid #00eaff;
    color:#00eaff;
    font-weight:700;
    border-radius:25px;
    padding:10px 25px;
    transition:0.3s ease;
    box-shadow:0 0 15px rgba(0,255,255,0.25);
}

.btn-primary:hover {
    transform:scale(1.05);
    box-shadow:0 0 25px #00eaff;
}

/* bouton retour */
.btn-default {
    background:#111;
    border:1px solid #777;
    color:white;
    border-radius:20px;
    transition:.3s;
}

.btn-default:hover {
    border-color:#00eaff;
    color:#00eaff;
    box-shadow:0 0 12px #00eaff;
}

/* ERREURS EN NEON ROUGE */
.help-block {
    color:#ff4f6d !important;
    font-weight:600;
    text-shadow:0 0 10px #ff4f6d;
}

/* ANIMATIONS */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to   {opacity:1; transform:translateY(0);}
}

@keyframes popIn {
    0% {opacity:0; transform:scale(0.7);}
    60% {opacity:1; transform:scale(1.08);}
    100% {transform:scale(1);}
}

@keyframes slideDown {
    from {opacity:0; transform:translateY(-10px);}
    to   {opacity:1; transform:translateY(0);}
}
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}

</style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Créer un événement</h1>
    <hr>

    <form method="post" action="<?= site_url('evenements/store') ?>" enctype="multipart/form-data">

        <?= csrf_field() ?>

        <!-- Champ Type -->
        <div class="form-group">
            <label for="type" class="control-label">Type :</label>
            <select id="type" name="type" class="form-control" required>
                <option value="">-- Choisir un type --</option>
                <option value="concert" <?= old('type') === 'concert' ? 'selected' : '' ?>>Concert</option>
                <option value="festival" <?= old('type') === 'festival' ? 'selected' : '' ?>>Festival</option>
            </select>
            <?php if (isset($validation) && $validation->hasError('type')): ?>
                <span class="help-block"><?= $validation->getError('type') ?></span>
            <?php endif; ?>
        </div>

        <!-- Champs Communs -->
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" class="form-control" required value="<?= old('nom') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <input type="text" id="description" name="description" class="form-control" required value="<?= old('description') ?>">
        </div>

        <div class="form-group">
            <label for="date_heure_debut">Début :</label>
            <input type="datetime-local" id="date_heure_debut" name="date_heure_debut" class="form-control" required value="<?= old('date_heure_debut') ?>">
        </div>

        <div class="form-group">
            <label for="date_heure_fin">Fin :</label>
            <input type="datetime-local" id="date_heure_fin" name="date_heure_fin" class="form-control" required value="<?= old('date_heure_fin') ?>">
        </div>

        <div class="form-group">
            <label for="nb_place">Nombre de places :</label>
            <input type="number" id="nb_place" name="nb_place" class="form-control" required value="<?= old('nb_place') ?>">
        </div>

        <div class="form-group">
            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" class="form-control" required value="<?= old('lieu') ?>">
        </div>

        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" id="image" name="image" class="form-control" required>
        </div>


        <!-- Champs spécifiques Concert -->
        <div id="concertFields" style="display:none;">
            <div class="form-group">
                <label for="artiste">Artiste :</label>
                <input type="text" id="artiste" name="artiste" class="form-control">
            </div>
            <div class="form-group">
                <label for="style_musique">Style de musique :</label>
                <input type="text" id="style_musique" name="style_musique" class="form-control">
            </div>
            <div class="form-group">
                <label for="duree_minutes">Durée (minutes) :</label>
                <input type="number" id="duree_minutes" name="duree_minutes" class="form-control">
            </div>
            <div class="form-group">
                <label for="audio">Fichier audio :</label>
                <input type="file" id="audio" name="audio" class="form-control">
            </div>
        </div>

        <!-- Champs spécifiques Festival -->
        <div id="festivalFields" style="display:none;">
            <div class="form-group">
                <label for="duree_jours">Durée (jours) :</label>
                <input type="number" id="duree_jours" name="duree_jours" class="form-control">
            </div>
            <div class="form-group">
                <label for="nombre_scenes">Nombre de scènes :</label>
                <input type="number" id="nombre_scenes" name="nombre_scenes" class="form-control">
            </div>
        </div>

        <br>
        <button class="btn btn-primary" type="submit">Enregistrer</button>
    </form>

    <br>
    <a href="<?= site_url('evenements') ?>" class="btn btn-default">Retour à la liste</a>
</div>

<script>
document.getElementById('type').addEventListener('change', function () {
    var concertFields = document.getElementById('concertFields');
    var festivalFields = document.getElementById('festivalFields');

    concertFields.style.display = 'none';
    festivalFields.style.display = 'none';

    if (this.value === 'concert') {
        concertFields.style.display = 'block';
    } 
    else if (this.value === 'festival') {
        festivalFields.style.display = 'block';
    }
});
</script>

</body>
</html>
