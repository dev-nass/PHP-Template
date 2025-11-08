<?php

function dump($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($value)
{
    return $_SERVER['REQUEST_URL'] === `/.../.../public/index.php/{$value}`;
}

function base_path($path)
{
    return str_replace('\\', '/', BASE_PATH . $path);
}

function old($input)
{
    return Core\Session::get('__flash', 'data')['old'][$input] ?? null;
}

/**
 * Tailwind CSS styled error message display
 */
function error($input_name)
{

    $errors = $_SESSION['__flash']['errors'][$input_name];

    if (empty($errors)) {
        return;
    }

    echo "<p class='text-red-500 text-xs italic mt-2'>";
    echo $errors[0];
    echo "</p>";

    return;
}


function view($path, $attribute = [])
{

    extract($attribute);

    $viewPath = base_path("resources/views/{$path}");

    if (!file_exists($viewPath)) {
        respond(['error' => 'View not found'], 500);
    }

    require base_path("resources/views/{$path}");
}

function redirect($path)
{
    header("location: {$path}");
    exit();
}

/**
 * Shows a responsive message in the form of JSON
 * on the screen. Mostly, erros msgs.
 * 
 * Also used for sending PHP errors to a JS file
 */
function respond($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function abort($code = 404)
{
    http_response_code($code);

    require base_path("resources/views/{$code}.view.php");

    die();
}
