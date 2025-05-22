<?php

namespace App\Http\Controllers;

use Core\Controller;

class UserController extends Controller
{

    public function index()
    {

        return $this->view('index.view.php', [
            'title' => 'Home Page',
        ]);
    }

    public function store()
    {

        $data = $this->request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:10|confirmed'
        ]);

        dd($data);

        // $data = [
        //     'email' => $this->request('email'),
        //     'password' => $this->request('password'),
        //     'password_confirmation' => $this->request('password_confirmation')
        // ];

        // $this->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|min:5|max:10|confirmed'
        // ]);
    }
}
