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

                        if ($result = $database->select("SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE `status`='Accepted' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . " GROUP BY MONTH(`start`);")) {
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

                        if ($result = $database->select("SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE `status`='Pending' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . " GROUP BY MONTH(`start`);")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $response->data->pending[($row['m'] - 1)] = $row['c'];
                                }
                            }
                        }
                        break;
                    case 'PENDING':
                        $response->code = 200;
                        $response->description = 'success';
                        $response->data->accepted = 0;
                        $response->data->pending = 0;

                        if ($result = $database->select("SELECT COUNT(*) AS 'c' FROM `vacation` WHERE `status`='Accepted' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . ";")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $response->data->accepted = $row['c'];
                                }
                            }
                        }

                        if ($result = $database->select("SELECT COUNT(*) AS 'c' FROM `vacation` WHERE `status`='Pending' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . ";")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $response->data->pending = $row['c'];
                                }
                            }
                        }

                        if ($result = $database->select("SELECT COUNT(*) AS 'c' FROM `vacation` WHERE `status`='Refused' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . ";")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $response->data->refused = $row['c'];
                                }
                            }
                        }

                        if ($result = $database->select("SELECT COUNT(*) AS 'c' FROM `vacation` WHERE `status`='Canceled' AND YEAR(`start`)='" . date("Y") . "' AND `user_id`=" . User::getCurrentUser($database)->id . ";")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $response->data->canceled = $row['c'];
                                }
                            }
                        }

                        break;
                    case 'MIN':
                        $response->code = 200;
                        $response->description = 'success';

                        if ($result = $database->select("SELECT `title`, `description`, `start`, `end`, `days`, `status` FROM `vacation` WHERE `status`='Accepted' AND `user_id`=" . User::getCurrentUser($database)->id . ";")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $row['start'] = date_format(date_create($row['start']), 'd.m.Y');
                                    $row['end'] = date_format(date_create($row['end']), 'd.m.Y');
                                    $response->data[] = $row;
                                }
                            }
                        }
                        break;
                    case 'NOT_ACCEPTED':
                        $response->code = 200;
                        $response->description = 'success';
                        $response->data = (array)null;
                        $response->data = User::getCurrentUser($database)->employer->getAllVacations($database);
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
            if (isset($_GET['title'], $_GET['description'], $_GET['start'], $_GET['end'], $_GET['end'], $_GET['days'], $_GET['vacation_type_id'])) {
                if (Vacation::create($database, $_GET['title'], $_GET['description'], $_GET['start'], $_GET['end'], $_GET['days'], User::getCurrentUser($database), $_GET['vacation_type_id'])) {
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
        case 'EDIT_VACATION':
            if (isset($_GET['id'], $_GET['title'], $_GET['description'])) {
                if (Vacation::construct_id($database, $_GET['id'])->update($database, htmlspecialchars($_GET['title']), htmlspecialchars($_GET['description']))) {
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
            if (isset($_GET['id']) && EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::CAN_ACCEPT))) {
                if (Vacation::construct_id($database, $_GET['id'])->accept($database, User::getCurrentUser($database))) {
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
            if (isset($_GET['id']) && EmployerPriv::check_employer_priv(new Database(), User::getCurrentUser(new Database())->employer, new Priv(Priv::CAN_ACCEPT))) {
                if (Vacation::construct_id($database, $_GET['id'])->refuse($database, User::getCurrentUser($database))) {
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
                if (Vacation::construct_id($database, $_GET['id'])->cancel($database, User::getCurrentUser($database))) {
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
        case 'FULLCALENDAR':
            $response = (array)null;
            $vacations = (array)null;
            if (isset($_GET['view']) && $_GET['view'] == 'EMPLOYER') {
                $users = Employer::getAllEmployee($database, User::getCurrentUser($database)->employer);
                foreach ($users as $key => $user) {
                    $vacations = array_merge($vacations, Vacation::getAll($database, $user));
                }
            } else {
                $vacations = Vacation::getAll($database, User::getCurrentUser($database));
            }

            foreach ($vacations as $key => $vacation) {
                $start = date_format(date_create($vacation->start), 'Y-m-d');
                $end = date_create($vacation->end);

                date_add($end, date_interval_create_from_date_string("1 days"));

                $background_color = 'orange';
                if ($vacation->status == 'Accepted') {
                    $background_color = 'green';
                } else if ($vacation->status == 'Canceled' || $vacation->status == 'Refused') {
                    $background_color = 'red';
                }

                if (isset($_GET['view']) && $_GET['view'] == 'EMPLOYER') {
                    $event = array(
                        'id' => $vacation->id,
                        'title' => $vacation->user->username . ': ' . $vacation->days . ' Day(s)',
                        'start' => $start,
                        'end' => date_format($end, 'Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => $background_color
                    );
                } else {
                    $event = array(
                        'title' => $vacation->title . ' - ' . $vacation->days . ' Day(s)',
                        'start' => $start,
                        'end' => date_format($end, 'Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => $background_color,
                        'url' => 'vacation.php?id=' . $vacation->id
                    );
                }


                $response[] = $event;
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
