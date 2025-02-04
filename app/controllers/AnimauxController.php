<?php

namespace app\controllers;  
use app\Models\TempModel; 
use Flight;

class AnimauxController {

    public function __construct()
    {
        
    }

    public function formulaireAchat()
    {
        Flight::render('form-achat-animaux');
    }

    public function acheterAnimal()
    {
        $capital = Flight::tempModel()->GetCapitalSurDate($_POST["date_achat"]);
        $cout = $_POST['prix_unitaire'] * $_POST['quantite'];
        if ($cout > $capital)
        {
            $data = array();
            $data['error'] = "Il vous manque ".($cout-$capital)." pour effectuer l'operation";
            Flight::render('form-achat-animaux', $data);
        }
        $data = array();
        $data["date"] = $_POST["date_achat"];
        $data["id_espece"] = $_POST["id_espece"];
        $data["poids"] = $_POST["poids"];
        $data["prix_unitaire"] = $_POST["prix_unitaire"];

        Flight::tempModel()->acheterAnimaux($data);
    }

}

?>