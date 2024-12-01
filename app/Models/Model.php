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
     * Ejecuta una consulta en la base de datos.
     *
     * @param string $sql La consulta SQL a ejecutar.
     */
    public function query($sql, $data = [], $params = null)
    {
        if ($data) {
            if ($params === null) {
                $params = str_repeat('s', count($data));
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param($params, ...$data);
            $stmt->execute();
            $this->query = $stmt->get_result();
        } else {
            $this->query = $this->connection->query($sql);
        }

        return $this;
    }

    /**
     * Ejecuta una consulta SELECT en la base de datos.
     *
     * @param string $column La columna a buscar.
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
     * Ordena los resultados de la consulta.
     *
     * @param string $column La columna a ordenar.
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY {$column} {$direction}";
        return $this;
    }

    /**
     * Devuelve el primer resultado de la consulta.
     *
     * @return array
     */
    public function first()
    {
        // Retorna un array asociativo que representa la primera fila de los resultados de la consulta SQL.
        return $this->query->fetch_assoc();
    }

    /**
     * Devuelve todos los resultados de la consulta, pero puede filtrarlos
     *
     * @return array
     */
    public function get()
    {
        // Retorna un array de arrays asociativos que representa todas las filas de los resultados de la consulta SQL.
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Pagina los resultados de una consulta SQL.
     *
     * @param int $cant La cantidad de resultados por página. Por defecto es 15.
     *
     * @return array Retorna un array que contiene los datos paginados y la información de la paginación.
     */
    public function paginate($cant = 15)
    {
        // Obtiene la página actual de la URL o asume que es la página 1 si no se proporciona.
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        // Crea la consulta SQL para obtener los resultados de la página actual.
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} LIMIT " . (($page - 1) * $cant) . ", {$cant}";

        // Ejecuta la consulta SQL y obtiene los resultados.
        $data = $this->query($sql)->get();

        // Obtiene el total de resultados sin tener en cuenta la cláusula LIMIT.
        $total = $this->query("SELECT FOUND_ROWS() as total")->first()['total'];

        // Prepara la URL para los enlaces de paginación.
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        if (strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // Calcula la última página.
        $last_page = ceil($total / $cant);

        // Retorna los datos paginados y la información de la paginación.
        return [
            'total' => $total,
            'from' => (($page - 1) * $cant) + 1,
            'to' => (($page - 1) * $cant) + count($data),
            'current_page' => $page,
            'last_page' => $last_page,
            'prev_page_url' => $page > 1 ? '/' . $uri . '?page=' . ($page - 1) : null,
            'next_page_url' => $page < $last_page ? '/' . $uri . '?page=' . ($page + 1) : null,
            'data' => $data,
        ];
    }

    /**
     * Devuelve todos los resultados de la consulta.
     *
     * @return array
     */
    public function all()
    {
        // Crea la consulta SQL para obtener todos los registros de la tabla.
        $sql = "SELECT * FROM {$this->table}";

        // Ejecuta la consulta SQL y retorna los resultados.
        return $this->query($sql)->get();
    }

    /**
     * Devuelve un resultado específico de la consulta.
     *
     * @param int $id El ID del resultado a buscar.
     */
    public function find($id)
    {
        // Crea la consulta SQL para obtener el registro con el ID especificado.
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";

        // Ejecuta la consulta SQL y retorna el resultado.
        return $this->query($sql, [$id], 'i')->first();
    }

    /**
     * Crea un nuevo registro en la base de datos.
     *
     * @param array $data Los datos a insertar.
     */
    public function create($data)
    {
        // Obtiene los nombres de las columnas de los datos y los une en una cadena.
        $columns = array_keys($data);
        $columns = implode(', ', $columns);

        // Obtiene los valores de los datos.
        $values = array_values($data);

        // Crea la consulta SQL para insertar el nuevo registro.
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (" . str_repeat('?, ', count($values) - 1) . " ?)";

        // Ejecuta la consulta SQL.
        $this->query($sql, $values);

        // Obtiene el ID del nuevo registro.
        $insert_id = $this->connection->insert_id;

        // Retorna el nuevo registro.
        return $this->find($insert_id);
    }

    /**
     * Actualiza un registro en la base de datos.
     *
     * @param int $id El ID del registro a actualizar.
     */
    public function update($id, $data)
    {
        // Prepara los campos y los valores para la consulta SQL.
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
        }
        $fields = implode(', ', $fields);

        // Crea la consulta SQL para actualizar el registro.
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";

        // Prepara los valores para la consulta SQL.
        $values = array_values($data);
        $values[] = $id;

        // Ejecuta la consulta SQL.
        $this->query($sql, $values);

        // Retorna el registro actualizado.
        return $this->find($id);
    }

    /**
     * Elimina un registro de la base de datos.
     *
     * @param int $id El ID del registro a eliminar.
     */
    public function delete($id)
    {
        // Crea la consulta SQL para eliminar el registro con el ID especificado.
        $sql = "DELETE FROM {$this->table} WHERE id = ?";

        // Ejecuta la consulta SQL y retorna la propia instancia de la clase para permitir encadenamiento de métodos.
        return $this->query($sql, [$id], 'i');
    }
}
