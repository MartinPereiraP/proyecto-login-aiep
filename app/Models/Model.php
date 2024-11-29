<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

class Model
{
    protected $connection; // Objeto PDO de la conexión a la base de datos.
    protected $query;      // Última consulta ejecutada.
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
        // Cargar la configuración de la base de datos
        $config = require __DIR__ . '/../../config/database.php';

        // Construir el DSN (Data Source Name)
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        try {
            // Crear una nueva conexión PDO
            $connection = new PDO($dsn, $config['username'], $config['password']);

            // Configurar PDO para lanzar excepciones en caso de errores
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection;
        } catch (PDOException $e) {
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }

        return $dsn;
    }

    /**
     * Ejecutar una consulta SQL.
     *
     * @param string $sql La consulta SQL.
     * @param array $params Parámetros para la consulta preparada.
     * @return mixed Resultado de la consulta.
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);

            // Ejecutar la consulta con los parámetros proporcionados
            $stmt->execute($params);

            // Almacenar la consulta ejecutada
            $this->query = $stmt;

            return $stmt;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve todos los registros de la tabla asociada.
     *
     * @return array Los registros como un arreglo asociativo.
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve un registro por su ID.
     *
     * @param int $id El ID del registro a buscar.
     * @return array|null El registro encontrado o null si no existe.
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->query($sql, ['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
