<?php
require('./class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
if (!User::check_login(new Database(), 1)) {
    $response = (object)array();
    $response->code = 403;
    $response->description = 'not allowed';
} else {

    if ($_GET['id'] == $_SESSION['user_id']) {
        $response = (object)array();
        $response->code = 405;
        $response->description = 'not allowed';
    } else {
        Session::assing(User::construct_id(new Database(), $_GET['id']));

        $response->code = 200;
        $response->description = 'success';
        $conn->close();
    }
}

echo json_encode($response);
