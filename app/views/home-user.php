<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Agency</title>
    <link rel="stylesheet" href="<?= Flight::get('flight.base_url') ?>assets/css/header-footer.css">
    <link rel="stylesheet" href="<?= Flight::get('flight.base_url') ?>assets/css/home.css">
</head>
<body>

    <?php include("header.php"); ?>

    <main class="container">

        <h2>Nos Propriétés Disponibles</h2>
        <div class="properties-grid">
            <div class="property-card">
                <img src="<?= Flight::get('flight.base_url') ?>assets/images/appartement_centre_ville.jpg" alt="Maison moderne">
                <div class="property-details">
                    <h3>Villa Contemporaine</h3>
                    <p>Location: Paris, 16ème arrondissement</p>
                    <p>Belle villa moderne avec vue panoramique</p>
                    <p class="price">250€ / nuit</p>
                </div>
            </div>

            <div class="property-card">
                <img src="<?= Flight::get('flight.base_url') ?>assets/images/maison_zone_residentielle.jpg" alt="Appartement de luxe">
                <div class="property-details">
                    <h3>Appartement de Luxe</h3>
                    <p>Location: Lyon, Centre-ville</p>
                    <p>Appartement élégant avec terrasse privée</p>
                    <p class="price">180€ / nuit</p>
                </div>
            </div>

            <div class="property-card">
                <img src="<?= Flight::get('flight.base_url') ?>assets/images/villa_bord_de_mer.jpg" alt="Chalet en montagne">
                <div class="property-details">
                    <h3>Chalet Alpin</h3>
                    <p>Location: Chamonix, Haute-Savoie</p>
                    <p>Chalet confortable avec vue sur les montagnes</p>
                    <p class="price">300€ / nuit</p>
                </div>
            </div>
        </div>
</main>

    <?php include("footer.php"); ?>

</body>
</html>