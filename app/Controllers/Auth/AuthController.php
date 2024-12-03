<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use App\Utils\Logger;
use Exception;

class AuthController extends Controller
{
    protected $logger;

    public function __construct()
    {
        // Configura el logger
        $loggerFactory = require __DIR__ . '/../../../config/logger.php';
        $this->logger = $loggerFactory();
    }

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
     * Autentica al usuario y gestiona la sesión.
     */
    public function authenticate()
    {
        try {
            // Sanitizar y validar entradas
            $email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? null);

            if (!$email || !$password) {
                throw new Exception('Correo y contraseña son obligatorios.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo electrónico inválido.');
            }

            // Buscar al usuario en la base de datos
            $user = (new User())->findByEmail($email);

            if (!$user) {
                throw new Exception('Credenciales incorrectas. Usuario no encontrado.');
            }

            // Verificar contraseña
            if (!password_verify($password, $user['password'])) {
                throw new Exception('Credenciales incorrectas.');
            }

            // Iniciar sesión de manera segura
            $this->startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Registrar el inicio de sesión exitoso
            $this->logger->info("Usuario autenticado: ID {$user['id']}");

            // Redirigir al dashboard
            $this->redirect('/admin/dashboard');
        } catch (Exception $e) {
            // Registrar errores y redirigir con mensaje
            $this->logger->error("Error durante autenticación: " . $e->getMessage());
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
     * Procesa el registro de un nuevo usuario.
     */
    public function store()
    {
        try {
            // Sanitizar y validar entradas
            $name = trim($_POST['name'] ?? null);
            $email = filter_var($_POST['email'] ?? null, FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? null);
            $passwordConfirmation = trim($_POST['password_confirmation'] ?? null);

            if (!$name || !$email || !$password || !$passwordConfirmation) {
                throw new Exception('Todos los campos son obligatorios.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo electrónico inválido.');
            }

            if ($password !== $passwordConfirmation) {
                throw new Exception('Las contraseñas no coinciden.');
            }

            if (strlen($password) < 8) {
                throw new Exception('La contraseña debe tener al menos 8 caracteres.');
            }

            // Verificar que el correo no exista
            $user = (new User())->findByEmail($email);
            if ($user) {
                throw new Exception('El correo ya está registrado.');
            }

            // Crear y guardar el usuario
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $newUser = new User();
            $newUser->setName($name);
            $newUser->setEmail($email);
            $newUser->setPassword($hashedPassword);
            $newUser->save();

            // Redirigir al login con éxito
            $this->redirect('/auth/login?success=Usuario registrado con éxito.');
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
