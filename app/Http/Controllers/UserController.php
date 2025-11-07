<?php

namespace App\Http\Controllers;

use Core\Controller;
use App\Models\User;
use Core\Database;
use Core\Request;

class UserController extends Controller
{

    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function index()
    {

        return view('index.view.php', [
            'title' => 'Home Page',
        ]);
    }

    public function store()
    {

        $request = new Request();

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:10|confirmed'
        ]);

        dd($data);

    }
}
