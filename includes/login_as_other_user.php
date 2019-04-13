<?php
require('./class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
if (!check_login(new Database(), 1)) {
    $response = (object)array();
    $response->code = 403;
    $response->description = 'not allowed'; 
} else {

    if ($_GET['id'] == $_SESSION['user_id']) {
        $response = (object)array();
        $response->code = 405;
        $response->description = 'not allowed'; 
    } else {
        $conn = openConnection();
        $sql = "SELECT `user`.`id` AS 'user_id', `user`.`username`, `user`.`salt`, `user`.`password`, `user`.`employer_id`, `employer`.`name`, `employer`.`shortname`, `user`.`is_banned`, `user`.`admin` FROM `user` LEFT JOIN `employer` ON `user`.`employer_id` = `employer`.`id` WHERE `user`.`id`='" . $_GET['id'] . "' LIMIT 1;";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_username'] = $row['username'];
                $_SESSION['user_hash'] = $row['password'];
                $_SESSION['employer_id'] = $row['employer_id'];
                $_SESSION['employer_name'] = $row['name'];
                $_SESSION['employer_shortname'] = $row['shortname'];
                $_SESSION['user_is_admin'] = $row['admin'];
            }
        
            $response->code = 200;
            $response->description = 'success';
            $conn->close();
        } else {
            // 0 results
            $response->code = 201;
            $response->description = 'no users found (create new)';
            $response->users = null;
            $conn->close();
        } 
    } 
}

echo json_encode($response);
