-- Créer la base de données
CREATE DATABASE IF NOT EXISTS compte_bancaire;
USE compte_bancaire;

-- ✅ Table administrateur
CREATE TABLE IF NOT EXISTS administrateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅ Table client (renommée correctement + clé étrangère ajoutée)
CREATE TABLE IF NOT EXISTS client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅ Table compte (clé étrangère avec suppression en cascade)
CREATE TABLE IF NOT EXISTS compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    numero_compte VARCHAR(50) NOT NULL UNIQUE,
    solde DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    type_compte ENUM('courant', 'épargne') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_client_compte FOREIGN KEY (client_id) 
        REFERENCES client(id) ON DELETE CASCADE
);

-- ✅ Table contrat (clé étrangère avec suppression en cascade)
CREATE TABLE IF NOT EXISTS contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compte_id INT NOT NULL,
    type_contrat VARCHAR(100) NOT NULL,
    montant DECIMAL(15, 2) NOT NULL,
    date_signature DATE NOT NULL,
    date_expiration DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_compte_contrat FOREIGN KEY (compte_id) 
        REFERENCES compte(id) ON DELETE CASCADE
);

-- ✅ Insertion administrateur avec mot de passe haché
INSERT INTO administrateur (nom, email, password) 
VALUES ('Romain Mondija', 'romain.mondija@proton.me', '@Rm05011984');

-- ✅ Récupération de l'ID de l'administrateur
SET @admin_id = LAST_INSERT_ID();

-- ✅ Insertion dynamique des clients
INSERT INTO client (nom, prenom, email, telephone, adresse) 
VALUES 
('Dupont', 'Jean', 'jean.dupont@example.com', '0612345678', '12 rue de Paris, France'),
('Durand', 'Marie', 'marie.durand@example.com', '0623456789', '34 avenue des Champs, France');

-- ✅ Récupération des IDs clients insérés
SET @client_id1 = LAST_INSERT_ID();
SET @client_id2 = @client_id1 + 1;

-- ✅ Insertion de comptes avec ID valide
INSERT INTO compte (client_id, numero_compte, solde, type_compte) 
VALUES 
(@client_id1, 'FR1234567890123456789012345', 1500.00, 'courant'),
(@client_id2, 'FR9876543210987654321098765', 3200.00, 'épargne');

-- ✅ Récupération des IDs comptes insérés
SET @compte_id1 = LAST_INSERT_ID();
SET @compte_id2 = @compte_id1 + 1;

-- ✅ Insertion de contrats avec ID valide
INSERT INTO contrat (compte_id, type_contrat, montant, date_signature, date_expiration) 
VALUES 
(@compte_id1, 'Crédit immobilier', 100000.00, '2023-01-10', '2033-01-10'),
(@compte_id2, 'Assurance vie', 5000.00, '2022-05-15', '2042-05-15');