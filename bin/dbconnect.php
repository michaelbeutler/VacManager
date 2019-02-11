<?php
function openConnection() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "vac";

    // Create connection
    $conn = new mysqli($hostname, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        return "failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        die();
    }

    return $conn;
}

?>