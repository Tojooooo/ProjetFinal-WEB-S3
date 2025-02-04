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
        $idEspece = $_GET['idEspece'];
        $poids = $_GET['poids'];
        $nbAnimal = $_GET['nbAnimal'];
        $date =  $_GET['date'];
        $alimentationModel = new TempModel(Flight::db());

        try {
            $alimentationModel->NourrirAnimal($idEspece, $poids, $nbAnimal,$date);
            Flight::redirect('/');
        }
        catch (Exception $e){

        }

    }
    public function GetAlimentActuel() {
        $date = $_GET['date'];
        $alimentationModel = new TempModel(Flight::db());

        try {
            $data = $alimentationModel->GetAlimentActuel($date);
            Flight::render("aliment_actuel",['aliments' =>$data]);
        }
        catch (Exception $e){

        }

    }
    public function actionAcheterAlimentation()
    {
        try {
            $id_alimentation = Flight::request()->data->id_alimentation;
            $date_achat = Flight::request()->data->date_achat;
            $quantite = Flight::request()->data->quantite;

            $data = [
                'id_alimentation' => $id_alimentation,
                'quantite' => $quantite,
                'date_achat' => $date_achat
            ];
    
            $elevageModel = new TempModel(Flight::db());
            $result = $elevageModel-> AcheterAlimentation($id_alimentation,$quantite,$date_achat);
    
            if ($result) {
                Flight::redirect('/achat/alimentation');
            } else {
                Flight::redirect('/');
            }
        } catch (\Throwable $th) {
            Flight::redirect('/');
        }
    }
    }
    ?>