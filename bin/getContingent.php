<?php
require('checkLogin.php');
if (!check_login()) {
    die();
}

$response = (object)array();
$response->code = 500;
$response->description = 'internal server error';

include_once('dbconnect.php');
$conn = openConnection();

$sql = "SELECT * FROM `tbl_contingent` WHERE `tbl_user_id`=" . $_SESSION['user_id'] . " AND `year`='" . $_GET['year'] . "'";
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
        $contingent = floatval($row['basis']);
    }

    $sql = "SELECT * FROM `tbl_vacation` WHERE `tbl_vacation_type_id`=1 AND YEAR(`start`)='". date('Y') ."' AND `canceled`=0 AND `tbl_user_id`=". $_SESSION['user_id'];
    $result = $conn->query($sql);
    require_once('functions.php');
    while($row = $result->fetch_assoc()) {
        if ($row['num'] < 1) {
            $used_days += 0.5;
        }
        $used_days += countBusinessDays(new DateTime($row['start']), new DateTime($row['end']));
    }

    /*$sql = "SELECT SUM(`num`) AS 'USED DAYS' FROM `tbl_vacation` WHERE `tbl_vacation_type_id`=1 AND YEAR(`start`)='". date('Y') ."' AND `canceled`=0 AND `tbl_user_id`=". $_SESSION['user_id'];
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $used_days = floatval($row['USED DAYS']);
    }*/

    $response->code = 200;
    $response->description = 'success';
    $response->basis = $contingent;
    $response->used = $used_days;
    $response->left = ($contingent - $used_days);
}

echo json_encode($response);
?>