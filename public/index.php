<?php
// public/index.php

// Establecer la configuración de errores de PHP para producción
// Para que los errores se muestren solo en el registro de errores
// y no en la salida al usuario.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Asegurarse de que solo se acceda al script desde el servidor web
if ($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR']) {
    http_response_code(403);
    exit;
}
define('BASE_PATH', dirname(__DIR__)); // Define la ruta base del proyecto

// Cargar el autoload de Composer para cargar automáticamente todas las clases
require_once BASE_PATH . '/vendor/autoload.php';
// Cargar el autoload de Composer para cargar automáticamente todas las clases.
require_once __DIR__ . '/../config/app.php';
// Cargar el archivo de rutas
require_once __DIR__ . '/../routes/web.php';
