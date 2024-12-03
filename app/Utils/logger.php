<?php

namespace App\Utils;

class Logger
{
    protected $logFile;

    public function __construct($logFile = null)
    {
        $this->logFile = $logFile ?? __DIR__ . '/../../logs/app.log';
    }

    /**
     * Registra un mensaje en el archivo de log.
     *
     * @param string $message Mensaje a registrar.
     * @param string $level Nivel del log (INFO, ERROR, DEBUG, etc.).
     */
    public function log($message, $level = 'INFO')
    {
        $date = date('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;

        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }

    /**
     * Registra un mensaje de información.
     *
     * @param string $message
     */
    public function info($message)
    {
        $this->log($message, 'INFO');
    }

    /**
     * Registra un mensaje de error.
     *
     * @param string $message
     */
    public function error($message)
    {
        $this->log($message, 'ERROR');
    }

    /**
     * Registra un mensaje de depuración.
     *
     * @param string $message
     */
    public function debug($message)
    {
        $this->log($message, 'DEBUG');
    }
}
