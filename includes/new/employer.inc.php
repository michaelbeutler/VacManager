<?php
require('../class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
$database = new Database();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'GET_ALL_EMPLOYERS':
            $response->code = 200;
            $response->description = 'success';
            $response->data = Employer::getAll($database);
            break;
    }
} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);
