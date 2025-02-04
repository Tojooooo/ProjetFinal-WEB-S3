<?php 
namespace app\controllers;  
use app\Models\TempModel; 
use Flight;  

class CapitauxController {   
    
    public function showFormCapitaux() {
        Flight::render('form-capital');
    }

    public function TraiterInsertionCapitaux() {
        try {
            $montant = $_POST["montant"];
            $data_mouvement = $_POST["date_mouvement"];
            
            $data = [
                'montant' => $montant,
                'data_mouvement' => $data_mouvement
            ];
            
            $capitauxModel = new TempModel(Flight::db());
            $result = $capitauxModel->InsertionCapitaux($data);
            
            if ($result) {
                Flight::redirect('/dashboard'); 
            } else {
                Flight::render('/');
            }
        } catch (\PDOException $th) { 
            error_log($th->getMessage());
            Flight::render('/', ['error' => 'Erreur de base de données']); 
        } catch (\InvalidArgumentException $e) {
            Flight::render('/', ['error' => $e->getMessage()]);
        }
    }
    

    public function TraiterCapitalActuel() {
        try {
            $capitauxModel = new TempModel(Flight::db());
            $capitalActuel = $capitauxModel->GetCapitalActuel();
            
            return [
                'capitalActuel' => $capitalActuel
            ];
        } catch (\PDOException $th) { 
            error_log($th->getMessage());
            return [
                'error' => 'Impossible de récupérer le capital actuel',
                'capitalActuel' => 0
            ];
        }
    }
}
?>