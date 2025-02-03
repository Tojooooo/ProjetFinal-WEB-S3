-- Insertion des espèces
INSERT INTO elevage_espece (nom, poids_min_vente, prix_vente_kg, poids_max, jours_sans_manger, perte_poids_par_jour_sans_manger) VALUES
('Boeuf', 300.00, 5.50, 800.00, 7, 2.00),
('Porc', 100.00, 4.00, 300.00, 5, 1.50),
('Poule', 1.50, 10.00, 5.00, 3, 0.50),
('Canard', 2.50, 8.00, 6.00, 4, 0.75);

-- Insertion des types d'alimentation
INSERT INTO elevage_alimentation (nom, prix, pourcentage_gain) VALUES
('Herbe', 1.00, 1.50),
('Grain', 2.50, 2.00),
('Farine animale', 3.00, 3.00),
('Pain', 0.80, 1.20);

-- Association des espèces aux types d'alimentation
INSERT INTO elevage_alimentation_espece (id_espece, id_alimentation, pourcentage_gain) VALUES
(1, 1, 1.50), -- Boeuf mange Herbe
(2, 2, 2.00), -- Porc mange Grain
(3, 3, 3.00), -- Poule mange Farine animale
(4, 4, 1.20); -- Canard mange Pain
