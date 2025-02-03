CREATE DATABASE elevage;
USE elevage;

CREATE TABLE elevage_capitale (
    capitale DECIMAL(10, 2)
);

CREATE TABLE elevage_espece (
    id_espece INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    poids_min_vente DECIMAL(10,2) NOT NULL,
    prix_vente_kg DECIMAL(10,2) NOT NULL,
    poids_max DECIMAL(10,2) NOT NULL,
    jours_sans_manger INT NOT NULL,
    perte_poids_par_jour_sans_manger DECIMAL(5,2) NOT NULL
);

CREATE TABLE elevage_alimentation (
    id_alimentation INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    pourcentage_gain DECIMAL(5, 2)
);

CREATE TABLE elevage_alimentation_espece (
    id_espece INT AUTO_INCREMENT PRIMARY KEY,
    id_alimentation INT NOT NULL,
    pourcentage_gain DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (id_alimentation) REFERENCES elevage_alimentation(id_alimentation)
);

CREATE TABLE elevage_achat_animal (
    id_achat_animal INT AUTO_INCREMENT PRIMARY KEY,
    id_espece INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    date_achat DATE NOT NULL,
    FOREIGN KEY (id_espece) REFERENCES elevage_espece(id_espece)
);

CREATE TABLE elevage_vente_animal (
    id_vente INT AUTO_INCREMENT PRIMARY KEY,
    id_achat_animal INT NOT NULL,
    date_vente DATE NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_achat_animal) REFERENCES elevage_achat_animal(id_achat_animal)
);

CREATE TABLE elevage_achat_alimentation (
    id_achat_alimentation INT AUTO_INCREMENT PRIMARY KEY,
    id_alimentation INT NOT NULL,
    date_achat DATE NOT NULL,
    quantite DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_alimentation) REFERENCES elevage_alimentation(id_alimentation)
);

CREATE TABLE elevage_nourrissage (
    id_nourrissage INT AUTO_INCREMENT PRIMARY KEY,
    id_achat_animal INT NOT NULL,
    date_nourrissage DATE NOT NULL,
    FOREIGN KEY (id_achat_animal) REFERENCES elevage_achat_animal(id_achat_animal)
);
