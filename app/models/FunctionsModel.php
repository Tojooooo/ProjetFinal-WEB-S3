<?php

namespace app\models;

use PDO;

class FunctionsModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Teste la connexion d'un utilisateur
    public function testConnection($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM estate_users WHERE email = :email AND password = :password");
        $stmt->execute([':email' => $email, ':password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Teste la connexion d'un administrateur
    public function testConnectionAdmin($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM estate_admins WHERE email = :email AND password = :password");
        $stmt->execute([':email' => $email, ':password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crée un utilisateur
    public function storeUser($name, $phoneNumber, $email, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO estate_users (name, phone_number, email, password) 
                                    VALUES (:name, :phone_number, :email, :password)");
        return $stmt->execute([
            ':name' => $name,
            ':phone_number' => $phoneNumber,
            ':email' => $email,
            ':password' => $password
        ]);
    }

    // Récupère une propriété spécifique
    public function getProperty($idProperty)
    {
        $stmt = $this->db->prepare("SELECT * FROM estate_properties WHERE id = :id");
        $stmt->execute([':id' => $idProperty]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère toutes les propriétés
    public function getAllProperties()
    {
        $stmt = $this->db->query("SELECT * FROM estate_properties");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajoute une nouvelle propriété
    public function storeNewProperty($type, $nbBedrooms, $dailyRent, $neighbourhood, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO estate_properties (id_type_property, bedrooms, daily_rent, id_neighbourhood, description) 
                                    VALUES (:type, :bedrooms, :daily_rent, :neighbourhood, :description)");
        return $stmt->execute([
            ':type' => $type,
            ':bedrooms' => $nbBedrooms,
            ':daily_rent' => $dailyRent,
            ':neighbourhood' => $neighbourhood,
            ':description' => $description
        ]);
    }

    // Ajoute une photo à une propriété
    public function storePictureProperty($idProperty, $nameFile)
    {
        $stmt = $this->db->prepare("INSERT INTO estate_property_pictures (id_property, file) VALUES (:id_property, :file)");
        return $stmt->execute([
            ':id_property' => $idProperty,
            ':file' => $nameFile
        ]);
    }

    // Supprime toutes les photos d'une propriété
    public function deletePictureProperty($idProperty)
    {
        $stmt = $this->db->prepare("DELETE FROM estate_property_pictures WHERE id_property = :id_property");
        return $stmt->execute([':id_property' => $idProperty]);
    }

    // Met à jour une propriété
    public function updateProperty($idProperty, $type, $nbBedrooms, $dailyRent, $neighbourhood, $description)
    {
        $stmt = $this->db->prepare("UPDATE estate_properties 
                                    SET id_type_property = :type, bedrooms = :bedrooms, daily_rent = :daily_rent, id_neighbourhood = :neighbourhood, description = :description
                                    WHERE id = :id");
        return $stmt->execute([
            ':id' => $idProperty,
            ':type' => $type,
            ':bedrooms' => $nbBedrooms,
            ':daily_rent' => $dailyRent,
            ':neighbourhood' => $neighbourhood,
            ':description' => $description
        ]);
    }

    // Vérifie si une propriété est déjà réservée pour une période donnée
    public function checkBooking($idProperty, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM estate_bookings 
                                    WHERE id_property = :id_property AND 
                                          ((start_date BETWEEN :start_date AND :end_date) OR 
                                           (end_date BETWEEN :start_date AND :end_date))");
        $stmt->execute([
            ':id_property' => $idProperty,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // Ajoute une réservation
    public function bookProperty($idUser, $idProperty, $startDate, $endDate)
    {
        if ($this->checkBooking($idProperty, $startDate, $endDate)) {
            return false; // Propriété déjà réservée
        }

        $stmt = $this->db->prepare("INSERT INTO estate_bookings (id_property, id_user, start_date, end_date) 
                                    VALUES (:id_property, :id_user, :start_date, :end_date)");
        return $stmt->execute([
            ':id_property' => $idProperty,
            ':id_user' => $idUser,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
    }

    // Recherche des propriétés
    public function searchProperties($idTypeProperty, $idNeighbourhood, $nbBedrooms, $minDailyRent, $maxDailyRent)
    {
        $stmt = $this->db->prepare("SELECT * FROM estate_properties 
                                    WHERE id_type_property = :type AND 
                                          id_neighbourhood = :neighbourhood AND 
                                          bedrooms >= :bedrooms AND 
                                          daily_rent BETWEEN :min_rent AND :max_rent");
        $stmt->execute([
            ':type' => $idTypeProperty,
            ':neighbourhood' => $idNeighbourhood,
            ':bedrooms' => $nbBedrooms,
            ':min_rent' => $minDailyRent,
            ':max_rent' => $maxDailyRent
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
