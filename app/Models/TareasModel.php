<?php

namespace App\Models;

use App\Models\Paginador;

require_once "DataBase.php";

class TareasModel
{
    private $db;
    private $dbprovinces;

    public function __construct()
    {

        $this->db = Database::getInstance('tasks');
        $this->db->setTable('tasks'); // Configuramos la tabla 'tasks'

        $this->dbprovinces = Database::getInstance('provinces');
        $this->dbprovinces->setTable('tbl_provincias');
    }


    public function getAllTareas(): array
    {
        return $this->db->getAll();
    }

    public function getAllProvinces(): array
    {

        return $this->dbprovinces->getAll();
    }


    public function getTareaById(int $id): ?array
    {
        $result = $this->db->getWhere(['task_id' => $id]);
        return $result[0] ?? null;
    }


    public function addTarea(array $tarea): int
    {
        return $this->db->insert($tarea);
    }


    public function updateTarea(int $id, array $data): bool
    {
        return $this->db->update($data, ['task_id' => $id]);
    }


    public function deleteTarea(int $id): bool
    {
        return $this->db->delete(['task_id' => $id]);
    }


    public function countTareas(): int
    {
        return $this->db->count();
    }


    public function getTareasByConditions(array $conditions): array
    {
        return $this->db->getWhere($conditions);
    }

    public function getTareasPaginated(int $perPage = 10, int $currentPage = 1): array
    {
        // Obtenemos todas las tareas
        $allTareas = $this->getAllTareas();

        // Usamos el Paginador para paginar los datos
        $paginator = new Paginador($allTareas, $perPage, $currentPage);

        return [
            'tareas' => $paginator->getResults(), // Datos de la página actual
            'paginationLinks' => $paginator->getPaginationLinks('?ctrl=TareasCtrl&action=Listar') // Enlaces de paginación
        ];
    }
}
