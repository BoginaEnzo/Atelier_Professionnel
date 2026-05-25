<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Détails de l'événement</title>
<link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

<style>
/* === GENERAL === */
html, body { margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#c8f7ff; }

/* NAV */
nav { width:100vw; position:absolute; top:0; left:0; z-index:1001;
      display:flex; justify-content:space-between; padding:1.2rem 3rem;
      background: rgba(0,0,0,0.25); backdrop-filter:blur(6px);
      border-bottom:1px solid rgba(0,255,255,0.2); }

.nav-left { font-weight:800; font-size:1.7rem; letter-spacing:2px;
            color:#fff; text-shadow:0 0 18px #fff; }

.menu-liste a { position:relative; color:#fff; font-weight:600;
                padding-bottom:4px; transition:0.3s ease; }

.menu-liste a::after {
    content:""; position:absolute; left:0; bottom:-3px; width:0%; height:2px;
    background:#00cfff; border-radius:10px; transition:width 0.3s ease;
}

.menu-liste a:hover::after,
.menu-liste a.active::after { width:100%; }

.boutonCoDeco a {
    background: rgba(0,217,255,0.12);
    border:1px solid rgba(0,217,255,0.5);
    color:#00cfff; padding:0.45rem 1.2rem; border-radius:2rem;
    backdrop-filter:blur(4px);
}

/* CONTAINER */
.container { max-width:1200px; margin:9rem auto 3rem auto; padding:1rem; }
h1 { text-align:center; font-size:3rem; color:#00cfff; font-weight:900;
     text-shadow:0 0 25px #00cfff; margin-bottom:2rem; }

/* GRID */
.grid-2col { display:grid; grid-template-columns:1fr 1fr; gap:2.5rem; }

/* PANEL */
.panel {
    background: linear-gradient(135deg,#00141a,#002733,#00141a);
    border-radius:1.5rem; padding:2rem;
    border:2px solid #00cfff;
    box-shadow:0 0 15px rgba(0,255,255,0.25);
}
.panel-heading {
    font-size:1.6rem; font-weight:700; color:#00cfff;
    margin-bottom:1.3rem; text-align:center;
}

/* IMAGE */
.event-img {
    width:100%; border-radius:1rem;
    border:1px solid #00cfff;
    box-shadow:0 0 12px rgba(0,200,255,0.3);
}

/* INFOS */
dl { display:grid; grid-template-columns:160px 1fr; gap:0.9rem; }
dt { font-weight:700; color:#00eaff; }
dd { margin:0; }

/* AUDIO PLAYER */
.card {
    background:#0f0f0f; border-radius:10px; padding:15px;
    border:2px solid #00cfff; box-shadow:0 0 20px rgba(0,200,255,0.4);
}
.top{display:flex;gap:10px;}
.pfp{height:50px;width:50px;background:#00141a;border-radius:8px;
     display:flex;justify-content:center;align-items:center;}

.playing{display:flex;gap:2px;width:35px;height:20px;}
.greenline{background-color:#00cfff;height:20px;width:2px;
           transform-origin:bottom;display:none;}

@keyframes playing {
 0%{transform:scaleY(.1);} 
 50%{transform:scaleY(.9);} 
 100%{transform:scaleY(.1);}
}
.line-1{animation:playing 1s infinite ease-in-out .2s;}
.line-2{animation:playing 1s infinite ease-in-out .4s;}
.line-3{animation:playing 1s infinite ease-in-out .6s;}
.line-4{animation:playing 1s infinite ease-in-out 0s;}
.line-5{animation:playing 1s infinite ease-in-out .5s;}

.controls{display:flex;justify-content:center;margin-top:12px;}
.controls svg#playBtn{
    cursor:pointer; fill:#00cfff; width:45px; height:45px;
    background:#00141a; border:2px solid #00cfff;
    border-radius:50%; padding:5px; transition:0.3s;
}
.controls svg#playBtn:hover{
    fill:#00eaff; box-shadow:0 0 12px #00cfff,0 0 25px #00eaff;
    transform:scale(1.1);
}

/* === REAL NEON BUTTON (TON ANCIEN) === */
.neon-btn {
    border:none; outline:none; background-color:#0f0f0f;
    width:170px; height:55px; font-size:16px; color:#fff;
    font-weight:600; border-radius:10px; cursor:pointer;
    position:relative; display:flex; margin:0 auto;
    justify-content:center; align-items:center; transition:0.3s;
    text-decoration:none;
}

.neon-btn::before {
    content:""; position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:108%; height:118%; border-radius:inherit;
    background:rgba(0,200,255,0.15);
    box-shadow:0 8px 32px rgba(0,200,255,0.37);
    z-index:-1; transition:0.3s;
}

.gradient-container {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:108%; height:118%; overflow:hidden;
    z-index:-2; border-radius:inherit; filter:blur(10px);
}

.gradient {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:120%; aspect-ratio:1; border-radius:50%;
    background-image:linear-gradient(90deg,#0099ff,#00eaff,#00cfff,#33ddff);
    animation:rotate 2.5s linear infinite;
    filter:blur(12px);
}

@keyframes rotate {
  to { transform:translate(-50%,-50%) rotate(360deg); }
}

.label {
    background:#0d0d0d; width:140px; height:45px;
    border-radius:20px; text-align:center; line-height:45px;
    font-weight:600; position:relative; z-index:3;
}

.neon-btn:hover .gradient-container { filter:blur(5px); }
.neon-btn:hover { transform:scale(1.05); }

/* BADGE COMPLET */
.badge-complet {
    background:#ff0033; padding:0.8rem 1.5rem;
    border-radius:1rem; font-size:1.2rem; display:inline-block;
    color:#fff; font-weight:700; text-align:center; margin:auto;
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-left">EventDuZ</div>

  <ul class="menu-liste flex gap-8">
    <li><a href="<?= site_url('/') ?>" class="active">Accueil</a></li>
    <li><a href="<?= site_url('concerts') ?>">Concerts</a></li>
    <li><a href="<?= site_url('festivals') ?>">Festival</a></li>
    <?php if(session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
      <li><a href="<?= site_url('admin') ?>">Admin</a></li>
    <?php endif; ?>
    <li><a href="<?= site_url('contact') ?>">Contact</a></li>
  </ul>

  <div class="boutonCoDeco">
    <?php if(session()->get('isLoggedIn')): ?>
      <a href="<?= site_url('logout') ?>">Se déconnecter</a>
    <?php else: ?>
      <a href="<?= site_url('login') ?>">Se connecter</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container">

<h1>Détails de l'événement</h1>

<div class="grid-2col">

    <!-- GAUCHE : INFOS -->
    <div>
        <div class="panel">
            <div class="panel-heading">Informations générales</div>

            <?php if (!empty($evenement['image'])): ?>
            <img src="<?= base_url('images/' . esc($evenement['image'])) ?>" class="event-img">
            <?php endif; ?>
            <br>
            <dl>
                <dt>Nom :</dt><dd><?= esc($evenement['nom']) ?></dd>
                <dt>Description :</dt><dd><?= esc($evenement['description']) ?></dd>
                <dt>Début :</dt><dd><?= date('d/m/Y H:i', strtotime($evenement['date_heure_debut'])) ?></dd>
                <dt>Lieu :</dt><dd><?= ucfirst(esc($evenement['lieu'])) ?></dd>
                <dt>Places :</dt><dd><?= esc($evenement['nb_place']) ?></dd>
            </dl>
        </div>
    </div>

    <div>
        <div class="panel">
            <div class="panel-heading">Détails spécifiques</div>
            <dl>
            <?php if ($evenement['type']==='concert'): ?>
                <dt>Artiste :</dt><dd><?= esc($details['artiste']) ?></dd>
                <dt>Style :</dt><dd><?= esc($details['style_musique']) ?></dd>
                <dt>Durée :</dt><dd><?= esc($details['duree_minutes']) ?> min</dd>
            <?php else: ?>
                <dt>Durée :</dt><dd><?= esc($details['duree_jours']) ?> jours</dd>
                <dt>Scènes :</dt><dd><?= esc($details['nombre_scenes']) ?></dd>
            <?php endif; ?>
            </dl>
        </div>

        <br>
        <?php if ($evenement['type']==='concert' && !empty($details['audio'])): ?>
        <div class="card">
            <div class="top">
                <div class="pfp">
                    <div class="playing" id="bars">
                        <div class="greenline line-1"></div>
                        <div class="greenline line-2"></div>
                        <div class="greenline line-3"></div>
                        <div class="greenline line-4"></div>
                        <div class="greenline line-5"></div>
                    </div>
                </div>
                <div>
                    <p class="title-1"><?= esc($details['artiste']) ?></p>
                </div>
            </div>

            <div class="controls">
                <svg id="playBtn" viewBox="0 0 24 24">
                    <path d="M12 21.6a9.6 9.6 0 1 0 0-19.2 9.6 9.6 0 0 0 0 19.2Zm-1.2-10.8v4.8l4.2-2.4-4.2-2.4Z"/>
                </svg>
            </div>

            <audio id="audioPlayer" src="<?= base_url('Ressource/' . $details['audio']) ?>"></audio>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- BOUTONS -->
<div class="mt-6 flex flex-col gap-3">
    <?php $places_restantes = $evenement['nb_place'] - ($evenement['places_reservees'] ?? 0); ?>

    <?php if ($places_restantes > 0): ?>
        <a href="<?= site_url('inscriptions/formulaire/' . $evenement['id_evenement']) ?>" class="neon-btn">
            <span class="label">Réserver</span>
            <span class="gradient-container"><span class="gradient"></span></span>
        </a>
    <?php else: ?>
        <span class="badge-complet">Complet</span>
    <?php endif; ?>

    <a href="<?= site_url('evenements') ?>" class="neon-btn">
        <span class="label">Retour à la liste</span>
        <span class="gradient-container"><span class="gradient"></span></span>
    </a>
</div>

</div>

<script>
const audio = document.getElementById('audioPlayer');
const playBtn = document.getElementById('playBtn');
const bars = document.getElementById('bars');

playBtn.addEventListener('click', () => {
    if(audio.paused){
        audio.play();
        bars.querySelectorAll('.greenline').forEach(b => b.style.display = 'block');

        playBtn.innerHTML = `<path d="M6 4h4v16H6V4Zm8 0h4v16h-4V4Z"/>`;
    } else {
        audio.pause();
        bars.querySelectorAll('.greenline').forEach(b => b.style.display = 'none');

        playBtn.innerHTML = `<path d="M12 21.6a9.6 9.6 0 1 0 0-19.2 9.6 9.6 0 0 0 0 19.2Zm-1.2-10.8v4.8l4.2-2.4-4.2-2.4Z"/>`;
    }
});
</script>

</body>
</html>
