<?php

    namespace app\models;

    use PDO;

    class TempModel {

        private $db;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function InsertionCapitaux($montant) {
            $date = date('Y-m-d');
            
            $stmt = $this->db->prepare("INSERT INTO elevage_mouvement_capitaux (montant, data_mouvement) VALUES (:montant, :date)");
            $stmt->execute([
                ':montant' => $montant,
                ':date' => $date
            ]);
            
            return $this->db->lastInsertId();
        }
    
        public function CapitalActuel() {
            // Total capital movements
            $capitalStmt = $this->db->query("SELECT SUM(montant) as total_capital FROM elevage_mouvement_capitaux");
            $capitalMovement = $capitalStmt->fetch(PDO::FETCH_ASSOC)['total_capital'];
            
            // Animal purchases
            $animalPurchaseStmt = $this->db->query("SELECT SUM(prix_unitaire) as total_animal_purchase FROM elevage_achat_animal");
            $animalPurchases = $animalPurchaseStmt->fetch(PDO::FETCH_ASSOC)['total_animal_purchase'];
            
            // Feed purchases
            $feedPurchaseStmt = $this->db->query("SELECT SUM(a.prix * b.quantite) as total_feed_purchase 
                             FROM elevage_alimentation a 
                             JOIN elevage_achat_alimentation b ON a.id_alimentation = b.id_alimentation");
            $feedPurchases = $feedPurchaseStmt->fetch(PDO::FETCH_ASSOC)['total_feed_purchase'];
            
            // Animal sales
            $salesStmt = $this->db->query("SELECT SUM(prix_unitaire) as total_animal_sales FROM elevage_vente_animal");
            $animalSales = $salesStmt->fetch(PDO::FETCH_ASSOC)['total_animal_sales'];
            
            return $capitalMovement - $animalPurchases - $feedPurchases + $animalSales;
        }

        public function NourirAnimal($idEspece, $poids, $nbAnimal,$date) {

            // Get animals of the specified species
            $stmt = $this->db->prepare("SELECT id_achat_animal FROM elevage_achat_animal 
                               WHERE id_espece = :idEspece AND poids = :poids ORDER BY date_achat");
//            $stmt->bindParam(':idEspece', $idEspece, PDO::PARAM_INT);
//            $stmt->bindParam(':nbAnimal', $nbAnimal, PDO::PARAM_INT);
//            $stmt->bindParam(':poids', $poids, PDO::PARAM_INT);

            $stmt->execute([
                ':idEspece' => $idEspece,
                ':poids' => $poids
            ]);
            $animals = $stmt->fetchAll(PDO::FETCH_COLUMN);
            try{
                $animals = array_slice($animals, 0, $nbAnimal);
            }
            catch (\Exception $e ){

            }

            // Find matching feed for the species
            // Insert feeding records
            $insertStmt = $this->db->prepare("INSERT INTO elevage_nourrissage 
                                   (id_achat_animal, date_nourrissage) VALUES (:animalId, :date)");
            
            foreach ($animals as $animalId) {
                $insertStmt->execute([
                    ':animalId' => $animalId,
                    ':date' => $date
                ]);
            }
            
            return count($animals);
        }
    
        public function AcheterAnimaux($data) {
            $date = date('Y-m-d');
            
            $stmt = $this->db->prepare("INSERT INTO elevage_achat_animal 
                               (id_espece, prix_unitaire, date_achat) 
                               VALUES (:idEspece, :prixUnitaire, :dateAchat)");
            
            $totalPurchase = 0;
            foreach ($data as $animal) {
                $stmt->execute([
                    ':idEspece' => $animal['id_espece'], 
                    ':prixUnitaire' => $animal['prix_unitaire'], 
                    ':dateAchat' => $date
                ]);
                $totalPurchase += $animal['prix_unitaire'];
            }
            
            // Record capital movement
            $this->InsertionCapitaux(-$totalPurchase);
            
            return $this->db->lastInsertId();
        }
    
        public function VenteAnimaux($nbAnimaux, $espece, $poids) {
            $date = date('Y-m-d');
            
            // Find animals to sell
            $stmt = $this->db->prepare("SELECT a.id_achat_animal, e.prix_vente_kg 
                               FROM elevage_achat_animal a
                               JOIN elevage_espece e ON a.id_espece = e.id_espece
                               WHERE a.id_espece = :espece 
                               ORDER BY a.date_achat 
                               LIMIT :nbAnimaux");
            $stmt->bindParam(':espece', $espece, PDO::PARAM_INT);
            $stmt->bindParam(':nbAnimaux', $nbAnimaux, PDO::PARAM_INT);
            $stmt->execute();
            $animalsToSell = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $totalSales = 0;
            $insertStmt = $this->db->prepare("INSERT INTO elevage_vente_animal 
                                   (id_achat_animal, date_vente, prix_unitaire) 
                                   VALUES (:idAchat, :dateVente, :prixUnitaire)");
            
            foreach ($animalsToSell as $animal) {
                $salePrice = $poids * $animal['prix_vente_kg'];
                $insertStmt->execute([
                    ':idAchat' => $animal['id_achat_animal'], 
                    ':dateVente' => $date, 
                    ':prixUnitaire' => $salePrice
                ]);
                
                $totalSales += $salePrice;
            }
            
            // Record capital movement
            $this->InsertionCapitaux($totalSales);
            
            return $totalSales;
        }
    
        //Mety
        public function AcheterAlimentation($idAlimentation, $quantite) {
            $date = date('Y-m-d');
            
            // Get feed price
            $stmt = $this->db->prepare("SELECT prix FROM elevage_alimentation WHERE id_alimentation = :idAlimentation");
            $stmt->execute([':idAlimentation' => $idAlimentation]);
            $feedPrice = $stmt->fetch(PDO::FETCH_ASSOC)['prix'];
            
            // Insert feed purchase
            $stmt = $this->db->prepare("INSERT INTO elevage_achat_alimentation 
                               (id_alimentation, date_achat, quantite) 
                               VALUES (:idAlimentation, :dateAchat, :quantite)");
            $stmt->execute([
                ':idAlimentation' => $idAlimentation,
                ':dateAchat' => $date,
                ':quantite' => $quantite
            ]);
            
            // Calculate total purchase cost
            $totalCost = $feedPrice * $quantite;
            
            // Record capital movement
            $this->InsertionCapitaux(-$totalCost);
            
            return $this->db->lastInsertId();
        }
    
        public function CalculAugmentationPoids($date, $idAnimal) {
            // Get feeding records
            $stmt = $this->db->prepare("SELECT n.date_nourrissage, a.pourcentage_gain 
                               FROM elevage_nourrissage n
                               JOIN elevage_alimentation_espece a ON n.id_achat_animal = a.id_espece
                               WHERE n.id_achat_animal = :idAnimal AND n.date_nourrissage <= :date
                               ORDER BY n.date_nourrissage");
            $stmt->execute([
                ':idAnimal' => $idAnimal,
                ':date' => $date
            ]);
            $feedingRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $totalWeightIncrease = 0;
            foreach ($feedingRecords as $record) {
                // Placeholder for weight increase calculation
                $weightIncrease = $record['pourcentage_gain'];
                $totalWeightIncrease += $weightIncrease;
            }
            
            return $totalWeightIncrease;
        }
    
        public function GetAlimentActuel($date) {
            $stmt = $this->db->prepare("SELECT a.id_alimentation, a.nom, 
                                      SUM(b.quantite) as total_purchased,
                                      (SELECT COUNT(*) FROM elevage_nourrissage n 
                                       WHERE n.date_nourrissage <= :date) as total_used
                               FROM elevage_alimentation a
                               JOIN elevage_achat_alimentation b ON a.id_alimentation = b.id_alimentation
                               WHERE b.date_achat <= :date
                               GROUP BY a.id_alimentation, a.nom");
            $stmt->execute([':date' => $date]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }

?>