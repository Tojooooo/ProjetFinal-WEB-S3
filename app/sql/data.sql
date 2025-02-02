-- Données pour estate_admins
INSERT INTO estate_admins (email, password)
VALUES
('admin1@example.com', 'motdepasse123'),
('admin2@example.com', 'adminpass456');

-- Données pour estate_users
INSERT INTO estate_users (email, name, password, phone_number)
VALUES
('utilisateur1@example.com', 'Jean Dupont', 'motdepasse123', '0612345678'),
('utilisateur2@example.com', 'Marie Curie', 'userpass456', '0698765432');

-- Données pour estate_property_type
INSERT INTO estate_property_type (type)
VALUES
('Appartement'),
('Maison'),
('Villa');

-- Données pour estate_property_neighbourhood
INSERT INTO estate_property_neighbourhood (neighbourhood)
VALUES
('Faravohitra'),
('Ivandry'),
('Mantansoa');

-- Données pour estate_properties
INSERT INTO estate_properties (id_type_property, id_neighbourhood, bedrooms, daily_rent, description)
VALUES
(1, 1, 2, 75.00, 'Un appartement cosy situé au cœur du centre-ville.'),
(2, 2, 3, 100.00, 'Une maison spacieuse dans une zone résidentielle calme avec un joli jardin.'),
(3, 3, 4, 250.00, 'Une villa de luxe située dans un quartier paisible en bord de mer avec une vue imprenable.');

-- Données pour estate_property_pictures
-- À adapter avec vos fichiers réels
INSERT INTO estate_property_pictures (id_property, file)
VALUES
(1, 'appartement_centre_ville.jpg'),
(2, 'maison_zone_residentielle.jpg'),
(3, 'villa_bord_de_mer.jpg');

-- Données pour estate_bookings
INSERT INTO estate_bookings (id_property, id_user, start_date, end_date)
VALUES
(1, 1, '2025-02-01', '2025-02-10'),
(2, 2, '2025-02-15', '2025-02-20'),
(3, 1, '2025-03-01', '2025-03-10');
