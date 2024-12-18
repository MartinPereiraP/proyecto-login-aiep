<?php

namespace App\Models;

use Exception;
use PDO;

class User extends Model
{
    /**
     * Nombre de la tabla asociada a este modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'users';
    protected $logger;

    /**
     * Metodos privados
     *
     * @var string
     */
    private $name;
    private $email;
    private $password;

    /**
     * Busca un usuario por su correo electrónico.
     *
     * @param string $email El correo electrónico del usuario.
     * @return array|null Un arreglo con los datos del usuario o null si no se encuentra.
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     *
     * @return void
     * @throws Exception Si ocurre un error al guardar los datos.
     */
    public function save()
    {
        $sql = "INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        if (!$stmt->execute()) {
            throw new Exception('Error al guardar el usuario en la base de datos.');
        }
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($hashedPassword)
    {
        $this->password = $hashedPassword;
    }
}
