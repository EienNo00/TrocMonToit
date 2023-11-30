<?php

namespace Models;

class Housing extends Model
{
    public function joinAllTables($typeFilter = null)
    {
        try {
            $sql = "SELECT 
                    housings.id_housings, types.housings_type AS types, address.city AS address, housings.housings_name AS name, housings.night_price AS price, housings.description,
                    GROUP_CONCAT(DISTINCT service.service_name SEPARATOR ', ') AS services,
                    GROUP_CONCAT(DISTINCT equipment.equipment_name SEPARATOR ', ') AS equipment,
                    GROUP_CONCAT(DISTINCT reservation.begin_date SEPARATOR ', ') AS reservation_dates,
                    GROUP_CONCAT(DISTINCT opinion.grade SEPARATOR ', ') AS opinion_grades
                    FROM housings
                    INNER JOIN types ON housings.id_type = types.id_type
                    INNER JOIN address ON housings.id_address = address.id_address
                    LEFT JOIN housings_service ON housings.id_housings = housings_service.id_housings
                    LEFT JOIN service ON housings_service.id_service = service.id_service
                    LEFT JOIN housings_equipment ON housings.id_housings = housings_equipment.id_housings
                    LEFT JOIN equipment ON housings_equipment.id_equipment = equipment.id_equipment
                    LEFT JOIN reservation ON housings.id_housings = reservation.id_housings
                    LEFT JOIN opinion ON housings.id_housings = opinion.id_housings";

            $whereConditions = [];

            if ($typeFilter !== null) {
                $whereConditions[] = "types.housings_type = :housings_type";
            }

            if (!empty($whereConditions)) {
                $sql .= " WHERE " . implode(" AND ", $whereConditions);
            }

            $sql .= " GROUP BY housings.id_housings";

            $statement = $this->getPDO()->prepare($sql);

            if ($typeFilter !== null) {
                $statement->bindParam(':housings_type', $typeFilter);
            }

            $statement->execute();

            return $statement->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error executing query: " . $e->getMessage());
            var_dump($e->getMessage());
            return [];
        }
    }

    public function getDistinctType()
    {
        $sql = "SELECT DISTINCT housings_type FROM types";
        $statement = $this->getPDO()->query($sql);
        $types = $statement->fetchAll(\PDO::FETCH_COLUMN);
        return $types;
    }
}
