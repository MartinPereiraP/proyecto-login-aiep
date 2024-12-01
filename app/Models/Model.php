<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

class Model
{
    protected $connection; // Objeto PDO para la conexión a la base de datos.
    protected $query;      // Última consulta ejecutada.
    protected $table;      // Nombre de la tabla asociada al modelo.
    protected $orderBy = ''; // Cláusula ORDER BY opcional.

    public function __construct()
    {
        $this->connection = $this->createConnection();
    }

    /**
     * Crea una conexión a la base de datos utilizando PDO.
     *
     * @throws Exception si la conexión a la base de datos falla.
     * @return PDO El objeto PDO para la conexión.
     */
    private function createConnection()
    {
        // Cargar la configuración de la base de datos
        $config = require __DIR__ . '/../../config/database.php';

        // Construir DSN
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        try {
            $connection = new PDO($dsn, $config['username'], $config['password']);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (Exception $e) {
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta SQL en la base de datos.
     *
     * @param string $sql Consulta SQL.
     * @param array $data Parámetros de la consulta preparada.
     * @return $this Retorna la instancia actual para encadenamiento.
     */
    public function query($sql, $data = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        $this->query = $stmt;
        return $this;
    }

    /**
     * Ejecuta una consulta SELECT con condiciones.
     *
     * @param string $column Nombre de la columna para la condición.
     * @param string $operator Operador de comparación.
     * @param mixed $value Valor para la comparación.
     * @return $this Retorna la instancia actual para encadenamiento.
     */
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        $this->query($sql, [$value]);

        return $this;
    }

    /**
     * Devuelve el primer registro de la consulta.
     *
     * @return array|null
     */
    public function first()
    {
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve todos los registros de la consulta.
     *
     * @return array
     */
    public function get()
    {
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve todos los registros de la tabla.
     *
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    /**
     * Busca un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return array|null
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id])->first();
    }

    public function paginate($perPage = 15)
    {
        // Obtener la página actual desde los parámetros de la URL
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        // Calcular el desplazamiento
        $offset = ($currentPage - 1) * $perPage;

        // Consulta para obtener los datos paginados
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} {$this->orderBy} LIMIT :offset, :perPage";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener el número total de registros
        $totalStmt = $this->connection->query("SELECT FOUND_ROWS()");
        $total = (int) $totalStmt->fetchColumn();

        // Calcular el total de páginas
        $totalPages = (int) ceil($total / $perPage);

        // Construir la URL base para los enlaces de paginación
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $queryParams = $_GET;
        unset($queryParams['page']);
        $baseQuery = http_build_query($queryParams);
        $baseUrl = $uri . ($baseQuery ? '?' . $baseQuery . '&' : '?');

        // Generar los datos de la paginación
        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => $totalPages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total),
            'prev_page_url' => $currentPage > 1 ? "{$baseUrl}page=" . ($currentPage - 1) : null,
            'next_page_url' => $currentPage < $totalPages ? "{$baseUrl}page=" . ($currentPage + 1) : null,
            'data' => $data,
        ];
    }

    /**
     * Crea un nuevo registro en la tabla.
     *
     * @param array $data Datos del nuevo registro.
     * @return array|null Registro recién creado.
     */
    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));

        return $this->find($this->connection->lastInsertId());
    }

    /**
     * Actualiza un registro en la tabla.
     *
     * @param int $id ID del registro a actualizar.
     * @param array $data Datos a actualizar.
     * @return array|null Registro actualizado.
     */
    public function update($id, $data)
    {
        $fields = implode(', ', array_map(fn($key) => "{$key} = ?", array_keys($data)));

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $this->query($sql, [...array_values($data), $id]);

        return $this->find($id);
    }

    /**
     * Elimina un registro de la tabla.
     *
     * @param int $id ID del registro.
     * @return bool Indica si la operación fue exitosa.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        return $this->query->rowCount() > 0;
    }
}
