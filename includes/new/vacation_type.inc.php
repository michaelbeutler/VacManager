<?php
require('../class/Autoload.php');

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

Session::start();
$database = new Database();
if (!User::check_login($database)) {
    die('check_login failed');
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'GET_ALL_VACATION_TYPES':
            $response->code = 200;
            $response->description = 'success';
            $response->data = VacationType::getAll($database);
            break;
        case 'ADD_VACATION_TYPE':
            if (User::check_login($database, 1)) {
                if (isset($_GET['name'], $_GET['substract_vacation_days'])) {
                    $name = $_GET['name'];
                    $substract_vacation_days = $_GET['substract_vacation_days'];
                    if (VacationType::create($database, $name, $substract_vacation_days)) {
                        $response->code = 200;
                        $response->description = 'success';
                    } else {
                        $response->code = 953;
                        $response->description = 'Execute failed';
                    }
                } else {
                    // Parameters missing
                    $response->code = 900;
                    $response->description = 'parameters missing';
                }
            } else {
                $response->code = 403;
                $response->description = 'not allowed';
            }
            break;
        default:
            # code...
            break;
    }
} else {
    // Parameters missing
    $response->code = 900;
    $response->description = 'parameters missing';
}

echo json_encode($response);
