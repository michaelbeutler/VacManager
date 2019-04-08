<?php
require('check_login.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

if (!check_login(true)) {
    $response->code = 403;
    $response->description = 'not allowed'; 
} else {
    $conn = openConnection();
    $sql = "SELECT * FROM `user`;";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // output data of each row
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $sql = "SELECT * FROM `employer`;";
        $result = $conn->query($sql);
        $employers = array();
        while($row = $result->fetch_assoc()) {
            $employers[] = $row;
        }
    
        $response->code = 200;
        $response->description = 'success';
        $response->users = $users;
        $response->employers = $employers;
        $conn->close();
    } else {
        // 0 results
        $response->code = 201;
        $response->description = 'no users found (create new)';
        $response->users = null;
        $conn->close();
    } 
}

echo json_encode($response);
?>