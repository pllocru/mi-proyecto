<?php

namespace App\Models;

require_once realpath(__DIR__ . '/../../config.php');

use PDO;
use PDOException;

class DataBase
{
    private $connection;
    private static $instances = [];
    private $table = "";

    private function __construct()
    {
        try {
            // Crear conexión con PDO
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASSWORD);

            // Configurar errores de PDO para que arrojen excepciones
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    // Método para obtener la instancia de la base de datos
    public static function getInstance($key = 'default')
    {
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new self();
        }
        return self::$instances[$key];
    }

    // Método para establecer la tabla
    public function setTable($table)
    {
        $this->table = $table;
    }

    // Método para obtener el nombre de la tabla
    public function getTable()
    {
        return $this->table;
    }

    public function query($query, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            // Ejecutar la consulta
            $stmt->execute($params);

            // Si la consulta es SELECT, devolver los resultados
            if (str_starts_with(strtoupper($query), 'SELECT')) {
                return $stmt->fetchAll();
            }

            // Si es otra consulta (INSERT, UPDATE, DELETE), devolver true
            return true;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->getTable();
        return $this->query($query);
    }

    public function getWhere($conditions = [])
    {
        $query = "SELECT * FROM " . $this->getTable();
        $params = [];
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "$column = :$column";
                $params[":$column"] = $value;
            }
            $query .= implode(' AND ', $clauses);
        }
        return $this->query($query, $params);
    }

    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO " . $this->getTable() . " ($columns) VALUES ($placeholders)";
        $this->query($query, $this->prepareParams($data));
        return $this->connection->lastInsertId();
    }

    public function update($data, $conditions)
    {
        $setClause = [];
        $params = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $query = "UPDATE " . $this->getTable() . " SET " . implode(', ', $setClause);

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "$column = :cond_$column";
                $params[":cond_$column"] = $value;
            }
            $query .= implode(' AND ', $clauses);
        }

        return $this->query($query, $params);
    }

    public function delete($conditions)
    {
        $query = "DELETE FROM " . $this->getTable();
        $params = [];
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "$column = :$column";
                $params[":$column"] = $value;
            }
            $query .= implode(' AND ', $clauses);
        }

        return $this->query($query, $params);
    }

    public function count($conditions = [])
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->getTable();
        $params = [];
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "$column = :$column";
                $params[":$column"] = $value;
            }
            $query .= implode(' AND ', $clauses);
        }
        $result = $this->query($query, $params);
        return $result[0]['total'] ?? 0;
    }

    private function prepareParams($data)
    {
        $params = [];
        foreach ($data as $column => $value) {
            $params[":$column"] = $value;
        }
        return $params;
    }
}
