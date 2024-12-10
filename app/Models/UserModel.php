<?php

namespace App\Models;

use App\Models\Paginador;

require_once "DataBase.php";

class UserModel
{
    private $db;

    public function __construct()
    {
        // Usamos la base de datos de usuarios (users)
        $this->db = Database::getInstance('users');
        $this->db->setTable('users'); // Configuramos la tabla 'users'
    }

    // Obtener todos los usuarios
    public function getAllUsers(): array
    {
        return $this->db->getAll();
    }

    // Obtener un usuario por su ID
    public function getUserById(int $id): ?array
    {
        $result = $this->db->getWhere(['id' => $id]);
        return $result[0] ?? null;
    }

    public function getUserByUsername(string $name): ?array
    {
        $result = $this->db->getWhere(['name' => $name]);
        return $result[0] ?? null;
    }


    // Agregar un nuevo usuario
    public function addUser(array $user): int
    {
        return $this->db->insert($user);
    }

    // Actualizar un usuario existente
    public function updateUser(int $id, array $data): bool
    {
        return $this->db->update($data, ['id' => $id]);
    }

    // Eliminar un usuario
    public function deleteUser(int $id): bool
    {
        return $this->db->delete(['id' => $id]);
    }

    // Contar el total de usuarios
    public function countUsers(): int
    {
        return $this->db->count();
    }


    // Obtener los usuarios con paginación
    public function getUsersPaginated(int $perPage = 10, int $currentPage = 1): array
    {
        // Obtenemos todos los usuarios
        $allUsers = $this->getAllUsers();

        // Usamos el Paginador para paginar los datos
        $paginator = new Paginador($allUsers, $perPage, $currentPage);

        return [
            'users' => $paginator->getResults(), // Datos de la página actual
            'paginationLinks' => $paginator->getPaginationLinks('?ctrl=UserCtrl&action=Listar') // Enlaces de paginación
        ];
    }

    // Verificar si las credenciales de usuario son correctas (para login)
    public function verifyUserCredentials(string $username, string $password): ?array
    {
        $user = $this->db->getWhere(['name' => $username]);

        if ($user) {
            // Verificamos que la contraseña coincida (suponiendo que se guarda con hash)
            if (password_verify($password, $user[0]['password'])) {
                return $user[0]; // Retornamos el usuario si las credenciales son correctas
            }
        }

        return null; // Retorna null si no se encuentra el usuario o las credenciales no son correctas
    }
}
