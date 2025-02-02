DROP DATABASE TP_real_estate;
CREATE DATABASE TP_real_estate;
USE TP_real_estate;

-- TABLES:
CREATE TABLE estate_admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE estate_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15) NOT NULL
);

CREATE TABLE estate_property_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE estate_property_neighbourhood (
    id INT AUTO_INCREMENT PRIMARY KEY,
    neighbourhood VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE estate_properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_type_property INT REFERENCES estate_property_type(id) ON DELETE CASCADE,
    id_neighbourhood INT REFERENCES estate_property_neighbourhood(id) ON DELETE CASCADE,
    bedrooms INT NOT NULL,
    daily_rent DECIMAL(10, 2) NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE estate_property_pictures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_property INT NOT NULL REFERENCES estate_properties(id) ON DELETE CASCADE,
    file TEXT
);

CREATE TABLE estate_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_property INT NOT NULL REFERENCES estate_properties(id) ON DELETE CASCADE,
    id_user INT NOT NULL REFERENCES estate_users(id) ON DELETE CASCADE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    CHECK (start_date < end_date)
);
