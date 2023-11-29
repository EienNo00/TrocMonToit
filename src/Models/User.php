<?php

namespace Models;

use PDO;
use PDOException;

class User extends Model
{
    public function findByCredentials($email, $password)
    {
        $sql = "SELECT id_users, password FROM {$this->table} WHERE email = :email";
        $statement = $this->getPDO()->prepare($sql);
        $statement->execute([':email' => $email]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return false;
        }

        // Vérifier le mot de passe en utilisant password_verify
        if (password_verify($password, $user['password'])) {
            return $user['id_users']; // Retourne l'ID de l'utilisateur en cas de correspondance
        }

        return false;
    }
}
