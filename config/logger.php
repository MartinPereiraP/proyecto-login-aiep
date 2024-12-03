<?php
// config/logger.php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return function () {
    $logFile = __DIR__ . '/../storage/logs/app.log';

    try {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
        $logger->info('Logger inicializado correctamente.');
    } catch (Exception $e) {
        error_log("Error al inicializar el logger: " . $e->getMessage());
    }

    return $logger ?? null;
};
