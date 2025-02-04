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
    public function AcheterAlimentation()
    {
        $idAlimentation = $_POST['idAlimentation'];
        $quantite = $_POST['quantite'];
        $date = $_POST['date'];
        $alimentationModel = new TempModel(Flight::db());

        try {
            $alimentationModel->AcheterAlimentation($idAlimentation, $quantite,$date);
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

    }