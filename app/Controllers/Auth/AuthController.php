<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\Usuario;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        return $this->view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate()
    {
        // Sanitizar y validar datos?

        try {
            // Obtener datos del formulario
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                throw new Exception('Correo y contraseña son obligatorios.');
            }

            // Buscar al usuario en la base de datos
            $usuario = (new Usuario())->findByEmail($email);

            // if ($usuario) {
            //     var_dump($usuario['password']); // Verifica que contiene el hash
            // }


            if ($usuario && $usuario['password'] && password_verify($password, $usuario['password'])) {
                // Contraseña válida
            } else {
                throw new Exception('Credenciales incorrectas.');
            }

            // Iniciar sesión (almacenar en $_SESSION)
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_email'] = $usuario['correo'];

            // Redirigir al dashboard o página principal
            header('Location: /admin/index'); // Cambia a tu ruta de dashboard
            exit;
        } catch (Exception $e) {
            // Mostrar errores o redirigir con un mensaje de error
            echo 'Error: ' . $e->getMessage();
        }
    }



    public function logout()
    {
        // Cerrar la sesión (eliminar $_SESSION)
        session_start();
        session_destroy();

        // Redirigir al login
        header('Location: /auth/login');
        exit;
    }

    public function register()
    {
        return $this->view('auth.register', [
            'title' => 'Register'
        ]);
    }

    public function store()
    {
        try {
            // Obtener datos del formulario
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                throw new Exception('Correo y contraseña son obligatorios.');
            }

            // Crear un nuevo usuario
            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setPassword($password);

            // Guardar el usuario en la base de datos
            $usuario->save();

            // Redirigir al login
            header('Location: /auth/login');
            exit;
        } catch (Exception $e) {
            // Mostrar errores o redirigir con un mensaje de error
            echo 'Error: ' . $e->getMessage();
        }
    }
}
