<?php

class Task
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, client_id, worker_id)
            VALUES (:title, :description, :client_id, :worker_id)
        ");

        return $stmt->execute([
            'title'       => $data['title'],
            'description' => $data['description'],
            'client_id'   => $data['client_id'],
            'worker_id'   => isset($data['worker_id']) && $data['worker_id'] !== '' ? $data['worker_id'] : null
        ]);
    }

    public function getByClient($clientId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE client_id = ?");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByWorker($workerId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE worker_id = ?");
        $stmt->execute([$workerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM tasks");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignWorker($taskId, $workerId)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET worker_id = :worker WHERE id = :id
        ");
        return $stmt->execute([
            'worker' => $workerId,
            'id' => $taskId
        ]);
    }

    public function updateStatus($taskId, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET status = :status WHERE id = :id
        ");
        return $stmt->execute([
            'status' => $status,
            'id' => $taskId
        ]);
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks SET title = :title, description = :description, worker_id = :worker
            WHERE id = :id
        ");
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'worker' => $data['worker_id'],
            'id' => $id
        ]);
    }
}
