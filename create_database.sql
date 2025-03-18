
-- Créer la base de données
CREATE DATABASE IF NOT EXISTS compte_bancaire;
USE compte_bancaire;

-- Table administrateur
CREATE TABLE IF NOT EXISTS administrateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table client
CREATE TABLE IF NOT EXISTS client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    administrateur_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (administrateur_id) REFERENCES administrateur(id)
);

-- Table compte
CREATE TABLE IF NOT EXISTS compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    numero_compte VARCHAR(50) NOT NULL UNIQUE,
    solde DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    type_compte ENUM('courant', 'épargne') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id)
);

-- Table contrat
CREATE TABLE IF NOT EXISTS contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compte_id INT NOT NULL,
    type_contrat VARCHAR(100) NOT NULL,
    montant DECIMAL(15, 2) NOT NULL,
    date_signature DATE NOT NULL,
    date_expiration DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (compte_id) REFERENCES compte(id)
);

-- Insérer un administrateur
INSERT INTO administrateur (nom, email, password) 
VALUES ('Romain Mondija', 'romain.mondija@proton.me', 'abcdC');

-- Insertion de clients (administrateur_id fixé à 1)
INSERT INTO client (administrateur_id, nom, prenom, email, telephone, adresse) 
VALUES 
(1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0612345678', '12 rue de Paris, France'),
(1, 'Durand', 'Marie', 'marie.durand@example.com', '0623456789', '34 avenue des Champs, France');

-- Insertion de comptes (référencement du client_id existant)
INSERT INTO compte (client_id, numero_compte, solde, type_compte) 
VALUES 
(3, 'FR1234567890123456789012345', 1500.00, 'courant'),
(4, 'FR9876543210987654321098765', 3200.00, 'épargne');

-- Insertion de contrats (référencement correct avec compte_id)
INSERT INTO contrat (compte_id, type_contrat, montant, date_signature, date_expiration) 
VALUES 
(5, 'Crédit immobilier', 100000.00, '2023-01-10', '2033-01-10'),
(6, 'Assurance vie', 5000.00, '2022-05-15', '2042-05-15');
