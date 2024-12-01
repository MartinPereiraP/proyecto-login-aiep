<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Usuario;

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
        $this->userModel = new Usuario();
    }

    public function index()
    {
        $usuarios = $this->userModel->all();

        return  $this->view('admin.users.index', [
            'title' => 'Panel Usuarios'
        ], compact('usuarios'));
    }

    public function create()
    {
        // Crear Una instacia nueva para el modelo
        $usuario = new Usuario();

        // Retorna la vista del formulario de creación de users.
        return $this->view('admin.users.create', compact('usuario'));
    }

    public function store()
    {
        $usuario = $this->userModel->create([
            'nombre' => $_POST['nombre'],
            'correo' => $_POST['correo'],
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);
    }

    public function show($id)
    {
        // Buscar un usuario por ID
        $usuario = $this->userModel->find($id);

        return $usuario;
        return 'Mostrar usuario con ID: ' . $id;
    }

    public function edit($id)
    {
        return 'Formulario de edición de usuario con ID: ' . $id;
    }

    public function update($id)
    {
        return 'Actualizar usuario con ID: ' . $id;
    }

    public function destroy($id)
    {
        return 'Eliminar usuario con ID: ' . $id;
    }
}
