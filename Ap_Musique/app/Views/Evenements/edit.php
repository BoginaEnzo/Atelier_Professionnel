<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Modifier un événement</title>
<link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
<style>
  html, body { margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#c8f7ff; }
  .container { max-width:900px; margin:5rem auto 3rem auto; padding:1rem; }
  h1 { text-align:center; font-size:3rem; color:#00cfff; font-weight:900; text-shadow:0 0 25px #00cfff; margin-bottom:2rem; }

  .panel { background:linear-gradient(135deg,#00141a,#002733,#00141a); border-radius:1.5rem; padding:2rem; margin-bottom:2rem; border:2px solid #00cfff; box-shadow:0 0 15px rgba(0,255,255,0.25); }
  .panel-heading { font-size:1.6rem; font-weight:700; color:#00cfff; margin-bottom:1rem; text-align:center; text-shadow:0 0 18px #00cfff; }
  label { font-weight:600; }
  input, select { width:100%; padding:0.5rem 0.7rem; border-radius:0.7rem; border:1px solid #00cfff; background:rgba(0,0,0,0.6); color:#c8f7ff; margin-bottom:0.5rem; }
  input:focus, select:focus { outline:none; box-shadow:0 0 8px #00eaff; border-color:#00eaff; }

  .neon-btn { border:none; outline:none; background-color:#0f0f0f; width:180px; height:55px; font-size:16px; color:#fff; font-weight:600; border-radius:10px; cursor:pointer; position:relative; display:flex; justify-content:center; align-items:center; margin:0.5rem auto; text-decoration:none; }
  .neon-btn::before { content:""; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:108%; height:118%; border-radius:inherit; background:rgba(0,200,255,0.15); box-shadow:0 8px 32px rgba(0,200,255,0.37); z-index:-1; transition:0.3s; }
  .gradient-container { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:108%; height:118%; overflow:hidden; z-index:-2; border-radius:inherit; filter:blur(10px); }
  .gradient { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:120%; aspect-ratio:1; border-radius:50%; background-image:linear-gradient(90deg,#0099ff,#00eaff,#00cfff,#33ddff); animation:rotate 2.5s linear infinite; filter:blur(12px); }
  .label { background:#0d0d0d; width:140px; height:45px; border-radius:20px; text-align:center; line-height:45px; font-weight:600; z-index:2; }
  .neon-btn:hover .gradient-container { filter:blur(5px); }
  .neon-btn:hover { transform:scale(1.05); }
  @keyframes rotate { to{transform:translate(-50%,-50%) rotate(360deg);} }

  img.event-img { max-width:200px; display:block; margin-bottom:10px; border-radius:1rem; border:1px solid #00cfff; box-shadow:0 0 12px rgba(0,200,255,0.3); }
  .text-danger { color:#ff0033; font-weight:600; }
</style>
</head>
<body>

<div class="container">
    <h1>Modifier un événement</h1>

    <form action="<?= site_url('evenements/update/' . $evenement['id_evenement']) ?>" 
          method="post" 
          enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="panel">
            <div class="panel-heading">Informations générales</div>

            <label for="type">Type :</label>
            <select id="type" name="type" required>
                <option value="">-- Choisir un type --</option>
                <option value="concert" <?= $evenement['type'] === 'concert' ? 'selected' : '' ?>>Concert</option>
                <option value="festival" <?= $evenement['type'] === 'festival' ? 'selected' : '' ?>>Festival</option>
            </select>
            <?php if (isset($validation) && $validation->hasError('type')): ?>
                <span class="text-danger"><?= $validation->getError('type') ?></span>
            <?php endif; ?>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required value="<?= esc($evenement['nom']) ?>">
            <?php if (isset($validation) && $validation->hasError('nom')): ?>
                <span class="text-danger"><?= $validation->getError('nom') ?></span>
            <?php endif; ?>

            <label for="description">Description :</label>
            <input type="text" id="description" name="description" required value="<?= esc($evenement['description']) ?>">
            <?php if (isset($validation) && $validation->hasError('description')): ?>
                <span class="text-danger"><?= $validation->getError('description') ?></span>
            <?php endif; ?>

            <label for="date_heure_debut">Date et heure de début :</label>
            <input type="datetime-local" id="date_heure_debut" name="date_heure_debut" required value="<?= str_replace(' ', 'T', esc($evenement['date_heure_debut'])) ?>">
            <?php if (isset($validation) && $validation->hasError('date_heure_debut')): ?>
                <span class="text-danger"><?= $validation->getError('date_heure_debut') ?></span>
            <?php endif; ?>

            <label for="date_heure_fin">Date et heure de fin :</label>
            <input type="datetime-local" id="date_heure_fin" name="date_heure_fin" required value="<?= str_replace(' ', 'T', esc($evenement['date_heure_fin'])) ?>">
            <?php if (isset($validation) && $validation->hasError('date_heure_fin')): ?>
                <span class="text-danger"><?= $validation->getError('date_heure_fin') ?></span>
            <?php endif; ?>

            <label for="nb_place">Nombre de places :</label>
            <input type="number" id="nb_place" name="nb_place" required value="<?= esc($evenement['nb_place']) ?>">
            <?php if (isset($validation) && $validation->hasError('nb_place')): ?>
                <span class="text-danger"><?= $validation->getError('nb_place') ?></span>
            <?php endif; ?>

            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" required value="<?= esc($evenement['lieu']) ?>">
            <?php if (isset($validation) && $validation->hasError('lieu')): ?>
                <span class="text-danger"><?= $validation->getError('lieu') ?></span>
            <?php endif; ?>

            <label for="image">Image :</label>
            <?php if(!empty($evenement['image'])): ?>
                <img src="<?= base_url('images/' . esc($evenement['image'])) ?>" alt="<?= esc($evenement['nom']) ?>" class="event-img">
            <?php endif; ?>
            <input type="file" id="image" name="image">
            <?php if (isset($validation) && $validation->hasError('image')): ?>
                <span class="text-danger"><?= $validation->getError('image') ?></span>
            <?php endif; ?>
        </div>

        <!-- Champs spécifiques Concert -->
        <div id="concertFields" style="display: <?= $evenement['type'] === 'concert' ? 'block' : 'none' ?>;" class="panel">
            <div class="panel-heading">Détails Concert</div>
            <label for="artiste">Artiste :</label>
            <input type="text" id="artiste" name="artiste" value="<?= esc($details['artiste'] ?? '') ?>">
            <label for="style_musique">Style de musique :</label>
            <input type="text" id="style_musique" name="style_musique" value="<?= esc($details['style_musique'] ?? '') ?>">
            <label for="duree_minutes">Durée (minutes) :</label>
            <input type="number" id="duree_minutes" name="duree_minutes" value="<?= esc($details['duree_minutes'] ?? '') ?>">
            <label for="audio">Fichier audio :</label>
            <input type="file" id="audio" name="audio">
        </div>

        <!-- Champs spécifiques Festival -->
        <div id="festivalFields" style="display: <?= $evenement['type'] === 'festival' ? 'block' : 'none' ?>;" class="panel">
            <div class="panel-heading">Détails Festival</div>
            <label for="duree_jours">Durée (jours) :</label>
            <input type="number" id="duree_jours" name="duree_jours" value="<?= esc($details['duree_jours'] ?? '') ?>">
            <label for="nombre_scenes">Nombre de scènes :</label>
            <input type="number" id="nombre_scenes" name="nombre_scenes" value="<?= esc($details['nombre_scenes'] ?? '') ?>">
        </div>

        <a href="<?= site_url('evenements') ?>" class="neon-btn">
            <span class="label">Annuler</span>
            <span class="gradient-container"><span class="gradient"></span></span>
        </a>
        <button type="submit" class="neon-btn">
            <span class="label">Enregistrer</span>
            <span class="gradient-container"><span class="gradient"></span></span>
        </button>
    </form>
</div>

<script>
document.getElementById('type').addEventListener('change', function () {
    var concertFields = document.getElementById('concertFields');
    var festivalFields = document.getElementById('festivalFields');

    if (this.value === 'concert') {
        concertFields.style.display = 'block';
        festivalFields.style.display = 'none';
    } else if (this.value === 'festival') {
        concertFields.style.display = 'none';
        festivalFields.style.display = 'block';
    } else {
        concertFields.style.display = 'none';
        festivalFields.style.display = 'none';
    }
});
</script>
</body>
</html>
