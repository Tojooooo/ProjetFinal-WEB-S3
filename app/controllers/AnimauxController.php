<?php

namespace app\controllers;  
use app\Models\TempModel; 
use Flight;

class AnimauxController {

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
        $cout = Flight::tempModel()->calculPrixEspece($_POST["id_espece"], $_POST["poids"]) * $_POST["quantite"];
        
        if ($cout > $capital)
        {
            $data = array();
            $data['error'] = "Il vous manque ".($cout-$capital)." pour effectuer l'operation";
            Flight::render('form-achat-animaux', $data);
            return;
        }
        $data = array();
        $data["date"] = $_POST["date_achat"];
        $data["id_espece"] = $_POST["id_espece"];
        $data["poids"] = $_POST["poids"];
        $data["quantite"] = $_POST["quantite"];

        Flight::tempModel()->acheterAnimaux($data);
        Flight::redirect("/");
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