<?php

namespace App\Providers;

use InvalidArgumentException;

/**
 * Clase RouteProvider
 *
 * Un simple enrutador (router) de PHP que permite registrar rutas y sus respectivos
 * callbacks para diferentes métodos HTTP (GET, POST, PUT, DELETE).
 */
class RouteProvider
{
    /**
     * Array que contiene todas las rutas registradas, organizadas por método HTTP.
     *
     * @var array
     */
    private static $routes = ['GET' => [], 'POST' => [], 'PUT' => [], 'DELETE' => []];

    /**
     * Método privado para validar y agregar una ruta al array de rutas.
     *
     * @param string $method El método HTTP de la ruta.
     * @param string $uri El URI de la ruta.
     * @param callable|array $callback El callback que se ejecutará cuando se acceda a la ruta.
     *
     * @throws InvalidArgumentException Si el URI no es un string o el callback no es callable o un array.
     */
    private static function validateRoute($method, $uri, $callback)
    {
        if (!is_string($uri) || !(is_callable($callback) || is_array($callback))) {
            throw new InvalidArgumentException('El URI debe ser un string y el callback debe ser callable o un array');
        }

        // Eliminar los slashes iniciales y finales del URI y registrar la ruta en el array de rutas
        $uri = trim($uri, '/');
        self::$routes[strtoupper($method)][$uri] = $callback;
    }

    /**
     * Método para agregar una ruta GET al array de rutas.
     *
     * @param string $uri El URI de la ruta.
     * @param callable|array $callback El callback que se ejecutará cuando se acceda a la ruta.
     */
    public static function get($uri, $callback)
    {
        self::validateRoute('GET', $uri, $callback);
    }

    /**
     * Método para agregar una ruta POST al array de rutas.
     *
     * @param string $uri El URI de la ruta.
     * @param callable|array $callback El callback que se ejecutará cuando se acceda a la ruta.
     */
    public static function post($uri, $callback)
    {
        self::validateRoute('POST', $uri, $callback);
    }

    /**
     * Método para agregar una ruta PUT al array de rutas.
     *
     * @param string $uri El URI de la ruta.
     * @param callable|array $callback El callback que se ejecutará cuando se acceda a la ruta.
     */
    public static function put($uri, $callback)
    {
        self::validateRoute('PUT', $uri, $callback);
    }

    /**
     * Método para agregar una ruta DELETE al array de rutas.
     *
     * @param string $uri El URI de la ruta.
     * @param callable|array $callback El callback que se ejecutará cuando se acceda a la ruta.
     */
    public static function delete($uri, $callback)
    {
        self::validateRoute('DELETE', $uri, $callback);
    }

    /**
     * Método para agregar una ruta de tipo resource al array de rutas.
     *
     * @param string $uri El URI de la ruta.
     * @param string $controller El nombre de la clase del controlador.
     */
    public static function resource($uri, $controller)
    {
        // Agrega las rutas de un recurso RESTful al array de rutas
        self::get($uri, [$controller, 'index']);
        self::get($uri . '/create', [$controller, 'create']);
        self::post($uri, [$controller, 'store']);
        self::get($uri . '/:id', [$controller, 'show']);
        self::get($uri . '/:id/edit', [$controller, 'edit']);
        self::put($uri . '/:id', [$controller, 'update']);
        self::delete($uri . '/:id', [$controller, 'destroy']);
    }

    /**
     * Método para manejar la solicitud actual y ejecutar el callback de la ruta correspondiente.
     *
     * @throws InvalidArgumentException Si el método HTTP de la solicitud no está soportado.
     */
    public static function dispatch()
    {
        // Obtiene la URI de la solicitud actual, eliminando los slashes iniciales y finales si existen
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        // Si hay una cadena de consulta en la URI, se remueve para ignorarla en el enrutamiento
        if (strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // Obtiene el método HTTP de la solicitud (GET, POST, PUT, DELETE, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Verifica si el método HTTP está definido en las rutas del enrutador
        if (!isset(self::$routes[$method])) {
            // Si el método no está permitido, devuelve una respuesta 405 (Método no permitido) y sale de la función
            http_response_code(405);
            echo "405 - Método {$method} no permitido";
            return;
        }

        // Recorre las rutas registradas para el método HTTP de la solicitud actual
        foreach (self::$routes[$method] as $route => $callback) {
            // Si la ruta contiene parámetros dinámicos definidos con ':' (por ejemplo, /users/:id), se los convierte a expresiones regulares
            if (strpos($route, ':') !== false) {
                $route = preg_replace('#:[a-zA-Z]+#', '([a-zA-Z0-9-]+)', $route);
            }

            // Comprueba si la URI actual coincide con la ruta definida en el enrutador
            if (preg_match("#^$route$#", $uri, $matches)) {
                // Si hay coincidencia de ruta, se extraen los parámetros capturados en la expresión regular
                $params = array_slice($matches, 1);

                // Ejecuta el callback asociado a la ruta
                if (is_callable($callback)) {
                    // Si el callback es una función anónima o un callable, se llama con los parámetros obtenidos
                    $response = $callback(...$params);
                } elseif (is_array($callback)) {
                    // Si el callback es un array, se asume que es un controlador (clase y método) y se ejecuta el método correspondiente
                    list($class, $method) = $callback;
                    if (!class_exists($class)) {
                        // Si la clase del controlador no existe, se lanza una excepción
                        throw new InvalidArgumentException("La clase {$class} no existe");
                    }

                    $controller = new $class;
                    if (!method_exists($controller, $method)) {
                        // Si el método del controlador no existe, se lanza una excepción
                        throw new InvalidArgumentException("El método {$method} de la clase {$class} no existe");
                    }

                    // Llama al método del controlador con los parámetros obtenidos
                    $response = $controller->{$method}(...$params);
                } else {
                    // Si el callback no es válido, se lanza una excepción
                    throw new InvalidArgumentException('El callback no es válido');
                }

                // Procesa la respuesta obtenida del callback y la envía al cliente
                if (is_array($response) || is_object($response)) {
                    // Si la respuesta es un objeto o un array, se envía como JSON
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    // Si la respuesta es otro tipo, se imprime directamente
                    echo $response;
                }

                // Finaliza la ejecución del script después de enviar la respuesta
                return;
            }
        }

        // Si ninguna ruta coincide, se devuelve un error 404
        http_response_code(404);
        echo '404 - Página no encontrada';
    }
}
