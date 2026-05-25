<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EventDuZ - Contact</title>
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #0d0d0d;
            color: #fff;
        }

        body {
            padding-top: 100px; /* espace pour que le titre/formulaire ne soit pas caché par la nav */
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

        /* === TITRE PRINCIPAL === */
        .big-title {
            font-size: 3rem;
            text-align: center;
            font-weight: 900;
            color: #00cfff;
            text-shadow: 0 0 25px #00eaff, 0 0 45px #00cfff;
            margin: 3rem 0;
        }

        /* === FORMULAIRE CONTACT === */
        .contact-form {
            max-width: 600px;
            margin: 2rem auto 5rem auto;
            background: rgba(0,0,0,0.6);
            padding: 2rem;
            border-radius: 1.5rem;
            border: 1px solid #00cfff;
            box-shadow: 0 0 15px rgba(0,255,255,0.25);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-form label {
            font-weight: 700;
            color: #00cfff;
        }

        .contact-form input,
        .contact-form textarea {
            background: rgba(15,15,15,0.8);
            border: 1px solid #00a2c9;
            border-radius: 0.8rem;
            padding: 0.8rem 1rem;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #00eaff;
            box-shadow: 0 0 10px #00eaff;
        }

        .contact-form button {
            width: fit-content;
            margin: 1rem auto 0 auto;
        }

        /* BOUTON NEON */
        .neon-btn {
            border: none;
            outline: none;
            background-color: #0f0f0f;
            width: 170px;
            height: 50px;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
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
            transform: scale(1.15) rotate(10deg);
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

    <!-- NAVIGATION -->
    <nav>
        <div class="nav-left">EventDuZ</div>
        <ul class="menu-liste flex gap-8">
            <li><a href="<?= site_url('/') ?>">Accueil</a></li>
            <li><a href="<?= site_url('concerts') ?>">Concerts</a></li>
            <li><a href="<?= site_url('festivals') ?>">Festival</a></li>
            <?php if(session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
                <li><a href="<?= site_url('admin') ?>">Admin</a></li>
            <?php endif; ?>
            <li><a href="<?= site_url('contact') ?>" class="active">Contact</a></li>
        </ul>
        <div class="boutonCoDeco">
            <?php if(session()->get('isLoggedIn')): ?>
                <a href="<?= site_url('logout') ?>">Se déconnecter</a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- TITRE -->
    <h1 class="big-title">Contactez-nous</h1>

    <!-- FORMULAIRE CONTACT -->
    <form action="<?= site_url('contact') ?>" method="post" class="contact-form">
        <label for="name">Nom</label>
        <input type="text" id="name" placeholder="Votre nom">

        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Votre email">

        <label for="message">Message</label>
        <textarea id="message" rows="5" placeholder="Votre message"></textarea>

        <button class="neon-btn">Envoyer</button>
    </form>

    <!-- BACK TO TOP -->
    <div class="btn-back-to-top hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="icone" viewBox="0 0 24 24" fill="none" stroke="#00cfff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="19" x2="12" y2="5"></line>
            <polyline points="5 12 12 5 19 12"></polyline>
        </svg>
    </div>

    <script>
        const btnBackToTop = document.querySelector('.btn-back-to-top');

        window.addEventListener('scroll', () => {
            if(window.scrollY > 300) btnBackToTop.classList.remove('hidden');
            else btnBackToTop.classList.add('hidden');
        });

        btnBackToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
        });
    </script>

</body>
</html>
