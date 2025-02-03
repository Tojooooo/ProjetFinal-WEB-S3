<?php

    namespace app\models;

    use PDO;
    use DateTime;

    class TempModel {

        private $db;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function parseUnknownDate($dateString) {
            $formats = [
                'Y-m-d',     // 2024-12-12
                'd/m/Y',     // 12/12/2024
                'm/d/Y',     // 12/12/2024 (U.S.)
                'd-m-Y',     // 12-12-2024
                'Y/m/d',     // 2024/12/12
                'd F Y',     // 12 December 2024
                'F d, Y',    // December 12, 2024
            ];
            
            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date) {
                    return $date->format('Y-m-d'); // Convert to MySQL format
                }
            }
            
            return false; // No valid format found
        }

        public function GetPoidsAnimalADate($idAchatAnimal, $date) {
            $date = $this->parseUnknownDate($date);

            // Find the initial weight of the animal at purchase
            $stmtInitialWeight = $this->db->prepare("
                SELECT poids
                FROM elevage_achat_animal a
                WHERE a.id_achat_animal = :idAchatAnimal
            ");
            $stmtInitialWeight->execute([':idAchatAnimal' => $idAchatAnimal]);
            $initialWeight = $stmtInitialWeight->fetch(PDO::FETCH_ASSOC)['poids'];
        
            // Find all feedings for this animal up to the specified date
            $stmtFeedings = $this->db->prepare("
                SELECT a.pourcentage_gain
                FROM elevage_nourrissage n
                JOIN elevage_achat_animal aa ON n.id_achat_animal=aa.id_achat_animal
                JOIN elevage_espece e ON aa.id_espece=aa.id_espece
                JOIN elevage_alimentation_espece ae ON n.id_achat_animal = e.id_espece
                JOIN elevage_alimentation a ON ae.id_alimentation=a.id_alimentation
                WHERE n.id_achat_animal = :idAchatAnimal 
                AND n.date_nourrissage <= :date
            ");
            $stmtFeedings->execute([
                ':idAchatAnimal' => $idAchatAnimal,
                ':date' => $date
            ]);
            $feedings = $stmtFeedings->fetchAll(PDO::FETCH_ASSOC);
        
            // Calculate weight increase
            $weightIncrease = $initialWeight;
            foreach ($feedings as $feeding) {
                $weightIncrease += $weightIncrease * ($feeding['pourcentage_gain'] / 100);
            }
        
            return $weightIncrease;
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

        public function NourirAnimal($idEspece, $poids, $nbAnimal) {
            $date = date('Y-m-d');
            
            // Get animals of the specified species
            $stmt = $this->db->prepare("SELECT id_achat_animal FROM elevage_achat_animal 
                               WHERE id_espece = :idEspece ORDER BY date_achat LIMIT :nbAnimal");
            $stmt->bindParam(':idEspece', $idEspece, PDO::PARAM_INT);
            $stmt->bindParam(':nbAnimal', $nbAnimal, PDO::PARAM_INT);
            $stmt->execute();
            $animals = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Find matching feed for the species
            $stmt = $this->db->prepare("SELECT id_alimentation, pourcentage_gain 
                               FROM elevage_alimentation_espece 
                               WHERE id_espece = :idEspece");
            $stmt->bindParam(':idEspece', $idEspece, PDO::PARAM_INT);
            $stmt->execute();
            $feedInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
            $date = $this->parseUnknownDate($data["date"]);
            
            $stmt = $this->db->prepare("INSERT INTO elevage_achat_animal 
                               (id_espece, poids, prix_unitaire, date_achat) 
                               VALUES (:idEspece, :poids, :prixUnitaire, :dateAchat)");
            
            $stmt->execute([
                ':idEspece' => $data['id_espece'], 
                ':poids' => $data['poids'],
                ':prixUnitaire' => $data['prix_unitaire'], 
                ':dateAchat' => $date
            ]);
            
            // Record capital movement
            
            return $this->db->lastInsertId();
        }
    
        public function VenteAnimaux($nbAnimaux, $idEspece, $poids, $dateVente, $prix) {
            $date = $this->parseUnknownDate($dateVente);
            
            // Find animals to sell
            $stmt = $this->db->prepare("SELECT a.id_achat_animal, e.prix_vente_kg 
                               FROM elevage_achat_animal a
                               WHERE a.id_espece=:espece && a.poids=:poids
                               ORDER BY a.date_achat 
                               LIMIT :nbAnimaux");
            $stmt->bindParam(':espece', $idEspece, PDO::PARAM_INT);
            $stmt->bindParam(':poids', $poids, PDO::PARAM_INT);
            $stmt->bindParam(':nbAnimaux', $nbAnimaux, PDO::PARAM_INT);
            $stmt->execute();
            $animalsToSell = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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
                
            }
        }
    
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
    
        public function CalculAugmentationPoids($date, $idAchatAnimal) {
            $date = $this->parseUnknownDate($date);

            // Find the initial weight of the animal at purchase
            $stmtInitialWeight = $this->db->prepare("
                SELECT poids
                FROM elevage_achat_animal a
                WHERE a.id_achat_animal = :idAchatAnimal
            ");
            $stmtInitialWeight->execute([':idAchatAnimal' => $idAchatAnimal]);
            $initialWeight = $stmtInitialWeight->fetch(PDO::FETCH_ASSOC)['poids'];
        
            // Find all feedings for this animal up to the specified date
            $stmtFeedings = $this->db->prepare("
                SELECT a.pourcentage_gain
                FROM elevage_nourrissage n
                JOIN elevage_achat_animal aa ON n.id_achat_animal=aa.id_achat_animal
                JOIN elevage_espece e ON aa.id_espece=aa.id_espece
                JOIN elevage_alimentation_espece ae ON n.id_achat_animal = e.id_espece
                JOIN elevage_alimentation a ON ae.id_alimentation=a.id_alimentation
                WHERE n.id_achat_animal = :idAchatAnimal 
                AND n.date_nourrissage <= :date
            ");
            $stmtFeedings->execute([
                ':idAchatAnimal' => $idAchatAnimal,
                ':date' => $date
            ]);
            $feedings = $stmtFeedings->fetchAll(PDO::FETCH_ASSOC);
        
            // Calculate weight increase
            $weightIncrease = $initialWeight;
            foreach ($feedings as $feeding) {
                $weightIncrease += $weightIncrease * ($feeding['pourcentage_gain'] / 100);
            }
        
            return $weightIncrease;
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