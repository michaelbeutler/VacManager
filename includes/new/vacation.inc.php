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
        case 'GET_ALL_VACATIONS':
                if (isset($_GET['view'])) {
                    switch ($_GET['view']) {
                        case 'MONTH_STATISTIC':
                            $response->code = 200;
                            $response->description = 'success';

                            $response->data->accepted[] = array();
                            for ($i = 0; $i < 12; $i++) {
                                $response->data->accepted[$i] = 0;
                            }
                        
                            if ($result = $database->select("SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE `accepted`=1 AND `user_id`=". User::getCurrentUser($database)->id ." GROUP BY MONTH(`start`);")) {
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $response->data->accepted[($row['m'] - 1)] = $row['c'];
                                    }
                                }
                            }

                            $response->data->pending[] = array();
                            for ($i = 0; $i < 12; $i++) {
                                $response->data->pending[$i] = 0;
                            }
                        
                            if ($result = $database->select("SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE `accepted`=0 AND `user_id`=". User::getCurrentUser($database)->id ." GROUP BY MONTH(`start`);")) {
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $response->data->pending[($row['m'] - 1)] = $row['c'];
                                    }
                                }
                            }
                            break;
                    }
                } else {
                    $response->code = 200;
                    $response->description = 'success';
                    $response->data = Vacation::getAll($database, User::getCurrentUser($database));
                } 
            break;
        case 'GET_VACATION':
            if (isset($_GET['id'])) {
                $response->code = 200;
                $response->description = 'success';
                $response->data = Vacation::construct_id($database, $_GET['id']);
            } else {
                // Parameters missing
                $response->code = 900;
                $response->description = 'parameters missing';
            }
            break;
        case 'CREATE_VACATION':
            if (isset($_GET['title'], $_GET['description'], $_GET['start'], $_GET['end'], $_GET['end'], $_GET['vacation_type_id'])) {
                if (create($database, $_GET['title'], $_GET['description'], $_GET['start'], $_GET['end'], $_GET['end'], User::getCurrentUser($database), $_GET['vacation_type_id'])) {
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
            break;
        case 'ACCEPT_VACATION':
            if (isset($_GET['id'])) {   
                if(Vacation::construct_id($database, $_GET['id'])->accept($database, User::getCurrentUser($database))) {
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
            break;
        case 'REFUSE_VACATION':
            if (isset($_GET['id'])) {   
                if(Vacation::construct_id($database, $_GET['id'])->refuse($database, User::getCurrentUser($database))) {
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
            break;
        case 'CANCEL_VACATION':
            if (isset($_GET['id'])) {   
                if(Vacation::construct_id($database, $_GET['id'])->cancel($database, User::getCurrentUser($database))) {
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