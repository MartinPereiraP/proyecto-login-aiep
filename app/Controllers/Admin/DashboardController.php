<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{

    /**
     * El modelo de contacto utilizado por este controlador.
     */
    private $userModel;

    /**
     * Crea una nueva instancia de ContactController.
     */
    public function __construct()
    {
        // Inicializa el modelo de contacto.
        $this->userModel = new User();
    }

    public function index()
    {
        // Obtiene todos los users.
        $users = $this->userModel->paginate(3);

        return $users;

        return $this->view('admin.index', [
            'title' => 'Dashboard'
        ], compact('users'));
    }
}
