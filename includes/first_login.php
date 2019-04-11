<?php
$currentYear = date("Y");

include_once('dbconnect.php');
$conn = openConnection();

$sql = "SELECT * FROM `contingent` WHERE `user_id`=" . $_GET['user_id'] . " AND `year`='" . $currentYear . "'";
$result = $conn->query($sql);

if ($result->num_rows < 1) {
    echo 'no contingent for this year found ('. $currentYear .')';
} else {
    echo 'contingent for this year found ('. $currentYear .')<br />';
    while($row = $result->fetch_assoc()) {
        echo 'contingent: '. $row['contingent'];
    }   
}
