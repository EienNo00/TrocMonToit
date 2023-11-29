<?php
require 'vendor/autoload.php';

use Faker\Factory;

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

    // Create type table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS type (
            id_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            housings_type VARCHAR(255) NOT NULL
        );"
    );

    // Create users table
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

    // Create address table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS address (
        id_address INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(255) NOT NULL,
        address VARCHAR(255) NOT NULL
    );"
    );

    // Create housings table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housings (
            id_housings INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_type INT NOT NULL,
            night_price INT NOT NULL,
            housings_name VARCHAR(255),
            description VARCHAR(255),
            id_address INT NOT NULL,
            id_image INT NOT NULL,
            FOREIGN KEY (id_type) REFERENCES type(id_type)
        );
    "
    );

    // Create equipment table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS equipment (
            id_equipment INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            equipment_name VARCHAR(255) NOT NULL
        );"
    );

    // Create service table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS service (
            id_service INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            service_name VARCHAR(255) NOT NULL
        );"
    );

    // Create housings_service table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housings_service (
            id_housings_service INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_service INT NOT NULL,
            id_housings INT NOT NULL,
            FOREIGN KEY (id_service) REFERENCES service(id_service),
            FOREIGN KEY (id_housings) REFERENCES housings(id_housings),
            UNIQUE KEY unique_housings_service (id_housings, id_service)
        );"
    );

    // Create housings_equipment table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housings_equipment (
            id_housings_equipment INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_equipment INT NOT NULL,
            id_housings INT NOT NULL,
            FOREIGN KEY (id_equipment) REFERENCES equipment(id_equipment),
            FOREIGN KEY (id_housings) REFERENCES housings(id_housings),
            UNIQUE KEY unique_housings_equipment (id_housings, id_equipment)
        );"
    );

    // Create reservation table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS reservation (
            id_reservation INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_housings INT NOT NULL,
            begin_date DATE,
            end_date DATE,
            id_users INT NOT NULL,
            FOREIGN KEY (id_housings) REFERENCES housings(id_housings),
            FOREIGN KEY (id_users) REFERENCES users(id_users)
        );"
    );

    // Create opinion table
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opinion (
            id_opinion INT AUTO_INCREMENT PRIMARY KEY,
            id_housings INT NOT NULL,
            id_users INT NOT NULL,
            commentary TEXT,
            grade INT CHECK (grade >= 1 AND grade <= 20),
            FOREIGN KEY (id_housings) REFERENCES housings(id_housings),
            FOREIGN KEY (id_users) REFERENCES users(id_users)
        );"
    );

    // Insert data into type table
    $pdo->exec(
        "INSERT INTO type(id_type, housings_type) VALUES
        (1,'Appartements'),
        (2,'Maisons'),
        (3,'Chalets'),
        (4,'Villas'),
        (5,'Peniches'),
        (6,'Yourtes'),
        (7,'Cabanes'),
        (8,'Igloos'),
        (9,'Tentes'),
        (10,'Cars');
        "
    );

    // Insert data into service table
    $pdo->exec(
        "INSERT INTO service(id_service,service_name) VALUES
        (1,'Transfert aeroport'),
        (2,'Petit-dejeuner'),
        (3,'Service de menage'),
        (4,'Location de voiture'),
        (5,'Visites guidees'),
        (6,'Cours de cuisine'),
        (7,'Loisirs');
        "
    );

    // Insert data into equipment table
    $pdo->exec(
        "INSERT INTO equipment(id_equipment,equipment_name) VALUES
        (1,'Connexion Wifi'),
        (2,'Climatiseur'),
        (3,'Chauffage'),
        (4,'Machine a laver'),
        (5,'Seche linge'),
        (6,'Television'),
        (7,'Fer a repasser / Planche a repasser'),
        (8,'Nintendo Switch'),
        (9, 'PS5'),
        (10,'Terrasse'),
        (11,'Balcon'),
        (12,'Piscine'),
        (13,'Jardin');
        "
    );

    // Use Faker to generate fake data
    $faker = Factory::create();

    // Insert data into address table
    for ($i = 0; $i < 10; $i++) {
        $city = $faker->city;
        $streetAddress = $faker->streetAddress;

        $pdo->exec(
            "INSERT INTO address (city, address) VALUES ('{$city}', '{$streetAddress}');
        "
        );
    }

    // Insert data into housings and users tables
    for ($i = 0; $i < 10; $i++) {
        $typeId = $faker->numberBetween(1, 10);
        $addressId = $faker->numberBetween(1, 10);
        $housingsName = $faker->word;
        $nightPrice = $faker->numberBetween($min = 50, $max = 200);

        $pdo->exec(
            "INSERT INTO housings(id_type, night_price, housings_name, description, id_address, id_image) VALUES
            ('{$typeId}', '{$nightPrice}', '{$housingsName}', '{$faker->sentence}','{$addressId}' , 1);
            "
        );

        $phoneNumber = substr($faker->phoneNumber, 0, 15);
        $pdo->exec(
            "INSERT INTO users (last_name, first_name, email, password, phone_number) VALUES 
                ('{$faker->lastName}', '{$faker->firstName}', '{$faker->email}', 'pass', '{$phoneNumber}');
            "
        );
    }

    // Insert data into housings_equipment table
    for ($i = 0; $i < 20; $i++) {
        $equipmentId = $faker->numberBetween(1, 13);
        $housingsId = $faker->numberBetween(1, 10);

        // Check for existing entry
        $existingEntry = $pdo->query("SELECT COUNT(*) FROM housings_equipment WHERE id_housings = '{$housingsId}' AND id_equipment = '{$equipmentId}'")->fetchColumn();

        if ($existingEntry == 0) {
            $pdo->exec(
                "INSERT INTO housings_equipment (id_equipment, id_housings) VALUES ('{$equipmentId}', '{$housingsId}');
            "
            );
        } else {
            echo "La combinaison id_housings = {$housingsId} et id_equipment = {$equipmentId} existe déjà. Évitant le doublon.\n";
        }
    }

    // Insert data into housings_service table
    for ($i = 0; $i < 20; $i++) {
        $serviceId = $faker->numberBetween(1, 7); // Assuming you have 7 services in the table
        $housingsId = $faker->numberBetween(1, 10);

        // Check for existing entry
        $existingEntry = $pdo->prepare("SELECT COUNT(*) FROM housings_service WHERE id_housings = ? AND id_service = ?");
        $existingEntry->execute([$housingsId, $serviceId]);

        if ($existingEntry->fetchColumn() == 0) {
            $pdo->exec(
                "INSERT INTO housings_service (id_service, id_housings) VALUES ('{$serviceId}', '{$housingsId}');
            "
            );
        } else {
            echo "La combinaison id_housings = {$housingsId} et id_service = {$serviceId} existe déjà. Évitant le doublon.\n";
        }
    }

    echo "Tables créées et données insérées avec succès.";
} catch (\PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
