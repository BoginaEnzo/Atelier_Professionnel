<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>EventDuZ - Concerts & Événements</title>
<link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
<style>
html, body {
    margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#fff;
}

/* === NAVIGATION === */
nav { 
    width:100vw; 
    position:fixed; top:0; left:0; 
    z-index:1001; 
    display:flex; justify-content:space-between; align-items:center;
    padding:1.5rem 4rem; 
    background: rgba(0,0,0,0.35); 
    backdrop-filter:blur(6px); 
    border-bottom:1px solid rgba(0,255,255,0.2); 
    animation: slideDown 1s ease forwards;
}
@keyframes slideDown {
    0% { transform: translateY(-100%); opacity:0;}
    100% { transform: translateY(0); opacity:1;}
}

.nav-left { 
    font-weight:900; 
    font-size:2.2rem; 
    letter-spacing:2px; 
    color:#fff; 
    text-shadow:0 0 25px #00cfff, 0 0 35px #00eaff; 
    animation: neonPulse 2s ease-in-out infinite;
}
@keyframes neonPulse {
    0%,100% { text-shadow:0 0 25px #00cfff, 0 0 35px #00eaff; }
    50% { text-shadow:0 0 35px #00eaff, 0 0 55px #00cfff; }
}

.menu-liste a { 
    position:relative; 
    color:#fff; 
    font-weight:700; 
    font-size:1.3rem; 
    padding-bottom:6px; 
    transition:0.3s ease, transform 0.3s ease; 
}
.menu-liste a:hover { transform: translateY(-3px); color:#00eaff; text-shadow:0 0 12px #00cfff; }
.menu-liste a::after { 
    content:""; position:absolute; left:0; bottom:-4px; 
    width:0%; height:3px; background:#00cfff; border-radius:10px; 
    transition:width 0.3s ease; 
}
.menu-liste a:hover::after, .menu-liste a.active::after { width:100%; }

.boutonCoDeco a { 
    background: rgba(0,217,255,0.12); 
    border:1px solid rgba(0,217,255,0.5); 
    color:#00cfff; 
    padding:0.5rem 1.5rem; 
    border-radius:2rem; 
    backdrop-filter:blur(4px); 
    transition:0.3s; 
}
.boutonCoDeco a:hover { transform:scale(1.05); box-shadow:0 0 20px #00eaff, 0 0 35px #00cfff; }

/* TITRE */
.big-title { font-size:3rem; text-align:center; font-weight:900; color:#00cfff; text-shadow:0 0 25px #00eaff,0 0 45px #00cfff; margin:3rem 0; }

/* LIGNES ÉVÉNEMENTS */
.event-line {
    display:flex; align-items:center; justify-content:space-between; 
    background:rgba(0,0,0,0.6); border:1px solid #00cfff; border-radius:15px; padding:1rem; margin:1rem auto; 
    max-width:1100px; color:#c8f7ff; box-shadow:0 0 12px rgba(0,255,255,0.15); transition:0.25s;
}
.event-line:hover { transform:scale(1.02); box-shadow:0 0 20px #00eaff; }

.event-img { width:160px; height:100px; border-radius:10px; object-fit:cover; border:1px solid #00a2c9; margin-right:20px; }
.event-info { flex:1; display:flex; flex-direction:column; gap:4px; }
.event-title { font-size:1.4rem; font-weight:700; color:#00cfff }
.event-desc { font-size:0.95rem; }

/* BOUTONS DROITE */
.event-actions { display:flex; flex-direction:column; gap:10px; }
.neon-btn { border:none; outline:none; background-color:#0f0f0f; width:150px; height:45px; font-size:15px; color:#fff; font-weight:600; border-radius:10px; cursor:pointer; position:relative; display:flex; justify-content:center; align-items:center; text-decoration:none; }
.neon-btn .label {display: block; width: 100%; text-align: center; }

.neon-btn::before { content:""; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:108%; height:118%; border-radius:inherit; background:rgba(0,200,255,0.1); box-shadow:0 8px 20px rgba(0,200,255,0.35); z-index:-1; }
.neon-btn:hover { transform:scale(1.05); }

/* BOUTON AJOUTER */
.add-btn { display:block; margin:0 auto 2rem auto; }

/* BACK TO TOP */
.btn-back-to-top { position: fixed; right: 20px; bottom:30px; height:50px; width:50px; background:#0f0f0f; border-radius:50%; display:flex; justify-content:center; align-items:center; cursor:pointer; box-shadow:0 0 15px #00cfff; transition: transform 0.3s, box-shadow 0.3s; z-index:1000; }
.btn-back-to-top:hover { box-shadow:0 0 25px #00eaff,0 0 45px #00cfff; }
.btn-back-to-top .icone { width:24px; height:24px; stroke:#00cfff; }
</style>
</head>
<body>

<!-- BACK TO TOP -->
<div class="btn-back-to-top hidden">
    <svg xmlns="http://www.w3.org/2000/svg" class="icone" viewBox="0 0 24 24" fill="none" stroke="#00cfff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</div>

<!-- NAVIGATION -->
<nav>
  <div class="nav-left">EventDuZ</div>
  <ul class="menu-liste flex gap-8">
    <li><a href="<?= site_url('/') ?>">Accueil</a></li>
    <li><a href="<?= site_url('concerts') ?>">Concerts</a></li>
    <li><a href="<?= site_url('festivals') ?>">Festival</a></li>
    <?php if(session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
      <li><a href="<?= site_url('admin') ?>" class="active">Admin</a></li>
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

<!-- SECTION PRINCIPALE -->
<div class="fond-blanc-etendu pt-32">
  <h1 class="big-title">Gestion des événements</h1>

  <?php if(session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
    <a href="<?= site_url('evenements/create') ?>" class="neon-btn add-btn">
      <span class="label">+ Ajouter</span>
    </a>
  <?php endif; ?>

  <div class="px-6 max-w-6xl mx-auto mt-6">
    <?php foreach($evenements as $event): ?>
    <div class="event-line">
        <?php if(!empty($event['image'])): ?>
            <img src="<?= base_url('images/' . $event['image']) ?>" alt="<?= esc($event['nom']) ?>" class="event-img">
        <?php endif; ?>
        <div class="event-info">
            <div class="event-title"><?= esc($event['nom']) ?></div>
            <div class="event-desc"><?= esc($event['description']) ?></div>
            <div class="event-desc"><strong>Début :</strong> <?= date('d/m/Y H:i', strtotime($event['date_heure_debut'])) ?><br><strong>Lieu :</strong> <?= ucfirst(esc($event['lieu'])) ?></div>
        </div>
        <div class="event-actions">
            <a href="<?= site_url('evenements/show/' . $event['id_evenement']) ?>" class="neon-btn"><span class="label">Détails</span></a>
            <?php if(session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
                <a href="<?= site_url('evenements/edit/' . $event['id_evenement']) ?>" class="neon-btn"><span class="label">Modifier</span></a>
                <a href="<?= site_url('evenements/delete/' . $event['id_evenement']) ?>" onclick="return confirm('Supprimer cet événement ?');" class="neon-btn"><span class="label">Supprimer</span></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
// Back to top
const btnBackToTop = document.querySelector('.btn-back-to-top');
window.addEventListener('scroll', () => {
    if(window.scrollY > 300) btnBackToTop.classList.remove('hidden'); else btnBackToTop.classList.add('hidden');
});
btnBackToTop.addEventListener('click', () => { window.scrollTo({top:0, left:0, behavior:'smooth'}); });
</script>

</body>
</html>
