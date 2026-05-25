<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Réserver des places</title>
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html, body { margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#c8f7ff; }
        .container { max-width:600px; margin:4rem auto; padding:1rem; }

        h1 { text-align:center; font-size:2.5rem; color:#00cfff; font-weight:900; text-shadow:0 0 25px #00cfff; margin-bottom:2rem; }

        .form-group { margin-bottom:1.5rem; }
        label { display:block; margin-bottom:0.5rem; font-weight:600; color:#00eaff; }
        input { width:100%; padding:0.6rem 1rem; border-radius:0.7rem; border:1px solid #00cfff; background:#0f0f0f; color:#fff; outline:none; }

        .alert { padding:0.8rem 1rem; border-radius:0.7rem; margin-bottom:1rem; font-weight:600; }
        .alert-danger { background:#ff0033; color:#fff; }
        .alert-success { background:#00cfff; color:#0d0d0d; }

        .neon-btn { border:none; outline:none; background-color:#0f0f0f; width:170px; height:55px; font-size:16px; color:#fff; font-weight:600; border-radius:10px; cursor:pointer; position:relative; display:flex; margin:0 auto; justify-content:center; align-items:center; transition:0.3s; text-decoration:none; }
        .neon-btn::before { content:""; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:108%; height:118%; border-radius:inherit; background:rgba(0,200,255,0.15); box-shadow:0 8px 32px rgba(0,200,255,0.37); z-index:-1; transition:0.3s; }
        .gradient-container { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:108%; height:118%; overflow:hidden; z-index:-2; border-radius:inherit; filter:blur(10px); }
        .gradient { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:120%; aspect-ratio:1; border-radius:50%; background-image:linear-gradient(90deg,#0099ff,#00eaff,#00cfff,#33ddff); animation:rotate 2.5s linear infinite; filter:blur(12px); }
        @keyframes rotate { to{transform:translate(-50%,-50%) rotate(360deg);} }
        .label { background:#0d0d0d; width:140px; height:45px; border-radius:20px; text-align:center; line-height:45px; font-weight:600; }
        .neon-btn:hover .gradient-container { filter:blur(5px); }
        .neon-btn:hover { transform:scale(1.05); }
    </style>
</head>
<body>

<div class="container">
    <h1>Réserver des places pour : <?= esc($evenement['nom']) ?></h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= \Config\Services::validation()->listErrors() ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('inscriptions/reserver/' . $evenement['id_evenement']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= old('nom') ?>" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= old('prenom') ?>" required>
        </div>

        <div class="form-group">
            <label for="nombre_places">Nombre de places à réserver (max <?= $places_restantes ?>) :</label>
            <input type="number" id="nombre_places" name="nombre_places"
                   min="1" max="<?= $places_restantes ?>" value="<?= old('nombre_places') ?? 1 ?>" required>
        </div>

        <button type="submit" class="neon-btn">
            <span class="label">Réserver</span>
            <span class="gradient-container"><span class="gradient"></span></span>
        </button>
    </form>
    <br>

    <a href="<?= site_url('evenements/show/' . $evenement['id_evenement']) ?>" class="neon-btn">
        <span class="label">Retour au détail</span>
        <span class="gradient-container"><span class="gradient"></span></span>
    </a>
</div>

</body>
</html>
