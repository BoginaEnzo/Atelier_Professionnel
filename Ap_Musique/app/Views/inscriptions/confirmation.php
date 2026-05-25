<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Confirmation de réservation</title>
<link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
<style>
  html, body { margin:0; padding:0; font-family:'Poppins', sans-serif; background:#0d0d0d; color:#c8f7ff; }

  /* Loader overlay */
  .loader-overlay {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#0d0d0d;
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:9999;
  }

  .loader {
    position: relative;
    width:100px;
    height:100px;
    animation: speeder 0.4s linear infinite;
  }
  .loader > span { height:5px; width:35px; background:#00cfff; position:absolute; top:-19px; left:60px; border-radius:2px 10px 1px 0;}
  .base span { position:absolute; width:0; height:0; border-top:6px solid transparent; border-right:100px solid #00cfff; border-bottom:6px solid transparent;}
  .base span:before { content:""; height:22px; width:22px; border-radius:50%; background:#00cfff; position:absolute; right:-110px; top:-16px;}
  .base span:after { content:""; position:absolute; width:0; height:0; border-top:0 solid transparent; border-right:55px solid #00cfff; border-bottom:16px solid transparent; top:-16px; right:-98px;}

  /* Tête ajustée pour coller au corps */
  .face {
      position:absolute;
      height:12px;
      width:20px;
      background:#00cfff;
      border-radius:20px 20px 0 0;
      transform:rotate(-40deg);
      right:-12px; /* Décalage léger pour coller au corps */
      top:-14px;   /* Ajustement vertical pour coller */
  }
  .face:after {
      content:"";
      height:12px;
      width:12px;
      background:#00cfff;
      right:4px;
      top:7px;
      position:absolute;
      transform:rotate(40deg);
      transform-origin:50% 50%;
      border-radius:0 0 0 2px;
  }

  .longfazers { position:absolute; width:100%; height:100%; top:0; left:0; }
  .longfazers span { position:absolute; height:2px; width:20%; background:#00cfff; }
  .longfazers span:nth-child(1) { top:20%; animation: lf 0.6s linear infinite; animation-delay:-5s; }
  .longfazers span:nth-child(2) { top:40%; animation: lf2 0.8s linear infinite; animation-delay:-1s; }
  .longfazers span:nth-child(3) { top:60%; animation: lf3 0.6s linear infinite; }
  .longfazers span:nth-child(4) { top:80%; animation: lf4 0.5s linear infinite; animation-delay:-3s; }

  @keyframes lf { 0%{left:200%;}100%{left:-200%;opacity:0;} }
  @keyframes lf2 { 0%{left:200%;}100%{left:-200%;opacity:0;} }
  @keyframes lf3 { 0%{left:200%;}100%{left:-100%;opacity:0;} }
  @keyframes lf4 { 0%{left:200%;}100%{left:-100%;opacity:0;} }

  @keyframes speeder { 0%{transform:translate(2px,1px) rotate(0deg);} 100%{transform:translate(1px,-2px) rotate(-1deg);} }

  /* Cacher le body tant que le loader est visible */
  body.loaded .loader-overlay { display:none; }
  body.loaded .page-content { display:block; }

  /* Page content cachée par défaut */
  .page-content { display:none; }

  /* Style confirmation et boutons néon */
  .container { max-width:600px; margin:8rem auto; padding:1rem; text-align:center; }
  h2 { font-size:2.5rem; color:#00cfff; font-weight:900; text-shadow:0 0 25px #00cfff; margin-bottom:1.5rem; }
  p { font-size:1.2rem; margin-bottom:1rem; color:#e7fbff; }

  .neon-btn { 
      border:none; outline:none; background-color:#0f0f0f; width:170px; height:55px; 
      font-size:16px; color:#fff; font-weight:600; border-radius:10px; cursor:pointer; 
      position:relative; display:flex; margin:2rem auto; justify-content:center; align-items:center; 
      transition:0.3s; text-decoration:none; overflow:hidden;
  }
  .neon-btn::before { 
      content:""; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); 
      width:108%; height:118%; border-radius:inherit; background:rgba(0,200,255,0.15); 
      box-shadow:0 8px 32px rgba(0,200,255,0.37); z-index:-1; transition:0.3s; 
  }
  .gradient-container { 
      position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); 
      width:108%; height:118%; overflow:hidden; z-index:-2; border-radius:inherit; filter:blur(10px); 
  }
  .gradient { 
      position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); 
      width:120%; aspect-ratio:1; border-radius:50%; 
      background-image:linear-gradient(90deg,#0099ff,#00eaff,#00cfff,#33ddff); 
      animation:rotate 2.5s linear infinite; filter:blur(12px); 
  }
  .neon-btn:hover .gradient-container { filter:blur(5px); }
  .neon-btn:hover { transform:scale(1.05); }

  .label { background:#0d0d0d; width:140px; height:45px; border-radius:20px; text-align:center; line-height:45px; font-weight:600; position:relative; z-index:2; }

  @keyframes rotate { to { transform:translate(-50%,-50%) rotate(360deg); } }

</style>
</head>
<body>

<!-- Loader -->
<div class="loader-overlay">
  <div class="loader">
    <span><span></span><span></span><span></span><span></span></span>
    <div class="base">
      <span></span>
      <div class="face"></div>
    </div>
  </div>
  <div class="longfazers">
    <span></span><span></span><span></span><span></span>
  </div>
</div>

<!-- Contenu confirmation -->
<div class="page-content container">
    <h2>Réservation confirmée !</h2>
    <p>Un email de confirmation vous sera envoyé prochainement.</p>
    <p>Vous allez être redirigé vers la liste des événements.</p>

    <a href="<?= site_url('evenements') ?>" class="neon-btn">
        <span class="label">Retour à la liste</span>
        <span class="gradient-container"><span class="gradient"></span></span>
    </a>
</div>

<script>
  // Affiche la page après 2 secondes et cache le loader
  setTimeout(() => {
    document.body.classList.add('loaded');
  }, 2000);
  setTimeout(() => {
    document.body.classList.add('loaded'); // cache le loader et montre le contenu
    window.location.href = "<?= site_url('evenements') ?>"; // redirige après 2 secondes
}, 5000);

</script>


</body>
</html>
