<?php

use Core\Session;

session_start();

// Set the default timezone to Philippine Time (PHT)
date_default_timezone_set('Asia/Manila');

const BASE_PATH = __DIR__ . '/../' ;
const BASE_URL = 'http://localhost/PHP%202025/PHP%20Template/public/index.php/';

require BASE_PATH . 'Core/functions.php';

/**
 * Autolaods every file it encounters
*/
spl_autoload_register(function ($class) {

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("{$class}.php");
});

$router = new Core\Router;

require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['__method'] ?? $_SERVER['REQUEST_METHOD'];

try {
    Session::set('__url', 'last_url', $uri);
    $router->route($uri, $method);
} catch (PDOException $e) {
    "Error within public/index.php {$e}";
}

Session::unflash();