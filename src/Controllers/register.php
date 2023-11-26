<?php
// register.php
require_once __DIR__ . '../../../../vendor/autoload.php';

// Initialisation de Twig avec le chemin correct vers les templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../pages');
$twig = new \Twig\Environment($loader);

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $mail = $_POST['mail'];
    $birthdate = $_POST['birthdate'];

    // Ajoute ta logique de validation et d'ajout à la base de données ici

    // Exemple simple d'affichage d'une page de confirmation avec Twig
    echo $twig->render('confirmation.twig', ['username' => $username, 'password' => $password, 'mail' => $mail, 'birthdate' => $birthdate]);
} else {
    // Affiche le formulaire d'inscription
    echo $twig->render('register.twig');
}
