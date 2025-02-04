<?php

namespace app\controllers;
use Ahc\Cli\Exception;
use app\models\TempModel;
use Flight;

class AlimentationController
{
    public function __construct()
    {

    }
    public function Nourrir()
    {
        $idEspece = $_POST['idEspece'];
        $poids = $_POST['poids'];
        $nbAnimal = $_POST['nbAnimal'];
        $date =  $_POST['date'];
        $alimentationModel = new TempModel(Flight::db());

        try {
            $alimentationModel->NourrirAnimal($idEspece, $poids, $nbAnimal,$date);
            Flight::redirect('/');
        }
        catch (Exception $e){

        }

    }
    public function GetAlimentActuel() {
        $date = $_POST['date'];
        $alimentationModel = new TempModel(Flight::db());

        try {
            $data = $alimentationModel->GetAlimentActuel($date);
            Flight::render("aliment_actuel",['aliments' =>$data]);
        }
        catch (Exception $e){

        }

    }

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