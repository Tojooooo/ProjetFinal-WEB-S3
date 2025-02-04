<?php

    namespace app\models;

    use PDO;
    use DateTime;
    use Exception;
    use PDOException;

    class TempModel {

        private $db;
        public function __construct($db)
        {
            $this->db = $db;
        }

        public function GetAllCapitaux() {
            $stmt = $this->db->prepare("SELECT * FROM elevage_mouvement_capitaux");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function GetAllEspeces() {
            $stmt = $this->db->prepare("SELECT * FROM elevage_espece");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function calculPrixEspece(int $idEspece, float $poids) {
            try {
                // Préparation de la requête
                $sql = "SELECT prix_vente_kg, poids_min_vente, poids_max 
                        FROM elevage_espece 
                        WHERE id_espece = ".$idEspece;
                        
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                
                // Récupération des données avec vérification explicite
                $espece = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Calcul du prix
                $prix = $poids * $espece['prix_vente_kg'];
                
                return $prix;
                
            } catch (PDOException $e) {
                throw new Exception("Erreur de base de données : " . $e->getMessage());
            }
        }

        public function InsertionCapitaux($data) {
            try {
                $data_mouvement = $this->parseUnknownDate($data['data_mouvement']);
        
                $query = "INSERT INTO elevage_mouvement_capitaux (montant, data_mouvement) VALUES (:montant, :data_mouvement)";
                $stmt = $this->db->prepare($query);
                
                return $stmt->execute([
                    ':montant' => (float)$data['montant'], 
                    ':data_mouvement' => $data_mouvement
                ]);
            } catch (\PDOException $th) {
                return false;
            }
        }
        
        public function GetCapitalActuel() {
    
            $capitalStmt = $this->db->query("SELECT SUM(montant) as total_capital FROM elevage_mouvement_capitaux");
            $capitalMouvement = $capitalStmt->fetch(PDO::FETCH_ASSOC)['total_capital'];

        
            $achatstmt = $this->db->query("
                SELECT e.prix_vente_kg, aa.poids as total_animal_achat FROM elevage_achat_animal aa
                JOIN elevage_espece e ON e.id_espece=aa.id_espece
            ");
            $achatAnimal = 0;
            while ( $data = $achatstmt->fetch(PDO::FETCH_ASSOC)) {
                $achatAnimal += $data["prix_vente_kg"]*$data["poids"];
            }


            $alimentationstmt = $this->db->query("SELECT SUM(a.prix * b.quantite) as total_aliment_achat
                                    FROM elevage_alimentation a
                                    JOIN elevage_achat_alimentation b ON a.id_alimentation = b.id_alimentation");
            $alimentAchat = $alimentationstmt->fetch(PDO::FETCH_ASSOC)['total_aliment_achat'];

            
            $ventestmt = $this->db->query("SELECT SUM(prix_unitaire) as total_animal_vente FROM elevage_vente_animal");
            $venteAnimal = $ventestmt->fetch(PDO::FETCH_ASSOC)['total_animal_vente'];

            
            return ($capitalMouvement - $achatAnimal - $alimentAchat) + $venteAnimal;
        }

        public function getNombreAnimauxAchetes(string $date): int {
            try {
                $date = $this->parseUnknownDate($date);
                $sql = "SELECT COUNT(*) FROM elevage_achat_animal WHERE date_achat <= :date";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
                
                return (int) $stmt->fetchColumn();
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la récupération du nombre d'animaux élevés : " . $e->getMessage());
            }
        }
        
        function getNombreAnimauxVendus(string $date): int {
            try {
                $sql = "SELECT COUNT(*) FROM elevage_vente_animal WHERE date_vente <= :date";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
                
                return (int) $stmt->fetchColumn();
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la récupération du nombre d'animaux vendus : " . $e->getMessage());
            }
        }
        
        function getNombreAnimauxParEspece(string $date): array {
            try {
                $sql = "SELECT e.nom, COUNT(a.id_achat_animal) AS nombre
                        FROM elevage_achat_animal a
                        JOIN elevage_espece e ON a.id_espece = e.id_espece
                        WHERE a.date_achat <= :date
                        GROUP BY e.nom
                        ORDER BY nombre DESC";
        
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
        
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la récupération des animaux par espèce : " . $e->getMessage());
            }
        }        

        public function GetCapitalSurDate($date) {
            $date = $this->parseUnknownDate($date);
            // Capital des mouvements jusqu'à la date donnée
            $capitalStmt = $this->db->prepare("SELECT SUM(montant) as total_capital 
                                              FROM elevage_mouvement_capitaux 
                                              WHERE data_mouvement <= :date");
            $capitalStmt->bindParam(':date', $date);
            $capitalStmt->execute();
            $capitalMouvement = $capitalStmt->fetch(PDO::FETCH_ASSOC)['total_capital'];
        
            // Achats d'animaux jusqu'à la date donnée
            $achatstmt = $this->db->prepare("SELECT e.prix_vente_kg, aa.poids FROM elevage_achat_animal aa
                                    JOIN elevage_espece e ON e.id_espece=aa.id_espece
                                    WHERE aa.date_achat <= :date");
            $achatstmt->bindParam(':date', $date);
            $achatstmt->execute();
            $achatAnimal = 0;
            while($data = $achatstmt->fetch(PDO::FETCH_ASSOC)) {
                $achatAnimal += $data["prix_vente_kg"]*$data["poids"];
            }
        
            // Achats d'aliments jusqu'à la date donnée
            $alimentationstmt = $this->db->prepare("SELECT SUM(a.prix * b.quantite) as total_aliment_achat
                                                   FROM elevage_alimentation a
                                                   JOIN elevage_achat_alimentation b ON a.id_alimentation = b.id_alimentation
                                                   WHERE b.date_achat <= :date");
            $alimentationstmt->bindParam(':date', $date);
            $alimentationstmt->execute();
            $alimentAchat = $alimentationstmt->fetch(PDO::FETCH_ASSOC)['total_aliment_achat'];
        
            // Ventes d'animaux jusqu'à la date donnée
            $ventestmt = $this->db->prepare("SELECT SUM(prix_unitaire) as total_animal_vente 
                                            FROM elevage_vente_animal 
                                            WHERE date_vente <= :date");
            $ventestmt->bindParam(':date', $date);
            $ventestmt->execute();
            $venteAnimal = $ventestmt->fetch(PDO::FETCH_ASSOC)['total_animal_vente'];
        
            // Calcul du capital total
            return ($capitalMouvement - $achatAnimal - $alimentAchat) + $venteAnimal;
        }

        public function NombreAnimauxPossedes($date)
        {
            $date = $this->parseUnknownDate($date);
            
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
    


        public function NourrirAnimal($idEspece, $poids, $nbAnimal,$date) {

            // Get animals of the specified species

            $stmt = $this->db->prepare("SELECT id_achat_animal FROM elevage_achat_animal 
                               WHERE id_espece = :idEspece AND poids = :poids ORDER BY date_achat");

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
            $date = $this->parseUnknownDate($data["date"]);
            
            $stmt = $this->db->prepare("INSERT INTO elevage_achat_animal 
                               (id_espece, poids, prix_unitaire, date_achat) 
                               VALUES (:idEspece, :poids, 0, :dateAchat)");
            
            for ($i = 0; $i < $data["quantite"]; $i++) {
                $stmt->execute([
                    ':idEspece' => $data['id_espece'], 
                    ':poids' => $data['poids'],
                    ':dateAchat' => $date
                ]);
            }
            
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
            
            $totalvente = 0;
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
                
                $totalvente += $salePrice;
            }
            
            // Record capital movement
            $this->InsertionCapitaux($totalvente);
            
            return $totalvente;
        }
    
        //Mety
        public function AcheterAlimentation($idAlimentation, $quantite, $date) {

            // Get aliment price
            $stmt = $this->db->prepare("SELECT prix FROM elevage_alimentation WHERE id_alimentation = :idAlimentation");
            $stmt->execute([':idAlimentation' => $idAlimentation]);
            $alimentPrice = $stmt->fetch(PDO::FETCH_ASSOC)['prix'];
            
            // Insert aliment achat
            $stmt = $this->db->prepare("INSERT INTO elevage_achat_alimentation 
                               (id_alimentation, date_achat, quantite) 
                               VALUES (:idAlimentation, :dateAchat, :quantite)");
            $stmt->execute([
                ':idAlimentation' => $idAlimentation,
                ':dateAchat' => $date,
                ':quantite' => $quantite
            ]);
            
            // Calculate total achat cost
            $totalCost = $alimentPrice * $quantite;
            
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
                                      SUM(b.quantite) as total_achat,
                                      (SELECT COUNT(*) FROM elevage_nourrissage n 
                                       WHERE n.date_nourrissage <= :date) as total_used
                               FROM elevage_alimentation a
                               JOIN elevage_achat_alimentation b ON a.id_alimentation = b.id_alimentation
                               WHERE b.date_achat <= :date
                               GROUP BY a.id_alimentation, a.nom");
            $stmt->execute([':date' => $date]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getEspeceById($idEspece) {
            $stmt = $this->db->prepare("SELECT * FROM elevage_espece WHERE id_espece = :idEspece");
            $stmt->execute([':idEspece' => $idEspece]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function getDistinctWeightsByEspeceId($idEspece) {
            $stmt = $this->db->prepare("SELECT DISTINCT poids FROM elevage_achat_animal WHERE id_espece = :idEspece");
            $stmt->execute([':idEspece' => $idEspece]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function getSoldPercentage(string $date): float {
            $date = $this->parseUnknownDate($date);
            $stmt = $this->db->prepare("
                SELECT 
                    (SELECT COUNT(*) 
                     FROM elevage_vente_animal ev
                     JOIN elevage_achat_animal aa ON ev.id_achat_animal = aa.id_achat_animal
                     WHERE ev.date_vente <= :date) * 100.0 /
                    (SELECT COUNT(*) 
                     FROM elevage_achat_animal
                     WHERE date_achat <= :date) as percentage
            ");
            $stmt->execute(['date' => $date]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['percentage'] ?? 0.0;
        }

        public function getDeathPercentage(string $date): float {
            $date = $this->parseUnknownDate($date);
            // Récupérer tous les animaux achetés avant la date donnée
            $stmt = $this->db->prepare("
                SELECT aa.id_achat_animal, aa.date_achat, e.jours_sans_manger, 
                       e.perte_poids_par_jour_sans_manger, aa.poids,
                       e.poids_max, aa.id_espece
                FROM elevage_achat_animal aa
                JOIN elevage_espece e ON aa.id_espece = e.id_espece
                WHERE aa.date_achat <= :date
            ");
            $stmt->execute(['date' => $date]);
            $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($animals)) {
                return 0.0;
            }
            
            $totalAnimals = count($animals);
            $deadAnimals = 0;
            
            foreach ($animals as $animal) {
                // Vérifier si l'animal a été vendu avant la date
                $stmt = $this->db->prepare("
                    SELECT COUNT(*) as vendu
                    FROM elevage_vente_animal
                    WHERE id_achat_animal = :id AND date_vente <= :date
                ");
                $stmt->execute([
                    'id' => $animal['id_achat_animal'],
                    'date' => $date
                ]);
                $sold = $stmt->fetch(PDO::FETCH_ASSOC)['vendu'] > 0;
                
                if (!$sold) {
                    // Calculer les jours consécutifs sans manger
                    $currentDate = $animal['date_achat'];
                    $daysWithoutFood = 0;
                    $currentWeight = $animal['poids'];
                    
                    while ($currentDate <= $date) {
                        // Vérifier s'il y a eu de l'alimentation ce jour
                        $stmt = $this->db->prepare("
                            SELECT SUM(aa.quantite) as stock_total
                            FROM elevage_achat_alimentation aa
                            JOIN elevage_alimentation_espece ae ON aa.id_alimentation = ae.id_alimentation
                            WHERE ae.id_espece = :espece
                            AND aa.date_achat <= :date
                        ");
                        $stmt->execute([
                            'espece' => $animal['id_espece'],
                            'date' => $currentDate
                        ]);
                        $foodAvailable = $stmt->fetch(PDO::FETCH_ASSOC)['stock_total'] > 0;
                        
                        if (!$foodAvailable) {
                            $daysWithoutFood++;
                            $currentWeight -= $animal['perte_poids_par_jour_sans_manger'];
                            
                            // Vérifier si l'animal est mort (plus de jours sans manger que permis ou poids nul)
                            if ($daysWithoutFood >= $animal['jours_sans_manger'] || $currentWeight <= 0) {
                                $deadAnimals++;
                                break;
                            }
                        } else {
                            $daysWithoutFood = 0;
                        }
                        
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    }
                }
            }
            
            return ($deadAnimals / $totalAnimals) * 100;
        }

    }

?>