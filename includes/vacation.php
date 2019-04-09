<?php
require('check_login.php');
if (!check_login()) {
    $response = (object)array();
    $response->code = 403;
    $response->description = 'not allowed';
    echo json_encode($response);
    die();
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getVacationsMinimal':
            getVacations(0);
        break;
        case 'getVacations':
            getVacations(1);
        break;
        case 'deleteVacation':
            deleteVacation($_GET['id']);
        break;
    }
}

function deleteVacation($id) {
    $response = (object)array();
    if (isset($id)) {

        include_once('dbconnect.php');
        $conn = openConnection();
    
        $sql = "SELECT `id`, `user_id` FROM `vacation` WHERE `user_id`=". $_SESSION['user_id'] ." AND `id`=". $id . " LIMIT 1;";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {

            $sql = "DELETE FROM `vacation` WHERE `vacation`.`id` = ". $id .";";
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

function getVacations($view = 0) {
    $response = (object)array();
    $response->data = array();

    include_once('dbconnect.php');
    $conn = openConnection();
    
    $sql = "SELECT `vacation`.`id` AS VID, `vacation`.`title`, `vacation`.`description`, `vacation`.`start`, `vacation`.`end`, `vacation`.`days`, `vacation`.`user_id`, `vacation`.`accepted`, `vacation`.`user_id_accepted`, `vacation`.`vacation_type_id`, `user`.`id`, `user`.`username`, `vacation_type`.`id`, `vacation_type`.`name` FROM `vacation` LEFT JOIN `user` ON `vacation`.`user_id_accepted` = `user`.`id` LEFT JOIN `vacation_type` ON `vacation`.`vacation_type_id`=`vacation_type`.`id` WHERE `user_id`=". $_SESSION['user_id'] . " ORDER BY `start` DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            switch ($view) {
                case 0: 
                    $event = array();
                    $event[] = $row['title'];
                    $event[] = date_format(date_create($row['start']),"d.m.Y");;
                    $event[] = date_format(date_create($row['end']),"d.m.Y");;
                    $event[] = $row['days'];
                    $event[] = $row['accepted'];
                    $response->data[] = $event;
                break;
                case 1: 
                    // normal view
                    $event = array();
                    $event[] = $row['title'];
                    $event[] = $row['description'];
                    $event[] = date_format(date_create($row['start']),"d.m.Y");;
                    $event[] = date_format(date_create($row['end']),"d.m.Y");;
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
