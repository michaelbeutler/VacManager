<?php
$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

require('./class/Autoload.php');
require('check_employer_privileges.php');
Session::start();
if (!User::check_login(new Database()) || !check_employer_privileges($_SESSION['employer_id'], new Priv(Priv::CAN_ACCEPT))) {
    $response->code = 403;
    $response->description = 'not allowed';
    echo json_encode($response);
    die();
}

include_once('dbconnect.php');
$conn = openConnection();

$sql = "SELECT `user`.`id`, `user`.`username`, `user`.`employer_id`, `vacation`.`id` AS 'VID', `vacation`.`title`, `vacation`.`start`, `vacation`.`end`, `vacation`.`days`, `vacation`.`create_date` FROM `user` LEFT JOIN `vacation` ON `user`.`id` = `vacation`.`user_id` WHERE `user`.`employer_id`=". $_SESSION['employer_id'] ." AND `vacation`.`accepted` = 0;";
$result = $conn->query($sql);

$requests = array();

if ($result->num_rows < 1) {
    $response->code = 250;
    $response->description = 'no vacation request found';
    $response->requests = null;
} else {
    $request = (object)array();
    while($row = $result->fetch_assoc()) {
        $request->id = $row['VID'];
        $request->username = $row['username'];
        $request->employer_id = $row['employer_id'];
        $request->title = $row['title'];
        $request->start = date_format(date_create($row['start']),"d.m.Y H:i");
        $request->end = date_format(date_create($row['end']),"d.m.Y H:i");
        $request->days = $row['days'];
        $request->create_date = date_format(date_create($row['create_date']),"d.m.Y H:i");
        $requests[] = $request;
    }

    $response->code = 200;
    $response->description = 'success';
    $response->requests = $requests;
}

echo json_encode($response);
$conn->close();
