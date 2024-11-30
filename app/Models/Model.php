<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

class Model
{
    protected $connection; // Objeto PDO de la conexión a la base de datos.
    protected $table;      // Nombre de la tabla asociada al modelo.

    public function __construct()
    {
        $this->connection = $this->createConnection();
    }

    /**
     * Crea una nueva conexión a la base de datos utilizando PDO.
     *
     * @throws Exception si la conexión a la base de datos falla.
     * @return PDO el objeto PDO de la conexión a la base de datos.
     */
    private function createConnection()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        try {
            $connection = new PDO($dsn, $config['username'], $config['password']);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }
    }

    /**
     * Recupera todos los registros de la tabla asociada.
     *
     * @return array Un arreglo de registros.
     */
    public function all()
    {
        if (!$this->table) {
            throw new Exception('No se ha definido la propiedad $table en el modelo.');
        }

        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->connection->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
