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
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing),
            UNIQUE KEY unique_housing_service (id_housing, id_service)
        );"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS housing_equipment (
            id_housing_equipment INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            id_equipment INT NOT NULL,
            id_housing INT NOT NULL,
            FOREIGN KEY (id_equipment) REFERENCES equipment(id_equipment),
            FOREIGN KEY (id_housing) REFERENCES housing(id_housing),
            UNIQUE KEY unique_housing_equipment (id_housing, id_equipment)
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
        "INSERT INTO type(id_type, housing_type) VALUES
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

    $faker = Factory::create();
    for ($i = 0; $i < 10; $i++) {
        $typeId = $faker->numberBetween(1, 10);

        $address = $faker->address;
        $housingName = $faker->word;
        $nightPrice = $faker->numberBetween($min = 50, $max = 200);

        $pdo->exec(
            "INSERT INTO housing(id_type, night_price, housing_name, description, id_address, id_image)
        VALUES
        ('{$typeId}', '{$nightPrice}', '{$housingName}', '{$faker->sentence}', 1, 1);
        "
        );

        $phoneNumber = substr($faker->phoneNumber, 0, 15); // Trim phone number if necessary
        $pdo->exec(
            "INSERT INTO users (last_name, first_name, email, password, phone_number)
        VALUES 
            ('{$faker->lastName}', '{$faker->firstName}', '{$faker->email}', 'pass', '{$phoneNumber}');
        "
        );
    }
    for ($i = 0; $i < 10; $i++) {
        $equipmentId = $faker->numberBetween(1, 13);
        $housingId = $faker->numberBetween(1, 10);
        $existingEntry = $pdo->query("SELECT COUNT(*) FROM housing_equipment WHERE id_housing = '{$housingId}' AND id_equipment = '{$equipmentId}'")->fetchColumn();
        if ($existingEntry == 0) {
            $pdo->exec(
                "INSERT INTO housing_equipment (id_equipment, id_housing)
            VALUES ('{$equipmentId}', '{$housingId}');
        "
            );
        } else {
            echo "La combinaison id_housing = {$housingId} et id_equipment = {$equipmentId} existe déjà. Évitant le doublon.\n";
        }
    }




    echo "Tables créées et données insérées avec succès.";
} catch (\PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
