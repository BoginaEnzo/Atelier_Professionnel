<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Bonjour <?= esc($utilisateur['prenom']) ?> <?= esc($utilisateur['nom']) ?>,</p>

    <p>Votre réservation de <?= esc($nombre_places) ?> place(s) pour l'événement "<?= esc($evenement['nom']) ?>" a bien été prise en compte.</p>

    <p>Date de début : <?= esc($evenement['date_heure_debut']) ?></p>
    <p>Date de fin : <?= esc($evenement['date_heure_fin']) ?></p>
    <p>Lieu : <?= esc($evenement['lieu']) ?></p>

    <p>Merci et à bientôt !</p>

</body>
</html>