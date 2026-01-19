<?php

require_once __DIR__ . '/../config/database.php';

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /* =========================
     * GENERIC CRUD
     * ========================= */

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
            WHERE {$this->primaryKey} = :id"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM {$this->table}"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} 
            WHERE {$this->primaryKey} = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    protected function insert(array $data): bool
    {
        $fields = array_keys($data);
        $columns = implode(',', $fields);
        $params = ':' . implode(',:', $fields);

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ($columns) 
            VALUES ($params)"
        );

        return $stmt->execute($data);
    }

    protected function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(
            fn($f) => "$f = :$f",
            array_keys($data)
        ));

        $data['id'] = $id;

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET $set 
            WHERE {$this->primaryKey} = :id"
        );

        return $stmt->execute($data);
    }
}