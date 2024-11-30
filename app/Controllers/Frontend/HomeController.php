<?php

namespace App\Controllers\Frontend;

use App\Controllers\Controller;
use App\Models\Usuario;

class HomeController extends Controller
{
    /**
     * El modelo de contacto utilizado por este controlador.
     */
    // private $contactModel;

    /**
     * Crea una nueva instancia de ContactController.
     */
    // public function __construct()
    // {
    //     // Inicializa el modelo de contacto.
    //     $this->contactModel = new Usuario();
    // }


    public function index()
    {
        // $usuarios = $this->contactModel->all();

        // return $usuarios;
        return $this->view('frontend.index', [
            'title' => 'Home'
        ]);
    }
}
