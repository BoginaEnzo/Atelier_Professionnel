<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription</title>
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin:0; padding:0;
            font-family:'Poppins', sans-serif;
            background:#0d0d0d;
            color:#c8f7ff;
        }
        .container {
            max-width:450px;
            margin:6rem auto;
            padding:2rem;
            background: #0f0f0f;
            border-radius:1rem;
            box-shadow: 0 0 30px rgba(0,200,255,0.4);
        }
        h1 {
            font-size:2.5rem;
            color:#00cfff;
            text-align:center;
            font-weight:900;
            text-shadow:0 0 25px #00cfff;
            margin-bottom:1.5rem;
        }
        .form-group {
            margin-bottom:1.5rem;
        }
        label {
            display:block;
            margin-bottom:0.5rem;
            font-weight:600;
            color:#00eaff;
        }
        input {
            width:100%;
            padding:0.6rem 1rem;
            border-radius:0.5rem;
            border:none;
            background:#0d0d0d;
            color:#c8f7ff;
            font-size:1rem;
            box-shadow: 0 0 10px rgba(0,200,255,0.2) inset;
            transition:0.3s;
        }
        input:focus {
            outline:none;
            box-shadow: 0 0 15px #00cfff inset;
        }
        .neon-btn {
            display:block;
            width:100%;
            padding:0.7rem;
            font-size:1rem;
            font-weight:600;
            text-align:center;
            color:#fff;
            border-radius:0.7rem;
            border:none;
            cursor:pointer;
            background:#0f0f0f;
            position:relative;
            overflow:hidden;
            transition:0.3s;
            margin-top:1rem;
            box-shadow: 0 0 15px #00cfff;
        }
        .neon-btn::before {
            content:"";
            position:absolute;
            top:50%; left:50%;
            transform:translate(-50%,-50%);
            width:120%; height:120%;
            border-radius:inherit;
            background:rgba(0,200,255,0.1);
            box-shadow:0 8px 32px rgba(0,200,255,0.3);
            z-index:-1;
            transition:0.3s;
        }
        .neon-btn:hover {
            transform:scale(1.03);
            box-shadow: 0 0 25px #00eaff, 0 0 45px #00cfff;
        }
        .help-block {
            color:#ff4d4d;
            font-weight:600;
            margin-top:0.3rem;
            display:block;
        }
        .text-center {
            text-align:center;
            margin-top:1.5rem;
            color:#e7fbff;
        }
        .text-center a {
            color:#00cfff;
            text-decoration:underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Créer un compte Client</h1>

    <form action="<?= site_url('register') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group <?= (isset($validation) && $validation->hasError('prenom')) ? 'has-error' : '' ?>">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= old('prenom') ?>" required />
            <?php if (isset($validation) && $validation->hasError('prenom')): ?>
                <span class="help-block"><?= $validation->getError('prenom') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= (isset($validation) && $validation->hasError('nom')) ? 'has-error' : '' ?>">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= old('nom') ?>" required />
            <?php if (isset($validation) && $validation->hasError('nom')): ?>
                <span class="help-block"><?= $validation->getError('nom') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= (isset($validation) && $validation->hasError('email')) ? 'has-error' : '' ?>">
            <label for="email">Adresse Email :</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required />
            <?php if (isset($validation) && $validation->hasError('email')): ?>
                <span class="help-block"><?= $validation->getError('email') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= (isset($validation) && $validation->hasError('password')) ? 'has-error' : '' ?>">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required />
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <span class="help-block"><?= $validation->getError('password') ?></span>
            <?php endif; ?>
        </div>

        <button class="neon-btn" type="submit">S'inscrire</button>
    </form>

    <div class="text-center">
        Vous avez déjà un compte ? <a href="<?= site_url('login') ?>">Connectez-vous ici</a>
    </div>
</div>
</body>
</html>
