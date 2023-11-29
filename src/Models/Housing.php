<?php

namespace Models;

use Source\Constant;

class Housing extends Model
{
    public function allTables()
    {
        try {
            $sql = "SELECT housings.id_housings, type.housings_type AS type, address.city AS address, 
            housings.housings_name AS name, housings.night_price AS price, housings.description,
            GROUP_CONCAT(service.service_name SEPARATOR ', ') AS services
            FROM {$this->table}
            INNER JOIN type ON {$this->table}.id_type = type.id_type
            INNER JOIN address ON {$this->table}.id_address = address.id_address
            LEFT JOIN housings_service ON {$this->table}.id_housings = housings_service.id_housings
            LEFT JOIN service ON housings_service.id_service = service.id_service
            GROUP BY housings.id_housings";

            // Utilisation d'une requête préparée
            $statement = $this->getPDO()->prepare($sql);
            $statement->execute();

            // Récupération des résultats sous forme d'objets
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            // Gestion des erreurs de base de données
            // Vous pourriez logger l'erreur ou la gérer de manière appropriée selon les besoins
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}
