<?php
require('check_login.php');
if (!check_login()) {
    die();
}

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

include_once('dbconnect.php');
$conn = openConnection();

$sql = "SELECT * FROM `contingent` WHERE `user_id`=" . $_SESSION['user_id'] . " AND `year`='" . $_GET['year'] . "'";
$result = $conn->query($sql);

$contingent;
$used_days = 0.0;

if ($result->num_rows < 1) {
    $response->code = 250;
    $response->description = 'no contingent for this year found ('. $_GET['year'] .')';
    $response->basis = 0;
    $response->used = 0;
    $response->left = 0;
} else {
    while($row = $result->fetch_assoc()) {
        $contingent = floatval($row['contingent']);
    }

    $sql = "SELECT SUM(`days`) AS 'USED DAYS' FROM `vacation` WHERE `vacation_type_id`=1 AND YEAR(`start`)='". date('Y') ."' AND `accepted`=1 AND `user_id`=". $_SESSION['user_id'];
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $used_days = floatval($row['USED DAYS']);
    }

    $response->code = 200;
    $response->description = 'success';
    $response->basis = $contingent;
    $response->used = $used_days;
    $response->left = ($contingent - $used_days);
}

echo json_encode($response);
?>