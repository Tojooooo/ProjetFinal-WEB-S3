<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo Flight::get('flight.base_url'); ?>/capitauxControl" method="post">
        Elevage:
        <br><br>
    Date:
    <input type="text" name="data_mouvement">
    <br>
    <br>
    Montant:
    <input type="number" name="montant">
    <br><br>
    <input type="submit" value="Valideo">
    </form>
</body>
</html>