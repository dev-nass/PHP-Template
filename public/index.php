<?php

use Core\Session;

const BASE_PATH = __DIR__ . '/../' ;

require BASE_PATH . '/vendor/autoload.php';

// registers the detected .env file and make it available to $_ENV, $_SERVER, and getenv()
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();

// Set the default timezone to Philippine Time (PHT)
date_default_timezone_set('Asia/Manila');

require BASE_PATH . 'Core/functions.php';


/**
 * Autolaods every file it encounters
*/
// spl_autoload_register(function ($class) {

//     $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
//     dump($class);
//     require base_path("{$class}.php");
// });


$router = new Core\Router;

require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['__method'] ?? $_SERVER['REQUEST_METHOD'];

// dump($_ENV['APP_URL']); added for testing
// dum($_SERVER['REQUEST_URI']); added for testing

try {
    Session::set('__url', 'last_url', $uri);
    $router->route($uri, $method);
} catch (PDOException $e) {
    "Error within public/index.php {$e}";
}

Session::unflash();