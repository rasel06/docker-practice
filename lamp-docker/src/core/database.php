<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new mysqli(
            'db',
            'lamp_docker',
            'password',
            'lamp_docker'
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // CRUD methods (replace with your actual logic)

    public function create($data, $table)
    {
        $columns = implode(',', array_keys($data));
        $values = implode("','", array_values($data));

        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        $result = $this->connection->query($sql);

        if ($result) {
            return $this->connection->insert_id;
        } else {
            return false;
        }
    }

    public function read($table, $id = null)
    {
        // $sql = "SELECT * FROM $table " . ($id != null) ? " WHERE id = $id" : "";
        $sql = "SELECT * FROM $table";

        if ($id !== null) {
            $sql .= " WHERE id = $id";
        }
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function update($data, $id, $table)
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key='$value'";
        }
        $updates = implode(',', $updates);

        $sql = "UPDATE $table SET $updates WHERE id = $id";
        $result = $this->connection->query($sql);

        return $result;
    }

    public function delete($id, $table)
    {
        $sql = "DELETE FROM $table WHERE id = $id";
        $result = $this->connection->query($sql);

        return $result;
    }
}

// Usage example
//$db = Database::getInstance();

// Create
// $data = [
//     'name' => 'John Doe',
//     'email' => 'john.doe@example.com'
// ];
// $new_id = $db->create($data, 'users');

// Read
// $user = $db->read($new_id, 'users');

// Update
// $data = [
//     'email' => 'updated.email@example.com'
// ];
// $db->update($data, $new_id, 'users');

// Delete
// $db->delete($new_id, 'users');

// Remember to close the connection when done
// $db->getConnection()->close();
