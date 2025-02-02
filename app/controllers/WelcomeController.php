<?php

namespace app\controllers;

use app\models\ProductModel;
use Flight;

class WelcomeController {

	public function __construct() 
    {

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