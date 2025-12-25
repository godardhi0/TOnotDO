<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, role)
            VALUES (:username, :email, :password, :role)
        ");

        return $stmt->execute([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => $data['role']
        ]);
    }

    public function findbyRole($role)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET username = :username, email = :email WHERE id = :id
        ");
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'id' => $id
        ]);
    }

    public function updatePassword($id, $password)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET password = :password WHERE id = :id
        ");
        return $stmt->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
