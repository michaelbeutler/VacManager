<?php
require('./class/Autoload.php');
Session::start();
if (!User::check_login(new Database())) {
    $response = (object)array();
    $response->code = 403;
    $response->description = 'not allowed';
    echo json_encode($response);
    die();
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getMinimal':
            getVacations(0);
            break;
        case 'get':
            getVacations(1);
            break;
        case 'delete':
            deleteVacation($_GET['id']);
            break;
        case 'accept':
            acceptVacation($_GET['id']);
            break;
        case 'refuse':
            refuseVacation($_GET['id']);
            break;
        case 'monthdata':
            monthdata();
            break;
    }
}

function deleteVacation($id)
{
    $response = (object)array();
    if (isset($id)) {

        include_once('dbconnect.php');
        $conn = openConnection();

        $sql = "SELECT `id`, `user_id` FROM `vacation` WHERE `user_id`=" . $_SESSION['user_id'] . " AND `id`=" . $id . " LIMIT 1;";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {

            $sql = "DELETE FROM `vacation` WHERE `vacation`.`id` = " . $id . ";";
            $result = $conn->query($sql);

            if ($conn->query($sql)) {
                $response->code = 200;
                $response->description = 'success';
            } else {
                // error while executing query
                $response->code = 953;
                $response->description = "Execute failed";
            }
            echo json_encode($response);
        } else {
            $response->code = 403;
            $response->description = 'not allowed';
            echo json_encode($response);
        }

        $conn->close();
    } else {
        // Parameters missing
        $response->code = 900;
        $response->description = 'parameters missing';
        echo json_encode($response);
    }
}

function getVacations($view = 0)
{
    $response = (object)array();
    $response->data = array();

    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "SELECT `vacation`.`id` AS VID, `vacation`.`title`, `vacation`.`description`, `vacation`.`start`, `vacation`.`end`, `vacation`.`days`, `vacation`.`user_id`, `vacation`.`accepted`, `vacation`.`user_id_accepted`, `vacation`.`vacation_type_id`, `user`.`id`, `user`.`username`, `vacation_type`.`id`, `vacation_type`.`name` FROM `vacation` LEFT JOIN `user` ON `vacation`.`user_id_accepted` = `user`.`id` LEFT JOIN `vacation_type` ON `vacation`.`vacation_type_id`=`vacation_type`.`id` WHERE `user_id`=" . $_SESSION['user_id'] . " ORDER BY `start` DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            switch ($view) {
                case 0:
                    $event = array();
                    $event[] = $row['title'];
                    $event[] = date_format(date_create($row['start']), "d.m.Y");;
                    $event[] = date_format(date_create($row['end']), "d.m.Y");;
                    $event[] = $row['days'];
                    $event[] = $row['accepted'];
                    $response->data[] = $event;
                    break;
                case 1:
                    // normal view
                    $event = array();
                    $event[] = $row['title'];
                    $event[] = $row['description'];
                    $event[] = date_format(date_create($row['start']), "d.m.Y");;
                    $event[] = date_format(date_create($row['end']), "d.m.Y");;
                    $event[] = $row['days'];
                    $event[] = $row['accepted'];
                    $event[] = $row['username'];
                    $event[] = $row['name']; // vacation type
                    $event[] = $row['VID'];
                    $response->data[] = $event;
                    break;
            }
        }
    }

    echo json_encode($response);
}

function acceptVacation($id)
{
    $response = (object)array();

    require('check_employer_privileges.php');
    if (!check_employer_privileges($_SESSION['employer_id'], new Priv(Priv::CAN_ACCEPT))) {
        $response->code = 403;
        $response->description = 'not allowed';
        echo json_encode($response);
        die();
    }

    if (isset($id)) {

        include_once('dbconnect.php');
        $conn = openConnection();

        $sql = "UPDATE `vacation` SET `accepted`=1, `user_id_accepted`=" . $_SESSION['user_id'] . " WHERE `id`=" . $id . ";";
        $result = $conn->query($sql);

        if ($conn->query($sql)) {
            //include_once('mail.php');

            $sql = "SELECT * FROM `vacation` LEFT JOIN `user` ON `vacation`.`user_id` = `user`.`id` WHERE `vacation`.`id`=" . $id . ";";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    //if (sendAcceptedVacationMail($row['email'], $row['firstname'], $row['lastname'], $row['start'], $row['end'], $_SESSION['user_username'])) {
                        $response->code = 200;
                        $response->description = 'success';
                    /*} else {
                        $response->code = 201;
                        $response->description = 'cant send mail';
                    }*/
                }
            }
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
}

function refuseVacation($id)
{
    require('check_employer_privileges.php');
    if (!check_employer_privileges($_SESSION['employer_id'], new Priv(Priv::CAN_ACCEPT))) {
        $response->code = 403;
        $response->description = 'not allowed';
        echo json_encode($response);
        die();
    }

    if (isset($id)) {

        include_once('dbconnect.php');
        $conn = openConnection();

        $sql = "DELETE FROM `vacation` WHERE `vacation`.`id` = " . $id . ";";
        $conn->query($sql);

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
}

function monthdata()
{
    include_once('dbconnect.php');
    $conn = openConnection();

    $sql = "SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE `accepted`=1 AND `user_id`=" . $_SESSION['user_id'] . " GROUP BY MONTH(`start`);";

    $response->accepted[] = array();
    for ($i = 0; $i < 12; $i++) {
        $response->accepted[$i] = 0;
    }

    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response->accepted[($row['m'] - 1)] = $row['c'];
            }
        }

        $sql = "SELECT MONTH(`start`) AS 'm', SUM(`days`) AS 'c' FROM `vacation` WHERE YEAR(`start`)=". date('Y') ." AND `accepted`=0 AND `user_id`=" . $_SESSION['user_id'] . " GROUP BY MONTH(`start`);";

        $response->pending[] = array();
        for ($i = 0; $i < 12; $i++) {
            $response->pending[$i] = 0;
        }

        if ($result = $conn->query($sql)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $response->pending[($row['m'] - 1)] = $row['c'];
                }
            }
        }
        $response->code = 200;
        $response->description = 'success';
    } else {
        // error while executing query
        $response->code = 953;
        $response->description = "Execute failed";
    }
    $conn->close();

    echo json_encode($response);
};
