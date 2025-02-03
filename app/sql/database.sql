CREATE DATABASE elevage;
USE elevage;

CREATE TABLE elevage_individu (
    id_individu INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    poids DECIMAL(10,2) NOT NULL,
    date_achat DATE NOT NULL,
    FOREIGN KEY (animal_id) REFERENCES animal(id)
);

CREATE TABLE elevage_animal (
    id_animal INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    poids_min_vente DECIMAL(10,2) NOT NULL,
    prix_vente_kg DECIMAL(10,2) NOT NULL,
    poids_max DECIMAL(10,2) NOT NULL,
    jours_sans_manger INT NOT NULL,
    perte_poids_par_jour_sans_manger DECIMAL(5,2) NOT NULL
);

CREATE TABLE elevage_individu_animal (
    id_individu INT NOT NULL,
    id_animal INT NOT NULL,
    quantite INT DEFAULT 0
);

CREATE TABLE elevage_alimentation (
    id_alimentation INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE elevage_alimentation_animal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alimentation INT NOT NULL,
    pourcentage_gain DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (animal_id) REFERENCES animal(id),
    FOREIGN KEY (alimentation_id) REFERENCES alimentation(id)
);

CREATE TABLE elevage_achat_animal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    individu_id INT NOT NULL,
    date_achat DATE NOT NULL,
    prix_achat DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (individu_id) REFERENCES individu(id)
);

CREATE TABLE elevage_vente_animal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    individu_id INT NOT NULL,
    date_vente DATE NOT NULL,
    poids DECIMAL(10,2) NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (individu_id) REFERENCES individu(id)
);

CREATE TABLE elevage_achat_alimentation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alimentation_id INT NOT NULL,
    date_achat DATE NOT NULL,
    quantite DECIMAL(10,2) NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (alimentation_id) REFERENCES alimentation(id)
);

CREATE TABLE elevage_nourrissage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    individu_id INT NOT NULL,
    alimentation_id INT NOT NULL,
    date_nourrissage DATE NOT NULL,
    quantite DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (individu_id) REFERENCES individu(id),
    FOREIGN KEY (alimentation_id) REFERENCES alimentation(id)
);
