<?php

namespace app\controllers;

use app\models\ProductModel;
use Flight;

class WelcomeController {

	public function __construct() 
    {

	}

    public function salesBySpecies() {
        // Récupérer la date depuis la requête
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        
        $db = Flight::db();
        
        // Requête pour obtenir les ventes par espèce à une date donnée
        $query = "
    SELECT 
        sf.name_specie, 
        COALESCE(SUM(sa.quantity), 0) AS total_sold,
        COALESCE(SUM(sa.quantity * af.price), 0) AS total_revenue
    FROM specie_farming sf
    LEFT JOIN animal_farming af ON af.id_specie = sf.id_specie
    LEFT JOIN sell_animal_farming sa 
        ON sa.id_animal = af.id_animal 
        AND DATE(sa.date_sell) <= CURRENT_DATE
    GROUP BY sf.id_specie
";
        
        $stmt = $db->prepare($query);

        $stmt->execute();
        
        $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Renvoi des données au format JSON
        header('Content-Type: application/json');
        echo json_encode([
            'salesData' => $salesData,
            'selectedDate' => $selectedDate
        ]);
    }

    public function dashboard()
    {
        $data = Flight::tempModel()->GetAllEspeces();
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