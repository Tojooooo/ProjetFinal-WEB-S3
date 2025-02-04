<?php 
namespace App\Controllers;  
use App\Models\TempModel; 
use Flight;  

class CapitauxController {   
    
    public function __construct()
    {
        
    }
  
    public function showFormCapitaux() {
        Flight::render('Verssement.php');
    }

    public function TraiterInsertionCapitaux() {
        try {
            $montant = (float)Flight::request()->data->montant;
            $data_mouvement = Flight::request()->data->data_mouvement;
            
            if (empty($montant) || empty($data_mouvement)) {
                throw new \InvalidArgumentException('Montant et date de mouvement sont requis');
            }

            $data = [
                'montant' => $montant,
                'data_mouvement' => $data_mouvement
            ];
            
            $capitauxModel = new TempModel(Flight::db());
            $result = $capitauxModel->InsertionCapitaux($data);
            
            if ($result) {
                Flight::redirect('/accueil'); 
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