<?php

namespace Models;

use PDO;
use PDOException;

class User extends Model
{
}



class Register
{
    public function create_user(array $data): bool
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $phone_number = ($data['phone_number']);
        $password = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        $password_confirmation = $data['password_confirmation'];



        $pdo = new PDO('mysql:host=mysql;port=3306;dbname=database', 'root', 'root');

        try {
            $stmt = $pdo->prepare("INSERT INTO User (`last_name`, `first_name`, `email`, `phone_number`, `password`,`password_confirmation`) VALUES (`last_name`, `first_name`, `email`, `phone_number`, `password`,,`password_confirmation`)");
            $stmt->bindParam("last_name", $last_name);
            $stmt->bindParam("first_name", $first_name);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("phone_number", $phone_number);
            $stmt->bindParam("password", $password);
            $stmt->bindParam("password_confirmation", $password_confirmation);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
