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

function error($input)
{

    $errors = Core\Session::get('__flash', 'data')['errors'][$input] ?? null;

    if (isset($_SESSION['__flash']['data']['errors'][$input])) {
        echo "<ul class='m-0 p-0' style='list-style: none;'>";
        foreach ($errors as $error) {
            echo "<li class='text-danger'>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
}

function abort($code = 404)
{
    http_response_code($code);

    require base_path("resources/views/{$code}.view.php");

    die();
}
