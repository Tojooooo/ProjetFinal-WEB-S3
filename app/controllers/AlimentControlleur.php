<?php 
namespace App\Controllers;  
use App\Models\TempModel; 
use Flight;  

class AlimentControlleur { 
    
    public function TraiterAcheterAlimentation() {
        try {
            $idAlimentation = (int)Flight::request()->data->id_alimentation;
            $quantite = (float)Flight::request()->data->quantite;

            if (empty($idAlimentation) || empty($quantite)) {
                throw new \InvalidArgumentException('ID alimentation et quantité sont requis');
            }

            $alimentationModel = new TempModel(Flight::db());
            $result = $alimentationModel->AcheterAlimentation($idAlimentation, $quantite);

            if ($result) {
                Flight::redirect('/accueil');
            } else {
                Flight::render('/');
            }
        } catch (\PDOException $th) {
            return false;
        }
    }
}

?>