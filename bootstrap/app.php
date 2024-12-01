<?php
// bootsrap/app.php

use Dotenv\Dotenv;

/**
 * Configuración de la aplicación.
 * Se encarga de cargar las variables de entorno.
 */

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
