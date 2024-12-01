<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function login()
    {
        return $this->view('auth.login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Autentica al user y gestiona la sesión.
     */
    public function authenticate()
    {
        try {
            $email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? null);

            if (!$email || !$password) {
                throw new Exception('Correo y contraseña son obligatorios.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo electrónico inválido.');
            }

            $user = (new User())->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception('Credenciales incorrectas.');
            }

            $this->startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            $this->redirect('/admin/index');
        } catch (Exception $e) {
            $this->redirect('/auth/login?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Cierra la sesión y redirige al login.
     */
    public function logout()
    {
        session_start();
        session_destroy();

        $this->redirect('/auth/login');
    }

    /**
     * Muestra la vista de registro.
     */
    public function register()
    {
        return $this->view('auth.register', [
            'title' => 'Register'
        ]);
    }

    /**
     * Almacena un nuevo user en la base de datos.
     */
    public function store()
    {
        try {
            $email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? null);

            if (!$email || !$password) {
                throw new Exception('Correo y contraseña son obligatorios.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo electrónico inválido.');
            }

            if (strlen($password) < 8) {
                throw new Exception('La contraseña debe tener al menos 8 caracteres.');
            }

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->save();

            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->redirect('/auth/register?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Inicia una nueva sesión o reanuda la existente.
     */
    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}
