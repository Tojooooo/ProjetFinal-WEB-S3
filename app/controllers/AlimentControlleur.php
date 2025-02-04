<?php
namespace App\Controllers;

use App\Models\TempModel;
use Flight;

class AlimentationController {
    public function acheterAlimentation() {
        try {
            $data = [
                'id_alimentation' => (int)$_POST['id_alimentation'],
                'date_achat' => (int)$_POST['date_achat'],
                'quantite' => (float)$_POST['quantite']
            ];

            if (empty($data['id_alimentation']) || empty($data['quantite'])) {
                throw new \InvalidArgumentException('Données manquantes');
            }

            $tempModel = new TempModel(Flight::db());
            $resultat = $tempModel->AcheterAlimentation($data);

            if ($resultat['success']) {
                Flight::redirect('/accueil');
            } else {
                Flight::render('/');
                 
            }
        } catch (\InvalidArgumentException $e) {
            return false;
        
        }
    }
}
?>