<?php

namespace app\controllers;

use app\models\FunctionsModel;
use Flight;

class ConnectionController {

	public function __construct() {

	}

    public function testConnection() {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        
    
        if (!$email || !$password) {
            $_SESSION["error_message"] = "Veuillez fournir un nom d'utilisateur et un mot de passe.";
            $data = ['page' => "user-login", 'title' => "Connexion client" ];
            Flight::render("template-connection", $data);
            return;
        }
    
        $testConnection = Flight::functionsModel()->testConnection($email, $password);
        if (!$testConnection) {
            $_SESSION["error_message"] = "Informations de connexion incorrectes.";
            $data = ['page' => "user-login", 'title' => "Connexion client" ];
            Flight::render("template-connection", $data);
        } else {
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;
            $_SESSION["id"] = $testConnection["id"];
            Flight::redirect(Flight::get('flight.base_url')."/home");
        }
    }

    public function testConnectionAdmin() {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        
    
        $testConnection = Flight::functionsModel()->testConnectionAdmin($email, $password);
        if (!$testConnection) {
            $_SESSION["error_message"] = "Informations de connexion en tant qu'administrateur incorrectes.";
            $data = ['page' => "admin-login", 'title' => "Connexion Administrateur" ];
            Flight::render("template-connection", $data);
        } else {
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;
            $_SESSION["id"] = $testConnection["id"];
            Flight::redirect(Flight::get('flight.base_url')."/home-admin");
        }
    }

    public function testConnectionInside($email,$password) {

        
    
        if (!$email || !$password) {
            $_SESSION["error_message"] = "Veuillez fournir un nom d'utilisateur et un mot de passe.";
            $data = ['page' => "user-login", 'title' => "Connexion client" ];
            Flight::render("template-connection", $data);
            return;
        }
    
        $testConnection = Flight::functionsModel()->testConnexion($email, $password);
        if (!$testConnection) {
            $_SESSION["error_message"] = "Informations de connexion incorrectes.";
            $data = ['page' => "user-login", 'title' => "Connexion client" ];
            Flight::render("template-connection", $data);
        }
    }

    public function testConnectionAdminInside($email,$password) {
    
        

        if (!$email || !$password) {
            $_SESSION["error_message"] = "Veuillez fournir un nom d'utilisateur et un mot de passe.";
            $data = ['page' => "admin-login", 'title' => "Connexion Administrateur" ];
            Flight::render("template-connection", $data);
            return;
        }
    
        $testConnection = Flight::functionsModel()->testConnexion($email, $password);
        if (!$testConnection) {
            $_SESSION["error_message"] = "Informations de connexion en tant qu'administrateurincorrectes.";
            $data = ['page' => "admin-login", 'title' => "Connexion Administrateur" ];
            Flight::render("template-connection", $data);
        }
    } 

    public function createUser() {

        
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $phone = $_POST['phone'] ?? null;
    
        if (!$username || !$password) {
            $_SESSION["error_message"] = "Veuillez fournir un nom d'utilisateur et un mot de passe.";
            $data = ['page' => "user-login", 'title' => "Connexion client" ];
            Flight::render("template-connection", $data);
            return;
        }
    
        Flight::functionsModel()->storeUser($name, $phone, $email, $password);
        $_SESSION["success_message"] = "Nouvel utilisateur créé avec succès.";
        $data = ['page' => "user-login", 'title' => "Connexion client" ];
        Flight::render("template-connection", $data);
    }

    public function deconnectionUser(){

        session_unset(); // Supprime les données de session
        session_destroy(); // Détruit la session
        session_start(); // Redémarre une nouvelle session

        // Redirigez l'utilisateur vers la page de connexion ou d'accueil
        $_SESSION["success_message"] = "Déconnexion réussie.";
        $data = ['page' => "user-login", 'title' => "Connexion client" ];
        Flight::render("template-connection", $data);
    }

    public function deconnectionAdmin(){
        session_unset(); // Supprime les données de session
        session_destroy(); // Détruit la session
        session_start(); // Redémarre une nouvelle session

        // Redirigez l'utilisateur vers la page de connexion ou d'accueil
        $_SESSION["success_message"] = "Déconnexion de l'administrateur réussie.";
        $data = ['page' => "admin-login", 'title' => "Connexion Administrateur" ];
        Flight::render("template-connection", $data);
    }

}