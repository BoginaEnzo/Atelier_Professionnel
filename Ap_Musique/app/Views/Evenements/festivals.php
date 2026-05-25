<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>EventDuZ - Concerts & Événements</title>
<link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
<style>
/* === GLOBAL === */
html, body { margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#fff; overflow-x:hidden; }

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

/* === TITRE PRINCIPAL === */
.big-title { 
    font-size:3rem; text-align:center; font-weight:900; color:#00cfff; 
    text-shadow:0 0 25px #00eaff, 0 0 45px #00cfff; margin:3rem 0; 
    animation: bounceTitle 1.5s ease-in-out infinite, glowTitle 2s ease-in-out infinite alternate;
}
@keyframes bounceTitle { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
@keyframes glowTitle {
    0% { text-shadow:0 0 25px #00cfff, 0 0 45px #00eaff; }
    50% { text-shadow:0 0 35px #00eaff, 0 0 60px #00cfff; }
    100% { text-shadow:0 0 25px #00cfff, 0 0 45px #00eaff; }
}

/* === CARDS === */
.sunset-card { 
    background: linear-gradient(135deg,#00141a,#002733,#00141a); 
    border-radius:1.5rem; 
    padding:1rem; 
    border:2px solid #00cfff; 
    text-align:center; 
    box-shadow:0 0 15px rgba(0,255,255,0.25); 
    display:flex; flex-direction:column; 
    height:100%; 
    transition:0.5s; 
    transform: perspective(1000px) rotateY(0deg); 
    animation: fadeUp 1s ease forwards; 
}
.sunset-card:hover { 
    transform: perspective(1000px) rotateY(10deg) translateY(-10px); 
    box-shadow:0 0 35px #00eaff, 0 0 60px #00cfff; 
}
@keyframes fadeUp { 0%{opacity:0; transform:translateY(50px);} 100%{opacity:1; transform:translateY(0);} }

.card-inner { 
    background:rgba(0,0,0,0.65); 
    border:1px solid #00a2c9; border-radius:1rem; padding:1.5rem; 
    color:#c8f7ff; display:flex; flex-direction:column; justify-content:space-between; height:100%; gap:1rem; transition:0.3s; 
}
.card-inner:hover { transform: scale(1.02); }

.card-title { 
    font-size:1.8rem; font-weight:800; color:#00cfff; text-shadow:0 0 12px #00eaff; 
    animation: titlePop 0.8s ease forwards; 
}
@keyframes titlePop { 0%{transform: scale(0.9); opacity:0;} 100%{transform: scale(1); opacity:1;} }

.card-desc { color:#e7fbff; line-height:1.4rem; }
.card-inner img { border-radius:12px; transition:0.5s; }
.card-inner img:hover { transform: scale(1.05) rotate(1deg); box-shadow:0 0 25px #00cfff; }

/* === NEON BUTTONS COMME DANS DETAIL === */
.neon-btn {
    border:none; outline:none; background-color:#0f0f0f;
    width:170px; height:55px; font-size:16px; color:#fff;
    font-weight:600; border-radius:10px; cursor:pointer;
    position:relative; display:flex; margin:0 auto;
    justify-content:center; align-items:center; transition:0.3s; text-decoration:none;
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
    animation:rotate 2.5s linear infinite; filter:blur(12px);
}

@keyframes rotate { to{transform:translate(-50%,-50%) rotate(360deg);} }

.neon-btn .label { position:relative; z-index:2; background:#0d0d0d; width:140px; height:45px; border-radius:20px; text-align:center; line-height:45px; font-weight:600; }
.neon-btn:hover { transform: scale(1.05); }
.neon-btn:hover .gradient-container { filter:blur(5px); }


/* === BACK TO TOP === */
.btn-back-to-top { 
    position: fixed; right: 20px; bottom: 30px; height: 50px; width: 50px; 
    background: #0f0f0f; border-radius: 50%; display:flex; justify-content:center; align-items:center; 
    cursor:pointer; box-shadow: 0 0 15px #00cfff; transition: transform 0.3s, box-shadow 0.3s; z-index:1000; 
}
.btn-back-to-top:hover { box-shadow:0 0 25px #00eaff,0 0 45px #00cfff; }
.btn-back-to-top .icone { width:24px; height:24px; stroke:#00cfff; }

/* === FADE IN ANIMATION POUR CHAQUE CARTE === */
.sunset-card { opacity:0; animation:fadeInCard 0.8s forwards; }
.sunset-card:nth-child(1){ animation-delay:0.2s; }
.sunset-card:nth-child(2){ animation-delay:0.4s; }
.sunset-card:nth-child(3){ animation-delay:0.6s; }
.sunset-card:nth-child(4){ animation-delay:0.8s; }
.sunset-card:nth-child(5){ animation-delay:1s; }
@keyframes fadeInCard { 0%{opacity:0; transform:translateY(30px);} 100%{opacity:1; transform:translateY(0);} }
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
    <li><a href="<?= site_url('festivals') ?>" class="active">Festival</a></li>
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

<!-- SECTION PRINCIPALE -->
<div class="fond-blanc-etendu pt-32">
  <h1 class="big-title">Les festivals</h1>


  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6 max-w-6xl mx-auto mt-6">
    <?php foreach($evenements as $event): ?>
      <div class="sunset-card h-full">
        <div class="card-inner">
          <div>
            <?php if(!empty($event['image'])): ?>
              <img src="<?= base_url('images/' . $event['image']) ?>" alt="<?= esc($event['nom']) ?>" class="rounded-lg mb-4 w-full h-48 object-cover">
            <?php endif; ?>
            <div class="card-title"><?= esc($event['nom']) ?></div>
            <p class="card-desc"><?= esc($event['description']) ?></p>
            <p class="card-desc">
              <strong>Début :</strong> <?= date('d/m/Y H:i', strtotime($event['date_heure_debut'])) ?><br>
              <strong>Lieu :</strong> <?= ucfirst(esc($event['lieu'])) ?>
            </p>
          </div>
          <div class="mt-4 flex flex-col gap-2">
            <a href="<?= site_url('evenements/show/' . $event['id_evenement']) ?>" class="neon-btn">
              <span class="label">Détails</span>
              <span class="gradient-container"><span class="gradient"></span></span>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- JS BACK TO TOP + FADE IN SCROLL -->
<script>
const btnBackToTop = document.querySelector('.btn-back-to-top');
window.addEventListener('scroll', () => {
    if(window.scrollY > 300) btnBackToTop.classList.remove('hidden');
    else btnBackToTop.classList.add('hidden');

    // Fade-in animation on scroll
    document.querySelectorAll('.sunset-card').forEach(card=>{
        const rect = card.getBoundingClientRect();
        if(rect.top < window.innerHeight - 50){
            card.style.opacity='1';
            card.style.transform='translateY(0)';
        }
    });
});
btnBackToTop.addEventListener('click', () => {
    window.scrollTo({ top:0, left:0, behavior:'smooth' });
});
</script>

</body>
</html>
