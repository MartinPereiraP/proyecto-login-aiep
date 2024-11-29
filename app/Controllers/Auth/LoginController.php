<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;

class LoginController extends Controller
{
    public function login()
    {
        return $this->view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate()
    {
        // Implementar la autenticación

    }
}
