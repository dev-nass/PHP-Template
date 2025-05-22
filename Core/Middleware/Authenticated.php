<?php

namespace Core\Middleware;

class Authenticated
{

    public function handle($role)
    {

        if (! isset($_SESSION['__currentUser']) ?? false) {
            header('location: 403');
            exit();
        }

        if ($_SESSION['__currentUser']['credentials']['role'] !== $role) {
            header('location: 403');
            exit();
        }
    }
}
