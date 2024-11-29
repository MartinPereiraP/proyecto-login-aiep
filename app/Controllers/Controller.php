<?php

namespace App\Controllers;

use Exception;

class Controller
{
    /**
     * Muestra una vista.
     *
     * @param string $route La ruta de la vista a mostrar.
     * @param array $data Datos que se pasarán a la vista.
     * @return string El contenido de la vista.
     * @throws Exception Si la vista no se encuentra.
     */
    public function view(string $route, array $data = []): string
    {
        // Extrae los datos en variables separadas para que estén disponibles en la vista.
        extract($data, EXTR_SKIP);

        // Convierte la ruta de la vista a una ruta de archivo.
        $route = str_replace('.', '/', $route);

        // Define el directorio base de las vistas.
        $viewDirectory = '../resources/views/';

        // Crea el path completo hacia la vista.
        $viewPath = $viewDirectory . $route . '.php';

        // Verifica si el archivo de la vista existe antes de intentar incluirlo.
        if (!file_exists($viewPath)) {
            throw new Exception("View not found at {$viewPath}");
        }

        // Inicia el buffer de salida.
        ob_start();

        // Incluye el archivo de la vista.
        include $viewPath;

        // Obtiene el contenido del buffer de salida y luego limpia el buffer.
        return ob_get_clean();
    }

    /**
     * Redirige al usuario a una nueva ruta.
     *
     * @param string $route La ruta a la que redirigir al usuario.
     * @return void
     */
    public function redirect(string $route): void
    {
        // Envía un encabezado de redirección al navegador.
        header("Location: {$route}");

        // Termina la ejecución del script.
        exit;
    }
}
