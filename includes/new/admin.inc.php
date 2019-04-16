<?php
require('../class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
if (!User::check_login(new Database(), 1)) {
    $response->code = 403;
    $response->description = 'not allowed';
} else {
    $database = new Database();
    $response->code = 200;
    $response->description = 'success';
    $response->data = User::getAll($database);
}

echo json_encode($response);
