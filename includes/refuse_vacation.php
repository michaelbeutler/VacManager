<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

require('check_login.php');
require('check_employer_privileges.php');
if (!check_login() || !check_employer_privileges($_SESSION['user_employer_id'], new Priv(Priv::CAN_ACCEPT))) {
    $response->code = 403;
    $response->description = 'not allowed';
    echo json_encode($response);
    die();
}

if (isset($_GET['request_id'])) {

    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "DELETE FROM `vacation` WHERE `vacation`.`id` = ". $_GET['request_id'] .";";
    $result = $conn->query($sql);

    if ($conn->query($sql)) {
        $response->code = 200;
        $response->description = 'success';
    } else {
        // error while executing query
        $response->code = 953;
        $response->description = "Execute failed";
    }
    $conn->close();

} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}
echo json_encode($response);
?>