<?php


/*
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

    public function create($table, $data)
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

    public function update($table, $id, $data)
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

    public function delete($table, $id)
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

*/



// SELECT 
//     table_name,
//     column_name,
//     data_type,
//     character_maximum_length,
//     is_nullable,
//     column_default
// FROM
//     information_schema.tables
//     LEFT JOIN information_schema.columns USING (table_schema, table_name)
// WHERE
//     information_schema.tables.table_schema = 'lamp_docker';


class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // Read credentials from environment variables or a secure configuration file
        // $host = getenv('DB_HOST');
        // $username = getenv('DB_USERNAME');
        // $password = getenv('DB_PASSWORD');
        // $database = getenv('DB_DATABASE');

        $host = 'db';
        $username = 'lamp_docker';
        $password = 'password';
        $database = 'lamp_docker';

        $this->connection = new mysqli($host, $username, $password, $database);

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

    // CRUD methods (using prepared statements)

    public function create($data, $table)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = str_repeat('?, ', count($data) - 1) . '?';

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param('s' . str_repeat('s', count($data)), ...array_values($data));

        if ($stmt->execute()) {
            return $this->connection->insert_id;
        } else {
            // Handle errors here (e.g., log the error)
            return false;
        }
    }

    public function read($table, $id = null)
    {
        try {
            $sql = "SELECT * FROM $table";
            $params = [];

            if ($id !== null) {
                // Validate the ID as an integer (optional for additional layer of security)
                if (!is_numeric($id)) {
                    throw new InvalidArgumentException("Invalid ID: $id");
                }

                $sql .= " WHERE id = ?";
                $params[] = $id; // Using positional parameter binding
            }

            $stmt = $this->connection->prepare($sql);
            if (count($params) > 0) {
                $stmt->bind_param('i', $id); // Use spread operator for efficient binding
            }


            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    if ($id !== null) {
                        $data[] = $result->fetch_assoc();
                        return $data;
                    } else {
                        $data = [];
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                        return $data;
                    }
                } else {
                    return false;
                }
            } else {
                throw new RuntimeException("Error executing statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            error_log($e->getMessage(), 3, '/path/to/error.log');
            return false;
        }
    }

    public function update($data, $id, $table)
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key=?";
        }
        $updates = implode(',', $updates);

        $sql = "UPDATE $table SET $updates WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $bind_param_types = array_fill(0, count($data), 's');
        $bind_param_types[] = 'i'; // Add 'i' for the ID
        $stmt->bind_param(...array_merge($bind_param_types, array_values($data), [$id]));

        if ($stmt->execute()) {
            return true;
        } else {
            // Handle errors here (e.g., log the error)
            return false;
        }
    }

    public function delete($id, $table)
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            // Handle errors here (e.g., log the error)
            return false;
        }
    }
}

// Usage example
// $db = Database::getInstance();

// Create (assuming $data is already sanitized)
// $data = [
//   'name' => 'John Doe (sanitized)',
//   'email' => 'john.doe@example.com'
// ];
// $new_id = $db->create($data, 'users');

// Read
// $user = $db->read($new_id, 'users');

// Update (assuming $data is already sanitized)
// $data = [
//   'email' => 'updated.email@example.com'
// ];
// $db->update($data, $new_id, 'users');

// Delete
// $db->delete($new_id, 'users');

// Remember to close the connection when done
// $db->getConnection()->close();
