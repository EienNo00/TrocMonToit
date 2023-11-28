<?php

namespace Controllers;

use Models\User;
use ReflectionFunctionAbstract;
use Source\Renderer;

class UserController
{
    public function connexion()
    {
        $userModel = new User();
        $users = $userModel->all();
        Renderer::view('user/connexion');
        return;
    }
    public function register()
    {
        $userModel = new User();

        $users = $userModel->all();

        if (empty($_POST["first_name"])) {
            die("Le prénom est requis");
        }
        if (empty($_POST["last_name"])) {
            die("Le nom est requis");
        }
        if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            die("Une adresse email valide est requise");
        }
        if (strlen($_POST["password"]) < 8 || !preg_match("/[a-z]/i", $_POST["password"]) || !preg_match("/[0-9]/i", $_POST["password"])) {
            die("Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre");
        }
        if ($_POST["password"] !== $_POST["password_confirmation"]) {
            die("Les mots de passe ne correspondent pas");
        }

        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

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

            // Utilisation de prepared statements pour éviter les attaques par injection SQL
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST["first_name"], $_POST["last_name"], $_POST["email"], $password_hash, $_POST["phone_number"]]);

            // Stockez le message de réussite dans une variable de session
            $_SESSION['success_message'] = "Enregistrement réussi";

            // Redirigez l'utilisateur vers /registersuccess
            header("Location: /registersuccess");
            exit(); // Assurez-vous de terminer l'exécution du script après la redirection
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000' && strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
                echo "L'email est déjà pris. Veuillez choisir une autre adresse email.";
            } else {
                echo "Erreur : " . $e->getMessage();
            }
            Renderer::view('user/register');
            return;
        }
    }
    public function registersuccess()
    {
        $userModel = new User();
        $users = $userModel->all();
        Renderer::view('user/registersuccess');
        return;
    }
    public function login()
    {
        $userModel = new User();
        $users = $userModel->all();
        Renderer::view('user/login');
        return;
    }
    public function registertest()
    {
    }
}
