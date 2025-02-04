<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Intuitif</title>
    <link rel="stylesheet" href="<?= Flight::get('flight.base_url') ?>/assets/css/capital.css">
</head>
<body>
    <div class="form-container">
        <form action="<?= Flight::get("flight.base_url") ?>/treatment/insertionCapital" method="post">
            <?php
            if(!Flight::tempModel()->GetAllCapitaux()) { ?>
            <input type="hidden" value="03/02/2025" name="date_mouvement">
            <?php } else { ?>
            <input type="date" name="date_mouvement" required>
            <?php }
            ?>
            <label for="number">Entrez votre capital :</label>
            <input type="number" id="number" name="montant" placeholder="capital" required>
            <button type="submit">inserer</button>
        </form>
    </div>
</body>
</html>