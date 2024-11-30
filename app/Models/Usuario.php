<?php

namespace App\Models;

use PDO;

class Usuario extends Model
{
    /**
     * Nombre de la tabla asociada a este modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Busca un usuario por su correo electrónico.
     *
     * @param string $email El correo electrónico del usuario.
     * @return array|false Un arreglo con los datos del usuario o false si no se encuentra.
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE correo = :email LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Depuración
        var_dump($email); // Verifica el correo que se busca
        var_dump($stmt->rowCount()); // Verifica si se encontraron filas

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario;
    }
}
