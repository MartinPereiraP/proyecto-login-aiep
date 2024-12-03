<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * El modelo de contacto utilizado por este controlador.
     */
    private $userModel;

    /**
     * Crea una nueva instancia de Controller.
     */
    public function __construct()
    {
        // Inicializa el modelo.
        $this->userModel = new User();
    }

    public function index()
    {
        $users = $this->userModel->all();

        return  $this->view('admin.users.index', [
            'title' => 'Panel Users'
        ], compact('users'));
    }

    public function create()
    {
        // Crear Una instacia nueva para el modelo
        $user = new User();

        // Retorna la vista del formulario de creación de users.
        return $this->view('admin.users.create', compact('user'));
    }

    public function store()
    {
        $user = $this->userModel->create([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);
    }

    public function show($id)
    {
        // Buscar un user por ID
        $user = $this->userModel->find($id);

        return $user;
        return 'Mostrar user con ID: ' . $id;
    }

    public function edit($id)
    {
        return 'Formulario de edición de user con ID: ' . $id;
    }

    public function update($id)
    {
        return 'Actualizar user con ID: ' . $id;
    }

    public function destroy($id)
    {
        return 'Eliminar user con ID: ' . $id;
    }
}
