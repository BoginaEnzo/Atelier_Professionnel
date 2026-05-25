<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #0d0d0d;
            color: #c8f7ff;
        }

        .container {
            max-width: 450px;
            margin: 6rem auto;
            padding: 2rem;
            background: #0f0f0f;
            border-radius: 1rem;
            box-shadow: 0 0 30px rgba(0,200,255,0.4);
        }

        h1 {
            font-size: 2.5rem;
            color: #00cfff;
            text-align: center;
            font-weight: 900;
            text-shadow: 0 0 25px #00cfff;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #00eaff;
        }

        input {
            width: 100%;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            border: none;
            background: #0d0d0d;
            color: #c8f7ff;
            font-size: 1rem;
            box-shadow: 0 0 10px rgba(0,200,255,0.2) inset;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 15px #00cfff inset;
        }

        .neon-btn {
            display: block;
            width: 100%;
            padding: 0.7rem;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            color: #fff;
            border-radius: 0.7rem;
            border: none;
            cursor: pointer;
            background: #0f0f0f;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
            margin-top: 1rem;
            box-shadow: 0 0 15px #00cfff;
        }

        .neon-btn::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120%; height: 120%;
            border-radius: inherit;
            background: rgba(0,200,255,0.1);
            box-shadow: 0 8px 32px rgba(0,200,255,0.3);
            z-index: -1;
            transition: 0.3s;
        }

        .neon-btn:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px #00eaff, 0 0 45px #00cfff;
        }

        .neon-btn a {
            color: #fff;
            text-decoration: none;
        }

        .alert {
            padding: 0.8rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            text-align: center;
        }

        .alert-danger {
            background: rgba(255,0,0,0.2);
            color: #ff4d4d;
            box-shadow: 0 0 15px rgba(255,0,0,0.3);
        }

        .alert-success {
            background: rgba(0,255,200,0.2);
            color: #00ffea;
            box-shadow: 0 0 15px rgba(0,255,200,0.3);
        }

        p.text-center {
            margin-top: 1.5rem;
            color: #e7fbff;
        }

        p.text-center a {
            color: #00cfff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= site_url('auth/auth') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= old('email') ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button class="neon-btn" type="submit">Se connecter</button>
        </form>

        <p class="text-center">
            Vous n'avez pas de compte ? <a href="<?= site_url('register') ?>">Créer un compte</a>
        </p>
    </div>
</body>
</html>
<!DOCTYPE html>