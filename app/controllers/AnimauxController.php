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

    
    public function formulaireAchat2()
    {
        Flight::render('form-achat-aliment');
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

    
    public function venteAnimaux() {
        try {

            $nbAnimaux = (int)$_POST['nb_animaux'];
            $idEspece = (int)$_POST['id_espece'];
            $poids = (float)$_POST['poids'];
            $dateVente = $_POST['date_vente'];
            $prix = (float)$_POST['prix_unitaire'];

            if (empty($nbAnimaux) || empty($idEspece) || empty($poids) || empty($dateVente)) {
                throw new \InvalidArgumentException('Tous les champs sont obligatoires');
            }

            $tempModel = new TempModel(Flight::db());
            
            $totalVente = $tempModel->VenteAnimaux(
                $nbAnimaux, 
                $idEspece, 
                $poids, 
                $dateVente, 
                $prix
            );

            if ($totalVente > 0) {
                Flight::redirect('/accueil', [
                ]);
            } else {
                Flight::render('/');
            }

        } catch (\Exception $e) {
           return false;
        }
    }
}


?>