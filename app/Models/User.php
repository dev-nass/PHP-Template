<?php

namespace App\Models;

use Core\Model;

class User extends Model
{

    protected $table = "users";
    protected $role = ""; // for sub classes ('Customers', 'Admin' ...)


    /**
     * Used for logged-in user and redirect them to their proper
     * default pages
     */
    public function loadRoleView()
    {
        $role = $_SESSION['__currentUser']['credentials']['role'];

        $routes = [
        //     'Admin' => 'dashboard',
        //     'Employee' => 'dashboard',
        //     'Rider' => 'assigned-transaction-queue-rider',
        //     'Customer' => 'index',
        ];

        header('Location: ' . $routes[$role] ?? 'index');
        exit();
    }
}
