<?php
require('checkLogin.php');
if (!check_login()) {
    die();
}
$events = array();


include_once('dbconnect.php');
$conn = openConnection();
//echo $_SESSION['user_id'];
$sql = "SELECT * FROM `tbl_vacation` WHERE `tbl_user_id`=". $_SESSION['user_id'];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $event = (object)array();
        $event->title = $row['description'] . ' - ' . $row['num'] . ' Day(s)';
        $event->start = $row['start'];
        if ($row['num'] < 1.0) {
            $event->allDay = false;
        } else {
            $event->allDay = true;
        }
        $event->end = $row['end'];

        if ($row['tbl_vacation_type_id'] == 1) {
            $event->color = 'blue';
        } else {
            $event->color = 'yellow';
            $event->textColor = 'black';
        }
        $event->description = $event->title;

        $events[] = $event;
    }
}

echo json_encode($events);
$conn->close();
?>