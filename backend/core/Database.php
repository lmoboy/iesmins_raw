<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'your_database';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

    public function generateTables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `role` boolean NOT NULL DEFAULT 0
        )';

        $this->query($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `price` float NOT NULL,
            `quantity` int(11) NOT NULL,
            `image` varchar(255) NOT NULL
        )';

        $this->query($sql);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function create($table, $data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        return $this->conn->lastInsertId();
    }

    public function read($table, $conditions = [], $fields = '*')
    {
        $sql = "SELECT {$fields} FROM {$table}";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
            }
            $sql .= implode(' AND ', $where);
        }

        $stmt = $this->query($sql, $conditions);
        return $stmt->fetchAll();
    }

    public function update($table, $data, $conditions)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :set_{$key}";
        }

        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :where_{$key}";
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);

        $params = [];
        foreach ($data as $key => $value) {
            $params["set_{$key}"] = $value;
        }
        foreach ($conditions as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        return $this->query($sql, $params);
    }

    public function delete($table, $conditions)
    {
        $sql = "DELETE FROM {$table}";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
            }
            $sql .= implode(' AND ', $where);
        }

        return $this->query($sql, $conditions);
    }
}