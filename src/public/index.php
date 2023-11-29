<?php

use Source\App;
use Router\Router;

require './../vendor/autoload.php';

define('BASE_VIEW_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views'
    . DIRECTORY_SEPARATOR);

$router = new Router();

$router->register('/', ['Controllers\HomeController', 'index']);
$router->register('/home', ['Controllers\HousingController', 'home']);
$router->register('/connexion', ['Controllers\UserController', 'connexion']);
$router->register('/register', ['Controllers\UserController', 'register']);
$router->register('/registersuccess', ['Controllers\UserController', 'registersuccess']);
$router->register('/login', ['Controllers\UserController', 'login']);

if ($_SERVER["REQUEST_URI"] == "POST") {
    $router->register('/register', ['Controllers\UserController', 'register']);
}
(new App($router, $_SERVER['REQUEST_URI']))->run();
