<?php

namespace Core\Middleware;

class Guest
{

    public function handle()
    {

        if (isset($_SESSION['__currentUser']['credentials'])) {
            header('location: 403');
            exit();
        }
    }
}
