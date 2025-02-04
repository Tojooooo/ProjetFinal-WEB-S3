<?php
    use app\models\TempModel;

    $tempModel = new TempModel(Flight::db());
//    $tempModel->NourrirAnimal(1,50,2,'2020-11-13');

?>
<!DOCTYPE>
<html>
    <head>

    </head>
    <body>
        <h1>Tongasoa</h1>

    <p>Entrez la date</p>
        <form action="<?=Flight::get('flight.base_url'); ?>/nourriture" method="post">
            <input type="date" name="date">
            <input type="submit" value="Valider">
        </form>
    </body>
</html>

