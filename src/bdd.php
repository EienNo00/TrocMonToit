<?php

try {
    $pdo = new \PDO(
        'mysql:host=mysql;port=3306;dbname=my_database',
        'root',
        'my-secret-pw',
        [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS type (
            id_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            housing_type VARCHAR(255) NOT NULL
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id_users INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            last_name VARCHAR(50) NOT NULL DEFAULT '',
            first_name VARCHAR(50) NOT NULL DEFAULT '',
            email VARCHAR(60) UNIQUE NOT NULL,
            password VARCHAR(60) NOT NULL,
            phone_number VARCHAR(15) NOT NULL DEFAULT 'N/A'
        );
    "
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housing (
            id_housing INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_type INT NOT NULL,
            night_price INT NOT NULL,
            housing_name VARCHAR(255),
            description VARCHAR(255),
            id_address INT NOT NULL,
            id_image INT NOT NULL,
            FOREIGN KEY (id_type) REFERENCES type(id_type)
        );
    "
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS equipment (
            id_equipment INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            equipment_name VARCHAR(255) NOT NULL
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS service (
            id_service INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            service_name VARCHAR(255) NOT NULL
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housing_service (
            id_housing_service INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_service INT NOT NULL,
            id_housing INT NOT NULL,
            FOREIGN KEY (id_service) REFERENCES service(id_service),
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing)
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housing_equipment (
            id_housing_equipment INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_equipment INT NOT NULL,
            id_housing INT NOT NULL,
            FOREIGN KEY (id_equipment) REFERENCES equipment(id_equipment),
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing)
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS reservation (
            id_reservation INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_housing INT NOT NULL,
            begin_date DATE,
            end_date DATE,
            id_users INT NOT NULL,
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing),
            FOREIGN KEY (id_users) REFERENCES users(id_users)
        );"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opinion (
            id_opinion INT AUTO_INCREMENT PRIMARY KEY,
            id_housing INT NOT NULL,
            id_users INT NOT NULL,
            commentary TEXT,
            grade INT CHECK (grade >= 1 AND grade <= 20),
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing),
            FOREIGN KEY (id_users) REFERENCES users(id_users)
        );"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Appartements');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Maisons');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Chalets');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Villas');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Péniches');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Yourtes');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Cabanes');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Igloos');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Tentes');"
    );
    $pdo->exec(
        "INSERT INTO type (housing_type)
    VALUES ('Cars');"
    );
    $pdo->exec("INSERT INTO users (last_name, first_name, email, password, phone_number)
        VALUES 
            ('Doe', 'John', 'test@gmail.com', 'mypass','0607080910'),
            ('Smith', 'Jane', 'test1@gmail.com', 'mypass1','0607080910'),
            ('Johnson', 'Bob', 'test2@gmail.com', 'mypass2','0607080910');
    ");

    echo "Tables créées et données insérées avec succès.";
} catch (\PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
