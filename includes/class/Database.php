<?php
class Database
{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'vac_manager';
    private $conn = null;

    function open()
    {
        // Create connection
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        // Check connection
        if ($this->conn->connect_error) {
            return "failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
        } else {
            return true;
        }
    }

    function close()
    {
        if ($this->conn !== null) {
            return $this->conn->close();
        } else {
            return false;
        }
    }

    function select($query)
    {
        if ($result = $this->conn->query($query)) {
            return $result;
        } else {
            throw new Exception($this->conn->error);
            $this->close();
            return false;
        }
    }

    function update($query)
    {
        return $this->conn->query($query);
    }

    function delete($query)
    {
        return $this->conn->query($query);
    }
}
