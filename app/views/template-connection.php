<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Location Saisonnière</title>
    <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/assets/css/style_connection.css">
    <title><?php echo $title; ?> - Agence immobilière</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">Location Saisonnière</div>
        </header>

        <main>
            <?php include($page.'.php'); ?>
        </main>

        <footer>
            <p>&copy; <?= date('Y') ?> Agence de Location Saisonnière</p>
        </footer>
    </div>
</body>
</html>
