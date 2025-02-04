<?php

namespace app\controllers;

use app\models\ProductModel;
use PDO;
use Flight;
use Exception;

class WelcomeController {

	public function __construct() 
    {

	}

    function reinitialiser() {
        Flight::tempModel()->reinitialiserBase();
        Flight::redirect("/");
    }

    public function refreshDate()
    {
        $date = $_POST["date"];
        $data = array();
        $data["capital"] = Flight::tempModel()->GetCapitalSurDate($date);
        $data["nbAchetes"] = Flight::tempModel()->getNombreAnimauxAchetes($date);
        $data["nbVendus"] = Flight::tempModel()->getNombreAnimauxVendus($date);
        $data["animauxParEspeces"] = Flight::tempModel()->getNombreAnimauxParEspece($date);
        $data["pourcentage_vente"] = Flight::tempModel()->getSoldPercentage($date);
        $data["pourcentage_mort"] = Flight::tempModel()->getSoldPercentage($date);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function dashboard()
    {
        $date = "2025/02/04";
        $data = array();
        $data["date"] = $date;
        $data["especes"] = Flight::tempModel()->GetAllEspeces();
        $data["capital"] = Flight::tempModel()->GetCapitalSurDate($date);
        $data["nbAchetes"] = Flight::tempModel()->getNombreAnimauxAchetes($date);
        $data["nbVendus"] = Flight::tempModel()->getNombreAnimauxVendus($date);
        $data["animauxParEspeces"] = Flight::tempModel()->getNombreAnimauxParEspece($date);
        $data["pourcentage_vente"] = Flight::tempModel()->getSoldPercentage($date);
        $data["pourcentage_mort"] = Flight::tempModel()->getSoldPercentage($date);

        Flight::render("dashboard", $data);
    }

    public function home()
    {
        Flight::render("home");
    }

	public function homeLogin() 
    {
        $data = ['page' => "user-login", 'title' => "Connexion client" ];
        Flight::render("template-connection", $data);
    }

    public function homeRegister() 
    {
        $data = ['page' => "user-register", 'title' => "Inscription" ];
        Flight::render("template-connection", $data);
    }

    public function homeAdmin() 
    {
        $data = ['page' => "admin-login", 'title' => "Connexion Administrateur" ];
        Flight::render("template-connection", $data);
    }
}