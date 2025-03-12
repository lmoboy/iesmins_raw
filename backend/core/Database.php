<?php
if (!defined('DATABASE_INCLUDED')) {
    define('DATABASE_INCLUDED', true);

class Database
{
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    private function debug_log($message, $type = 'info') {
        if (!DEBUG_DB) return;
        
        $log_message = date('[Y-m-d H:i:s]') . " [DB] [{$type}] {$message}\n";
        error_log($log_message, 3, DEBUG_LOG_FILE);
    }

    public function connect()
    {
        $this->conn = null;
        $this->debug_log("Attempting database connection");

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->debug_log("Database connection successful");
        } catch (PDOException $e) {
            $this->debug_log("Connection Error: " . $e->getMessage(), 'error');
            if (DEBUG_MODE) {
                echo 'Connection Error: ' . $e->getMessage();
            } else {
                echo 'Database connection error occurred. Please try again later.';
            }
        }

        return $this->conn;
    }

    public function generateTables()
    {
        $this->debug_log("Generating database tables");
        
        $sql = 'CREATE TABLE IF NOT EXISTS `categories` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `description` varchar(255)
        )';

        $this->query($sql);
        $this->debug_log("Categories table created/verified");

        $sql = 'CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `role` boolean NOT NULL DEFAULT 0
        )';

        $this->query($sql);
        $this->debug_log("Users table created/verified");

        $sql = 'CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `category_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `quantity` int(11) NOT NULL,
            `image` varchar(255) NOT NULL,
            FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
        )';

        $this->query($sql);
        $this->debug_log("Products table created/verified");

        $sql = 'CREATE TABLE IF NOT EXISTS `orders` (
            `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `quantity` int(11) NOT NULL,
            `total_price` float NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
        )';

        $this->query($sql);
        $this->debug_log("Orders table created/verified");
    }

    public function query($sql, $params = [])
    {
        try {
            $this->debug_log("Executing query: {$sql}");
            if (!empty($params)) {
                $this->debug_log("With parameters: " . json_encode($params));
            }
            
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameters with appropriate types
            foreach ($params as $key => $value) {
                $type = PDO::PARAM_STR;
                if (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                }
                $stmt->bindValue($key, $value, $type);
            }
            
            $stmt->execute();
            $this->debug_log("Query executed successfully");
            return $stmt;
        } catch (PDOException $e) {
            $this->debug_log("Query Error: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    public function create($table, $data)
    {
        $this->debug_log("Creating new record in {$table}");
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        $lastId = $this->conn->lastInsertId();
        $this->debug_log("Created record with ID: {$lastId}");
        return $lastId;
    }

    public function read($table, $conditions = [], $fields = '*')
    {
        $this->debug_log("Reading from {$table}");
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
        $result = $stmt->fetchAll();
        $this->debug_log("Retrieved " . count($result) . " records");
        return $result;
    }

    public function update($table, $data, $conditions)
    {
        $this->debug_log("Updating record(s) in {$table}");
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

        $result = $this->query($sql, $params);
        $this->debug_log("Updated " . $result->rowCount() . " records");
        return $result;
    }

    public function delete($table, $conditions)
    {
        $this->debug_log("Deleting record(s) from {$table}");
        $sql = "DELETE FROM {$table}";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
            }
            $sql .= implode(' AND ', $where);
        }

        $result = $this->query($sql, $conditions);
        $this->debug_log("Deleted " . $result->rowCount() . " records");
        return $result;
    }
}
}