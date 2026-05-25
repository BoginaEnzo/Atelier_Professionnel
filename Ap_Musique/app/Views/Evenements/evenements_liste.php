<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EventFlow - Nos événements à ne pas manquer</title>
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #0d0d0d;
        }

        /* === NAVIGATION === */
        nav { 
            width: 100vw;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1001;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 4rem;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(0,255,255,0.2);
            animation: slideDown 1s ease forwards;
        }

        @keyframes slideDown {
            0% { transform: translateY(-100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .nav-left { 
            font-weight: 900;
            font-size: 2.2rem;
            letter-spacing: 2px;
            color: #fff;
            text-shadow: 0 0 25px #00cfff, 0 0 35px #00eaff;
            animation: neonPulse 2s ease-in-out infinite;
        }

        @keyframes neonPulse {
            0%,100% { text-shadow: 0 0 25px #00cfff, 0 0 35px #00eaff; }
            50% { text-shadow: 0 0 35px #00eaff, 0 0 55px #00cfff; }
        }

        .menu-liste a {
            position: relative;
            color: #fff;
            font-weight: 700;
            font-size: 1.3rem;
            padding-bottom: 6px;
            transition: 0.3s ease, transform 0.3s ease;
        }

        .menu-liste a:hover {
            transform: translateY(-3px);
            color: #00eaff;
            text-shadow: 0 0 12px #00cfff;
        }

        .menu-liste a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0%;
            height: 3px;
            background: #00cfff;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .menu-liste a:hover::after,
        .menu-liste a.active::after { width: 100%; }

        .boutonCoDeco a {
            background: rgba(0,217,255,0.12);
            border: 1px solid rgba(0,217,255,0.5);
            color: #00cfff;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            backdrop-filter: blur(4px);
            transition: 0.3s;
        }

        .boutonCoDeco a:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #00eaff, 0 0 35px #00cfff;
        }

        /* HEADER VIDEO */
        .header-video {
            width: 100%;
            height: 70vh;
            position: relative;
            overflow: hidden;
        }

        #bgVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.55);
        }

        .hero-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .site-title-hero {
            color: #fff;
            padding: 0.5rem 2rem;
            border: 3px solid #00cfff;
            border-radius: 1rem;
            font-size: 4rem;
            font-weight: 900;
            text-shadow: 0 0 25px #00cfff;
            opacity: 0;
            animation: fadeZoomIn 1.6s ease-out forwards, pulseGlow 2s ease-in-out infinite 1.6s;
        }

        @keyframes fadeZoomIn {
            0% { opacity: 0; transform: scale(0.7); }
            60% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes pulseGlow {
            0%,100% { transform: scale(1); box-shadow: 0 0 25px #00cfff; }
            50% { transform: scale(1.07); box-shadow: 0 0 45px #00eaff; }
        }

        /* SECTION PRINCIPALE */
        .fond-blanc-etendu {
            background: #0d0d0d;
            border-radius: 2.2rem 2.2rem 0 0;
            padding-bottom: 3rem;
            margin-top: -50px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.6);
        }

        .curvy-separator {
            width: 100%;
            height: 100px;
            background: #0d0d0d;
            margin-top: -50px;
            border-bottom-left-radius: 50% 35%;
            border-bottom-right-radius: 50% 35%;
        }

        .big-title {
            font-size: 2.8rem;
            text-align: center;
            font-weight: 900;
            color: #00cfff;
            text-shadow: 0 0 22px #00eaff;
            animation: bounceTitle 1.5s ease-in-out infinite;
        }

        @keyframes bounceTitle {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* CARDS ÉVÉNEMENTS */
        .sunset-card {
            background: linear-gradient(135deg, #00141a, #002733, #00141a);
            border-radius: 1.5rem;
            padding: 1rem;
            border: 2px solid #00cfff;
            text-align: center;
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(0,255,255,0.25);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .sunset-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px #00eaff;
        }

        .card-inner {
            background: rgba(0,0,0,0.6);
            border: 1px solid #00a2c9;
            border-radius: 1rem;
            padding: 1.5rem;
            color: #c8f7ff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            gap: 0.8rem;
        }

        .card-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #00cfff;
        }

        .card-desc { color: #e7fbff; }

        /* BOUTONS NEON */
        .neon-btn {
            border: none;
            outline: none;
            background-color: #0f0f0f;
            width: 170px;
            height: 55px;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            display: flex;
            margin: 0 auto;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
            text-decoration: none;
        }

        .neon-btn::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 108%;
            height: 118%;
            border-radius: inherit;
            background: rgba(0,200,255,0.15);
            box-shadow: 0 8px 32px rgba(0,200,255,0.37);
            z-index: -1;
            transition: 0.3s;
        }

        .gradient-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 108%;
            height: 118%;
            overflow: hidden;
            z-index: -2;
            border-radius: inherit;
            filter: blur(10px);
        }

        .gradient {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            aspect-ratio: 1;
            border-radius: 50%;
            background-image: linear-gradient(90deg, #0099ff,#00eaff,#00cfff,#33ddff);
            animation: rotate 2.5s linear infinite;
            filter: blur(12px);
        }

        @keyframes rotate { to { transform: translate(-50%, -50%) rotate(360deg); } }

        .label {
            background: #0d0d0d;
            width: 140px;
            height: 45px;
            border-radius: 20px;
            text-align: center;
            line-height: 45px;
            font-weight: 600;
        }

        .neon-btn:hover .gradient-container { filter: blur(5px); }
        .neon-btn:hover { transform: scale(1.05); }

        /* BACK TO TOP */
        .btn-back-to-top {
            position: fixed;
            right: 20px;
            bottom: 30px;
            height: 50px;
            width: 50px;
            background: #0f0f0f;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 0 15px #00cfff;
            transition: transform 0.3s, box-shadow 0.3s;
            z-index: 1000;
        }

        .btn-back-to-top:hover {
            transform: scale(1.1);
            box-shadow: 0 0 25px #00eaff, 0 0 45px #00cfff;
        }

        .btn-back-to-top .icone {
            width: 24px;
            height: 24px;
            stroke: #00cfff;
        }
    </style>
</head>
<body>

    <div class="btn-back-to-top hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="icone" viewBox="0 0 24 24" fill="none" stroke="#00cfff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="19" x2="12" y2="5"></line>
            <polyline points="5 12 12 5 19 12"></polyline>
        </svg>
    </div>

    <nav>
        <div class="nav-left">EventDuZ</div>
        <ul class="menu-liste flex gap-8">
            <li><a href="#" class="active">Accueil</a></li>
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

    <div class="header-video">
        <video id="bgVideo" autoplay muted loop playsinline>
            <source src="<?= base_url('Ressource/BOUM.mp4') ?>" type="video/mp4" />
        </video>
        <div class="hero-wrapper">
            <div class="site-title-hero">EventDuZ</div>
        </div>
    </div>

    <div class="fond-blanc-etendu">
        <div class="curvy-separator"></div>
        <h1 class="big-title">Nos Événements À Ne Pas Manquer</h1>

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
                                <?= date('d/m/Y H:i', strtotime($event['date_heure_debut'])) ?><br>
                                <?= ucfirst(esc($event['lieu'])) ?>
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

    <script>
        const btnBackToTop = document.querySelector('.btn-back-to-top');

        window.addEventListener('scroll', () => {
            if(window.scrollY > 300) {
                btnBackToTop.classList.remove('hidden');
            } else {
                btnBackToTop.classList.add('hidden');
            }
        });

        btnBackToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>
